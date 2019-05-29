<?php 
session_start();
include('membres/base.php');
include('membres/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mangas'Fan - L'actualité sur les mangas et animes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="icon" href="images/favicon.png"/>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
	<div id="bloc_page">
		<?php include('elements/header.php'); ?>
		<h2 class="titre_principal_news">Archives des news</h2>
		<div class="conteneur">
			<?php 
				$archives_news = $pdo->prepare('SELECT id, titre, auteur, theme, description, DATE_FORMAT(date_creation, \'%d %M %Y à %Hh %imin\') AS date_news, visible FROM billets WHERE visible = 0 ORDER BY id DESC LIMIT 100');
				$archives_news->execute();
				while($archives = $archives_news->fetch()){
				?>
				<div class="element">
					<div class="class_test">
						<img src="<?php echo $archives['theme']; ?>" id="image_news" />
						<p class="text">
							<a href="commentaire/<?= sanitize(traduire_nom($archives['titre'])); ?>">
								<span class="btn btn-outline-light">
									Voir la news
								</span>
							</a>
						</p>
					</div>
						<p class="titre_news">
							<a href="commentaire/<?= sanitize(traduire_nom($archives['titre'])); ?>">
								<?php echo sanitize($archives['titre']); ?>
							</a>
						</p>
						<p class="description_news">
							<?php echo $archives['description']; ?>
						</p>
						<div id="bloc_auteur">
							<span class="auteur_news"><?php echo $archives['auteur']; ?></span>
							<span class="date_news">Le <?php echo $archives['date_news']; ?></span>
						</div>
					</div>
					<?php } ?>
				</div>
			<?php include('elements/footer.php'); ?>
		</div>
	</body>
	</html>