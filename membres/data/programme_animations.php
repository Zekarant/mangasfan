<?php
	session_start();
	include('../base.php');
	include('../functions.php');
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
	<title>Programme d'animation - Mangas'Fan</title>
	<link rel="icon" href="../../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
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
	<div id="bloc_page">
		<?php include('../../elements/header.php'); ?>
		<section class="marge_page">
			<?php 
				$programme_animation = $pdo->prepare('SELECT * FROM programme_animation ORDER BY id DESC LIMIT 1');
				$programme_animation->execute();
				$programme = $programme_animation->fetch();
			?>
			<h3 class="titre_principal_news">
				<?php echo sanitize($programme['title']); ?> - Mangas'Fan
			</h3>
			<hr>
			<?php 
				echo htmlspecialchars_decode(sanitize($programme['contenu'])); 
			?>
			<hr>
			<small class="text-muted">
				Ce programme d'animations est susceptible de subir des modifications. Posté par <?php echo sanitize($programme['auteur']); ?>. Validé par l'équipe d'administration du site.
			</small>
		</section>
		<?php include('../../elements/footer.php'); ?>
	</div>
</body>
</html>