<h2 class="titre">Demande de réinitialisation de mot de passe</h2>
<hr>
<?php if(isset($error) && $error != ""){ ?>
	<div class="alert alert-warning" role="alert">
		<?= $error; ?>
	</div>
<?php } ?>
<form method="POST" action="">
	<label>Votre adresse mail sur le site :</label>
	<input type="email" class="form-control" name="email" placeholder="Renseigner l'adresse mail utilisée sur le site" required>
	<input type="submit" name="valider" class="btn btn-outline-info" value="M'envoyer un mail">
</form>