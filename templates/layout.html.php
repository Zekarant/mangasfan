<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $pageTitle ?> - Mangas'Fan</title>
	<link rel="icon" href="http://localhost/mangasfan/images/favicon.png"/>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="<?= $style ?>">
</head>
<body>
	<?php if(isset($_SESSION['flash-message'])){ ?>
		<div id="temporate-message" class="alert alert-warning alert-dismissible fade show d-none <?= $_SESSION['flash-type']; ?>">
			<?= $_SESSION['flash-message']; ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
  <?php unset($_SESSION['flash-message']); // je ne me souviens plus si c'est exactement ça
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
					<a class="nav-link" href="/mangasfan">Accueil</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Jeux</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Mangas</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Animes/Films</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#">Galeries</a>
				</li>
				<?php if(isset($_SESSION['auth'])){ ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span style="color: <?= Color::rang_etat($utilisateur['grade']) ?>"><?= $utilisateur['username'] ?></span>
						</a>
						<div class="dropleft" role="group">
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<a class="dropdown-item" href="#">Modifier votre profil</a>
								<a class="dropdown-item" href="/mangasfan/membres/deconnexion.php">Déconnexion</a>
							</div>
						</li>
					<?php } else { ?>
						<li class="nav-item">
							<a class="nav-link" href="/mangasfan/membres/inscription.php">Inscription</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/mangasfan/membres/connexion.php">Connexion</a>
						</li>
					<?php } ?>
					<li class="nav-item">
						<a class="nav-link" href="#">Contact</a>
					</li>
				</ul>
			</div>
		</nav>
		<h1 class="slogan">Votre site d'actualité sur les mangas, les animes et les jeux vidéo !</h1>
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
		</div>
	</header>
	<section>
		<?= $pageContent ?>
	</section>
	<div class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h3>Newsletter</h3>
					<form method="POST">
						<label>Tenez-vous informés des dernières nouveautés</label><br />
						<input type="email" class="form-control" name="newsletter" placeholder="Entrez votre adresse mail" />
						<input type="submit" class="btn btn-sm btn-info" name="newsletterform" value="Envoyer"/>
					</form>
					<div class="row">
						<div class="col-md-12">
							<h3>Liens utiles</h3>
							<nav class="lien_site">
								<ul>
									<li><a href="https://www.mangasfan.fr/">Index</a> - </li>
									<li><a href="https://www.mangasfan.fr/membres/liste_membres.php">Liste des membres</a> - </li>
									<li><a href="https://www.mangasfan.fr/changelog.php">Mises à jour</a> - </li>
									<li><a href="https://www.mangasfan.fr/partenaires.php">Partenaires</a> - </li>
									<li><a href="https://www.mangasfan.fr/foire-aux-questions.php">F.A.Q</a> - </li>
									<li><a href="https://www.mangasfan.fr/recrutements">Recrutements</a> - </li>
									<li><a href="https://www.mangasfan.fr/mentions_legales.php">Mentions Légales</a></li>
								</ul>
							</nav>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<h3>Nos partenaires</h3>
					<a href="https://www.pokelove.fr/" target="_blank">
						<img src="https://www.mangasfan.fr/images/pokelove.png" alt="Logo de Pokélove" width="88" height="31" />
					</a>
					<a href="http://www.nexgate.ch" target="_blank">
						<img style="border:0;" src="https://www.nexgate.ch/images/button8831.png" alt="Hébergement gratuit !" title="Hébergement gratuit - nexgate.ch" />
					</a>
					<a href="https://www.bclover.net/" target="_blank">
						<img style="border:0;" src="https://www.mangasfan.fr/images/bryx.png" alt="Logo de Black Clover" width="88" height="31" />
					</a>
					<a href="http://pokemon-boutique.fr/?afmc=1r&utm_campaign=1r&utm_source=leaddyno&utm_medium=affiliate" target="_blank">
						<img style="border:0;" src="https://www.mangasfan.fr/images/mf-petit.png" alt="Logo pour Pokémon Boutique" width="88" height="31" />
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
						</div>
					</div>  
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom">       
		<div class="container">           
			<p class="pull-left">Version 6.6.0 de Mangas'Fan © 2017 - 2020. Développé par Zekarant et Nico. Design by Asami. Header by よねやままい. Tous droits réservés. Toute atteinte au droit d'auteur n'est pas désirée.<br/> Propulsé par <a href="https://www.nexgate.ch/">https://www.nexgate.ch/.</a></p>        
		</div>    
	</div>
</body>
</html>
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