<?php 
session_start();
include('../membres/base.php');
include('../membres/functions.php');
if (!isset($_SESSION['auth'])) {
	header('Location: ../erreurs/erreur_403.php');
	exit();
} elseif(isset($_SESSION['auth']) AND $utilisateur['grade'] < 10) {
	header('Location: ../erreurs/erreur_403.php');
	exit();
}
if (isset($_POST['valider'])) {
	if (isset($_SESSION['auth'])) {
		if ($utilisateur['grade'] >= 8) {
			$modifier_changelog = $pdo->prepare('UPDATE changelog SET title = ?, contenu = ? WHERE id = ?');
			$modifier_changelog->execute(array($_POST['titre'], $_POST['contenu_changelog'], $_GET['changelog']));
			$couleur = "success";
			$texte = "Le changelog a bien été modifié.";
		}
	}
}
$recuperer_changelog = $pdo->prepare('SELECT * FROM changelog WHERE id = ?');
$recuperer_changelog->execute(array($_GET['changelog']));
$changelog = $recuperer_changelog->fetch();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title><?= sanitize($changelog['title']); ?> - Mangas'Fan</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../images/favicon.png"/>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript" src="../redaction/tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="../redaction/tinymce/js/tinymce/tinymce.js"></script>
	<script>
		tinymce.init({
			selector: 'textarea',
			language: 'fr_FR',
			force_br_newlines : true,
			force_p_newlines : false,
			entity_encoding : "raw", 
			plugins: "lists link",
			toolbar: "bold italic underline | numlist bullist | link"
		});
	</script>
	<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important; border-right: 2px solid grey;">
				<?php include('../elements/navadmin_v.php'); ?>
			</div>
			<div class="col-sm-10" style="background-color: white; padding: 0px!important;">
				<?php include ('../elements/nav_admin.php'); ?>
				<h1 class="titre_principal_news">Modifier le changelog « <?= sanitize($changelog['title']); ?> »</h1>
				<hr>
				<a href="changelog.php" class="btn btn-outline-info">Retourner à l'index des changelogs</a>
				<br/><br/>
				<?php if (isset($_POST['valider'])) { ?>
					<div class='alert alert-<?= sanitize($couleur); ?>' role='alert'>
						<?= sanitize($texte); ?>
					</div>
					<hr>
				<?php } ?>
				<div class="container">
					<form method="POST" action="">
						<div class="row">
							<div class="col-md-6">
								<label>Titre du changelog :</label>
								<input type="text" name="titre" class="form-control" value="<?= sanitize($changelog['title']); ?>">
								<input type="submit" name="valider" class="btn btn-outline-info" value="Modifier le changelog">
							</div>
							<div class="col-md-6">
								<textarea rows="20" name="contenu_changelog"><?= sanitize($changelog['contenu']); ?></textarea>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php include('../elements/footer.php'); ?>
</body>
</html>