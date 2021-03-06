<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
$recuperer_recrutements = $pdo->prepare('SELECT * FROM recrutements WHERE link = "administrateurs"');
$recuperer_recrutements->execute();
$redacteurs = $recuperer_recrutements->fetch();
if ($redacteurs['recrutement'] == 0) {
	$_SESSION['flash']['warning'] = '<div class="alert alert-warning">Les recrutements Administrateurs ne sont pas ouverts pour le moment.</div>';
	header('Location: ../');
	exit();
}
if (isset($_POST['envoyer']) && !empty($_POST['envoyer'])) {
	if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['motivations']) AND isset($_POST['experiences']) AND isset($_POST['idees']) AND isset($_POST['disponibilites']) AND isset($_POST['prenom']) AND isset($_POST['nom']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){
		$header="MIME-Version: 1.0\r\n";
		$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
		$header.='Content-Type:text/html; charset="utf-8"'."\n";
		$header.='Content-Transfer-Encoding: 8bit';
		$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
		$demande = "
		Vous avez reçu un mail de la part de " . sanitize($_POST['pseudo']) . " !<br/>
		Prénom : <strong>" . sanitize($_POST['prenom']) . "</strong><br/>
		Nom : <strong>" . sanitize($_POST['nom']) . "</strong><br/>
		Idées : <strong>" . sanitize($_POST['idees']) . "</strong><br/>
		Email : " . sanitize($_POST['email']) . "<br/>
		Expériences : " . sanitize($_POST['experiences']) . "<br/>
		disponibilités : " . sanitize($_POST['disponibilites']) . "<br/>
		Autre : " . sanitize($_POST['autre']) . "<br/>
		Motivations : " . sanitize($_POST['motivations']) . "<br/>";
		mail("contact@mangasfan.fr", "Recrutements Administrateurs - " . sanitize($_POST['pseudo']) . "", $demande, $header);
		$texte = "Votre candidature a bien été envoyée ! Elle sera étudiée sous 24h.";
		$couleur = "success";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Recrutement <?= sanitize($redacteurs['name']); ?> - Mangas'Fan</title>
	<link rel="icon" href="../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="../style.css">
</head>
<body>
	<div class="bg-recrutements">
		<?php include('../elements/navigation_principale.php'); ?>
		<h1 class="titre-principal-recrutements">Recrutements - Devenez Administrateur !</h1>
	</div>
	<section>
		<h2 class="titre_principal_news">Recrutements - La direction de Mangas'Fan</h2>
		<hr>
		<div class="alert <?= sanitize($redacteurs['color']); ?>">
			<strong>Important :</strong> Sachez qu'en postulant au rôle d'administrateur, vous vous engagez à respecter les conditions d'utilisation de Mangas'Fan.
		</div>
		<?php if(isset($_POST['envoyer'])){ ?>
			<div class="alert alert-<?= sanitize($couleur); ?>">
				<?= sanitize($texte); ?>
			</div>
		<?php } ?>
		<div class="container">
			<div class="card">
				<div class="card-body">
					<p>* = Champs obligatoires</p>
					<hr>
					<form method="POST" action="">
						<div class="row">
							<div class="col-md-6">
								<label>Pseudo : *</label>
								<input type="text" class="form-control" name="pseudo" placeholder="Saisir votre pseudo" required>
							</div>
							<div class="col-md-6">
								<label>Adresse email : *</label>
								<input type="email" class="form-control" name="email" placeholder="Saisir votre adresse email" required>
							</div>
						</div>
						<br/>
						<div class="row">
							<div class="col-md-6">
								<label>Prénom : *</label>
								<input type="text" class="form-control" name="prenom" placeholder="Saisir votre pseudo" required>
							</div>
							<div class="col-md-6">
								<label>Nom : *</label>
								<input type="email" class="form-control" name="nom" placeholder="Saisir votre adresse email" required>
							</div>
						</div>
						<br/>
						<div class="row">
							<div class="col-md-6">
								<label>Quelles sont vos motivations : *</label>
								<textarea type="text" class="form-control" rows="15" name="motivations" placeholder="Cette partie est très importante, elle nous permet de voir ce qui vous motive à intégrer l'équipe" required></textarea>
							</div>
							<div class="col-md-6">
								<label>Avez-vous un ou plusieurs expérience(s) dans l'administration : *</label>
								<textarea type="text" class="form-control" rows="15" name="experiences" placeholder="Il ne s'agit pas d'une question pénalisante, nous avons seulement besoin de savoir si vous possédez de l'expérience dans le domaine de la gestion administratrive" required></textarea>
							</div>
						</div>
						<br/>
						<div class="row">
							<div class="col-md-6">
								<label>Quelles sont vos disponibilités : *</label>
								<textarea type="text" class="form-control" rows="5" name="disponibilites" placeholder="Nous avons besoin de connaître la fréquence de votre disponibilité car il faut en effet être assez actif !" required></textarea>
							</div>
							<div class="col-md-6">
								<label>Un dernier mot (par exemple, pourquoi vous et pas un autre ?) :</label>
								<textarea type="text" class="form-control" rows="5" name="autre" placeholder="Si vous avez d'autres éléments à nous faire parvenir, n'hésitez pas, c'est ici ! Attention, la réponse à la question peut peser dans la balance"></textarea>
							</div>
						</div>
						<br/>
						<div class="row">
							<div class="alert alert-info" role="alert">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
									<label class="form-check-label" for="invalidCheck2">
										En cochant cette case, vous acceptez les <a href="../mentions_legales.php" target="_blank">Conditions Générales d'Utilisation</a> de Mangas'Fan. Indispensables si votre situation est favorable pour nous.
									</label>
								</div>
							</div>
							<input type="submit" class="btn btn-info" name="envoyer" value="Valider le formulaire">
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<?php include('../elements/footer.php'); ?>
</body>
</html>