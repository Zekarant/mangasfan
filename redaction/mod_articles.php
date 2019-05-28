<?php 
    session_start();
    require_once '../inc/base.php';
    include('../inc/functions.php');
    include('../theme_temporaire.php');

    // Ouverture session
    if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
	} else {
		header("Location: ../erreurs/erreur_404.php");
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

	    	echo "ok";

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
	        	echo "ok2";
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
        <title>Mangas'Fan - Modifier une page</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
        <link rel="stylesheet" href="<?= $url_rewriting ?>../bootstrap/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="icon" href="<?= $url_rewriting ?>../images/favicon.png"/>
        <link rel="stylesheet" href="<?php echo $lienCss; ?>" />
        <link rel="stylesheet" href="<?= $url_rewriting ?>../style/redac_style.css" />
        <script type="text/javascript" src="https://www.mangasfan.fr/redaction/tinymce/js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="https://www.mangasfan.fr/redaction/tinymce/js/tinymce/tinymce.js"></script>
        <script>
          tinymce.init({
          selector: 'textarea',
          height: 250,
          theme: 'modern',
          language: 'fr_FR',
          entity_encoding : "raw",
          plugins: ['preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern'],
          toolbar: 'insert | undo redo |  formatselect | bold italic underline strikethrough backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat |',
          content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css']
        });
        </script>
    </head>
	<body>
		<div id="bloc_page">
  		<?php include('../elements/nav_redac.php') ?>

  		<center>
        	<h2 id="titre_newsp"><img src="https://zupimages.net/up/18/25/mjqz.png" /> Modifier <span class="couleur_mangas">une</span> <span class="couleur_fans">page</span></h2>
      	</center>

		<a href="<?= $url_rewriting; ?>redac.php" style="margin-left:20px;">Retourner à l'index du panel</a>

      	<div id="rediger" class="bloc_contenu">
      		<form method="POST" action="">
		        <label for="titre_article_redac">Titre : </label>
		        <input type="text" class="form-control" id="titre_article_redac" name="title_page" placeholder="Titre compris entre 5 et 20 caractères." value="<?= $page_courant['nom'] ?>" /><span class="message1"></span><br />
		        <label for="modif_image_redac">Image du jeu :<br /><span style="font-weight:normal;font-style:italic;">(600px*260px)</span></label>
		        <input type="text" class="form-control" id="modif_image_redac" name="picture_game" placeholder='Image pour "Derniers articles" sur le profil du jeu.' value="<?= $page_courant['image'] ?>"/><span class="message2"></span><br />
		        <label for="select_cat_redac">Catégorie :</label>
		        <select class="form-control" id="select_cat_redac" name="liste_categories">
               		<?php $i = 0; while($i < $liste_cat->rowCount()) { ?>
               			<?php if($parcours_categorie[$i]['id'] == $page_courant['num_onglet']) { ?>
               				<option selected="select"><?= $parcours_categorie[$i]['nom'] ;?></option>
               			<?php } else { ?> 
               				<option><?= $parcours_categorie[$i]['nom'] ;?></option>
               			<?php } ?>
                	<?php $i++; } ?>
           		</select><span class="message3"></span><br />
		        <label for="text_redac">Texte :</label>

		        <textarea name="text_pres" class="form-control" id="text_redac" rows="10" cols="70" placeholder="Rédiger votre page ici." ><?= $sqdd; ?></textarea><br />
		        <span class="message4"></span>
		        <!-- Checkbox pour l'importance du sujet -->
		        <div class="button_mess">
				    <input type="radio" id="pi_mess" name="type_message" value="post-it" <?php if($page_courant['type_art'] !== null) { echo 'checked'; } ?> />
				    <label for="pi_mess">Post-It</label>

				    <input type="radio" id="mess_neutre" name="type_message" value="neutre" <?php if($page_courant['type_art'] === null) { echo 'checked'; } ?> />
				    <label for="mess_neutre">Neutre</label>
				</div>


		        <input type="submit" class="btn btn-sm btn-info" name="valid_modif_page" value="Valider" />
       		</form>
      	</div>
      	<?php include('../elements/footer.php'); ?>
      </div>
	</body>
</html>


    
