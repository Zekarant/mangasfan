<?php
session_start(); 
include('../membres/base.php');
include('../membres/data/maintenance_galeries.php');
include('../membres/functions.php');
$current_url = $_SERVER['REQUEST_URI'];
if(strpos($current_url,'/galeries/commentaires.php'))
{
	$variable = intval($_GET['image_galerie']);
	$billet = $pdo->prepare("SELECT titre FROM galerie WHERE id = ?");
	$billet->execute(array($variable));
	$billet_title = $billet->fetch();

	header("Status: 301 Moved Permanently", false, 301);
	header("Location: ".traduire_nom($billet_title['titre']));
	die();

}
if(isset($_GET['image_galeries']) && !is_numeric($_GET['image_galeries'])){
	$id_galerie = $_GET['image_galeries'];
	$recuperation_id = $pdo->query("SELECT id, titre FROM galerie");
	while($parcours_id = $recuperation_id->fetch()){
		if(traduire_nom($parcours_id['titre']) == $id_galerie){
			$id_galerie = $parcours_id['id'];
		}
	}
}
// Vérification du formulaire
if (isset($_POST['envoyer_commentaire'])){
	if (isset($_SESSION['auth'])) {
		if ($utilisateur['grade'] > 1 && $utilisateur['grade'] <= 10) {
			$inserer_commentaire = $pdo->prepare('INSERT INTO commentaires_galerie(id_galerie, auteur, commentaire, date_commentaire) VALUES(?, ?, ?, NOW())');
			$inserer_commentaire->execute(array($id_galerie, $utilisateur['id'], $_POST['commentaire']));
			$couleur = "success";
			$texte = "Votre commentaire a bien été posté.";
		}
	}
}
// Suppression commentaire
if (isset($_POST['supprimer'])) {
	if (isset($_SESSION['auth'])) {
		if ($utilisateur['grade'] > 1 && $utilisateur['grade'] <= 10) {
			if ($utilisateur['id'] == $commentaires['auteur'] || $utilisateur['grade'] >= 7) {
				$commentaire_concerne = $_POST['id_suppr'];
				$supprimer_commentaire = $pdo->prepare('DELETE FROM commentaires_galerie WHERE id = ?');
				$supprimer_commentaire->execute(array($commentaire_concerne));
				$couleur = "success";
				$texte = "Le commentaire a bien été supprimé.";
			}
		}
	}
}
$recuperation = $pdo->prepare('SELECT g.id, g.filename, g.titre, g.titre_image, g.texte, g.auteur, g.date_image, g.rappel, g.nsfw, u.username, u.grade, u.galerie FROM galerie g LEFT JOIN users u ON u.id = g.auteur WHERE g.id = ?');
$recuperation->execute(array($id_galerie));
$galerie = $recuperation->fetch();
// Affichage commentaires
$recuperer_commentaire = $pdo->prepare('SELECT c.id AS numero_galerie, c.id_galerie, c.auteur, c.commentaire, c.date_commentaire, u.id, u.username, u.grade, u.avatar FROM commentaires_galerie c LEFT JOIN users u ON c.auteur = u.id WHERE id_galerie = ? ORDER BY date_commentaire DESC');
$recuperer_commentaire->execute(array($id_galerie));
if ($galerie['nsfw'] == 1 && !isset($_SESSION['auth'])) {
	header("Location: ../erreurs/erreur_403.php");
  	exit();
} elseif (isset($_SESSION['auth']) && $galerie['nsfw'] == 1 && $utilisateur['galerie'] == 0 && $utilisateur['grade'] <= 2) {
	header("Location: ../erreurs/erreur_403.php");
  	exit();
}
// Envoi du rappel
if (isset($_POST['valider_rappel'])) {
	if (isset($_SESSION['auth'])) {
		if ($utilisateur['grade'] <= 7) {
			if (!empty($_POST['raison_suspension'])) {
				$inserer_rappel = $pdo->prepare('UPDATE galerie SET rappel = ? WHERE id = ?');
				$inserer_rappel->execute(array($_POST['raison_suspension'], $id_galerie));
				$text_avertissement = "
				<p>Cher membre de Mangas'Fan,<br/>
				Si vous recevez ce message privé, c'est que l'image " . sanitize($galerie['titre']) . " a été suspendue du site pour la raison suivante :</p>
				<p>« " . sanitize($_POST['raison_suspension']) . " »<br/>
				Ce rappel vous a été attribué par <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
				<hr>
				<p>Nous vous remercions de bien vouloir modifier ce qui vous est demandé dans le mail. Si vous ne modifiez rien, des sanctions seront appliquées à votre compte.
				<br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
				~ L'équipe de Mangas'Fan</p>";
				$premier_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
				$premier_mp->execute(array($galerie['auteur'], "L'image " . sanitize($galerie['titre']) . " a été suspendue de votre galerie", $text_avertissement, time()));
				$couleur = "success";
				$texte = "L'image a bien reçu un rappel, et est donc temporairement retirée de l'index des galeries.";
			} else {
				$couleur = "danger";
				$texte = "Vous devez spécifier un motif sinon l'image n'aura pas de rappel.";
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?= sanitize($galerie['titre']); ?> - Mangas'Fan</title>
	<link rel="icon" href="../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<?php if(!empty($galerie['titre_image'])){ ?>
		<meta name="keywords" content="<?= sanitize($galerie['titre_image']); ?>"/>
	<?php } ?>
	<link rel="stylesheet" type="text/css" href="/galeries/style.css" />
	<link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
	<?php include('../elements/header.php'); ?>
	<section>
		<?php if(isset($_POST['valider_rappel'])){ ?>
			<div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
				<?= sanitize($texte); ?>
			</div>
		<?php } if(isset($_SESSION['auth']) AND $utilisateur['id'] == $galerie['auteur']){ ?>
			<h1 class="titre_principal_news">Gestion de l'image</h1>
			<hr>
			<center>
				<a href="modifier.php?galerie=<?= intval($galerie['id']); ?>" class="btn btn-primary btn-sm">Modifier la description de l'image ou le contenu</a>
				<a href="supprimer.php?galerie=<?= intval($galerie['id']); ?>" class="btn btn-danger btn-sm">Supprimer l'image de la galerie</a>
				<?php if ($utilisateur['grade'] >= 7 && $galerie['rappel'] == NULL) { ?>
					<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rappel">
						Envoyer un rappel pour modifier l'image
					</button>
					<div class="modal fade" id="rappel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Envoyer un rappel pour l'image <i><?php echo sanitize($galerie['titre']); ?></i> de <?= rang_etat(sanitize($galerie['grade']), sanitize($galerie['username'])); ?></h5>
								</div>
								<div class="modal-body">
									<form method="POST" action="">
										<textarea name="raison_suspension" class="form-control" rows="10" placeholder="Rédigez le motif de la suspension ici (Cela servira pour prévenir le membre par MP)."></textarea>
										<input type="submit" name="valider_rappel" class="btn btn-info">
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
								</div>
							</div>
						</div>
					</div>
				</center>
			<?php } ?>
		<?php } elseif($utilisateur['grade'] >= 7 && $galerie['rappel'] == NULL){ ?>
			<h1 class="titre_principal_news">Modération de l'image</h1>
			<hr>
			<center>
				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rappel">
					Envoyer un rappel pour modifier l'image
				</button>
			</center>
			<div class="modal fade" id="rappel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Envoyer un rappel pour l'image <i><?= sanitize($galerie['titre']); ?></i> de <?= rang_etat(sanitize($galerie['grade']), sanitize($galerie['username'])); ?></h5>
						</div>
						<div class="modal-body">
							<form method="POST" action="">
								<textarea name="raison_suspension" class="form-control" rows="10" placeholder="Rédigez le motif de la suspension ici (Cela servira pour prévenir le membre par MP)."></textarea>
								<input type="submit" name="valider_rappel" class="btn btn-info">
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
						</div>
					</div>
				</div>
			</div>
			<hr>
		<?php } ?>
		<h1 class="titre_principal_news"><?= sanitize($galerie['titre']); ?> par <?= sanitize($galerie['username']); ?></h1>
		<hr>
		<img src="images/<?= sanitize($galerie['filename']); ?>" alt="<?= sanitize($galerie['titre']); ?> - <?= sanitize($galerie['username']); ?>" class="image_galeries" />
		<hr>
		<h3 class="titre_description_galerie">Concernant cette image :</h3>
		<p><?= htmlspecialchars_decode(sanitize($galerie['texte'])); ?></p>
		<div class="card-footer" style="border-bottom: 1px solid rgba(0,0,0,.125);">
			<center>
				<small class="text-muted">Cette image a été postée par <strong><a href="../profil/profil-<?= sanitize($galerie['auteur']); ?>"><?= sanitize($galerie['username']); ?></a></strong> le <?= date('d M Y à H:i', strtotime(htmlspecialchars($galerie['date_image']))); ?>. Elle appartient à son auteur. Toute reproduction sans son accord peut entrainer des sanctions. © <?= sanitize($galerie['username']); ?></small>
			</center>
		</div>
		<hr>
		<h2>ESPACE COMMENTAIRES</h2>
		<hr class="tiret_news">
		<?php if(isset($_POST['envoyer_commentaire']) || isset($_POST['supprimer'])){ ?>
			<div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
				<?= sanitize($texte); ?>
			</div>
		<?php } ?>
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
							<textarea name="commentaire" class="form-control" rows="10" placeholder="Écrivez-ici votre commentaire. (BBCodes utilisable)"></textarea>
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
		<?php if ($recuperer_commentaire->rowCount() == 0) { ?>
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
									<?php echo rang_etat($commentaires['grade'], sanitize($commentaires['username'])); ?>
								</a>
								<?php
								if ($galerie['auteur'] == $commentaires['auteur']) { ?>
									<span class="badge badge-primary">L'artiste de l'image</span>
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
								if (isset($_SESSION['auth']) AND $utilisateur['id'] == $commentaires['auteur'] OR isset($_SESSION['auth']) AND $utilisateur['grade'] >= 9) { ?>
									<form method="POST" name="formulaire_commentaire_supp" action="">
										<input type="hidden" name="id_suppr" value="<?= sanitize($commentaires['numero_galerie']); ?>">
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
	<?php include('../elements/footer.php'); ?>
</body>
</html>