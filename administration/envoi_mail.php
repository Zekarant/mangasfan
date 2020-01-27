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
		<title>Mangas'Fan - Pannel de mails</title>
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
		<div id="bloc_page">
			<?php
			$user = $pdo->prepare("SELECT * FROM users WHERE username = ?");
			$user->execute(array($utilisateur['username']));
			$user->fetch();
			if($_SESSION['auth'] === false AND $utilisateur['grade'] <= 4){
				echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
				exit; }
				include('../elements/nav_admin.php') ?> 
				<section class="marge_page">
					<h3 class="titre_principal_news">
						Envoyer un mail aux membres
					</h3>
					<div class="marge_page">
						<?php
						if(isset($_POST['mailform'])){
							$enregistrement = $pdo->prepare("INSERT INTO newsletters_historique (titre, contenu, date_envoi) VALUES(?, ?, NOW())");
							$enregistrement->execute(array($_POST['titre_mail'], $_POST['news_mail']));
							$mail = $pdo->prepare('SELECT email FROM users WHERE confirmed_at IS NOT NULL');
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
								Mail important - <span style="color: #ff5980">M</span>angas\'<span style="color: #00daf9">F</span>an
								</div>
								<hr/>
								</div>
								<div style="padding: 5px;">
								' . $_POST['news_mail'] . '
								</div>
								<div align="center">
								<div style="background-color: #333333; padding: 5px; border-top: 3px solid #DDDDDD; color: white; text-align: center;">Mangas\'Fan © 2017 - 2019. Développé par Zekarant et Nico. Tous droits réservés.
								</div>
								</div>
								</div>
								</body>
								</html>
								';

								mail($envoie_mail['email'], $_POST['titre_mail'], $message, $header);

							}
							echo "<div class='alert alert-success' role='alert'>Le mail a bien été envoyé !</div>";
						}
						?>
						<form method="POST" action="">
							Titre du mail : <input type="text" class="form-control" name="titre_mail" placeholder="Entrez le titre du mail"><br/>
							Contenu du mail : <textarea name="news_mail" id="contenu_mail" placeholder="Entrez le contenu du mail"></textarea><br/>
							<input type="submit" class="btn btn-sm btn-info" value="Recevoir un mail !" name="mailform"/>
						</form>
					</div>
				</section>
				<?php include('../elements/footer.php'); ?>
			</div>
		</body>
		</html>
