<?php 
session_start();
include('membres/base.php');
include('membres/functions.php');
if(isset($_POST['envoyer'])){
	if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['demande']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){
		$header="MIME-Version: 1.0\r\n";
		$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
		$header.='Content-Type:text/html; charset="utf-8"'."\n";
		$header.='Content-Transfer-Encoding: 8bit';
		$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
		$demande = "
		Vous avez reçu un mail de la part de " . sanitize($_POST['pseudo']) . " !<br/>
		Sa demande concerne : <strong>" . sanitize($_POST['sujet']) . "</strong><br/>
		Email : " . sanitize($_POST['email']) . "<br/>
		Voici son message : <br/>
		<i>« " . sanitize($_POST['demande']) . " »</i><br/>
		Cette demande a été envoyée depuis le formulaire de contact de Mangas'Fan.";
		mail("contact@mangasfan.fr", "Demande de membre - " . sanitize($_POST['sujet']) . "", $demande, $header);
		$errors[] = "Votre message a bien été envoyé. Vous obtiendrez une réponse dans les prochaines 24 heures.";
		$couleur = "success";
	} else {
		$errors[] = "Vous n'avez pas remplis tous les champs. Nous avons besoin du plus d'informations possibles.";
		$couleur = "danger";
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Contact - Mangas'Fan</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="icon" href="images/favicon.png" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
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
	<section>
		<h1 class="titre_principal_news">Formulaire de contact</h1>
		<hr>
		<div class="alert alert-info" role="alert">
			<h5 class="alert-heading">A l'intention de nos utilisateurs</h5>
			<hr>
			<p>Chers membres,<br/>Dans le but de faciliter vos demandes pour le formulaire de contact, nous vous demandons de bien vouloir remplir <strong>tous</strong> les champs demandés correctement.</p>
			<p>Pour les membres inscrits sur le site et actuellement connectés, les informations suivantes seront déjà remplies de base :
				<ul>
					<li>Votre pseudonyme sur Mangas'Fan</li>
					<li>Votre email</li>
				</ul>
			</p>
		</div>
		<?php if(!empty($errors)): ?>
			<div class='alert alert-<?= sanitize($couleur); ?>' role='alert'>
				<?php foreach($errors as $error):
					echo $error;
				endforeach; ?>
			</div>
		<?php endif; ?>
		<div class="container">
			<form method="POST" action="">
				<div class="row">
					<div class="col-md-6">
						<label>Pseudonyme sur le site :</label>
						<input type="text" name="pseudo" class="form-control" placeholder="Entrez le pseudo que vous utilisez sur Mangas'Fan" value="<?php if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
							echo sanitize($utilisateur['username']); 
						} ?>">
						<br/>
						<label>Adresse email :</label>
						<input type="email" name="email" class="form-control" placeholder="Entrez votre adresse mail" value="<?php if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
							echo sanitize($utilisateur['email']); 
						} ?>">
						<br/>
						<label>Sujet de la demande :</label>
						<select name="sujet" class="form-control">
							<option value="inscription_connexion">Inscription/Connexion</option>
							<option value="recrutements">Recrutements</option>
							<option value="partenariats">Partenariats</option>
							<option value="bugs_suggestions">Bugs/Suggestions</option>
							<option value="membre">Problème de compte</option>
							<option value="Autre">Autre</option>
						</select>
					</div>
					<div class="col-md-6">
						<label>Contenu de votre demande :</label>
						<textarea class="form-control" rows="9" name="demande" placeholder="Dans ce cadre, veuillez décrire votre demande de la manière la plus complète possible."></textarea>
						<input class="btn btn-sm btn-info" type="submit" name="envoyer" value="Envoyer ma demande">
					</div>
				</div>
			</form>
		</div>
	</section>
	<?php include('elements/footer.php'); ?>
</body>
</html>