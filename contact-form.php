<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/security.php';
rarsm_bootstrap_security();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/includes/i18n.php';

header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$isEnglish = rarsm_current_language() === 'en';

function rarsm_contact_text(string $french, string $english): string
{
    global $isEnglish;

    return $isEnglish ? $english : $french;
}

function rarsm_contact_error(string $message): never
{
    echo '<span class="form-errors" role="alert">'
        . htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
        . '</span>';
    exit;
}

rarsm_require_same_origin_post();

// Champ invisible : les robots le remplissent souvent, les visiteurs non.
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    echo rarsm_contact_text(
        'Merci. Votre message a été reçu.',
        'Thank you. Your message has been received.'
    );
    exit;
}

$name = trim(strip_tags((string) ($_POST['name'] ?? '')));
$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$subject = trim(strip_tags((string) ($_POST['subject'] ?? '')));
$message = trim(strip_tags((string) ($_POST['message'] ?? '')));

if ($name === '' || mb_strlen($name) < 2 || mb_strlen($name) > 120) {
    rarsm_contact_error(rarsm_contact_text(
        'Veuillez saisir un nom valide.',
        'Please enter a valid name.'
    ));
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
    rarsm_contact_error(rarsm_contact_text(
        'Veuillez saisir une adresse email valide.',
        'Please enter a valid email address.'
    ));
}

if (preg_match('/[\r\n]/', $email . $subject)) {
    rarsm_contact_error(rarsm_contact_text(
        'Les informations envoyées ne sont pas valides.',
        'The submitted information is invalid.'
    ));
}

if ($subject === '') {
    $subject = rarsm_contact_text('Demande depuis le site RARSM', 'Request from the RARSM website');
}

if (mb_strlen($subject) > 160) {
    rarsm_contact_error(rarsm_contact_text(
        'L’objet est trop long.',
        'The subject is too long.'
    ));
}

if ($message === '' || mb_strlen($message) < 10 || mb_strlen($message) > 5000) {
    rarsm_contact_error(rarsm_contact_text(
        'Veuillez saisir un message de 10 à 5 000 caractères.',
        'Please enter a message between 10 and 5,000 characters.'
    ));
}

$now = time();
$lastSubmission = (int) ($_SESSION['rarsm_contact_submitted_at'] ?? 0);
if ($lastSubmission > 0 && ($now - $lastSubmission) < 30) {
    rarsm_contact_error(rarsm_contact_text(
        'Veuillez patienter quelques secondes avant d’envoyer un autre message.',
        'Please wait a few seconds before sending another message.'
    ));
}

$recipient = trim((string) getenv('RARSM_CONTACT_TO'));
$fromAddress = trim((string) getenv('RARSM_CONTACT_FROM'));

if (!filter_var($recipient, FILTER_VALIDATE_EMAIL) || !filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
    rarsm_contact_error(rarsm_contact_text(
        'Le service de messagerie n’est pas encore configuré.',
        'The mail service is not configured yet.'
    ));
}

$safeSubject = '[RARSM] ' . $subject;
$body = implode("\n", [
    'Nom : ' . $name,
    'Email : ' . $email,
    'Objet : ' . $subject,
    '',
    'Message :',
    $message,
]);
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: RARSM Website <' . $fromAddress . '>',
    'Reply-To: ' . $name . ' <' . $email . '>',
    'X-Mailer: PHP/' . PHP_VERSION,
];

if (!mail($recipient, $safeSubject, $body, implode("\r\n", $headers))) {
    rarsm_contact_error(rarsm_contact_text(
        'Le message n’a pas pu être envoyé. Veuillez réessayer plus tard.',
        'The message could not be sent. Please try again later.'
    ));
}

$_SESSION['rarsm_contact_submitted_at'] = $now;

echo rarsm_contact_text(
    'Merci. Votre message a été envoyé avec succès.',
    'Thank you. Your message has been sent successfully.'
);
