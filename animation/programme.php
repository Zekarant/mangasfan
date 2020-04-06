<?php 
session_start();
include('../membres/base.php');
include('../membres/functions.php');
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
	<title>Publier le programme d'animation - Mangas'Fan</title>
	<link rel="icon" href="../images/favicon.png"/>
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
	<script type="text/javascript" src="../redaction/tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="../redaction/tinymce/js/tinymce/tinymce.js"></script>
	<script>
		tinymce.init({
			selector: 'textarea',
			height: 700,
			theme: 'modern',
			language: 'fr_FR',
			plugins: ['print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help'],
			toolbar: 'insert | undo redo |  formatselect | bold italic underline backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
			image_class_list: [
			{title: 'Image news', value: 'image_tiny'},
			],
			content_css: [
			'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
			'//www.tinymce.com/css/codepen.min.css']
		});
	</script>
</head>
<body>
	<?php 
	if (!isset($_SESSION['auth'])){
		?>
		<div class='alert alert-danger' role='alert'>
			Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
		</div>
		<?php
	}
	elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] != 3 AND $utilisateur['grade'] < 10) {
		?>
		<div class='alert alert-danger' role='alert'>
			Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
		</div>
		<?php
	}
	else { 
		?>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
					<nav>
						<center>
							<h5 style="padding-top: 15px;">Bienvenue <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));?> !</h5>
							<hr>
							<?php 
							if (!empty($utilisateur['avatar'])){
								if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
									<img src="https://www.mangasfan.fr/membres/images/avatars/<?php echo $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
									<?php } } ?><br/><br/>
									<p>Status : <?php echo statut(sanitize($utilisateur['grade'])); ?></p>
									<hr>
									<a href="../staff_index.php" class="btn btn-sm btn-info">Retournez à l'index staff</a>
								</center>

								<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
									<span>Animation</span>
								</h6>
								<ul class="nav flex-column">
									<li class="nav-item">
										<a class="nav-link active" href="#points">  
											» Points
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="#stats">
											» Tableau des membres
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="#animation">
											» Billet d'animation
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="programme.php">
											» Programme d'animation
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="#">
											<s>» Gestion des badges</s>
										</a>
									</li>
								</ul>
								<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
									<span>Autres liens du pannel</span>
									<a class="d-flex align-items-center text-muted" href="#">
									</a>
								</h6>
								<ul class="nav flex-column mb-2">
									<li class="nav-item">
										<a class="nav-link" href="https://discord.gg/Cv5qkvV">
											» Discord du site
										</a>
									</li>
								</ul>
							</nav>
						</div>
						<div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
							<?php include ('../elements/nav_anim.php'); ?>
							<h3 class="titre_principal_news">Rédiger le programme d'animation</h3>
							<div class="alert alert-info" type="alert">
								<strong>Important :</strong> Tous les programmes d'animations sont concervés dans la base de données. En cas de problèmes, n'hésitez pas à contacter un administrateur !
							</div>
							<hr>
							<?php
							if (!empty($_POST['valider']) AND isset($_POST['valider'])) {
								if (!empty($_POST['titre']) AND isset($_POST['titre'])) {
									if (!empty($_POST['contenu']) AND isset($_POST['contenu'])) {
										$enregistrement = $pdo->prepare('INSERT INTO programme_animation SET title = ?, contenu = ?, auteur = ?');
										$enregistrement->execute(array($_POST['titre'], $_POST['contenu'], $utilisateur['username']));
										?>
										<div class="alert alert-success" type="alert">
											Le programme d'animation a bien été ajouté <?php echo sanitize($utilisateur['username']); ?> !
										</div>
									<?php } else { ?>
										<div class="alert alert-danger" type="alert">
											Vous n'avez renseigné aucun contenu.
										</div>
									<?php }
								}
								else {
									?>
									<div class="alert alert-danger" type="alert">
										Vous n'avez renseigné aucun titre.
									</div>
								<?php }
							}
							?>
							<div class="container">
								<div class="row">
									<div class="col-md-6">
										<center><h4>Récapitulatifs des programmes</h4></center>
										<table class="table table-striped">
											<thead>
												<tr>
													<th>Titre</th>
													<th>Auteur</th>
													<th>Modifier</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$programmes = $pdo->prepare('SELECT * FROM programme_animation ORDER BY id DESC');
												$programmes->execute();
												while ($archives = $programmes->fetch()) {
													?>
													<tr>
														<td><?php echo sanitize($archives['title']); ?></td>
														<td><strong><?php echo sanitize($archives['auteur']); ?></strong></td>
														<td><a href="modifier_planning.php?planning=<?php echo sanitize($archives['id']); ?>" class="btn btn-outline-info" target="_blank">Modifier</a></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
									<div class="col-md-6">
										<center><h4>Publier un programme</h4></center>
										<form method="POST" action="">
											<label>Titre du programme : (Animation de juillet, décembre, d'été)</label>
											<input type="text" name="titre" class="form-control" placeholder="Mettez le titre du programme d'animations">
											<br/>
											<label>Contenu du programme : </label>
											<textarea name="contenu" class="form-control" placeholder="Mettez le programme d'animations ici">
											</textarea>
											<input type="submit" class="btn btn-sm btn-info" name="valider" value="Publier le programme">
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php include('../elements/footer.php'); ?>
		</body>
		</html>