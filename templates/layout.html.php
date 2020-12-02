<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $pageTitle ?> - Mangas'Fan</title>
	<link rel="icon" href="/images/favicon.png"/>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Stint+Ultra+Condensed" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Quicksand" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Oswald" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
	<meta name=”twitter:card” content="summary_large_image" />
	<meta name="twitter:site" content="@Mangas_Fans" />
	<meta name="twitter:creator" content="@Mangas_Fans" />
	<meta property="og:site_name" content="mangasfan.fr"/>
	<meta property="og:url" content="https://www.mangasfan.fr" />
	<meta property="og:title" content="<?= $pageTitle ?> - Mangas'Fan" />
	<meta name="twitter:title" content="<?= $pageTitle ?> - Mangas'Fan">
	<link rel="stylesheet" type="text/css" href="<?= $style ?>">
	<?php if (isset($description)) { ?>
		<meta name="description" content="<?= $description ?>"/>
		<meta property="og:description" content="<?= $description ?>" />
		<meta name="twitter:description" content="<?= $description ?>">
	<?php } if (isset($keywords)) { ?>
		<meta name="keywords" content="<?= $keywords ?>"/>
	<?php } if(isset($image)){ ?>
		<meta property="og:image" content="<?= $image ?>" />
		<meta name="twitter:image" content="<?= $image ?>">
	<?php } ?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-129397962-1');
	</script>
	<script type="text/javascript" src="https://www.mangasfan.fr/templates/staff/tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="https://www.mangasfan.fr/templates/staff/tinymce/js/tinymce/tinymce.js"></script>
 <script>
  tinymce.init({
    selector: 'textarea',
    height: 300,
    language: 'fr_FR',
    force_br_newlines : true,
    force_p_newlines : false,
    entity_encoding : "raw",
    browser_spellcheck: true,
    contextmenu: false,
    plugins: ['autolink visualblocks visualchars image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern autosave'],
    toolbar: 'undo redo |  formatselect | tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | bold italic underline forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | restoredraft',
    image_class_list: [
    {title: 'Image news', value: 'image_tiny'},
    ]
  });
