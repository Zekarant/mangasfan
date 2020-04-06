<?php 
session_start();
include('membres/base.php');
include('membres/functions.php');
$current_url = $_SERVER['REQUEST_URI'];
if(strpos($current_url,'/commentaire.php'))
{
	$variable = intval($_GET['billet']);
	$billet = $pdo->prepare("SELECT titre FROM billets WHERE id = ?");
	$billet->execute(array($variable));
	$billet_title = $billet->fetch();
}
$string = name_url($_GET['billet']);
if(stristr($string, '-') === FALSE && !is_numeric($_GET['billet'])) {
	header("Status: 301 Moved Permanently", false, 301);
	header("Location: ".traduire_nom($string));
	die();
}
if (isset($_GET['billet']) && is_numeric($_GET['billet'])) {
	$id_news = $_GET['billet'];
	$recuperation_id = $pdo->query("SELECT id, titre FROM billets");
	while($parcours_id = $recuperation_id->fetch()){
		if($parcours_id['id'] == $id_news){
			header("Status: 301 Moved Permanently", false, 301);
			header("Location: commentaire/".traduire_nom($parcours_id['titre']));
			die();
		}
	}
}
if(isset($_GET['billet']) && !is_numeric($_GET['billet'])){
	$id_news = $_GET['billet'];
	$recuperation_id = $pdo->query("SELECT id, titre FROM billets");
	while($parcours_id = $recuperation_id->fetch()){
		if(traduire_nom($parcours_id['titre']) == $id_news){
			$id_news = $parcours_id['id'];
		}
	}
}
if (isset($_POST['envoyer_commentaire'])) {
	if (isset($_SESSION['auth'])) {
		if ($utilisateur['grade'] > 1 && $utilisateur['grade'] <= 10) {
			$inserer_commentaire = $pdo->prepare('INSERT INTO commentaires(id_billet, id_membre, commentaire, date_commentaire) VALUES(?, ?, ?, NOW())');
			$inserer_commentaire->execute(array($id_news, $utilisateur['id'], $_POST['comme']));
			header('Location: /');
			exit();
		}
	}
}
if (isset($_POST['supprimer'])) {
	if (isset($_SESSION['auth'])) {
		if ($utilisateur['grade'] > 1 && $utilisateur['grade'] <= 10) {
			if ($utilisateur['id'] == $commentaires['id_membre'] || $utilisateur['grade'] >= 9) {
				$commentaire_concerne = $_POST['id_suppr'];
				$supprimer_commentaire = $pdo->prepare('DELETE FROM commentaires WHERE id = ?');
				$supprimer_commentaire->execute(array($commentaire_concerne));
				header('Location: /');
				exit();
			}
		}
	}
}
$charger_news = $pdo->prepare('SELECT b.id, b.titre, b.contenu, b.date_creation, b.theme, b.description, b.keywords, b.auteur, b.sources, u.id, u.username, u.grade FROM billets b LEFT JOIN users u ON u.id = b.auteur WHERE b.id = ?');
$charger_news->execute(array($id_news));
$news = $charger_news->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title><?= htmlspecialchars($news['titre']); ?> - Mangas'Fan</title>
	<link rel="icon" href="../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta name="description" content="<?= htmlspecialchars($news['description']); ?>"/>
	<?php if(!empty($news['keywords'])){ ?>
		<meta name="keywords" content="<?= htmlspecialchars($news['keywords']); ?>"/>
	<?php } ?>
	<meta property="og:site_name" content="mangasfan.fr"/>
	<meta property="og:url" content="https://www.mangasfan.fr/commentaire/<?= htmlspecialchars(traduire_nom($news['titre'])); ?>" />
	<meta property="og:title" content="<?= htmlspecialchars($news['titre']); ?> - Mangas'Fan" />
	<meta property="og:description" content="<?= htmlspecialchars($news['description']); ?>" />
	<meta property="og:image" content="<?= htmlspecialchars($news['theme']); ?>" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@MangasFanOff" />
	<meta name="twitter:creator" content="@MangasFanOff" />
	<meta name="twitter:title" content="<?php echo htmlspecialchars($news['titre']); ?>">
	<meta name="twitter:description" content="<?php echo htmlspecialchars($news['description']); ?>">
	<meta name="twitter:image" content="<?php echo htmlspecialchars($news['theme']); ?>">
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-129397962-1');
	</script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../style.css">
