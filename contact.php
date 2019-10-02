<?php 
session_start();
include('membres/base.php');
include('membres/functions.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
	$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
	$user->execute(array($_SESSION['auth']['id']));
	$utilisateur = $user->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mangas'Fan - Contact</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="icon" href="images/favicon.png" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" media="screen and (max-device-width: 480px)" href="style/style_mobile.css" />
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-129397962-1');
    </script>
</head>
<body>
		<?php include('elements/header.php'); ?>
		<h2 class="titre_principal_news">
			Formulaire de contact
		</h2>
		<div class="alert alert-info" role="alert">
			<h5 class="alert-heading">
				A l'intention de nos utilisateurs
			</h5>
			<hr>
			<p>Chers membres,<br/>
				Dans le but de faciliter vos demandes pour le formulaire de contact, nous vous demandons de bien vouloir remplir <strong>tous</strong> les champs demandés correctement.</p>
				<p>Pour les membres inscrits sur le site et actuellement connectés, les informations suivantes seront déjà remplies de base :
					<ul>
						<li>Votre pseudo</li>
						<li>Votre email</li>
					</ul>
				</p>
			</div>
			<?php
			if(!empty($_POST['envoyer']) AND isset($_POST['envoyer'])) {
				if(!empty($_POST['pseudo']) AND !empty($_POST['email']) AND !empty($_POST['demande']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){
					$header="MIME-Version: 1.0\r\n";
					$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
					$header.='Content-Type:text/html; charset="utf-8"'."\n";
					$header.='Content-Transfer-Encoding: 8bit';
					$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
					$demande = "
					Vous avez reçu un mail de la part de " . sanitize($_POST['pseudo']) . " !<br/>
					Sa demande concerne : <strong>" . sanitize($_POST['sujet']) . "</strong><br/>
					Email : " . $_POST['email'] . "<br/>
					Voici son message : <br/>
					<i>« " . sanitize($_POST['demande']) . " »</i><br/>
					Cette demande a été envoyée depuis le formulaire de contact de Mangas'Fan.";
					mail("contact@mangasfan.fr", "Demande de membre - " . sanitize($_POST['sujet']) . "", $demande, $header);
					?>
					<div class="alert alert-success" role="alert">
						Votre email à bien été envoyé à l'équipe d'administration. Une réponse vous sera fournie dans les prochaines 24 heures.
					</div>
				<?php } else {
					?>
					<div class="alert alert-danger" role="alert">
						Vous n'avez pas remplis tous les champs, merci de recommencer.
					</div>
					<?php
				}
			}
			?>
			<div class="container">
				<form method="POST" class="formulaire_contact">
					<div class="row">
						<div class="col-md-6">
							<label>Pseudo :</label>
							<input type="text" class="form-control" name="pseudo" value="<?php if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ echo sanitize($utilisateur['username']); } ?>" placeholder="Entrez votre pseudo">
							<br/>
							<label>Votre email :</label>
							<input type="email" name="email" class="form-control" value="<?php if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ echo sanitize($utilisateur['email']); } ?>" placeholder="Entrez votre mail">
							<br/>
							<label>Sujet :</label>
							<select name="sujet" class="form-control">
								<option value="Inscription">Inscription</option>
								<option value="Connexion">Connexion</option>
								<option value="Recrutements">Recrutements</option>
								<option value="Bugs">Bugs</option>
								<option value="Suggestions">Suggestions</option>
								<option value="Autre">Autre</option>
							</select>
						</div>
						<div class="col-md-6">
							<label>Votre demande :</label>
							<textarea class="form-control" rows="9" name="demande" placeholder="Decrivez votre demande complète ici."></textarea>
							<input class="btn btn-sm btn-info" type="submit" name="envoyer">
						</div>
					</div>
				</form>
			</div>
			<?php include('elements/footer.php'); ?>
	</body>
	</html>