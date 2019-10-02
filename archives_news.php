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
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style/index_site.css" />
	<link rel="stylesheet" media="screen and (max-device-width: 480px)" href="style/style_mobile.css" />
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-129397962-1');
	</script>
</head>
<body>
	<?php include('elements/header.php'); ?>
	<h2 class="titre_principal_news">Archives des news</h2>
	<div class="conteneur">
		<?php 
		$archives_news = $pdo->prepare('SELECT id, titre, auteur, theme, description, date_creation, visible FROM billets WHERE visible = 0 ORDER BY id DESC LIMIT 50');
		$archives_news->execute();
		while($archives = $archives_news->fetch()){
			if (date('Y-m-d H:i:s') >= $archives['date_creation']) {
				?>
				<div class="element">
					<div class="effet_news">
						<img src="<?php echo htmlspecialchars($archives['theme']); ?>" class="image_news" alt="Image - <?php echo htmlspecialchars($archives['titre']); ?>" />
						<p class="text">
							<a href="commentaire/<?= htmlspecialchars(traduire_nom($archives['titre'])); ?>">
								<span class="btn btn-outline-light">
									Voir la news
								</span>
							</a>
						</p>
					</div>
					<p class="titre_news">
						<a href="commentaire/<?= htmlspecialchars(traduire_nom($archives['titre'])); ?>"><?php echo htmlspecialchars($archives['titre']); ?></a>
					</p>
					<p class="description_news"><?php echo htmlspecialchars($archives['description']); ?></p>
					<div class="bloc_auteur">
						<span class="auteur_news"><?php echo htmlspecialchars($archives['auteur']); ?></span>
						<span class="date_news">Le <?php echo date('d M Y à H:i', strtotime(htmlspecialchars($archives['date_creation']))); ?></span>
					</div>
				</div>
			<?php } } ?>
		</div>
		<?php include('elements/footer.php'); ?>
	</body>
	</html>