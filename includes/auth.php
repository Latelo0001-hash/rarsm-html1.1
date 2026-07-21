<?php
declare(strict_types=1);

function rarsm_current_user(): ?array
{
    $user = $_SESSION['rarsm_user'] ?? null;

    return is_array($user) ? $user : null;
}

function rarsm_current_user_id(): int
{
    $user = rarsm_current_user();

    return max(0, (int) ($user['id'] ?? 0));
}

function rarsm_is_logged_in(): bool
{
    return rarsm_current_user() !== null;
}

function rarsm_registered_users(): array
{
    if (!isset($_SESSION['rarsm_registered_users']) || !is_array($_SESSION['rarsm_registered_users'])) {
        $_SESSION['rarsm_registered_users'] = [];
    }

    return $_SESSION['rarsm_registered_users'];
}

function rarsm_first_non_empty(array $input, array $keys): string
{
    foreach ($keys as $key) {
        $value = trim((string) ($input[$key] ?? ''));
        if ($value !== '') {
            return $value;
        }
    }

    return '';
}

function rarsm_auth_uses_database(): bool
{
    return rarsm_db() instanceof PDO && rarsm_db_has_table('users');
}

function rarsm_normalize_user_record(array $user): array
{
    return [
        'id' => (string) ($user['id'] ?? ''),
        'first_name' => trim((string) ($user['first_name'] ?? '')),
        'last_name' => trim((string) ($user['last_name'] ?? '')),
        'username' => trim((string) ($user['username'] ?? '')),
        'email' => strtolower(trim((string) ($user['email'] ?? ''))),
        'phone' => trim((string) ($user['phone'] ?? '')),
        'created_at' => (string) ($user['created_at'] ?? ''),
    ];
}

function rarsm_db_user_has_username(): bool
{
    return rarsm_db_has_column('users', 'username');
}

function rarsm_db_find_user_by_id(int $userId): ?array
{
    if ($userId < 1 || !rarsm_auth_uses_database()) {
        return null;
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return null;
    }

    try {
        $statement = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute([
            ':id' => $userId,
        ]);
        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    } catch (Throwable $exception) {
        return null;
    }
}

function rarsm_db_find_user_by_login(string $login): ?array
{
    if ($login === '' || !rarsm_auth_uses_database()) {
        return null;
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return null;
    }

    $hasUsername = rarsm_db_user_has_username();

    try {
        if ($hasUsername) {
            $statement = $pdo->prepare(
                'SELECT *
                 FROM users
                 WHERE LOWER(email) = :login
                    OR LOWER(username) = :login
                 LIMIT 1'
            );
        } else {
            $statement = $pdo->prepare(
                'SELECT *
                 FROM users
                 WHERE LOWER(email) = :login
                 LIMIT 1'
            );
        }

        $statement->execute([
            ':login' => strtolower($login),
        ]);
        $user = $statement->fetch();

        return is_array($user) ? $user : null;
    } catch (Throwable $exception) {
        return null;
    }
}

function rarsm_db_email_exists(string $email): bool
{
    if ($email === '' || !rarsm_auth_uses_database()) {
        return false;
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return false;
    }

    try {
        $statement = $pdo->prepare(
            'SELECT 1
             FROM users
             WHERE LOWER(email) = :email
             LIMIT 1'
        );
        $statement->execute([
            ':email' => strtolower($email),
        ]);

        return (bool) $statement->fetchColumn();
    } catch (Throwable $exception) {
        return false;
    }
}

function rarsm_db_username_exists(string $username): bool
{
    if ($username === '' || !rarsm_auth_uses_database() || !rarsm_db_user_has_username()) {
        return false;
    }

    $pdo = rarsm_db();
    if (!$pdo instanceof PDO) {
        return false;
    }

    try {
        $statement = $pdo->prepare(
            'SELECT 1
             FROM users
             WHERE LOWER(username) = :username
             LIMIT 1'
        );
        $statement->execute([
            ':username' => strtolower($username),
        ]);

        return (bool) $statement->fetchColumn();
    } catch (Throwable $exception) {
        return false;
    }
}

