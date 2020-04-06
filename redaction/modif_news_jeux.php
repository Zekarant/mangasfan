<?php 
session_start();
include('../membres/base.php');
include('../membres/functions.php');
if (!isset($_SESSION['auth'])) {
	header('Location: ../erreurs/erreur_403.php');
	exit();
}
if (isset($_SESSION['auth']) && $utilisateur['grade'] < 5) {
	header('Location: ../erreurs/erreur_403.php');
	exit();
}
$verif_type = ($_GET["type"] == "jeux" || $_GET["type"] == "mangas") ? $_GET["type"] : null;

if($verif_type == null){
	header("Location: ../erreurs/erreur_404.php");
}

$type = ($_GET['type'] == "jeux") ? "billets_jeux" : "billets_mangas";
$type2 = ($_GET['type'] == "jeux") ? "billets_jeux_cat" : "billets_mangas_cat";
$type3 = ($_GET['type'] == "jeux") ? "billets_jeux_pages" : "billets_mangas_pages";

$type4 = ($_GET['type'] == "jeux") ? "jeux_id" : "mangas_id";

$url_rewriting = "";

$id_news = ($_GET['id_news'] !== null) ? $_GET['id_news'] : null;
if((int) $id_news == 0 && !is_null($id_news)){
	$recuperation_id_mangas = $id_news;
	$recup_id = $pdo->query("SELECT id,titre FROM $type");
	$id_news = null;
	$url_rewriting = "../";
	while($parcours_id = $recup_id->fetch()){
		if(traduire_nom(stripslashes($parcours_id['titre'])) == $recuperation_id_mangas){
			$save_name_jeu = $recuperation_id_mangas;
			$id_news = $parcours_id['id'];
		}
	}
}

if($id_news == null){
	header("Location: ../erreurs/erreur_404.php");
}

