<?php 
session_start();
include('membres/base.php');
include('membres/functions.php');
require_once 'markdown/Michelf/Markdown.inc.php';
require_once 'markdown/Michelf/MarkdownExtra.inc.php';
use Michelf\Markdown;
?>
<!DOCTYPE html>
<html lang="FR">
<head>
	<meta charset="utf-8">
	<title>Mangas'Fan - L'actualité sur les mangas et animes</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="icon" href="images/favicon.png"/>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-129397962-1');
	</script>
	<meta name=”twitter:card” content="summary_large_image" />
	<meta name="twitter:site" content="@Mangas_Fans" />
	<meta name="twitter:creator" content="@Mangas_Fans" />
	<meta property="og:site_name" content="mangasfan.fr"/>
	<meta property="og:url" content="https://www.mangasfan.fr" />
	<meta property="og:title" content="Mangas'Fan - L'actualité des mangas et animes" />
	<meta property="og:description" content="Site concernant les mangas et les animes anciens et récents. Retrouvez une communauté de fans pour discuter et partagez !" />
	<meta property="og:image" content="https://www.pixenli.com/image/J6FtHnhW" />
	<meta name="twitter:title" content="Mangas'Fan - L'actualité des mangas et animes">
	<meta name="twitter:description" content="Site concernant les mangas et les animes anciens et récents. Retrouvez une communauté de fans pour discuter et partagez !">
	<meta name="twitter:image" content="https://www.pixenli.com/image/J6FtHnhW">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="style/index_site.css" />
	<link rel="stylesheet" media="screen and (max-device-width: 480px)" href="style/style_mobile.css" />
	<meta name="description" content="Site concernant les mangas et les animes anciens et récents. Retrouvez une communauté de fans pour discuter et partagez !"/>
	<meta name="keywords" content="Mangas, Fans, Animes, Site Mangas, Produits, Adaptation, Contenu, Site, Communauté, Partenaires, Actualités, Sorties, Débats, Site de discussions mangas, Manga, Fan Manga, Mangas fans, Jeux, Jeux de mangas, Manga Fan"/>
