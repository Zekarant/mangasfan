<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_admin.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Modifier "<?= \Rewritting::sanitize($changelog['title_changelog']) ?>"</h2>
			<hr>
			<div class="text-center">
				<a href="gestion_changelog.php" class="btn btn-outline-info">Retourner à la gestion des changelogs</a>
			</div>
			<hr>
			<form method="POST" action="">
				<label>Titre du changelog :</label>
				<input type="text" name="titre-changelog" class="form-control" value="<?= \Rewritting::sanitize($changelog['title_changelog']) ?>">
				<br/>
				<label>Contenu du changelog :</label>
				<textarea name="text-changelog"><?= \Rewritting::sanitize($changelog['text_changelog']) ?></textarea>
				<input type="submit" name="modifier-changelog" class="btn btn-outline-info" value="Modifier le changelog">
			</form>
		</div>
	</div>
</div>