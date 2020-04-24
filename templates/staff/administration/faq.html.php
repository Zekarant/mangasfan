<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_admin.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Modifier la FAQ du site</h2>
			<form method="POST" action="">
				<textarea name="texte-faq"><?= $ligne ?></textarea>
				<input type="submit" name="modifier_faq" class="btn btn-info">
			</form>
		</div>
	</div>
</div>