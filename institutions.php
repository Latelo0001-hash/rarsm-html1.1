<?php
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
}

$sourceFile = __DIR__ . '/institutions.html';

if (is_file($sourceFile)) {
	readfile($sourceFile);
	return;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>RARSM | Institutions</title>
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
		<h1>Fichier manquant</h1>
		<p>La page <code>institutions.php</code> a bien ete executee, mais le fichier <code>institutions.html</code> n'a pas ete trouve dans le meme dossier sur le serveur.</p>
		<p>Televerse aussi <code>institutions.html</code>, ainsi que les dossiers <code>css</code>, <code>js</code>, <code>images</code> et <code>logo</code>.</p>
		<p>Pour afficher les erreurs PHP si necessaire, ouvre cette URL avec <code>?debug=1</code>.</p>
	</div>
</body>
</html>
