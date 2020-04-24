<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_admin.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Modifier les partenaires du site</h2>
			<form method="POST" action="">
				<textarea name="texte-partenaires"><?= $ligne ?></textarea>
				<input type="submit" name="partenaires" class="btn btn-info">
			</form>
		</div>
	</div>
</div>