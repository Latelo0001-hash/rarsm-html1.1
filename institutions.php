<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/i18n.php';
rarsm_apply_security_headers();

$sourceFile = __DIR__ . '/institutions.html';
$isEnglish = rarsm_current_language() === 'en';

if (is_file($sourceFile)) {
	readfile($sourceFile);
	return;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $isEnglish ? 'en' : 'fr'; ?>">
<head>
	<meta charset="utf-8">
	<title><?php echo $isEnglish ? 'RARSM | Institutions' : 'RARSM | Institutions'; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body {
			background: #f5f7fc;
			color: #142047;
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 40px 20px;
		}

		.error-box {
			background: #fff;
			border: 1px solid #dce3f2;
			border-radius: 18px;
			box-shadow: 0 18px 40px rgba(20, 32, 71, 0.08);
			margin: 0 auto;
			max-width: 760px;
			padding: 28px 24px;
		}

		h1 {
			margin-top: 0;
		}

		code {
			background: #f1f4fb;
			border-radius: 8px;
			padding: 2px 6px;
		}
	</style>
</head>
<body>
	<div class="error-box">
		<h1><?php echo $isEnglish ? 'Missing file' : 'Fichier manquant'; ?></h1>
		<p><?php echo $isEnglish
			? 'The <code>institutions.php</code> page was executed, but <code>institutions.html</code> was not found in the same server directory.'
			: 'La page <code>institutions.php</code> a bien été exécutée, mais le fichier <code>institutions.html</code> n’a pas été trouvé dans le même dossier sur le serveur.'; ?></p>
		<p><?php echo $isEnglish
			? 'Also upload <code>institutions.html</code> and the <code>css</code>, <code>js</code>, <code>images</code> and <code>logo</code> folders.'
			: 'Téléversez également <code>institutions.html</code>, ainsi que les dossiers <code>css</code>, <code>js</code>, <code>images</code> et <code>logo</code>.'; ?></p>
	</div>
</body>
</html>
