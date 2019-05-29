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
		<h2 class="titre_principal_news">News du site</h2>
		<hr class="tiret_news">
		<div class="conteneur">
			<?php 
			$news_site = $pdo->prepare('SELECT id, titre, auteur, theme, description, DATE_FORMAT(date_creation, \'%d %M %Y à %Hh %imin\') AS date_news, visible FROM billets WHERE visible = 0 ORDER BY id DESC LIMIT 9');
			$news_site->execute();
			while($news = $news_site->fetch()){
				?>
				<div class="element">
					<div class="class_test">
						<img src="<?php echo $news['theme']; ?>" id="image_news" />
						<p class="text">
							<a href="commentaire/<?= sanitize(traduire_nom($news['titre'])); ?>">
								<span class="btn btn-outline-light">
									Voir la news
								</span>
							</a>
						</p>
					</div>
						<p class="titre_news">
							<a href="commentaire/<?= sanitize(traduire_nom($news['titre'])); ?>">
								<?php echo sanitize($news['titre']); ?>
							</a>
						</p>
						<p class="description_news">
							<?php echo $news['description']; ?>
						</p>
						<div id="bloc_auteur">
							<span class="auteur_news"><?php echo $news['auteur']; ?></span>
							<span class="date_news">Le <?php echo $news['date_news']; ?></span>
						</div>
				</div>
			<?php } ?>
		</div>
		<center><a href="archives_news.php"><img src="images/test.png" target="_blank" class="image_archive"/></a></center>
		<img src="images/team.png" alt="Team Mangas'Fan" class="image_team" />
		<table class="table">
			<thead>
				<tr>
					<th>Pseudo</th>
					<th>Rang</th>
					<th class="tableau_mobile">Mangas Favori</th>
					<th class="tableau_mobile">Date d'inscription</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$liste_staff = $pdo->prepare('SELECT id, username, grade, manga, DATE_FORMAT(confirmed_at, \'%d/%m/%Y\') AS date_inscription FROM users WHERE grade >= 3 AND username != "Équipe du site" ORDER BY grade DESC');
				$liste_staff->execute();
				while($staff = $liste_staff->fetch()){
					?>
					<tr>
						<td><a href="profil/voirprofil.php?membre=<?php echo $staff['id'];?>&action=consulter" class="lien_staff"><?php echo $staff['username']; ?></td>
							<td><?php echo statut($staff['grade']); ?></td>
							<td class="tableau_mobile"><?php if($staff['manga'] == NULL){ echo'Non renseigné';} else {echo $staff['manga'];} ?></td>
							<td class="tableau_mobile"><?php echo $staff['date_inscription']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<h2 class="titre_principal_news">Derniers mangas</h2>
						<div id="conteneur_dossiers">
							<?php
							$derniers_mangas = $pdo->prepare('SELECT titre, vignette FROM billets_mangas ORDER BY id DESC LIMIT 2');
							$derniers_mangas->execute();
							while ($mangas = $derniers_mangas->fetch()) {
								?>

								<div class="gallery">
									<a href="../mangas/<?= traduire_nom($mangas['titre']);?>">
										<img src="<?= $mangas['vignette']; ?>" />
									</a>
									<div class="desc"><?= $mangas['titre']; ?></div>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-sm-6">
						<h2 class="titre_principal_news">Derniers jeux</h2>
						<div id="conteneur_dossiers">
							<?php
							$derniers_jeux = $pdo->prepare('SELECT titre, vignette FROM billets_jeux ORDER BY id DESC LIMIT 2');
							$derniers_jeux->execute();
							while ($jeux = $derniers_jeux->fetch()) {
								?>

								<div class="gallery">
									<a href="../jeux_video/<?= traduire_nom($jeux['titre']);?>">
										<img src="<?= $jeux['vignette']; ?>" />
									</a>
									<div class="desc"><?= $jeux['titre']; ?></div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php include('elements/footer.php'); ?>
		</div>
	</body>
	</html>