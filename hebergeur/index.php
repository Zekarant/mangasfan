<?php 
session_start();
include('../inc/functions.php');
include('../inc/base.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
include('../theme_temporaire.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Mangas'Fan - Hébergeur d'images</title>
		<link rel="icon" href="../images/favicon.png" />
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
	  	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
	  	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	  	<link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
	  	<link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
	  	<link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
	  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  	<link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
	  	<link rel="stylesheet" type="text/css" href="../overlay.css" />
	</head>
<body>
<div id="bloc_page">
	<header>
		<div id="banniere_image">
			<div id="titre_site">
				<span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN
			</div>
			<div class="slogan_site"><?php echo $slogan; ?></div>
			<?php include("../elements/navigation.php") ?>
			<h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
			<h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
			<div class="bouton_fofo">
				<a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a>
			</div>
			<?php include('../inc/bbcode.php'); 
        	include('../elements/header.php'); ?>
		</div>
	</header>
	<?php if(isset($_SESSION['auth']) AND $utilisateur['grade'] >= 4){ ?>
    <h3 id="titre_news">
        <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
        	Hébergeur <span class="couleur_mangas">d</span>'<span class="couleur_fans">images</span>
        <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
      </h3>
     <section class="marge_page">
     	<div class="alert alert-info" role="alert">
     		<h4>Bienvenue sur le pannel d'hébergement d'images de Mangas'Fan !</h4><hr><b>Informations importantes : </b> Ce pannel est réservé aux membres appartenant à l'équipe de <b>Rédacteurs/Newseurs</b>. Vous êtes donc les seules personnes à pouvoir voir cette interface.<br/>
     		Dans le but de simplifier la vie de nos rédacteurs, et dans le but d'éviter une panne des hébergeurs d'images externes, il a été décidé que Mangas'Fan possèderait son propre hébergeur d'image.<br/>
     		Pour l'utiliser, rien de bien compliquer, il suffit de cliquer sur <i>« Choisir un fichier »</i>, sélectionner l'image que vous souhaitez envoyer et cliquer sur <i>« Valider »</i>. <br/>
     		Une fois ceci fait, un lien sera crée et vous aurez juste à l'inclure à l'endroit souhaité sur le site. Notamment les espaces de rédaction.<br/><br/>
     		<b>Nous comptons sur vous pour utiliser cet hébergeur correctement !</b>
     	</div>
<?php 
	$poids_max = 512000;
	$repertoire = 'uploads/'; 
	if (isset($_FILES['fichier'])) 
	{
		if ($_FILES['fichier']['type'] != 'image/png' && $_FILES['fichier']['type'] != 'image/jpeg' && $_FILES['fichier']['type'] != 'image/jpg' && $_FILES['fichier']['type'] != 'image/gif' ){ 
		$erreur = '<div class="alert alert-danger" role="alert">Le fichier envoyé doit être au format .png, .jpg, .jpeg ou .gif. Le format que vous avez dû envoyer est invalide.</div>'; 
		} 
		elseif ($_FILES['fichier']['size'] > $poids_max) 
		{ 
			$erreur = '<div class="alert alert-danger" role="alert">Le poids de l\'image doit être inférieur à ' . $poids_max/1024 . 'Ko.</div>'; 
		} 
		elseif (!file_exists($repertoire)) 
		{ 
			$erreur = '<div class="alert alert-danger" role="alert">Erreur : Le dossier « Uploads » n\'existe pas.</div>'; 
		} 
		if(isset($erreur)) 
		{ 
		echo '' . $erreur . '<br/><a href="javascript:history.back(1)">Retourner à l\'accueil de l\'hébergeur</a>'; 
		} 
		else 
		{ 
		if ($_FILES['fichier']['type'] == 'image/jpeg')
			{ 
				$extention = '.jpeg'; 
			} 
		if ($_FILES['fichier']['type'] == 'image/jpg')
			{ 
				$extention = '.jpg'; 
			} 
		if ($_FILES['fichier']['type'] == 'image/png')
			{ 
				$extention = '.png'; 
			} 
		if ($_FILES['fichier']['type'] == 'image/gif')
			{ 
				$extention = '.gif'; 
			} 
		$nom_fichier = time().$extention; 
if (move_uploaded_file($_FILES['fichier']['tmp_name'], $repertoire.$nom_fichier)) 
{ 
	$url = 'https://www.mangasfan.fr/hebergeur/'.$repertoire.''.$nom_fichier.''; ?>
		<div class="alert alert-success" role="alert">Votre image a été hébergée sur le serveur avec succès !</div><br/>
	<center>
	  	<img src="uploads/<?php echo sanitize($nom_fichier); ?>" max-width="80%" class="image_herberge" /><br/><br/>
		<b>Lien pour le BBCode : </b> "[img] <?php echo sanitize($url); ?>[/img]"<br/><br/>
		<b>Lien direct </b> : <a href="<?php echo sanitize($url); ?>" target="_blank"><?php echo sanitize($url); ?></a><br/><br/>
		<a href="https://www.mangasfan.fr/hebergeur">Héberger une nouvelle image</a>
	</center>
<?php 
} 
else 
{ 
	echo '<div class="alert alert-danger" role="alert">Erreur : L\'image n\'a pas pu être uploadée sur le serveur.</div>'; 
} } } 
else 
{ 
?> 
<center>
	<form method="POST" enctype="multipart/form-data"> 
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $poids_max; ?>"><br/>
		Choisir l'image à uploader : <input type="file" class="file btn btn-info" name="fichier">
		<input type="submit" class="btn btn-sm btn-info" value="Uploader"> 
	</form> 
</center>
<?php } }
else
{
	echo '<div class="alert alert-danger" role="alert">Vous ne pouvez pas accéder à cette page.</div>'; 
}?>
		</section>
		<?php include('../elements/footer.php'); ?>
	</div>
</body>
</html>