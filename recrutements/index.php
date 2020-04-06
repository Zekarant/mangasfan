<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
$recuperer_recrutements = $pdo->prepare("SELECT * FROM recrutements WHERE recrutement = 1");
$recuperer_recrutements->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Index des recrutements - Mangas'Fan</title>
	<link rel="icon" href="../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-129397962-1');
	</script>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="../style.css">
</head>
<body class="text-center">
	<div class="bg">
		<?php include('../elements/navigation_principale.php'); ?>
		<h1 class="titre-principal">On recherche - Recrutements de Mangas'Fan</h1>
		<hr class="tiret-news">
		<div class="bloc-principal">
			<p class="lead">Nous sommes toujours à la recherche de nouvelles personnes pour nous aider de manière <strong>bénévole</strong> sur Mangas'Fan ! Retrouvez ci-dessous la liste des recrutements disponibles et venez postuler pour nous aider !
				<?php if ($recuperer_recrutements->rowCount() == 0) { ?>
					<br/><br/>
					Il n'y a actuellement aucun recrutement sur le site !
					<?php 
				} ?>
			</p>
			<?php if ($recuperer_recrutements->rowCount() != 0) {
				while($recuperer = $recuperer_recrutements->fetch()){ ?>
					<a href="recrutements-<?= sanitize($recuperer['link']); ?>.php" class="btn <?= sanitize($recuperer['color']); ?>">Postuler <?= sanitize($recuperer['name']); ?></a>
				<?php }
			} ?>
		</div>
	</div>
</body>
</html>