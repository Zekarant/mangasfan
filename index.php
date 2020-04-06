<?php 
session_start();
include('membres/base.php');
include('membres/functions.php');
require_once 'markdown/Michelf/Markdown.inc.php';
require_once 'markdown/Michelf/MarkdownExtra.inc.php';
use Michelf\Markdown;
$select_anim = $pdo->prepare("SELECT * FROM animation");
$select_anim->execute();
$animation = $select_anim->fetch();
$resultat = Markdown::defaultTransform($animation['contenu']);
switch ($animation['title']) {
	case "animation":
	$titre = "Animation en cours";
	$couleur = "warning";
	break;
	case "recrutement":
	$titre = "Recrutements en cours";
	$couleur = "info";
	break;
	case "annonce":
	$titre = "Annonce importante";
	$couleur = "warning";
	break;
	case "maj":
	$titre = "Nouvelle mise à jour";
	$couleur = "primary";
	break;
}
// Récupération news du site
$news_site = $pdo->prepare('SELECT b.id AS id_news, b.titre, b.auteur, b.theme, b.description, b.date_creation, b.visible, u.id, u.username FROM billets b LEFT JOIN users u ON b.auteur = u.id WHERE visible = 0 ORDER BY date_creation DESC LIMIT 9');
$news_site->execute();
// Récupération de la liste du staff
$liste_staff = $pdo->prepare('SELECT id, username, grade, manga, sexe, DATE_FORMAT(confirmed_at, \'%d/%m/%Y\') AS date_inscription, chef FROM users WHERE grade >= 3 AND username != "Équipe du site" ORDER BY grade DESC');
$liste_staff->execute();
// Récupération des deux derniers mangas ajoutés au site
$derniers_mangas = $pdo->prepare('SELECT titre, vignette FROM billets_mangas ORDER BY id DESC LIMIT 2');
$derniers_mangas->execute();
// Récupération des deux derniers jeux ajoutés au site
$derniers_jeux = $pdo->prepare('SELECT titre, vignette FROM billets_jeux ORDER BY id DESC LIMIT 2');
$derniers_jeux->execute();?>
<!DOCTYPE html>
<html lang="fr">
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
	<meta property="og:description" content="Toute l'actualité des animes sur Mangas'Fan ! News, mangas, animes, jeux, tout est à portée de main ! Votre communauté de fans sur Mangas'Fan." />
	<meta property="og:image" content="https://www.pixenli.com/image/J6FtHnhW" />
	<meta name="twitter:title" content="Mangas'Fan - L'actualité des mangas et animes">
	<meta name="twitter:description" content="Toute l'actualité des animes sur Mangas'Fan ! News, mangas, animes, jeux, tout est à portée de main ! Votre communauté de fans sur Mangas'Fan.">
	<meta name="twitter:image" content="https://www.pixenli.com/image/J6FtHnhW">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="elements/konamicode.js"></script>
	<link rel="stylesheet" type="text/css" href="style/index_site.css" />
	<meta name="description" content="Toute l'actualité des animes sur Mangas'Fan ! News, mangas, animes, jeux, tout est à portée de main ! Votre communauté de fans sur Mangas'Fan."/>
	<meta name="keywords" content="Mangas, Fan, Animes, Site Mangas, Produits, Adaptation, Contenu, Site, Communauté, Partenaires, Actualités, Sorties, Débats, Site de discussions mangas, Manga, Fan Manga, Mangas fans, Jeux, Jeux de mangas, Manga Fan, Mangas'Fan"/>
</head>
<body>
	<?php include('elements/header.php'); ?>
	<section>
		<?php include("elements/messages.php"); ?>
		<br/>
		<?php if($animation['visible'] == 1){ ?>
			<div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
				<h4 class="alert-heading"><?= sanitize($titre); ?></h4>
				<hr>
				<?= htmlspecialchars_decode(sanitize($resultat)); ?>
			</div>
		<?php } ?>
		<h2 class="titre_principal_news">News du site</h2>
		<hr class="tiret_news">
		<div class="conteneur">
			<?php while($news = $news_site->fetch()){
				if (date('Y-m-d H:i:s') >= $news['date_creation']) {
					?>
					<div class="element">
						<div class="effet_news">
							<img src="<?= sanitize($news['theme']); ?>" class="image_news" alt="Image - <?= sanitize($news['titre']); ?>" />
							<p class="text">
								<a href="commentaire/<?= sanitize(traduire_nom($news['titre'])); ?>">
									<span class="btn btn-outline-light">Voir la news</span>
								</a>
							</p>
						</div>
						<p class="titre_news">
							<a href="commentaire/<?= sanitize(traduire_nom($news['titre'])); ?>"><?= sanitize($news['titre']); ?></a>
						</p>
						<p class="description_news"><?= sanitize($news['description']); ?></p>
						<div class="bloc_auteur">
							<span class="auteur_news"><?= sanitize($news['username']); ?></span>
							<span class="date_news">Le <?= date('d M Y à H:i', strtotime(sanitize($news['date_creation']))); ?></span>
						</div>
					</div>
					<?php 
				}
			}
			?>
		</div>
		<a href="archives_news.php" target="_blank"><img src="images/test.png" class="image_archive" alt="Image des archives" /></a>
		<div class="container">
			<div class="row">
				<div class="offset-2 col-md-3">
					<img src="images/team.png" alt="Team Mangas'Fan" class="image_team" />
				</div>
				<div class="col-md-7 titre_staff">
					<h2>L'équipe bénévole de Mangas'Fan</h2>
				</div>
			</div>
		</div>
		<div class="table-responsive">
		<table class="table-responsive">
			<thead>
				<tr>
					<th>Pseudo</th>
					<th>Rang</th>
					<th>Mangas Favori</th>
					<th>Date d'inscription</th>
				</tr>
			</thead>
			<tbody>
				<?php while($staff = $liste_staff->fetch()){ ?>
					<tr>
						<td><a href="profil/profil-<?= sanitize(traduire_nom($staff['id'])); ?>"><?= sanitize($staff['username']); ?></a></td>
						<td><?php if($staff['chef'] != 0){ 
							echo chef(sanitize($staff['chef'])); 
						} else { 
							echo statut($staff['grade'], $staff['sexe']); 
						} ?></td>
						<td><?php if($staff['manga'] == NULL){ 
							echo "Non renseigné";
						} else { 
							echo sanitize($staff['manga']); 
						} ?></td>
						<td><?= sanitize($staff['date_inscription']); ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
		<h2 class="titre_principal_news">Derniers ajouts mangas/jeux</h2>
		<hr>
		<div class="container">
			<div class="row">
				<?php while ($mangas = $derniers_mangas->fetch()) { ?>
					<div class="col-md-3">
						<div class="card">
							<a href="../mangas/<?= traduire_nom(htmlspecialchars($mangas['titre'])); ?>" target="_blank">
								<img src="<?= htmlspecialchars($mangas['vignette']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($mangas['titre']); ?>">
							</a>
							<div class="card-body">
								<p class="card-text texte-gallery"><?= htmlspecialchars($mangas['titre']); ?></p>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php while ($jeux = $derniers_jeux->fetch()) { ?>
					<div class="col-md-3">
						<div class="card">
							<a href="../jeux-video/<?= traduire_nom(htmlspecialchars($jeux['titre'])); ?>" target="_blank">
								<img src="<?= htmlspecialchars($jeux['vignette']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($jeux['titre']); ?>">
							</a>
							<div class="card-body">
								<p class="card-text texte-gallery"><?= htmlspecialchars($jeux['titre']); ?></p>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>
	<?php include('elements/footer.php'); ?>
</body>
</html>