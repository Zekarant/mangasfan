<?php 
session_start();
require_once '../membres/base.php';
include('../membres/functions.php');

  
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
  $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $user->execute(array($_SESSION['auth']['id']));
  $utilisateur = $user->fetch(); 
} else {
  header("Location: ../erreurs/erreur_404.php");
}
if (!isset($_SESSION['auth'])) {
  header('Location: ../erreurs/erreur_403.php');
  exit();
}
if (isset($_SESSION['auth']) && $utilisateur['grade'] < 5) {
  header('Location: ../erreurs/erreur_403.php');
  exit();
}

	// Vérification nombres arguments dans l'url
$verif_type = ($_GET["type"] == "jeux" || $_GET["type"] == "mangas") ? $_GET["type"] : null;
$verif_elt = ($_GET["elt"] !== null) ? $_GET["elt"] : null;
$verif_page = ($_GET["page"] !== null) ? $_GET["page"] : null;

if($verif_type === null || $verif_elt === null || $verif_elt === null){
 header("Location: ../erreurs/erreur_404.php");
}

    // Variables nous permettant de savoir dans quels tables allons-nous devoir modifier les valeurs.
$type = ($_GET['type'] == "jeux") ? "billets_jeux" : "billets_mangas";
$type2 = ($_GET['type'] == "jeux") ? "billets_jeux_cat" : "billets_mangas_cat";
$type3 = ($_GET['type'] == "jeux") ? "billets_jeux_pages" : "billets_mangas_pages";
$type4 = ($_GET['type'] == "jeux") ? "jeux_id" : "mangas_id";

	// On modifie la variable pour récupérer l'id du jeu
if((int) $verif_elt == 0 && !is_null($verif_elt)){
		$recuperation_id_elt = $verif_elt; // on stock la valeur dans une nouvelle variable
		$verif_elt = null; // on le réinitialise à null pour faire la vérification après
		$recup_id = $pdo->query("SELECT id,titre FROM $type");
		$url_rewriting = "../";
		while($parcours_id = $recup_id->fetch()){
			if(traduire_nom($parcours_id['titre']) == $recuperation_id_elt){
				$save_name_elt = $recuperation_id_elt;
				$verif_elt = $parcours_id['id'];
			}
		}
	}

	if($verif_elt === null){
   header("Location: ../erreurs/erreur_404.php");
 }

    // On modifie la variable pour récupérer l'id du jeu
 if((int) $verif_page == 0 && !is_null($verif_page)){
		$recup_id_page = $verif_page; // on stock la valeur dans une nouvelle variable
		$verif_page = null; // on le réinitialise à null pour faire la vérification après
		$recup_page = $pdo->query("SELECT id,nom FROM $type3 WHERE $type4 = $verif_elt");
		$url_rewriting = "../../";
		while($parcours_id = $recup_page->fetch()){
			if(traduire_nom($parcours_id['nom']) == $recup_id_page){
				$save_name_page = $recup_id_page;
				$verif_page = $parcours_id['id'];
			}
		}
	}

	if($verif_page === null){
		header("Location: ../erreurs/erreur_404.php");
	}

    // On récupère les éléments de la page à modifier
  $recup_elt = $pdo->prepare("SELECT * FROM $type3 WHERE id = ? LIMIT 1");
  $recup_elt->execute(array($verif_page));
  $page_courant = $recup_elt->fetch();

    // On récupère la liste des catégories, nécessaire pour l'éventuel changements de catégorie
  $liste_cat = $pdo->prepare("SELECT id,nom,position FROM $type2 WHERE billets_id = ? ORDER BY position");
  $liste_cat->execute(array($verif_elt));
  $parcours_categorie = $liste_cat->fetchAll();

	// On vérifie le grade du membre
  if($utilisateur['grade'] < 4){
   header("Location: ../erreurs/erreur_404.php");
 }

    // Textarea pour le texte de présentation
 $d = preg_replace('/\r/','',$page_courant['contenu']);
 $clean = preg_replace('/\n{2,}/', "\n", preg_replace('/^\s+$/m', '', $d));
 $sqdd = str_replace('\n', "\n",$clean);
 $sqdd = str_replace('\r', "\n",$sqdd);
 $sqdd = str_replace('\r\n', "\n",$sqdd); 

    // On modifie dans la bdd une fois qu'on clique sur valider
 if(isset($_POST['valid_modif_page'])){
   $categorie = addslashes(htmlspecialchars($_POST['liste_categories']));
   if($categorie != NULL && !empty($_POST['title_page']) && !empty($_POST['text_pres'])){
	    	// Champ titre
    $title_page = addslashes(htmlspecialchars($_POST['title_page']));


	    	// Champ texte
    $replace = preg_replace('/\r/', '', htmlspecialchars($_POST['text_pres']));
    $replace = preg_replace('/\n{2,}/', '\n\n', preg_replace('/^\s+$/m', '', $replace));
    $text_page = $replace;

			// Champ image
    $image = htmlspecialchars($_POST['picture_game']);

	        // Checkbox post-it / message neutre
    $type_message = (isset($_POST['type_message']) && $_POST['type_message'] == "post-it") ? $_POST['type_message'] : null;

    $verification = True;

    if ($page_courant['nom'] != $title_page){
      $verif_deja_exist = $pdo->prepare("SELECT * FROM $type3 B INNER JOIN $type2 O ON B.num_onglet = O.id WHERE B.nom = ? AND B.$type4 = ? AND O.nom = ? LIMIT 1");
      $verif_deja_exist->execute(array($title_page,$verif_elt,$categorie));

      $verification = ($verif_deja_exist->rowCount() == 0) ? True : False;
    }

	        // C
    $num_id_onglet = $pdo->prepare("SELECT id FROM $type2 WHERE nom = ? AND billets_id = ?");
    $num_id_onglet->execute(array($categorie,$verif_elt));
    $num_id_onglet = $num_id_onglet->fetch();

	        // On vérifie s'il existe déjà une page à ce nom pour ce jeu. Si non, on modifie notre table
    if($verification){
      $modif_bdd = $pdo ->prepare("UPDATE $type3 SET num_onglet = ?, nom = ?, type_art = ?, contenu = ?, image = ? WHERE id = ?");
      $modif_bdd->execute(array($num_id_onglet['id'],$title_page,$type_message,$text_page,$image,$verif_page));

      header('Location: ../../modif_'.$verif_type.'/'.$save_name_elt);
    }
  }
}
?>