function rarsm_register_user(array $input): array
{
    $firstName = trim((string) ($input['first_name'] ?? ''));
    $lastName = trim((string) ($input['last_name'] ?? ''));
    $rawName = rarsm_first_non_empty($input, ['name', 'full_name']);
    $username = strtolower(rarsm_first_non_empty($input, ['username', 'login', 'name']));
    $email = strtolower(trim((string) ($input['email'] ?? '')));
    $phone = trim((string) ($input['phone'] ?? ''));
    $password = (string) ($input['password'] ?? '');
    $passwordConfirm = rarsm_first_non_empty($input, ['password_confirm', 'confirmPassword', 'password2']);

    if ($firstName === '' && $rawName !== '') {
        $parts = preg_split('/\s+/', $rawName) ?: [];
        $firstName = (string) array_shift($parts);
        $lastName = trim(implode(' ', $parts));
    }

    if ($firstName === '' && $username !== '') {
        $firstName = $username;
    }

    if ($username === '' && $email !== '') {
        $username = strtolower((string) strstr($email, '@', true));
    }

    if ($firstName === '' || $email === '' || $password === '') {
        return [false, 'Veuillez renseigner tous les champs obligatoires.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [false, 'L’adresse email n’est pas valide.'];
    }

    if (strlen($password) < 8) {
        return [false, 'Le mot de passe doit contenir au moins 8 caracteres.'];
    }

    if ($password !== $passwordConfirm) {
        return [false, 'La confirmation du mot de passe ne correspond pas.'];
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    if (rarsm_auth_uses_database()) {
        if (rarsm_db_email_exists($email)) {
            return [false, 'Un compte existe deja avec cette adresse email.'];
        }

        if ($username !== '' && rarsm_db_username_exists($username)) {
            return [false, 'Cet identifiant est deja utilise.'];
        }

        $pdo = rarsm_db();
        if (!$pdo instanceof PDO) {
            return [false, 'La base utilisateur est indisponible pour le moment.'];
        }

        $fields = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'password_hash' => $passwordHash,
        ];

        if (rarsm_db_user_has_username()) {
            $fields['username'] = $username !== '' ? $username : null;
        }

        $columns = array_keys($fields);
        $placeholders = array_map(static function (string $column): string {
            return ':' . $column;
        }, $columns);

        try {
            $parameters = [];
            foreach ($fields as $column => $value) {
                $parameters[':' . $column] = $value;
            }

            $userId = rarsm_db_insert_and_get_id(
                $pdo,
                'INSERT INTO users (' . implode(', ', $columns) . ')
                 VALUES (' . implode(', ', $placeholders) . ')',
                $parameters
            );

            if ($userId === null || $userId < 1) {
                return [false, 'Le compte n’a pas pu etre cree pour le moment.'];
            }

            $createdUser = rarsm_db_find_user_by_id($userId);

            if (!is_array($createdUser)) {
                return [false, 'Le compte a ete cree mais n’a pas pu etre recharge.'];
            }

            $_SESSION['rarsm_user'] = rarsm_normalize_user_record($createdUser);

            return [true, 'Compte cree avec succes.'];
        } catch (Throwable $exception) {
            return [false, 'Le compte n’a pas pu etre cree pour le moment.'];
        }
    }

    $users = rarsm_registered_users();
    if (isset($users[$email])) {
        return [false, 'Un compte existe deja avec cette adresse email.'];
    }

    foreach ($users as $registeredUser) {
        $registeredUsername = strtolower(trim((string) ($registeredUser['username'] ?? '')));
        if ($username !== '' && $registeredUsername !== '' && $registeredUsername === $username) {
            return [false, 'Cet identifiant est deja utilise.'];
        }
    }

    $user = [
        'id' => strtoupper(substr(hash('sha256', $email . microtime(true)), 0, 12)),
        'first_name' => $firstName,
        'last_name' => $lastName,
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'password_hash' => $passwordHash,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    $_SESSION['rarsm_registered_users'][$email] = $user;
    $_SESSION['rarsm_user'] = $user;

    return [true, 'Compte cree avec succes.'];
}

function rarsm_login_user(array $input): array
{
    $login = strtolower(rarsm_first_non_empty($input, ['email', 'login', 'name', 'username']));
    $password = (string) ($input['password'] ?? '');

    if ($login === '' || $password === '') {
        return [false, 'Veuillez entrer votre identifiant ou votre email ainsi que votre mot de passe.'];
    }

    if (rarsm_auth_uses_database()) {
        $user = rarsm_db_find_user_by_login($login);

        if (!is_array($user) || !password_verify($password, (string) ($user['password_hash'] ?? ''))) {
            return [false, 'Identifiants invalides. Creez un compte si vous n’etes pas encore inscrit.'];
        }

        $_SESSION['rarsm_user'] = rarsm_normalize_user_record($user);

        return [true, 'Connexion reussie.'];
    }

    $users = rarsm_registered_users();
    $user = $users[$login] ?? null;

    if (!is_array($user)) {
        foreach ($users as $registeredUser) {
            $registeredEmail = strtolower(trim((string) ($registeredUser['email'] ?? '')));
            $registeredUsername = strtolower(trim((string) ($registeredUser['username'] ?? '')));

            if ($registeredEmail === $login || ($registeredUsername !== '' && $registeredUsername === $login)) {
                $user = $registeredUser;
                break;
            }
        }
    }

    if (!is_array($user) || !password_verify($password, (string) ($user['password_hash'] ?? ''))) {
        return [false, 'Identifiants invalides. Creez un compte si vous n’etes pas encore inscrit.'];
    }

    $_SESSION['rarsm_user'] = $user;

    return [true, 'Connexion reussie.'];
}

function rarsm_logout_user(): void
{
    unset($_SESSION['rarsm_user']);
    $_SESSION['rarsm_cart'] = [];
    unset($_SESSION['rarsm_latest_order_id']);
}

function rarsm_user_display_name(array $user): string
{
    $parts = array_filter([
        trim((string) ($user['first_name'] ?? '')),
        trim((string) ($user['last_name'] ?? '')),
    ]);

    $fullName = trim(implode(' ', $parts));

    if ($fullName !== '') {
        return $fullName;
    }

    $username = trim((string) ($user['username'] ?? ''));
    if ($username !== '') {
        return $username;
    }

    return trim((string) ($user['email'] ?? ''));
}

function rarsm_user_initials(array $user): string
{
    $firstName = trim((string) ($user['first_name'] ?? ''));
    $lastName = trim((string) ($user['last_name'] ?? ''));
    $initials = '';

    if ($firstName !== '') {
        $initials .= strtoupper(substr($firstName, 0, 1));
    }

    if ($lastName !== '') {
        $initials .= strtoupper(substr($lastName, 0, 1));
    }

    if ($initials !== '') {
        return $initials;
    }

    $username = trim((string) ($user['username'] ?? ''));
    if ($username !== '') {
        return strtoupper(substr($username, 0, 2));
    }

    $email = trim((string) ($user['email'] ?? ''));
    if ($email !== '') {
        return strtoupper(substr($email, 0, 2));
    }

    return 'RC';
}
