<?php
session_start();
include('membres/base.php');
include('membres/functions.php');
if ($_SESSION['auth'] == FALSE) {
	header('Location: erreurs/erreur_403.php');
	die();
}
if (isset($_SESSION['auth']) && $utilisateur['grade'] <= 2) {
	header('Location: erreurs/erreur_403.php');
	die();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Index du staff - Mangas'Fan</title>
	<link rel="icon" href="images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<?php include('elements/header.php'); ?>
	<section>
		<h1 class="titre_principal_news">Partie staff de Mangas'Fan</h1>
		<hr>
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="card">
						<div class="card-header red">
							Panneau d'administation de Mangas'Fan <?php if($utilisateur['grade'] >= 8){ ?> - <a href="administration/" class="btn btn-danger btn-sm" target="_blank">Accéder à l'administation</a><?php } ?>
						</div>
						<div class="card-body">
							<ul>
								<?php $users = $pdo->prepare('SELECT * FROM users');
								$users->execute(); ?>
								<li>Nombre de membres inscrits sur Mangas'Fan : <strong><?= $users->rowCount(); ?></strong> membres.</li>
								<?php $users = $pdo->prepare('SELECT * FROM users WHERE grade >= 3');
								$users->execute(); ?>
								<li>Nombre de membres étant du staff : <strong><?= $users->rowCount(); ?></strong> membres.</li>
								<?php $users = $pdo->prepare('SELECT * FROM newsletters_historique');
								$users->execute(); ?>
								<li>Nombre de newsletters envoyées : <strong><?= $users->rowCount(); ?></strong> newsletters.</li>
								<?php $newsletters_membres = $pdo->prepare('SELECT * FROM newsletter');
								$newsletters_membres->execute();?>
								<li>Nombre de membres inscrits aux newsletters : <strong><?= $newsletters_membres->rowCount(); ?></strong> membre(s) inscrit(s).</li>
								<?php $admin = $pdo->prepare('SELECT * FROM users WHERE grade >= 10');
								$admin->execute(); ?>
								<li>Nombre de membres gérant l'administration : <strong><?= $admin->rowCount(); ?></strong> administrateurs.</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header green">
							Panneau de modération de Mangas'Fan <?php if($utilisateur['grade'] >= 8){ ?> - <a href="moderation/" class="btn btn-success btn-sm" target="_blank">Accéder à la modération</a><?php } ?>
						</div>
						<div class="card-body">
							<ul>
								<?php $banni = $pdo->prepare('SELECT * FROM users WHERE grade = 1');
								$banni->execute(); ?>
								<li>Nombre de membres bannis : <strong><?php if($banni->rowCount() == 0){ echo "Il n'y a aucun membre banni."; } else { echo $banni->rowCount(); } ?></strong></li>
								<?php $invalide = $pdo->prepare('SELECT * FROM users WHERE confirmation_token IS NOT NULL');
								$invalide->execute(); ?>
								<li>Nombre de membres n'ayant pas validé leur compte : <strong><?= $invalide->rowCount(); ?></strong> membres.</li>
								<?php $galeries = $pdo->prepare('SELECT * FROM galerie');
								$galeries->execute(); ?>
								<li>Nombre d'images dans les galeries : <strong><?= $galeries->rowCount(); ?></strong> images postées.</li>
								<?php $commentaires_news = $pdo->prepare('SELECT * FROM commentaires');
								$commentaires_news->execute(); ?>
								<li>Nombre de commentaires sur les news : <strong><?= $commentaires_news->rowCount(); ?></strong> commentaires.</li>
								<?php $modo = $pdo->prepare('SELECT * FROM users WHERE grade = 7');
								$modo->execute(); ?>
								<li>Nombre de membres gérant la modération : <strong><?= $modo->rowCount(); ?></strong> modos.</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="card">
						<div class="card-header blue">
							Panneau de rédaction de Mangas'Fan <?php if($utilisateur['grade'] == 5 || $utilisateur['grade'] == 6 || $utilisateur['grade'] >= 7){ ?> - <a href="redaction/index.php" class="btn btn-info btn-sm" target="_blank">Accéder à la rédaction</a><?php } ?>
						</div>
						<div class="card-body">
							<ul>
								<?php $news = $pdo->prepare('SELECT * FROM billets');
								$news->execute(); ?>
								<li>Nombre de news sur le site : <strong><?= $news->rowCount(); ?></strong> news.</li>
								<?php $jeux = $pdo->prepare('SELECT * FROM billets_jeux');
								$jeux->execute(); ?>
								<li>Nombre de jeux sur le site : <strong><?= $jeux->rowCount(); ?></strong> jeux.</li>
								<?php $articles_jeux = $pdo->prepare('SELECT * FROM billets_jeux_pages');
								$articles_jeux->execute(); ?>
								<li>Nombre de d'articles dans la catégorie jeux : <strong><?= $articles_jeux->rowCount(); ?></strong> jeux.</li>
								<?php $articles_mangas = $pdo->prepare('SELECT * FROM billets_mangas_pages');
								$articles_mangas->execute(); ?>
								<li>Nombre de d'articles dans la catégorie mangas : <strong><?= $articles_mangas->rowCount(); ?></strong> mangas.</li>
								<?php $total = $articles_jeux->rowCount() + $articles_mangas->rowCount(); ?>
								<li>Nombre de d'articles total : <strong><?= $total; ?></strong> articles.</li>
								<?php $membres_redaction = $pdo->prepare('SELECT * FROM users WHERE grade = 5 OR grade = 6');
								$membres_redaction->execute(); ?>
								<li>Nombre de membres gérant la rédaction : <strong><?= $membres_redaction->rowCount(); ?></strong> rédacteurs.</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header orange">
							Panneau d'animation de Mangas'Fan <?php if($utilisateur['grade'] == 3 || $utilisateur['grade'] >= 7){ ?> - <a href="animation/" class="btn badge-warning btn-sm" target="_blank">Accéder à l'animation</a><?php } ?>
						</div>
						<div class="card-body">
							<ul>
								<?php $nombre_badges = $pdo->prepare('SELECT * FROM badges');
								$nombre_badges->execute(); ?>
								<li>Nombre de badges sur le site : <strong><?= $nombre_badges->rowCount(); ?></strong> badges.</li>
								<?php $membres_animation = $pdo->prepare('SELECT * FROM users WHERE grade = 3');
								$membres_animation->execute(); ?>
								<li>Nombre de membres gérant l'animation : <strong><?= $membres_animation->rowCount(); ?></strong> animateurs.</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php include('elements/footer.php'); ?>
</body>
</html>