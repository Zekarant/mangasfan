	<?php
	session_start();
	include('../membres/base.php'); 
	include('../membres/functions.php');
	if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
	{ 
		$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
		$user->execute(array($_SESSION['auth']['id']));
		$utilisateur = $user->fetch(); 
	}
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8" />
		<title>Mangas'Fan - Pannel de newsletters</title>
		<link rel="icon" href="../images/favicon.png"/>
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
		<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="../style.css">
		<script type="text/javascript" src="https://www.mangasfan.fr/tinymce/js/tinymce/tinymce.min.js"></script>
		<script type="text/javascript" src="https://www.mangasfan.fr/tinymce/js/tinymce/tinymce.js"></script>
		<script>
			tinymce.init({
				selector: 'textarea',
				height: 250,
				theme: 'modern',
				language: 'fr_FR',
				plugins: ['print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help'],
				toolbar: 'insert | undo redo |  formatselect | bold italic underline backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
				content_css: [
				'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
				'//www.tinymce.com/css/codepen.min.css']
			});
		</script>
	</head>
	<body>
		<?php
		$user = $pdo->prepare("SELECT * FROM users WHERE username = ?");
		$user->execute(array($utilisateur['username']));
		$user->fetch();
		if($_SESSION['auth'] === false AND $utilisateur['grade'] <= 4){
			echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
			exit; } ?> 
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
										<span>Administration</span>
									</h6>
									<ul class="nav flex-column">
										<li class="nav-item">
											<a class="nav-link active" href="#maintenances">  
												» Maintenances
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#grades">
												» Gestion des grades
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#sanctions">
												» Sanctions
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#avertis">
												» Membres avertis
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#bannis">
												» Membres bannis
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="#fiches">
												» Fiches des membres
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
											<a class="nav-link" href="../membres/liste_membres.php">
												» Liste des membres
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" href="envoi_newsletter.php">
												» Newsletter
											</a>
										</li>
									</ul>
								</nav>
							</div>
							<div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
								<?php include ('../elements/nav_admin.php'); ?>
								
								<h3 class="titre_principal_news">
									Envoyer une newsletter
								</h3>
								<div class="marge_page">
									<?php
									if(isset($_POST['mailform'])){
										$enregistrement = $pdo->prepare("INSERT INTO newsletters_historique (titre, contenu, date_envoi) VALUES(?, ?, NOW())");
										$enregistrement->execute(array($_POST['titre_newsletter'], $_POST['news_newsletter']));
										$mail = $pdo->prepare('SELECT email FROM newsletter');
										$mail->execute();

										while ($envoie_mail = $mail->fetch()) {
											$header="MIME-Version: 1.0\r\n";
											$header.='From:"Mangas\'Fan"<contact@mangasfan.fr>'."\n";
											$header.='Content-Type:text/html; charset="uft-8"'."\n";
											$header.='Content-Transfer-Encoding: 8bit';

											$message='
											<html>
											<body>
											<div style="border: 2px solid black;">
											<div align="center" style="background-image:linear-gradient(#BAC1C8, #474747);">
											<img src="https://zupimages.net/up/17/24/4kp2.png" style="width: 100%;"/>
											<div style="font-family: Oswald; font-size: 23px; color: white;">
											Newsletter mensuelle - <span style="color: #ff5980">M</span>angas\'<span style="color: #00daf9">F</span>an
											</div>
											<hr/>
											</div>
											<div style="padding: 5px;">
											' . $_POST['news_newsletter'] . '
											</div>
											<div align="center">
											<div style="background-color: #333333; padding: 5px; border-top: 3px solid #DDDDDD; color: white; text-align: center;">Mangas\'Fan © 2017 - 2019. Développé par Zekarant, Nico et Lucryio. Tous droits réservés.
											</div>
											</div>
											</div>
											</body>
											</html>
											';

											mail($envoie_mail['email'], $_POST['titre_newsletter'], $message, $header);

										}
										echo "<div class='alert alert-success' role='alert'>La newsletter a bien été envoyée !</div>";
									}
									?>
									<form method="POST" action="">
										Titre de la newsletter : <input type="text" class="form-control" name="titre_newsletter" placeholder="Entrez le titre de la news"><br/>
										Contenu de la newsletter : <textarea name="news_newsletter" id="contenu_newsletter" placeholder="Entrez le contenu de la newsletter"></textarea><br/>
										<input type="submit" class="btn btn-sm btn-info" value="Recevoir un mail !" name="mailform"/>
									</form>
								</div>
							</div>
						</div>
					</div>
					<?php include('../elements/footer.php') ?>

				</body>
				</html>