$id_exist = $pdo->prepare("SELECT J.id, J.titre, O.nom AS name_onglet, J.vignette, J.presentation, J.theme, P.nom AS name_page, P.member_post, P.image, P.contenu
	FROM $type J
	INNER JOIN $type2 O
	ON J.id = O.billets_id 
	INNER JOIN $type3 P
	ON O.id = P.num_onglet
	WHERE J.id = ?");
$id_exist->execute(array($id_news));

$jeu_exist = $pdo->prepare("SELECT * FROM $type J WHERE id = ?");
$jeu_exist->execute(array($id_news));

$liste_onglet = $pdo->prepare("SELECT id,nom,position FROM $type2 WHERE billets_id = ? ORDER BY position");
$liste_onglet->execute(array($id_news));
$parcours_categorie = $liste_onglet->fetchAll();

$liste_page = $pdo->prepare("SELECT * FROM $type3 WHERE $type4 = ?");
$liste_page->execute(array($id_news));
$parcours_page = $liste_page->fetchAll();

if($utilisateur['grade'] >= 4 && $verif_page_existe = $jeu_exist->rowCount() != 0){
	$jeux = $jeu_exist->fetch();

} else {
	header("Location: ../erreurs/erreur_404.php");
}

    // Textarea pour le texte de présentation
$d = preg_replace('/\r/','',$jeux['presentation']);
$clean = preg_replace('/\n{2,}/', "\n", preg_replace('/^\s+$/m', '', $d));
$sqdd = str_replace('\n', "\n",$clean);
$sqdd = str_replace('\r', "\n",$sqdd);
$sqdd = str_replace('\r\n', "\n",$sqdd); 

include('modif_informations.php');
include('add_page.php');
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8" />
	<title>Modifier des contenus - Mangas'Fan</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="icon" href="<?= $url_rewriting ?>../images/favicon.png"/>
	<link rel="stylesheet" href="<?= $url_rewriting ?>../style.css" />
	<link rel="stylesheet" href="<?= $url_rewriting ?>../style/redac_style.css" />
	<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.js"></script>
	<script>
		tinymce.init({
			selector: '#text_redac',
			height: 500,
			language: 'fr_FR',
			force_br_newlines : true,
			force_p_newlines : false,
			entity_encoding : "raw",
			plugins: ['autolink visualblocks visualchars image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern autosave'],
			toolbar: 'undo redo |  formatselect | tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | bold italic underline forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | restoredraft',
			browser_spellcheck: true,
			contextmenu: false,
			image_class_list: [
			{title: 'Image news', value: 'image_tiny'},
			]
		});
	</script>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
				<?php include('../elements/navredac_v.php'); ?>
			</div>
			<div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
				<?php include ('../elements/nav_redac.php'); ?>
				<section class="marge_page">
					<center>
						<h2 id="titre_newsp"><img src="https://zupimages.net/up/18/25/mjqz.png" /> Modifier <span class="couleur_mangas">le</span> <span class="couleur_fans">contenu</span></h2>
					</center>
					<hr>
					<div class="alert alert-warning" role="alert">
						<strong>Informations :</strong> Chers rédacteurs, dans le cadre de quelques améliorations et corrections du pannel, ce dernier peut se montrer instable dans les prochains jours. Dans le cas où vous devriez rencontrer des bugs, merci de les signaler dans le channel adéquat.
					</div>
					<a href="<?= $url_rewriting; ?>index.php" class="btn btn-primary btn-sm">Retourner à l'index du panel</a>
					<a href="https://www.mangasfan.fr/hebergeur/" target="_blank" class="btn btn-primary btn-sm">Accéder à l'hébergeur d'image</a>
					<a href="https://www.mangasfan.fr/membres/bbcode_active.html" target="_blank" class="btn btn-primary btn-sm">Consulter la liste des BBCodes</a><br/><br/>

					<div id="navigation">
						<center>
							<span class="en-tete">En-tête</span>
							<span class="presentation">Présentation</span>
							<span class="articles">Articles</span>
							<span class="rediger">Rédiger</span>
						</center>
					</div>

					<div id="en-tete" class="bloc_contenu">
						<form method="POST" action="">
							<label>Modifier le titre du jeu : </label>
							<input type="text" class="form-control" name="title_game" value="<?= stripslashes($jeux['titre']);?>" /><br />
							<label>Modifier l'image du jeu :</label>
							<input type="text" class="form-control" name="picture_game" value="<?= htmlspecialchars($jeux['vignette']);?>" /><br />
							<label>Modifier l'image de présentation :</label>
							<input type="text" class="form-control" name="picture_pres" value="<?= htmlspecialchars($jeux['theme']);?>" /><br />
							<input type="hidden" id="id_news" class="<?= $jeux['id'];?>">
							<input type="hidden" id="type_news" class="<?= $verif_type; ?>">
							<label>Type : </label>
							<?php 
							if ($_GET['type'] == "mangas" || $_GET['type'] == "anime") {
								if ($jeux['type'] == "anime") { ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="anime" checked>
										<label class="form-check-label" for="inlineRadio1">Anime</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="mangas">
										<label class="form-check-label" for="inlineRadio2">Mangas</label>
									</div><br/>
								<?php } else { ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="anime">
										<label class="form-check-label" for="inlineRadio1">Anime</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="mangas" checked>
										<label class="form-check-label" for="inlineRadio2">Mangas</label>
									</div><br/>
							<?php } 
						}?>
							<br/>
							<input type="submit" class="btn btn-sm btn-info" name="valid_entete" value="Valider" />
						</form>
					</div>

					<div id="presentation" class="bloc_contenu">
						<form method="POST" action="">
							<label for="text_presentation">Modifier la présentation du jeu : </label>
							<textarea name="text_pres" class="form-control"  rows="10" cols="70" placeholder="Votre commentaire" ><?= stripslashes($sqdd);?></textarea><br/>
							<input type="submit" class="btn btn-sm btn-info" name="valid_presentation" value="Valider" />
						</form>
					</div>

					<div id="articles" class="bloc_contenu">
						<div class="alert alert-danger" role="alert">
							<strong>Alerte importante : </strong> Suppression des articles désactivée pour cause de problème technique.
						</div>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Catégorie</th>
									<th>Page</th>
									<th>Modification</th>
									<th>Suppression</th>
									<th>Accès à l'article</th>
								</tr>
							</thead>

							<?php $i = 0; 
							while ($i < $liste_onglet->rowCount()) {
								$j = 0; ?>
								<tr>
									<td><span class="name_cat"><?= stripslashes($parcours_categorie[$i]['nom']);?></span></td>
									<td></td>
									<td><span class="btn btn-outline-success modif_cat">Modifier la catégorie</span></td>
									<td><span class="btn btn-outline-warning suppr_cat">Supprimer la catégorie</span></td>
									<td></td>
									<td><!-- Position a refaire ? --></td>
								</tr>


								<?php 
								if($_GET['type'] == "jeux"){
									$jeux = "jeux-video";
								} elseif ($_GET['type'] == "mangas") {
									$jeux = "mangas";
								} else {
									$jeux = "animes";
								}
								while ($j < $liste_page->rowCount()){
									if($parcours_page[$j]['num_onglet'] == $parcours_categorie[$i]['id']){ ?>
										<tr>
											<td></td>
											<td><?= stripslashes($parcours_page[$j]['nom']);?></td>
											<td><b><a href="<?= $url_rewriting; ?>modif_page_<?= $verif_type; ?>/<?= $save_name_jeu; ?>/<?= traduire_nom(stripslashes($parcours_page[$j]['nom'])); ?>"><span class="btn btn-outline-info modif_page">Modifier la page</span></b></td>
												<td>Désactivé pour maintenance.</td>
												<td><a href="https://www.mangasfan.fr/<?= $jeux; ?>/<?= $save_name_jeu; ?>/<?= traduire_nom(stripslashes($parcours_page[$j]['nom'])); ?>" class="btn btn-outline-info" target="_blank">Accéder à l'article</a></td>
												<td></td>
											</tr>
										<?php }
										$j++;
									} ?>
									<?php $i++; } ?>
								</table>

								<form method="POST" action="" id="nvl_cat">
									<input type="text" class="form-control" name="new_cat" placeholder="Nom de la nouvelle catégorie"/>

									<input type="submit" class="add_cat" name="valid_nouvelle_cat" value="Ajouter une catégorie" />
								</form>
							</div>

							<div id="rediger" class="bloc_contenu">
								<form method="POST" action="">
									<label for="titre_article_redac">Titre de l'article : </label>
									<input type="text" class="form-control" id="titre_article_redac" name="title_page" placeholder="Titre compris entre 5 et 20 caractères." /><span class="message1"></span><br />
									<label for="modif_image_redac">Modifier l'image du jeu :<br /><span style="font-weight:normal;font-style:italic;">(600px*260px)</span></label>
									<input type="text" class="form-control" id="modif_image_redac" name="picture_game" placeholder='Image pour "Derniers articles" sur le profil du jeu.'/><span class="message2"></span><br />
									<label for="select_cat_redac">Catégorie :</label>
									<select class="form-control" id="select_cat_redac" name="liste_categories">
										<option>Sélectionner une catégorie</option>
										<?php $i = 0; while($i < $liste_onglet->rowCount()) { ?>
											<option><?= $parcours_categorie[$i]['nom'] ;?></option>
											<?php $i++; } ?>
										</select><span class="message3"></span><br />
										<label for="text_redac">Texte :</label>
										<textarea name="text_pres" class="form-control" id="text_redac" rows="10" cols="70" placeholder="Rédiger votre page ici." ></textarea><br />
										<span class="message4"></span>
										<!-- Checkbox pour l'importance du sujet -->
										<div class="button_mess">
											<label for="text_type">Type de l'article :</label>
											<input type="radio" id="pi_mess" name="type_message" value="post-it" />
											<label for="pi_mess">Post-It</label>
											<input type="radio" id="mess_neutre" name="type_message" value="neutre" checked />
											<label for="mess_neutre">Neutre</label>
										</div><br/>
										<input type="submit" class="btn btn-sm btn-info" name="valid_nouvelle_page" value="Valider" />
									</form>
								</div>
								<script type="text/javascript" src="<?= $url_rewriting ?>script.js"></script>
							</section>
						</div>
					</div>
				</div>
				<?php include('../elements/footer.php'); ?>
			</body>
			</html>