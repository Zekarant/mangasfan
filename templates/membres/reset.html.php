<h2 class="titre">Modifier mon mot de passe</h2>
<hr>
<div class="alert alert-info" role="alert">
	Chers membres,<br/><br/>
	Pour que votre nouveau mot de passe soit validé, il doit comporter les éléments suivants : 
	<ul>
		<li>8 caractères minimum</li>
		<li>Une majuscule et un chiffre requis</li>
	</ul>
	Si votre mot de passe ne remplit pas ces conditions, un message d'erreur apparaitra.
</div>
<?php if(isset($error) && $error != ""){ ?>
	<div class="alert alert-warning" role="alert">
		<?= $error; ?>
	</div>
<?php } ?>
<form method="POST" action="">
    <label>Mot de passe :</label>
    	<input type="password" name="password" class="form-control" placeholder="Entrez votre nouveau mot de passe" /><br/>
    <label>Confirmation du mot de passe : </label>
	    <input type="password" name="password_confirm" class="form-control" placeholder="Confirmez votre nouveau mot de passe" />
	    <input type="submit" name="valider" class="btn btn-info" value="Réinitialiser mon mot de passe">
</form>