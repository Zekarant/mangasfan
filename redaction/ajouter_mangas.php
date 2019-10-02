<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
	$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
	$user->execute(array($_SESSION['auth']['id']));
	$utilisateur = $user->fetch(); 
}
$valider_jeux = isset( $_POST['valider_mangas'] ) ? $_POST['valider_mangas'] : ''; 
if ($valider_jeux != ''){
	$titre_news = stripslashes(htmlspecialchars($_POST['titre_mangas']));
	$vignette_manga = stripslashes(htmlspecialchars($_POST['vignette_manga']));
	$theme_manga = stripslashes(htmlspecialchars($_POST['image_mangas']));
	$text_pres = stripslashes(htmlspecialchars($_POST['text_pres']));
	?>
	<div class='alert alert-success' role='alert'>
		Le mangas a bien été ajouté sur le site !
	</div>
	<?php
	$add_jeu = $pdo->prepare("INSERT INTO billets_mangas(titre, vignette, presentation, date_creation, theme) VALUES (?, ?, ?, NOW(), ?)");
	$add_jeu->execute(array($titre_news, $vignette_manga, $text_pres, $theme_manga));
	header('Location: redac.php');
} 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Mangas'Fan - Ajouter un mangas</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../images/favicon.png" />
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../style.css" />
	<link rel="stylesheet" href="../style/redac_style.css" />
</head>
<body>
	<?php 
	if (!isset($_SESSION['auth'])){
		?>
		<div class='alert alert-danger' role='alert'>
			Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
		</div>
		<?php
	}
	elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] < 5) {
		?>
		<div class='alert alert-danger' role='alert'>
			Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
		</div>
		<?php
	}
	else {
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
					<nav>
						<center>
							<h5 style="padding-top: 15px;">Bienvenue <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));?> !</h5>
							<hr>
							<?php 
							if (!empty($utilisateur['avatar'])){
								if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
									<img src="https://www.mangasfan.fr/membres/images/avatars/<?php echo $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
									<?php } } ?><br/><br/>
									<p>Status : <?php echo statut(sanitize($utilisateur['grade'])); ?></p>
									<hr>
									<a href="../staff_index.php" class="btn btn-sm btn-info">Retournez à l'index staff</a>
								</center>
								<ul class="nav flex-column">
									<?php if($utilisateur['grade'] == 5){ ?>
										<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
											<span>Newseurs</span>
										</h6>
										<li class="nav-item">
											<a class="nav-link" href="https://www.mangasfan.fr/redaction/rediger_news.php">» Rédiger une news</a>
										</li>
									<?php } elseif ($utilisateur['grade'] == 6) { ?>
										<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
											<span>Rédacteurs</span>
										</h6>
										<li class="nav-item">
											<a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_jeux.php"> » Gestion des jeux</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_mangas.php">» Gestion des mangas/animes</a>
										</li>
									<?php } elseif ($utilisateur['grade'] >= 9) {?>
										<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
											<span>Administration</span>
										</h6>
										<li class="nav-item">
											<a class="nav-link" href="https://www.mangasfan.fr/redaction/rediger_news.php">» Rédiger une news</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_jeux.php"> » Gestion des jeux</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_mangas.php">» Gestion des mangas/animes</a>
										</li>
									<?php } ?>  
								</ul>
							</nav>
						</div>
						<div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
							<?php include ('../elements/nav_redac.php'); ?>
							<section class="marge_page">
								<h3 class="titre_principal_news">
									Ajouter un mangas sur le site
								</h3>
								<a href="redac.php" class="btn btn-primary btn-sm">
									Retourner à l'index de la rédaction
								</a>
								<a href="../hebergeur/index.php" class="btn btn-primary btn-sm">
									Accéder à l'hébergeur d'images
								</a>
								<br/><br/>
								<form method="POST" action="">
									<label for="titre_manga">Titre du manga :</label>
									<input type="text" id="titre_manga" name="titre_mangas" class="form-control" placeholder="Entrez le titre" /><br/>

									<label for="vignette_manga">Vignette du manga :</label>
									<input type="text" id="vignette_manga" name="vignette_manga" class="form-control" placeholder="L'url de votre image" /><br />
									<i style="margin-left:10px;">Il s'agit de la vignette disponible à l'accueil des mangas</i><br /><br />

									<label for="image_manga">Image du manga :</label>
									<input type="text" id="image_manga" name="image_mangas" class="form-control" placeholder="L'url de votre image" /><br />
									<i style="margin-left:10px;">Il s'agit de la banderole disponible sur le descriptif du manga</i><br /><br />

									<label for="text_presentation">Ajouter une présentation : <br /><span style="font-weight:normal;font-style:italic;">BBCode autorisé</span></label>
									<textarea name="text_pres" class="form-control" id="text_presentation" rows="10" cols="70" placeholder="Facultatif" ></textarea><br />

									<input type="submit" class="btn btn-sm btn-info" name="valider_mangas" value="Envoyer" />
								</form>
							</section>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php include('../elements/footer.php') ?>
		</body>
		</html>