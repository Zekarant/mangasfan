<?php
	session_start();
	require_once '../inc/base.php';
	include('../inc/functions.php');
	if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
	{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
	}
	include('../theme_temporaire.php');
	$valider_jeux = isset( $_POST['valider_jeux'] ) ? $_POST['valider_jeux'] : ''; 
	if ($valider_jeux != ''){
		$titre_news = stripslashes(htmlspecialchars($_POST['titre_jeux']));
		$vignette_jeu = stripslashes(htmlspecialchars($_POST['vignette_jeu']));
		$theme_jeu = stripslashes(htmlspecialchars($_POST['image_jeux']));
		$text_pres = stripslashes(htmlspecialchars($_POST['text_pres']));
		?>
		<div class='alert alert-success' role='alert'>
			Le jeu a bien été ajouté sur le site !
		</div>
		<?php
		    $add_jeu = $pdo->prepare("INSERT INTO billets_jeux(titre, vignette, presentation, date_creation, theme) VALUES (?, ?, ?, NOW(), ?)");
		    $add_jeu->execute(array($titre_news, $vignette_jeu, $text_pres, $theme_jeu));
		    header('Location: redac.php');
		}
?>
<!DOCTYPE HTML>
	<html>
		<head>
  			<meta charset="utf-8" />
 			<title>Mangas'Fan - Ajouter un jeu</title>
 			<meta name="viewport" content="width=device-width, initial-scale=1">
	        <link rel="icon" href="../images/favicon.png" />
	        <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
	        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
	        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
	        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
			<link rel="stylesheet" href="<?php echo $lienCss; ?>" />
			<link rel="stylesheet" href="../style/redac_style.css" />
		</head>
	<body>
		<div id="bloc_page">
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
		         include('../elements/nav_redac.php');
		    ?>
			<section class="marge_page">
				<h3 class="titre_pannel">
              		Ajouter un jeu sur <span class="couleur_mangas">le</span> <span class="couleur_fans">site</span>
              	</h3>
              	<a href="redac.php" class="btn btn-primary btn-sm">
	                Retourner à l'index de la rédaction
	            </a>
	            <a href="../hebergeur/index.php" class="btn btn-primary btn-sm">
	                Accéder à l'hébergeur d'images
	            </a>
              	<br/><br/>
              	<form method="POST" action="">
					<label for="titre_jeu">Titre du jeu :</label>
					<input type="text" id="titre_jeu" name="titre_jeux" class="form-control" placeholder="Entrez le titre" /><br/>

					<label for="vignette_jeu">Vignette du jeu :</label>
					<input type="text" id="vignette_jeu" name="vignette_jeu" class="form-control" placeholder="L'url de votre image" /><br />
					<i style="margin-left:10px;">Il s'agit de la vignette disponible à l'accueil des jeux-vidéo</i><br /><br />

					<label for="image_jeu">Image du jeu :</label>
					<input type="text" id="image_jeu" name="image_jeux" class="form-control" placeholder="L'url de votre image" /><br />
					<i style="margin-left:10px;">Il s'agit de la banderole disponible sur le descriptif du jeu</i><br /><br />

					<label for="text_presentation">Ajouter une présentation : <br /><span style="font-weight:normal;font-style:italic;">BBCode autorisé</span></label>
			        <textarea name="text_pres" class="form-control" id="text_presentation" rows="10" cols="70" placeholder="Facultatif" ></textarea><br />

					<input type="submit" class="btn btn-sm btn-info" name="valider_jeux" value="Envoyer"/>
				</form>
			</section>
			<?php } ?>
  			<?php include('../elements/footer.php') ?>
  		</div>
	</body>
</html>