<h2 class="titre">Hébergeur d'images</h2>
<hr>
<?php if ($utilisateur['grade'] >= 4 AND $utilisateur['chef'] != 0) { ?>
	<a href="gestion_hebergeur.php" class="btn btn-outline-info">Accéder à la gestion des images</a>
	<hr>
<?php } ?>
<div class="alert alert-info" role="alert">
	<h4>Bienvenue sur le pannel d'hébergement d'images de Mangas'Fan !</h4><hr><b>Informations importantes : </b> Ce pannel est réservé aux membres appartenant à l'équipe de <b>Rédacteurs/Newseurs</b>. Vous êtes donc les seules personnes à pouvoir voir cette interface.<br/>
	Dans le but de simplifier la vie de nos rédacteurs, et dans le but d'éviter une panne des hébergeurs d'images externes, il a été décidé que Mangas'Fan possèderait son propre hébergeur d'image.<br/>
	Pour l'utiliser, rien de bien compliqué, il suffit de cliquer sur <i>« Choisir un fichier »</i>, sélectionner l'image que vous souhaitez envoyer et cliquer sur <i>« Valider »</i>. <br/>
	Une fois ceci fait, un lien sera crée et vous aurez juste à l'inclure à l'endroit souhaité sur le site. Notamment les espaces de rédaction.<br/><br/>
	<b>Nous comptons sur vous pour utiliser cet hébergeur correctement !</b>
</div>
<?php 
if (isset($_FILES['fichier'])){
	if ($erreur == "Ok") { ?>
		<div class="alert alert-success" role="alert">Votre image a été hébergée sur le serveur avec succès !</div><br/>
		<center>
			<img src="https://www.mangasfan.fr/hebergeur/uploads/<?= \Rewritting::sanitize($nom_fichier); ?>" max-width="80%" class="image_herberge" /><br/><br/>
			<b>Lien direct </b> : <a href="<?= \Rewritting::sanitize($url); ?>" target="_blank"><?= \Rewritting::sanitize($url); ?></a><br/><br/>
			<a href="https://www.mangasfan.fr/hebergeur">Héberger une nouvelle image</a>
		</center>
		<?php 
	} else { ?>
		<div class="alert alert-danger" role="alert">
			<?= $erreur ?>
		</div>
		<div class="d-flex justify-content-center">
			<form method="POST" enctype="multipart/form-data"> 
				<input type="hidden" name="MAX_FILE_SIZE" value="<?= \Rewritting::sanitize($poids_max); ?>"><br/>
				Choisir l'image à uploader : <input type="file" class="file btn btn-info" name="fichier">
				<input type="submit" class="btn btn-sm btn-info" value="Uploader"> 
			</form>
		</div>
	<?php } 
} else { ?>
	<div class="d-flex justify-content-center">
		<form method="POST" enctype="multipart/form-data"> 
			<input type="hidden" name="MAX_FILE_SIZE" value="<?= \Rewritting::sanitize($poids_max); ?>"><br/>
			Choisir l'image à uploader : <input type="file" class="file btn btn-info" name="fichier">
			<input type="submit" class="btn btn-sm btn-info" value="Uploader"> 
		</form>
	</div>
<?php } ?>