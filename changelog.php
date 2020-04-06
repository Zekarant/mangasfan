<?php
	session_start();
	include('membres/base.php');
	include('membres/functions.php');
$recuperation_changelog = $pdo->prepare('SELECT * FROM changelog ORDER BY id DESC LIMIT 1');
$recuperation_changelog->execute();
$changelog = $recuperation_changelog->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8" />
	<title>Changelog - Mangas'Fan</title>
	<link rel="icon" href="images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta name="description" content="Découvrez les mises à jour du site mangasfan.fr - Mangas'Fan">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-129397962-1');
    </script>
</head>
<body>
	<?php include('elements/header.php'); ?>
	<h1 class="titre_principal_news"><?= sanitize($changelog['title']); ?></h1>
	<hr>
	<section>
		<?= htmlspecialchars_decode(htmlspecialchars($changelog['contenu'])); ?>
		<hr>
		<p><i>Ce changelog a été posté le <?= date('d/m/Y', strtotime(sanitize($changelog['date_changelog']))); ?></i></p>
	</section>
	<?php include('elements/footer.php'); ?>
</body>
</html>