</head>
<body>
	<?php include('elements/header.php'); ?>
	<section>
		<?php if (isset($_SESSION['auth']) && $utilisateur['grade'] == 6 || isset($_SESSION['auth']) && $utilisateur['grade'] >= 9) { ?>
			<center><a href="../redaction/modifier_news.php?id_news=<?= sanitize($id_news); ?>" class="btn btn-primary" target="_blank">Modifier la news</a></center>
			<hr>
		<?php } ?>
		<h1 class="titre_principal_news"><?= htmlspecialchars($news['titre']); ?></h1>
		<hr>
		<article>
			<?= htmlspecialchars_decode(htmlspecialchars($news['contenu']));
			?>
		</article>
		<?php if(!empty($news['sources'])){?>
			<div class="sources">
				<em>Sources : <?= sanitize($news['sources']); ?></em>
			</div>
		<?php } ?>
		<div class="auteur_news_commentaire">
			Publié par <?= rang_etat($news['grade'], $news['username']); ?> <em>le <?= date('d/m/Y à H:i', strtotime(htmlspecialchars($news['date_creation']))); ?></em>
		</div>
		<hr>
		<h3 id="titre_commentaire">
			ESPACE COMMENTAIRES
		</h3>
		<br/><br/>
		<?php if (isset($_SESSION['auth']) AND $utilisateur['grade'] >= 2) { ?>
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<center>
						<h4>Ajouter un commentaire :</h4>
						<br/>
						<a href="../membres/bbcode_active.html" class="lien_bbcode" target="blank"><strong>Voici la liste des bbcodes possibles</strong></a>
					</center>
				</div>
				<div class="col-md-10">
					<form method="POST" action="">
						<textarea name="comme" class="form-control" rows="10" placeholder="Écrivez-ici votre commentaire. (BBCodes utilisable)"></textarea>
						<center>
							<input type="submit" name="envoyer_commentaire" class="btn btn-info btn-sm">
						</center>
					</form>
				</div>
			</div>
		</div>
	<?php } elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] == 1) { ?>
		<div class='alert alert-warning' role='alert'>
			Étant banni, vous pouvez seulement lire les commentaires mais vous ne pouvez pas en poster.
		</div>
	<?php } else { ?>
		<div class='alert alert-danger' role='alert'>
			Vous devez être connecté pour pouvoir poster un commentaire ! Cependant, tu peux te <a href="../membres/connexion.php">connecter</a> ou tu peux <a href="../membres/inscription.php">t'inscrire</a> !
		</div>
	<?php } ?>
	<hr class='tiret_news'>
	<!-- Affichage des commentaires -->
	<?php
	$recuperer_commentaire = $pdo->prepare('SELECT c.id AS numero_billet, c.id_billet, c.id_membre, c.commentaire, c.date_commentaire, u.id, u.username, u.grade, u.avatar FROM commentaires c LEFT JOIN users u ON c.id_membre = u.id WHERE id_billet = ? ORDER BY date_commentaire DESC');
	$recuperer_commentaire->execute(array($id_news));
	if ($recuperer_commentaire->rowCount() == 0) { ?>
		<div class='alert alert-info' role='alert'>
			Il n'y a actuellement aucun commentaire sur cette news, n'hésitez pas à en poster !
		</div>
	<?php } while ($commentaires = $recuperer_commentaire->fetch()) { ?>
		<div class="espace_commentaire">
			<table name="tableau_commentaire_news">
				<tr name="commentaire_news_tr">
					<td name="colonne_commentaire" align="center" width="100px">
						<?php if (!empty($commentaires['avatar'])){
							if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", htmlspecialchars($commentaires['avatar']))) { 
								$image_avatar =  '../membres/images/avatars/'.htmlspecialchars($commentaires['avatar']);
							} else {
								$image_avatar = stripslashes(htmlspecialchars($commentaires['avatar']));
							} ?>
							<div class="avatar" style="box-shadow: 0px 0px 2px 2px <?= avatar_color($commentaires['grade']) ?>; background:url('<?= $image_avatar ?>');background-size:100px; background-position: center;"/>
							</div>
						<?php } ?>
						<span class="pseudo">
							<a href="https://www.mangasfan.fr/profil/voirprofil.php?m=<?php echo $commentaires['id']; ?>" target="_blank">
								<?= rang_etat($commentaires['grade'], sanitize($commentaires['username'])); ?>
							</a>
							<?php
							if ($news['auteur'] == $commentaires['id_membre']) { ?>
								<span class="badge badge-primary">Le rédacteur</span>
							<?php } ?>
						</span>
					</td>
					<td name="colonne_commentaire">
						<span class="pointe"></span>
						<span class="contenu">
							<span style="display:block;padding-bottom:10px;">
								<?php echo htmlspecialchars_decode(sanitize($commentaires['commentaire'])); ?>
							</span>
							<span class="bottom">
								<hr style="margin-bottom: 10px!important">
								<?php 
								$liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
								$date_commentaire_fr = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})#",function ($key) use ($liste_mois){ 
									return 'Posté le '.$key[3].' '.$liste_mois[$key[2]-1].' '.$key[1].' à '.$key[4].'h'.$key[5]; }, $commentaires['date_commentaire']);
								echo sanitize($date_commentaire_fr); 
								?>
							</span>
						</span>
						<span class="bouton_app">
							<?php
							if (isset($_SESSION['auth']) && $utilisateur['id'] == $commentaires['id_membre'] || isset($_SESSION['auth']) && $utilisateur['grade'] >= 9) { ?>
								<form method="POST" name="formulaire_commentaire_supp" action="">
									<input type="hidden" name="id_suppr" value="<?= sanitize($commentaires['numero_billet']);?>">
									<input type="submit" class="btn btn-danger" name="supprimer" value="Supprimer">
								</form>	
							<?php } ?>
						</span>		
					</td>
				</tr>
			</table>
		</div>
	<?php } ?>
</section>
<?php include('elements/footer.php') ?>
</body>
</html>