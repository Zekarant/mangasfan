<?php
session_start();
include('../functions.php');
include('../base.php');
if (!isset($_SESSION['auth'])) {
	header('Location: ../../');
	exit();
}
if (isset($_POST['valider'])) {
	if (isset($_SESSION['auth'])) {
		$suppression = $pdo->prepare('DELETE FROM users WHERE id = ?');
		$suppression->execute(array($utilisateur['id']));
		session_destroy();
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Supprimer mon compte - Mangas'Fan</title>
	<link rel="icon" href="../../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../../style.css">
</head>
<body>
	<?php include('../../elements/header.php'); ?>
	<?php include("../../elements/messages.php"); ?>
	<h1 class="titre_principal_news">Supprimer mon compte - Mangas'Fan</h1>
	<hr>
	<section>
		<div class="alert alert-info" role="alert">
			<strong>Attention : </strong> Lorsque vous cliquerez sur le bouton "Je souhaite supprimer mon compte définivement", toutes les données du compte <strong><?= sanitize($utilisateur['username']); ?></strong> seront définitivement supprimées du site. Réfléchissez bien !
		</div>
		<form method="POST" action="">
			<input type="submit" name="valider" value="Je souhaite supprimer mon compte définivement" class="btn btn-outline-danger">
		</form>
	</section>
	<?php include('../../elements/footer.php'); ?>
</body>
</html>
