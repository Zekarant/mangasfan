<?php 
	session_start();
	include('membres/base.php');
	include('membres/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mangas'Fan - Bannissement</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  	<link rel="icon" href="images/favicon.png" />
  	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body id="bloc_bannissement">
		<div class="titre_principal_news">
			Bannissement de <?php echo $utilisateur['username']; ?>
		</div>
		<center><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Death_Note%2C_Book.svg/640px-Death_Note%2C_Book.svg.png" class="image_bannissement" title="Vous avez été victime du Death Note de Mangas'Fan." /><br/><br/>
		<p>Si vous vous trouvez sur cette page, c'est que votre compte a été banni des services de Mangas'Fan. Pour obtenir les raisons de ce bannissement, nous vous invitons à consulter vos MP et/ou vos emails dans lequel vous trouverez le motif et la durée de votre bannissement.<br/><br/>
		<b>Rappel : </b>votre adresse Mail est : <?php echo sanitize($utilisateur['email']); ?>.<br/><br/>
		<i>Adresse Mail de contact en cas de besoin : contact@mangasfan.fr</i></p>
		<a href="index.php" class="btn btn-primary">Index du site</a> <a href="profil/messagesprives.php" class="btn btn-info">Voir mes MP's</a><br/><br/>
		<span class="footer_ban">© Mangas'Fan - 2017 - 2019</span></center>
</body>
</html>