<?php 
session_start();
include('../membres/functions.php');
include('../membres/base.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
	$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
	$user->execute(array($_SESSION['auth']['id']));
	$utilisateur = $user->fetch(); 
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Mangas'Fan - Hébergeur d'images</title>
	<link rel="icon" href="../images/favicon.png" />
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
		<?php include('../elements/header.php'); ?>
		<?php if(isset($_SESSION['auth']) AND $utilisateur['grade'] >= 4){ ?>
			<h3 class="titre_principal_news">
				Hébergeur d'images
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
			</body>
			</html>