</script>
</head>
<body>
	<div class="loader">
		<h1>Votre page arrive très rapidement !</h1>
	</div>

	<?php if(isset($_SESSION['flash-message'])){ ?>
		<div id="temporate-message" class="alert alert-<?= $_SESSION['flash-color'] ?> alert-dismissible fade show d-none <?= $_SESSION['flash-type']; ?>">
			<?= $_SESSION['flash-message']; ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<?php unset($_SESSION['flash-message']);
	} ?>
	<header>
		<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light">
			<a class="navbar-brand" href="https://www.mangasfan.fr"><img src="https://www.mangasfan.fr/images/logo.png" class="logo_site" alt="Logo du site" /></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a class="nav-link" href="/">Accueil</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/jeux-video">Jeux</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/mangas">Mangas</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/animes">Animes/Films</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/galeries">Galeries</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/forum">Forum</a>
					</li>
					<?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 2) { ?>
						<li class="nav-item">
							<a class="nav-link" href="#" data-toggle="modal" data-target="#exampleModalCenter">Staff</a>
						</li>
					<?php }
					if(isset($_SESSION['auth'])){ ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span style="color: <?= Color::rang_etat($utilisateur['grade']) ?>"><?= \Rewritting::sanitize($utilisateur['username']) ?></span>
							</a>
							<div class="dropleft" role="group">
								<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
									<a class="dropdown-item" href="/membres/compte.php">Modifier votre profil</a>
									<a class="dropdown-item" href="/membres/deconnexion.php">Déconnexion</a>
								</div>
							</li>
						<?php } else { ?>
							<li class="nav-item">
								<a class="nav-link" href="/membres/inscription.php">Inscription</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="/membres/connexion.php">Connexion</a>
							</li>
						<?php } ?>
						<li class="nav-item">
							<a class="nav-link" href="/contact.php">Contact</a>
						</li>
					</ul>
				</div>
			</nav>
			<h1 class="slogan">Votre site d'actualité sur les mangas et les animes</h1>
			<div class="bouton">
				<a href="https://www.twitter.com/MangasFanOff" target="_blank" class="links">
					<img src="https://www.mangasfan.fr/images/tw.png" alt="Logo Twitter" class="image_reseaux"/>
				</a>
				<a href="https://discord.gg/KK43VKd" target="_blank" class="links">
					<img src="https://www.mangasfan.fr/images/discord.png" alt="Logo Discord" class="image_reseaux" />
				</a>
				<a href="https://www.facebook.com/MangasFanOff/" target="_blank" class="links">
					<img src="https://www.mangasfan.fr/images/fb.png" alt="Logo Facebook" class="image_reseaux" />
				</a>
				<a href="https://www.instagram.com/mangasfanoff/" target="_blank" class="links">
					<img src="https://www.mangasfan.fr/images/insta.png" alt="Logo Instagram" class="image_reseaux" />
				</a>
				<a href="https://utip.io/mangasfanoff" target="_blank" class="links">
					<img src="https://www.mangasfan.fr/images/utip.png" alt="Logo uTip" class="image_reseaux" />
				</a>
				<a href="https://www.twitch.tv/mangasfanofficiel" target="_blank" class="links">
					<img src="https://www.mangasfan.fr/images/twitch.png" alt="Logo Twitch" class="image_reseaux" />
				</a>
				<a href="https://www.youtube.com/channel/UCEKb-Gz4ZyNQo5jHckWimpQ" target="_blank" class="links">
					<img src="https://www.mangasfan.fr/images/youtube.png" alt="Logo Youtube" class="image_reseaux" />
				</a>
			</div>
			<?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 2) { ?>
				<div class="modal fade text-dark" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalCenterTitle">Bonjour <span style="color: <?= Color::rang_etat($utilisateur['grade']) ?>"><?= \Rewritting::sanitize($utilisateur['username']) ?></span>, heureux de vous revoir !</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								Hm... Nous voyons que vous êtes actuellement <span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($utilisateur['grade']) ?>;"><?= Color::getRang($utilisateur['grade'], $utilisateur['sexe'], $utilisateur['stagiaire'], $utilisateur['chef']) ?></span> sur Mangas'Fan !<br/><br/>
								<p>Au vu de votre rôle sur le site, nous pouvons vous proposer les accès suivants :</p>
								<?php if ($utilisateur['grade'] >= 7) { ?>
									<a href="/../staff/administration/index.php" class="btn btn-outline-danger">Administration</a>
								<?php } if($utilisateur['grade'] >= 6){ ?>
									<a href="/../staff/moderation/index.php" class="btn btn-outline-success">Modération</a>
								<?php } if($utilisateur['grade'] >= 4){ ?>
									<a href="/../staff/news/index.php" class="btn btn-outline-info">News</a>
									<a href="/../staff/redaction/index.php" class="btn btn-outline-info">Rédaction</a>
								<?php } if($utilisateur['grade'] == 3 || $utilisateur['grade'] >= 6){ ?>
									<a href="/../staff/animation/index.php" class="btn btn-outline-warning">Animation</a>
								<?php } if($utilisateur['grade'] == 2){ ?>
									Aucun accès pour vous malheureusement...
								<?php } ?>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer la fenêtre</button>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</header>
		<section>
			<?= $pageContent ?>
		</section>
		<div class="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h3>Liens utiles</h3>
						<nav class="lien_site">
							<ul>
								<li><a href="/">Index</a> - </li>
								<li><a href="/membres/members.php">Liste des membres</a> - </li>
								<li><a href="/changelog.php">Mises à jour</a> - </li>
								<li><a href="/partenaires.php">Partenaires</a> - </li>
								<li><a href="/equipe-du-site.php">Équipe du site</a> - </li>
								<li><a href="/foire-aux-questions.php">F.A.Q</a> - </li>
								<li><a href="/recrutements">Recrutements</a> - </li>
								<li><a href="/mentions_legales.php">Mentions Légales</a></li>
							</ul>
						</nav>
					</div>

					<div class="col-md-6">
						<h3>Nos partenaires</h3>
						<a href="http://www.nexgate.ch" target="_blank">
							<img style="border:0;" src="https://www.nexgate.ch/images/button8831.png" alt="Hébergement gratuit !" title="Hébergement gratuit - nexgate.ch" />
						</a>
						<a href="https://www.bclover.net/" target="_blank">
							<img style="border:0;" src="https://www.mangasfan.fr/images/bryx.png" alt="Logo de Black Clover" width="88" height="31" />
						</a>
						<a href="http://pokemon-boutique.fr/?afmc=1r&utm_campaign=1r&utm_source=leaddyno&utm_medium=affiliate" target="_blank">
							<img style="border:0;" src="https://www.mangasfan.fr/images/mf-petit.png" alt="Logo pour Pokémon Boutique" width="88" height="31" />
						</a>
						<a href="https://discord.gg/Gf4GPSD" target="_blank">
							<img style="border:0;" src="https://www.mangasfan.fr/images/noragami.jpg" alt="Logo pour Honagami" width="88" height="31" />
						</a>
						<div class="row">
							<div class="col-md-12">
								<h3>Nos réseaux</h3>
								<a href="https://www.facebook.com/MangasFanOff/" target="_blank">
									<img src="https://www.mangasfan.fr/images/fb.png" alt="Facebook - Mangas'Fan" class="image_reseaux" />
								</a>
								<a href="https://twitter.com/MangasFanOff" target="_blank">
									<img src="https://www.mangasfan.fr/images/tw.png" alt="Twitter - Mangas'Fan"  class="image_reseaux" />
								</a>
								<a href="https://discord.gg/KK43VKd" target="_blank">
									<img src="https://www.mangasfan.fr/images/discord.png" alt="Discord - Mangas'Fan"  class="image_reseaux" />
								</a>
								<a href="https://www.instagram.com/mangasfanoff/" target="_blank">
									<img src="https://www.mangasfan.fr/images/insta.png" alt="Instagram - Mangas'Fan"  class="image_reseaux" />
								</a>

								<a href="https://www.twitch.tv/mangasfanofficiel/" target="_blank">
									<img src="https://www.mangasfan.fr/images/twitch.png" alt="Twitch - Mangas'Fan"  class="image_reseaux" />
								</a>
								<a href="https://www.youtube.com/channel/UCEKb-Gz4ZyNQo5jHckWimpQ" target="_blank">
									<img src="https://www.mangasfan.fr/images/youtube.png" alt="Youtube - Mangas'Fan"  class="image_reseaux" />
								</a>
							</div>
						</div>  
					</div>
				</div>
			</div>
		</div>
		<?php if($showcookie){ ?>
			<div class="cookie-alert">
				En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de cookies pour nous permettre d'avoir des statistiques sur nos pages grâce à Google Analytics.<br /><a href="https://www.mangasfan.fr/accept_cookie.php">OK</a>
			</div>
		<?php } ?>
		<div class="footer-bottom">       
			<div class="container">           
				<p class="pull-left">Version 7.1.0 de Mangas'Fan © 2017 - 2020. Développé par Zekarant et Nico. Design by Asami. Tous droits réservés. Toute atteinte au droit d'auteur n'est pas désirée.<br/> Propulsé par <a href="https://www.nexgate.ch/">https://www.nexgate.ch/.</a></p>        
			</div>    
		</div>
	</body>
	</html>
	<script type="text/javascript">
		$(window).ready(function() {
			$(".loader").fadeOut("1000"); })
		</script>
		<script type="text/javascript">
			$(function(){
				const elt = $("#temporate-message");

				if(elt !== null) {
					elt.toggleClass("d-none");
					setTimeout(() => {
						elt.toggleClass("d-none");
					}, 5000);
				}
			});
		</script>
