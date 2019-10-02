<?php
session_start();
include('membres/base.php');
include('membres/functions.php');
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
	<link rel="stylesheet" type="text/css" href="style/index_site.css" />
	<link rel="stylesheet" media="screen and (max-device-width: 480px)" href="style/style_mobile.css" />
</head>
<body>
	<?php if (isset($_SESSION['auth']) AND $utilisateur['grade'] >= 3) {
		include('elements/header.php'); 
		?>
		<section>
			<h2 class="titre_principal_news">
				Quartier Général du Staff
			</h2>
			<hr>
			<center>
				<a href="https://www.mangasfan.fr/forum" target="_blank" class="btn btn-outline-info">Preview V1 du forum</a>
			</center>
			<br/>
			<div class="container">
				<div class="row justify-content-around">
					<div class="col-md-6">
						<div class="card">
							<div class="card-header red">
								Panneau d'administation de Mangas'Fan
							</div>
							<div class="card-body">
								<p class="card-text">
									<?php 
									$users = $pdo->prepare('SELECT * FROM users');
									$users->execute();
									?>
									Nombre de membres inscrits sur Mangas'Fan : <strong><?php echo $users->rowCount(); ?></strong> membres.
									<br/>
									<?php 
									$staff = $pdo->prepare('SELECT * FROM users WHERE grade >= 3');
									$staff->execute();
									?>
									Nombre de membres étant du staff : <strong><?php echo $staff->rowCount(); ?></strong> staffiens.
									<br/>
									<?php 
									$newsletters = $pdo->prepare('SELECT * FROM newsletters_historique');
									$newsletters->execute();
									?>
									Nombre de newsletters envoyées : <strong><?php echo $newsletters->rowCount(); ?></strong> newsletters envoyées.
									<br/>
									<?php 
									$newsletters_membres = $pdo->prepare('SELECT * FROM newsletter');
									$newsletters_membres->execute();
									?>
									Nombre de membres inscrits aux newsletters : <strong><?php echo $newsletters_membres->rowCount(); ?></strong> membre(s) inscrit(s).
									<br/>
									<?php 
									$admin = $pdo->prepare('SELECT * FROM users WHERE grade >= 10');
									$admin->execute();
									?>
									Nombre de membres gérant l'administration : <strong><?php echo $admin->rowCount(); ?></strong> admins.
								</p>
								<?php if($utilisateur['grade'] >= 10){ ?>
									<a href="administration/" class="btn btn-primary">Accéder à l'administation</a>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header green">
								Panneau de modération de Mangas'Fan
							</div>
							<div class="card-body">
								<p class="card-text">
									<?php 
									$banni = $pdo->prepare('SELECT * FROM users WHERE grade = 1');
									$banni->execute();
									?>
									Nombre de membres bannis : <strong><?php if($banni->rowCount() == 0){ echo "Aucun membre banni."; } else { echo $banni->rowCount(); } ?></strong>
									<br/>
									<?php 
									$invalide = $pdo->prepare('SELECT * FROM users WHERE confirmation_token IS NOT NULL');
									$invalide->execute();
									?>
									Nombre de membres n'ayant pas validé leur compte : <strong><?php echo $invalide->rowCount(); ?></strong> membres.
									<br/>
									<?php 
									$galeries = $pdo->prepare('SELECT * FROM galerie');
									$galeries->execute();
									?>
									Nombre d'images dans les galeries : <strong><?php echo $galeries->rowCount(); ?></strong> images postées.
									<br/>
									<?php 
									$commentaires_news = $pdo->prepare('SELECT * FROM commentaires');
									$commentaires_news->execute();
									?>
									Nombre de commentaires sur les news : <strong><?php echo $commentaires_news->rowCount(); ?></strong> commentaires.
									<br/>
									<?php 
									$modo = $pdo->prepare('SELECT * FROM users WHERE grade >= 10');
									$modo->execute();
									?>
									Nombre de membres gérant la modération : <strong><?php echo $modo->rowCount(); ?></strong> modos.
								</p>
								<?php if($utilisateur['grade'] >= 9){ ?>
									<a href="moderation/" class="btn btn-primary">Accéder à la modération</a>
								<?php } ?>
							</div>
						</div><br/>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header blue">
								Panneau de rédaction de Mangas'Fan
							</div>
							<div class="card-body">
								<p class="card-text">
									<?php 
									$news = $pdo->prepare('SELECT * FROM billets');
									$news->execute();
									?>
									Nombre de news sur le site : <strong><?php echo $news->rowCount(); ?></strong> news.
									<br/>
									<?php 
									$jeux = $pdo->prepare('SELECT * FROM billets_jeux');
									$jeux->execute();
									?>
									Nombre de jeux sur le site : <strong><?php echo $jeux->rowCount(); ?></strong> jeux.
									<br/>
									<?php 
									$articles_jeux = $pdo->prepare('SELECT * FROM billets_jeux_pages');
									$articles_jeux->execute();
									?>
									Nombre de d'articles dans la catégorie jeux : <strong><?php echo $articles_jeux->rowCount(); ?></strong> jeux.
									<br/>
									<?php 
									$articles_mangas = $pdo->prepare('SELECT * FROM billets_mangas_pages');
									$articles_mangas->execute();
									?>
									Nombre de d'articles dans la catégorie mangas : <strong><?php echo $articles_mangas->rowCount(); ?></strong> mangas.
									<br/>
									<?php 
									$total = $articles_jeux->rowCount() + $articles_mangas->rowCount();
									?>
									Nombre de d'articles total : <strong><?php echo $total; ?></strong> articles.
									<br/>
									<?php 
									$membres_redaction = $pdo->prepare('SELECT * FROM users WHERE grade >= 5 AND grade <= 8');
									$membres_redaction->execute();
									?>
									Nombre de membres gérant la rédaction : <strong><?php echo $membres_redaction->rowCount(); ?></strong> rédacteurs.
									<br/>
								</p>
								<?php if($utilisateur['grade'] >= 5){ ?>
									<a href="redaction/" class="btn btn-primary">Accéder à la rédaction</a>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="card">
							<div class="card-header orange">
								Panneau d'animation de Mangas'Fan
							</div>
							<div class="card-body">
								<p class="card-text">
									<?php 
									$nombre_badges = $pdo->prepare('SELECT * FROM badges');
									$nombre_badges->execute();
									?>
									Nombre de badges sur le site : <strong><?php echo $nombre_badges->rowCount(); ?></strong> badges.
									<br/>
									<?php 
									$membres_animation = $pdo->prepare('SELECT * FROM users WHERE grade = 3');
									$membres_animation->execute();
									?>
									Nombre de membres gérant l'animation : <strong><?php echo $membres_animation->rowCount(); ?></strong> animateurs.
									<br/>
								</p>
								<?php if($utilisateur['grade'] == 3 OR $utilisateur['grade'] >= 8){ ?>
									<a href="animation/" class="btn btn-primary">Accéder à l'animation</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>	
		</section>
		<?php 
		include('elements/footer.php');
		?>
	<?php } elseif(isset($_SESSION['auth']) AND $utilisateur['grade'] < 3) { 
		header('Location: erreurs/erreur_403.php'); 
	} else { 
		header('Location: erreurs/erreur_403.php');  
	} ?>
</body>
</html>