<?php 
	session_start();
	require_once '../membres/base.php';
	include('../membres/functions.php');
	if(isset($_SESSION['auth'])){
		$id_commentaire = $_GET['id'];
		if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
	        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
	        $user->execute(array($_SESSION['auth']['id']));
	        $utilisateur = $user->fetch(); 
		}

		$verif_author = $pdo->prepare("SELECT * FROM commentary_page WHERE id = ?");
		$verif_author->execute(array($id_commentaire));
		$verif_author2 = $verif_author->fetch();

		$grade_user = $utilisateur['grade'];
		$grade_other_user = $pdo->query("SELECT grade FROM users WHERE id =".$verif_author2['id_member'])->fetch();

		if($verif_author->rowCount() != 0){
			if($utilisateur['id'] == $verif_author2['id_member'] OR ($grade_user > 8 && $grade_user >= $grade_other_user['grade'])){
				if(isset($_POST['valide_edit']) AND !empty($_POST['description'])){
					$description = $_POST['description'];

					$d = preg_replace('/\r/', '', $description);
    				$clean = preg_replace('/\n{2,}/', '\n\n', preg_replace('/^\s+$/m', '', $d));

					$edit_commentary = $pdo->prepare("UPDATE commentary_page SET commentary = ?,editation = ?,date_editation = NOW() WHERE id = '$id_commentaire'");

					$edit_commentary->execute(array(htmlspecialchars($clean),$verif_author2['editation']+1));

					if($verif_author2['name_elt'] == 'jeux'){
						$type = "jeux_video";
					} elseif($verif_author2['name_elt'] == 'mangas'){
						$type = 'mangas';
					} else {
						$type = 'anime';
					}

					$type1 = ($verif_author2['name_elt'] == "jeux") ? "billets_jeux" : "billets_mangas";
					$type2 = ($verif_author2['name_elt'] == "jeux") ? "billets_jeux_pages" : "billets_mangas_pages";

					$recup_titre = $pdo->prepare("SELECT titre FROM $type1 WHERE id = ?");
					$recup_titre->execute(array($verif_author2['id_cat']));
					$recup_titre_elt = $recup_titre->fetch();

					$recup_titre_page = $pdo->prepare("SELECT nom FROM $type2 WHERE id = ?");
					$recup_titre_page->execute(array($verif_author2['id_page']));
					$recup_titre_pages = $recup_titre_page->fetch();

					$titre_elt = traduire_nom($recup_titre_elt['titre']);
					$titre_page = traduire_nom($recup_titre_pages['nom']);
					header("Location: ../$type/$titre_elt/$titre_page");
				}
			} else {
				header("Location: ../erreurs/erreur_404.php");
			}
		} else {
			header("Location: ../erreurs/erreur_404.php");
		}
	} else {
		header("Location: ../erreurs/erreur_404.php");
	}
	include('../theme_temporaire.php');
?>

<html>
	<head>
		<title>Mangas'Fan - Modifier un commentaire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="icon" href="../images/favicon.png"/>
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Marvel' rel='stylesheet' type='text/css' />
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="../style/index_jv.css">
		<link rel="stylesheet" href="../style/commentary_style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="<?php echo $lienCss; ?>">
		<link rel="stylesheet" href="../overlay.css" />
	</head>
	<body>
		<div id="bloc_page">
		<header>
			<div id="banniere_image">
			<div id="titre_site"><span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN</div>
			<div class="slogan_site">Votre référence Mangas</div>
		        <?php include("../elements/navigation.php") ?>
			<h2 id="actu_moment">NOTRE FORUM</h2>
			<h5 id="slogan_actu">Pour parler de notre passion commune</h5>
			<div class="bouton_fofo"><a href="https://mangasfan.000webhostapp.com/forum/index.php">Forum</a></div>
		   		<?php include('../elements/header.php'); ?>
			</div>
		</header>
		<section class="marge_page">
		<h3>Édition <span class="couleur_mangas">de</span> <span class="couleur_fans">commentaire</span> :</h3>
		<div class="espace_commentaire">
			<form action="" method="post">
			    <center><label class="col-sm-2" for="commentary">Modifier le commentaire : <br />
			    <a href="../inc/bbcode_active.html" class="lien_bbcode" style="color:black !important;font-weight:italic;font-size:14px" target="blank">Voici la liste des bbcodes possibles</a></label>
			    <textarea name="description" class="form-control" id="commentary" rows="10" cols="70"><?php $d = preg_replace('/\r/', '',  $verif_author2['commentary']);
    				$clean = preg_replace('/\n{2,}/', "\n", preg_replace('/^\s+$/m', '', $d));
    				$sqdd = str_replace('\n', "\n",$clean);
    				$sqdd = str_replace('\r', "\n",$sqdd);
    				$sqdd = str_replace('\r\n', "\n",$sqdd); 
					echo $sqdd;?></textarea>
				<button class="btn btn-sm btn-info" name="valide_edit"><span class="glyphicon glyphicon-pencil"></span> Valider</button></center>
			</form>
		</div>
		</section>
		<section>
			<div id="banniere_reseaux">
		    	<div id="twitter"><?php include('../elements/twitter.php') ?></div>
		    	<div id="facebook"><?php include('../elements/facebook.php') ?></div>
		    	<div id="discord"><?php include('../elements/discord.php') ?></div>
			</div>
		</section>
		<?php include('../elements/footer.php'); ?>
	</div>
	</body>
</html>