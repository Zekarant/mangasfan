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
if (isset($_POST['envoyer'])) {
	if (isset($_SESSION['auth'])) {
		if ($utilisateur['grade'] >= 8) {
			$inserer_changelog = $pdo->prepare('INSERT INTO changelog(title, contenu, date_changelog) VALUES(?, ?, NOW())');
			$inserer_changelog->execute(array($_POST['titre'], $_POST['contenu_changelog']));
			$couleur = "success";
			$texte = "Le changelog a bien été publié !";
		}
	}
}
$recuperer_changelog = $pdo->prepare('SELECT * FROM changelog ORDER BY id DESC');
$recuperer_changelog->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Administration des changelogs - Mangas'Fan</title>
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
			plugins: "lists",
			toolbar: "bold italic underline | numlist bullist"
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
				<h1 class="titre_principal_news">Gestion des changelogs du site</h1>
				<hr>
				<?php if (isset($_POST['envoyer'])) { ?>
					<div class='alert alert-<?= sanitize($couleur); ?>' role='alert'>
						<?= sanitize($texte); ?>
					</div>
				<?php } ?>
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<h4>Poster un nouveau changelog</h4>
							<form method="POST" action="">
								<input type="text" name="titre" class="form-control" placeholder="Saisir le titre du changelog">
								<br/>
								<textarea rows="20" name="contenu_changelog"></textarea>
								<input type="submit" name="envoyer" class="btn btn-outline-info" value="Publier le changelog">
							</form>
						</div>
						<div class="col-md-6">
							<h4>Récapitulatif des changelogs</h4>
							<table class="table">
								<thead>
									<th>Titre</th>
									<th>Date</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php while ($changelog = $recuperer_changelog->fetch()) { ?>
										<tr>
											<td><?= sanitize($changelog['title']); ?></td>
											<td><?= date('d/m/Y', strtotime(sanitize($changelog['date_changelog']))); ?></td>
											<td><a href="modifier_changelog.php?changelog=<?= sanitize($changelog['id']); ?>" class="btn btn-outline-info">Modifier</a></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('../elements/footer.php'); ?>
</body>
</html>