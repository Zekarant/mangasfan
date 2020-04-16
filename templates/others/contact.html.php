<h2 class="titre">Nous contacter - Mangas'Fan</h2>
<hr>
<?php if(!empty($errors)): ?>
	<div class='alert alert-<?= \Rewritting::sanitize($couleur); ?>' role='alert'>
		<?php foreach($errors as $error):
			echo $error;
		endforeach; ?>
	</div>
<?php endif; ?>
<div class="alert alert-info" role="alert">
	<h5 class="alert-heading">A l'intention de nos utilisateurs</h5>
	<hr>
	<p>Chers membres,<br/>Dans le but de faciliter vos demandes pour le formulaire de contact, nous vous demandons de bien vouloir remplir <strong>tous</strong> les champs demandés correctement.</p>
	<p>Pour les membres inscrits sur le site et actuellement connectés, les informations suivantes seront déjà remplies de base :
		<ul>
			<li>Votre pseudonyme</li>
			<li>Votre email</li>
		</ul>
	</p>
</div>
<div class="container">
	<form method="POST" action="">
		<div class="row">
			<div class="col-lg-6">
				<label>Pseudonyme :</label>
				<input type="text" name="pseudo" class="form-control" placeholder="Entrez le pseudo que vous utilisez sur Mangas'Fan" value="<?php if(isset($_SESSION['auth'])){ 
					echo \Rewritting::sanitize($utilisateur['username']); 
				} ?>">
				<br/>
				<label>Adresse email :</label>
				<input type="email" name="email" class="form-control" placeholder="Entrez votre adresse mail" value="<?php if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
					echo \Rewritting::sanitize($utilisateur['email']); 
				} ?>">
				<br/>
				<label>Sujet de la demande :</label>
				<select name="sujet" class="form-control">
					<option value="Inscription - Connexion">Inscription/Connexion</option>
					<option value="Recrutements">Recrutements</option>
					<option value="Partenariats">Partenariats</option>
					<option value="Bugs - Suggestions">Bugs/Suggestions</option>
					<option value="Problèmes de compte">Problème de compte</option>
					<option value="Autre">Autre</option>
				</select>
			</div>
			<div class="col-lg-6">
				<label>Contenu de votre demande :</label>
					<textarea class="form-control" rows="9" name="demande" placeholder="Dans ce cadre, veuillez décrire votre demande de la manière la plus complète possible."></textarea>
					<input class="btn btn-sm btn-info" type="submit" name="envoyer" value="Envoyer ma demande">
			</div>
		</div>
	</form>
</div>