</head>
<body>
	<?php include('elements/header.php'); ?>
	<br/>
	<?php $select_anim = $pdo->prepare("SELECT * FROM animation");
	$select_anim->execute();
	$animation = $select_anim->fetch();
	if($animation['visible'] == 1){
		switch ($animation['title']) {
			case 'animation':
			?>
			<div class="alert alert-success" role="alert">
				<h4 class="alert-heading"> 
					Animation en cours
				</h4>
				<hr>
				<?php   
				$resultat = Markdown::defaultTransform($animation['contenu']);
				echo htmlspecialchars_decode(sanitize($resultat)); ?>
			</div> 
			<div class="alert alert-info" role="alert">
				Vous souhaitez connaître toutes nos animations à venir ? Alors dans ce cas-là, consulter notre <a href="data/programme_animations.php" target="_blank">programme d'animations</a> !
			</div>
			<?php
			break;
			case 'recrutement':
			?>	
			<div class="alert alert-info" role="alert">
				<h4 class="alert-heading">
					Recrutements en cours
				</h4>
				<hr>
				<?php   
				$resultat = Markdown::defaultTransform($animation['contenu']);
				echo htmlspecialchars_decode(sanitize($resultat)); 
				?>
			</div> 
			<?php
			break;
			case 'annonce':
			?>
			<div class="alert alert-warning" role="alert">
				<h4 class="alert-heading">
					Annonce importante
				</h4>
				<hr>
				<?php   
				$resultat = Markdown::defaultTransform($animation['contenu']);
				echo htmlspecialchars_decode(sanitize($resultat)); 
				?>
			</div> 
			<?php 
			break;
			case 'maj':
			?>
			<div class="alert alert-dark" role="alert">
				<h4 class="alert-heading">
					Dernière mise à jour
				</h4>
				<hr>
				<?php   
				$resultat = Markdown::defaultTransform($animation['contenu']);
				echo htmlspecialchars_decode(sanitize($resultat)); 
				?>
			</div> 
			<?php 
			break;
		}
	}
	?>
	<h2 class="titre_principal_news">
		News du site
	</h2>
	<hr class="tiret_news">
	<div class="conteneur">
		<?php 
		$news_site = $pdo->prepare('SELECT id, titre, auteur, theme, description, date_creation, visible FROM billets WHERE visible = 0 ORDER BY date_creation DESC LIMIT 9');
		$news_site->execute();
		while($news = $news_site->fetch()){
			if (date('Y-m-d H:i:s') >= $news['date_creation']) {
				?>
				<div class="element">
					<div class="effet_news">
						<img src="<?php echo htmlspecialchars($news['theme']); ?>" class="image_news" alt="Image - <?php echo htmlspecialchars($news['titre']); ?>" />
						<p class="text">
							<a href="commentaire/<?= htmlspecialchars(traduire_nom($news['titre'])); ?>">
								<span class="btn btn-outline-light">
									Voir la news
								</span>
							</a>
						</p>
					</div>
					<p class="titre_news">
						<a href="commentaire/<?= htmlspecialchars(traduire_nom($news['titre'])); ?>"><?php echo htmlspecialchars($news['titre']); ?></a>
					</p>
					<p class="description_news"><?php echo htmlspecialchars($news['description']); ?></p>
					<div class="bloc_auteur">
						<span class="auteur_news"><?php echo htmlspecialchars($news['auteur']); ?></span>
						<span class="date_news">Le <?php echo date('d M Y à H:i', strtotime(htmlspecialchars($news['date_creation']))); ?></span>
					</div>
				</div>
				<?php 
			}
		}
		?>
	</div>
	<a href="archives_news.php" target="_blank">
		<img src="images/test.png" class="image_archive" alt="Image des archives" />
	</a>
	<img src="images/team.png" alt="Team Mangas'Fan" class="image_team" />
	<?php 
	$liste_staff = $pdo->prepare('SELECT id, username, grade, manga, DATE_FORMAT(confirmed_at, \'%d/%m/%Y\') AS date_inscription, chef FROM users WHERE grade >= 3 AND username != "Équipe du site" ORDER BY grade DESC');
	$liste_staff->execute();
	?>
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
			<?php while($staff = $liste_staff->fetch()){ ?>
				<tr>
					<td>» <a href="profil/voirprofil.php?membre=<?php echo htmlspecialchars($staff['id']); ?>&action=consulter" class="lien_staff"><?php echo htmlspecialchars($staff['username']); ?></a></td>
					<td><?php if($staff['chef'] != 0){ echo chef(htmlspecialchars($staff['chef'])); } else { echo statut(htmlspecialchars($staff['grade'])); } ?></td>
					<td class="tableau_mobile"><?php if($staff['manga'] == NULL){ echo "Non renseigné";} else { echo htmlspecialchars($staff['manga']); } ?></td>
					<td class="tableau_mobile"><?php echo htmlspecialchars($staff['date_inscription']); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2 class="titre_principal_news">Derniers mangas</h2>
				<div class="conteneur_dossiers">
					<?php
					$derniers_mangas = $pdo->prepare('SELECT titre, vignette FROM billets_mangas ORDER BY id DESC LIMIT 2');
					$derniers_mangas->execute();
					while ($mangas = $derniers_mangas->fetch()) { 
						?>
						<div class="gallery">
							<a href="../mangas/<?= traduire_nom(htmlspecialchars($mangas['titre'])); ?>">
								<img src="<?= htmlspecialchars($mangas['vignette']); ?>" alt="Image de <?php echo htmlspecialchars($mangas['titre']); ?>" />
							</a>
							<div class="desc">
								<?php echo htmlspecialchars($mangas['titre']); ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="col-md-6">
				<h2 class="titre_principal_news">Derniers jeux</h2>
				<div class="conteneur_dossiers">
					<?php
					$derniers_jeux = $pdo->prepare('SELECT titre, vignette FROM billets_jeux ORDER BY id DESC LIMIT 2');
					$derniers_jeux->execute();
					while ($jeux = $derniers_jeux->fetch()) { 
						?>
						<div class="gallery">
							<a href="../jeux/<?= traduire_nom(htmlspecialchars($jeux['titre'])); ?>">
								<img src="<?= htmlspecialchars($jeux['vignette']); ?>" alt="Image de <?php echo htmlspecialchars($jeux['titre']); ?>" />
							</a>
							<div class="desc">
								<?php echo htmlspecialchars($jeux['titre']); ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php include('elements/footer.php'); ?>
</body>
</html>