<h2 class="titre">Ajouter une nouvelle image à la galerie</h2>
<hr>
<a href="administration.php" class="btn btn-sm btn-outline-primary">Administration de ma galerie</a>
<hr>
<div class='alert alert-info' role='alert'>
	<strong>Information importante :</strong> Vous avez très certainement envie d'ajouter une image tout de suite, mais noter que pour que votre image soit ajoutée, il faut qu'elle respecte quelques contraintes pour permettre une bonne lecture :<br/><br/>
	<ul>
		<li>Votre titre doit comporter entre <strong>3 et 50 caractères.</strong></li>
		<li>Les <strong>mots clés</strong> sont facultatifs. Si vous en mettez, ils doivent être séparés par une virgule !</li>
		<li>Votre contenu doit faire au minimum <strong>20 caractères</strong>.</li>
	</ul>
	<strong>Note :</strong> Dans le cas où un de ces critères ne serait pas respecté, lorsque vous validerez votre formulaire, une erreur apparaitra vous indiquant ce que vous devez corriger.
</div>
<div class="container-fluid">
	<form action="" method="POST" enctype="multipart/form-data">
		<div class="row">
			<div class="col-lg-6">
				<label>Titre de votre image :</label> 
				<input type="text" name="titre" class="form-control" placeholder="Saisir le titre de votre image">
				<br/>
				<label>Votre image :</label><br/>
				<input type="file" name="image_galerie" class="file btn btn-info"/>
				<br/><br/>
				<label>Mots clés de l'image (Merci de les séparer par une virgule) :</label>
				<input type="text" name="titre_image" class="form-control" placeholder="Insérez les mots clés de votre image, ils serviront au référencement. Facultatif.">
				<br/>
				<div class="custom-control custom-checkbox mr-sm-2">
					<input type="checkbox" class="custom-control-input" name="nsfw" id="customControlAutosizing">
					<label class="custom-control-label" for="customControlAutosizing">Définir cette image comme NSFW</label>
				</div>
			</div>
			<div class="col-lg-6">
				<label>Contenu de votre image :</label>
            	<textarea type="texterea" name="contenu" rows="10" class="form-control" placeholder="Votre contenu"></textarea>
            	<input type="submit" name="valider" class="btn btn-sm btn-info" value="Publier mon image" />
			</div>
		</div>
	</form>
</div>