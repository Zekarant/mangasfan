<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_admin.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Modifier les Conditions Générales d'Utilisation du site</h2>
			<form method="POST" action="">
				<textarea name="texte-cgu"><?= $ligne ?></textarea>
				<input type="submit" name="modifier_cgu" class="btn btn-info">
			</form>
		</div>
	</div>
</div>