<?php 
session_start();
include('theme_temporaire.php');
include('inc/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mangas'Fan - Maintenance</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  	<link rel="icon" href="images/favicon.png" />
  	<link rel="stylesheet" href="https://www.mangasfan.fr/bootstrap/css/bootstrap.min.css" />
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
</head>
<body>
	<div id="bloc_bannissement">
		<div id="titre_news">
			Maintenance <span class="couleur_mangas">en</span> <span class="couleur_fans">cours</span>
		</div>
		<center><img src="https://vignette.wikia.nocookie.net/vsbattles/images/c/c0/Susanoo_2.png/revision/latest/scale-to-width-down/180?cb=20160925032203" class="image_bannissement" title="Nos services sont temporairement indisponibles." /><br/><br/>
		<p>Mangas'Fan subit actuellement une maintenance de quelques minutes voir quelques heures tout au plus. Pendant cette période, aucune page du site n'est accessible. Pour restez informé de l'avancement, vous pouvez consulter notre Twitter où nous vous tiendrons informés de notre avancement.<br/><br/>
		Nous nous excusons pour la gêne occasionnée. Nos services reviennent vite !<br/><br/>
		<i>Adresse Mail de contact en cas de besoin : contact@mangasfan.fr</i></p>
		<a href="https://www.mangasfan.fr/index.php" class="btn btn-primary">Index du site</a> <a href="https://twitter.com/Mangas_fans" class="btn btn-primary">Notre Twitter</a> <a href="https://www.facebook.com/mangasfansite/" class="btn btn-primary">Notre Facebook</a><br/><br/>
		<span class="footer_ban">© Mangas'Fan - 2017 - 2019</span></center>
	</div>
</body>
</html>