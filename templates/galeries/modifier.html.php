<h2 class="titre">Modifier l'image de ma galerie</h2>
<hr>
<a href="administration.php" class="btn btn-sm btn-outline-primary">Retourner à l'administration de ma galerie</a>
<hr>
<?php if ($galerie['rappel_image'] == 1) { ?>
	<div class='alert alert-warning' role='alert'>
		<strong>Avertissement :</strong> Cette image est actuellement cachée sur l'index des news car elle a récemment fait l'objet d'un rappel. Merci de modifier ce qu'il faut avec la raison ci-dessous :<br/><hr>
		<strong>Raison du rappel :</strong> <?= sanitize($galerie['rappel']); ?><br/><br/>
		Si jamais nous apprenons que vous avez modifié cette image sans répondre aux critères du rappel, des sanctions seront appliquées à votre compte.
	</div>
<?php } ?>
<div class="container-fluid">
	<form method="POST" method="">
		<div class="row">
			<div class="col-lg-6">
				<label>Titre :</label>
				<input type="text" class="form-control" name="titre" value="<?= \Rewritting::sanitize($galerie['title_image']); ?>">
				<br/>
				<label>Mots-clés (Facultatif mais recommandés) :</label>
				<input type="text" class="form-control" name="keywords" value="<?= \Rewritting::sanitize($galerie['keywords_image']);?>">
				<br/>
				<input type="submit" class="btn btn-sm btn-outline-info" name="modifier_image" value="Valider les modifications">
			</div>
			<div class="col-lg-6">
				<label>Contenu :</label>
            	<textarea type="text" class="form-control" rows="5" name="texte"><?= htmlspecialchars_decode(\Rewritting::sanitize($galerie['contenu_image'])); ?></textarea>
			</div>
		</div>
	</form>
</div>