<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8" />
  <title>Modifier une page - Mangas'Fan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
  <link rel="stylesheet" href="<?= $url_rewriting ?>../bootstrap/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="icon" href="<?= $url_rewriting ?>../images/favicon.png"/>
  <link rel="stylesheet" href="<?= $url_rewriting ?>../style.css" />
  <script type="text/javascript" src="<?= $url_rewriting ?>/tinymce/js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript" src="<?= $url_rewriting ?>/tinymce/js/tinymce/tinymce.js"></script>
  <script>
    tinymce.init({
      selector: 'textarea',
       height: 500,
      language: 'fr_FR',
      force_br_newlines : true,
      force_p_newlines : false,
      entity_encoding : "raw",
      browser_spellcheck: true,
      contextmenu: false,
      plugins: ['autolink visualblocks visualchars image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern autosave'],
      toolbar: 'undo redo |  formatselect | tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | bold italic underline forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | restoredraft',
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
        <h1 class="titre_principal_news"><img src="https://zupimages.net/up/18/25/mjqz.png" /> Modifier une page</h1>
        <a href="<?= $url_rewriting; ?>index.php" class="btn btn-primary btn-sm">Retourner à l'index du panel</a>
        <a href="https://www.mangasfan.fr/hebergeur/" target="_blank" class="btn btn-primary btn-sm">Accéder à l'hébergeur d'image</a>
        <a href="https://www.mangasfan.fr/membres/bbcode_active.html" target="_blank" class="btn btn-primary btn-sm">Consulter la liste des BBCodes</a>
        <hr>
        <div class="container">
          <form method="POST" action="">
                <label>Titre : </label>
                <input type="text" class="form-control" name="title_page" placeholder="Titre compris entre 5 et 20 caractères." value="<?= $page_courant['nom'] ?>" /><br/>
                <label>Image du jeu : (600px*260px)</label>
                <input type="text" class="form-control" name="picture_game" placeholder='Image pour "Derniers articles" sur le profil du jeu.' value="<?= $page_courant['image'] ?>"/><br/>
                <label>Catégorie :</label>
                <select class="form-control" name="liste_categories">
                  <?php $i = 0; while($i < $liste_cat->rowCount()) { ?>
                    <?php if($parcours_categorie[$i]['id'] == $page_courant['num_onglet']) { ?>
                      <option selected="select"><?= $parcours_categorie[$i]['nom'] ;?></option>
                    <?php } else { ?> 
                      <option><?= $parcours_categorie[$i]['nom'] ;?></option>
                    <?php } ?>
                    <?php $i++; 
                  } ?>
                </select><br/>
                <div class="button_mess">
                  <label>Type d'article :</label>
                  <input type="radio" id="pi_mess" name="type_message" value="post-it" <?php if($page_courant['type_art'] !== null) { echo 'checked'; } ?> />
                  <label for="pi_mess">Post-It</label>

                  <input type="radio" id="mess_neutre" name="type_message" value="neutre" <?php if($page_courant['type_art'] === null) { echo 'checked'; } ?> />
                  <label for="mess_neutre">Neutre</label>
                </div>
                <label>Texte :</label>
                <textarea name="text_pres" class="form-control" placeholder="Rédiger votre page ici." ><?= htmlspecialchars_decode(sanitize($sqdd)); ?></textarea>
                <input type="submit" class="btn btn-sm btn-info" name="valid_modif_page" value="Valider" />
              </div>
          </form>
        </div>
      </div>
    </div>
  <?php include('../elements/footer.php'); ?>
</body>
</html>



