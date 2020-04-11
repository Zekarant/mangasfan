<h2 class="titre">Se connecter à son compte</h2>
<hr>
<?php if(isset($error) && $error != ""){ ?>
	<div class="alert alert-warning" role="alert">
		<?= $error; ?>
	</div>
<?php } ?>
<div class="container">
	<div class="row">
		<div class="col-lg-7">
			<form method="POST" action="">
				<label>Pseudo ou Mail : </label>
				<input name="username" class="form-control" type="text" placeholder="Entrez votre pseudo">
				<br/>
				<label>Mot de passe : </label>
				<input type="password" name="password" placeholder="Entrez votre mot de passe" class="form-control" required/>
				<br/>
				<div class="alert alert-info" role="alert">
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="connexion_maintenue" name="connexion_maintenue">
						<label class="form-check-label" for="gridCheck1">
							Cocher la case pour rester connecté
						</label>
					</div>
				</div>
				<input type="submit" class="btn btn-info" value="Connexion">
			</form>
		</div>
		<div class="col-lg-5">
			<div class="alert alert-info" role="alert">
				<h4 class="alert-heading">Information importante !</h4>
				<p>Chers membres,<br/>
					En cochant la case « Rester connecté », vous acceptez <strong>l'utilisation des cookies</strong> qui vous permettront de rester connecté au site sans avoir à retaper vos identifiants. Merci de noter que si vous ne voulez pas être connecté de façon constante au site, vous n'avez pas à cocher cette option.</p>
					<hr>
					<p>En cas de quelconque problème concernant les cookies, n'hésitez pas à envoyer un mail à l'équipe d'administration de Mangas'Fan : contact@mangasfan.fr</p>
					<p>En cas de perte de mot de passe : <a href="forget.php" class="btn btn-sm btn-outline-warning">J'ai oublié mon mot de passe</a></p>
					<p>Consulter la partie "Cookies" dans nos CGU : <a href="#" class="btn btn-sm btn-outline-info">Informations sur les cookies</a></p>
				</div>
			</div>
		</div>
	</div>