<h2 class="titre">Formulaire d'inscription à Mangas'Fan</h2>
<hr>
<div class='alert alert-info' role='alert'>
	<strong>Information importante :</strong> Merci de noter que pour vous inscrire sur Mangas'Fan, votre mot de passe doit comporter une majuscule et un chiffre minimum !
</div>
<?php if(!empty($error)): ?>
	<div class='alert alert-warning' role='alert'>
		<h4>Oups ! On a un problème chef...</h4>
		<hr>
		<p>On a un petit problème chef, il semblerait qu'on ne puisse pas valider votre inscription à cause des détails suivants :</p>
		<ul><?php foreach($error as $errors): ?>
		<li><?= $errors; ?></li>
		<?php endforeach; ?></ul>
	</div>
<?php endif; ?>
<div class="container">
	<form method="POST" action="">
		<div class="row">
			<div class="col-lg-6">
				<label>Pseudonyme :</label>
				<input type="text" name="username" class="form-control" placeholder="Saisissez votre pseudo">
				<br/>
				<label>Mot de passe :</label>
				<input type="password" name="password" class="form-control" placeholder="Saisissez votre mot de passe">
			</div>
			<div class="col-lg-6">
				<label>Adresse Mail :</label>
				<input type="email" name="email" class="form-control" placeholder="Saisissez votre adresse mail" />
				<br/>
				<label>Confirmation du mot de passe :</label>
				<input type="password" name="password_confirm" class="form-control" placeholder="Retapez votre mot de passe" />
				<br/>
			</div>
			<div class="alert alert-info" role="alert">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
					<label class="form-check-label" for="invalidCheck2">
						En cochant cette case, vous acceptez les <a href="../mentions_legales.php" target="_blank">Conditions Générales d'Utilisation</a> de Mangas'Fan lors de votre inscription. Des sanctions peuvent être appliquées en cas de problèmes avec votre compte.
					</label>
				</div>
			</div>
			<input type="submit" name="validation" class="btn btn-info" value="Créer mon compte sur Mangas'Fan">
			<input type="hidden" id="token" name="token">
		</div>
	</form>
</div>