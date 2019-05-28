<?php
	session_start();
	require_once '../membres/base.php';
	require('../membres/functions.php');
	//include('../inc/data/maintenance_jeux.php');
	$id_jeu = ($_GET['jeux'] !== null) ? $_GET['jeux'] : null;
	$id_page = ($_GET['page'] !== null) ? $_GET['page'] : null;
	$type_elt = "jeux";

	if((int) $id_jeu == 0 && !is_null($id_jeu)){
		$recuperation_id_jeu = $id_jeu;
		$id_jeu = null;
		$recup_id = $pdo->query("SELECT id,titre FROM billets_jeux");
		while($parcours_id = $recup_id->fetch()){
			if(traduire_nom(stripslashes($parcours_id['titre'])) == $recuperation_id_jeu){
				$save_name_jeu = $id_jeu;
				$id_jeu = $parcours_id['id'];
			}
		}
	}

	if((int) $id_page == 0 && !is_null($id_page) && !is_null($id_jeu)){
		$recuperation_id_page = $id_page;
		$id_page = null;
		$recup_id_page = $pdo->query("SELECT id,nom FROM billets_jeux_pages WHERE jeux_id = $id_jeu");
		while($parcours_id = $recup_id_page->fetch()){
			if(traduire_nom(stripslashes($parcours_id['nom'])) == $recuperation_id_page){
				$save_name_page = $recuperation_id_page;
				$id_page = $parcours_id['id'];
			}
		}
	}

	$ok_page = ($id_page !== null) ? '../' : '';

	if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
	}


	$req = $pdo->prepare('SELECT * FROM billets_jeux WHERE id = ? ORDER BY date_creation');
	$req->execute(array($id_jeu));
	$requete = $req->fetch();
	

	//include('../inc/bbcode.php'); 


	if (!is_null($id_jeu) && !is_null($id_page)){
		include('../commentaires/ttt_commentary.php');
	}

	//include('../theme_temporaire.php');
	?>


<html>
	<head>
		<meta charset="utf-8">
		<title>Mangas'Fan - Accueil des jeux vidéos</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<link rel="icon" href="<?= $ok_page; ?>../images/favicon.png"/>
		<link rel="stylesheet" href="<?= $ok_page; ?>../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?= $ok_page; ?>../style/index_jv.css">
		<link rel="stylesheet" href="<?= $ok_page; ?>../style/commentary_style.css">
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Marvel' rel='stylesheet' type='text/css' />
		<link href="https://fonts.googleapis.com/css?family=Patrick+Hand" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Stint+Ultra+Condensed" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Bangers" rel="stylesheet"/>
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:site" content="@Mangas_Fans" />
		<meta name="twitter:creator" content="@Mangas_Fans" />
		<meta property="og:site_name" content="mangasfan.fr"/>
		<meta property="og:url" content="https://www.mangasfan.fr" />
		<meta property="og:title" content="Mangas'Fan - <?php echo $requete['titre']; ?>" />
		<meta property="og:description" content="<?php echo $requete['presentation']; ?>" />
		<meta property="og:image" content="<?php echo $requete['theme']; ?>" />
		<meta name="twitter:title" content="Mangas'Fan - <?php echo $requete['titre']; ?>">
  		<meta name="twitter:description" content="<?php echo $requete['presentation']; ?>">
  		<meta name="twitter:image" content="<?php echo $requete['theme']; ?>">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="<?= $ok_page; ?>../style.css" />
		<link rel="stylesheet" href="<?= $ok_page; ?>../style/jquery_ui_style.css" />
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
		<script>
		    window.dataLayer = window.dataLayer || [];
		    function gtag(){dataLayer.push(arguments);}
		    gtag('js', new Date());

		    gtag('config', 'UA-129397962-1');
		</script>
	</head>

	<body>
		 <div id="bloc_page">
		<?php include('../elements/header.php'); ?>
		<?php if (!is_null($id_jeu) && !is_null($id_page)){
			$verif_jeu_exist = $pdo->prepare("SELECT * FROM billets_jeux WHERE id = ?");
			$verif_jeu_exist->execute(array($id_jeu));
			$verif_page_exist = $pdo->prepare("SELECT * FROM billets_jeux_pages WHERE id = ?");
			$verif_page_exist->execute(array($id_page));
			if ($verif_jeu_exist->rowCount() > 0 && $verif_page_exist->rowCount() > 0){ 
				$verif_page_existe = $verif_page_exist->fetch();?>

				<!-- Concernant le titre et le contenu d'une page -->
				<h2 class="titre"><?= $verif_page_existe['nom'];?></h2>
				<div style="margin-left:10px;margin-right:10px;"><?= stripslashes(htmlspecialchars_decode($verif_page_existe['contenu']));?></div>

				<div>
					<?php include('../commentaires/commentary.php');?>
				</div>

			<?php } else {
				echo 'Vous ne pouvez pas accéder a cette page.';
			}

		} elseif (!is_null($id_jeu)){ // page du jeu en question
			$verif_jeu_exist = $pdo->prepare("
				SELECT * FROM billets_jeux WHERE id = ? LIMIT 1");
			$verif_jeu_exist->execute(array($id_jeu));

			$verif_jeu_existe = $verif_jeu_exist->rowCount();

			if ($verif_jeu_existe != 0){ 
				$liste_pages = $pdo->prepare("
					SELECT P.image, P.nom, O.nom AS name_onglet FROM billets_jeux_pages P INNER JOIN billets_jeux_cat O ON P.num_onglet = O.id WHERE P.jeux_id = ? ORDER BY date_post DESC");

				$donnees_jeu = $verif_jeu_exist->fetch();
				$liste_pages->execute(array($donnees_jeu['id']));
				$taille_liste_pages = $liste_pages->rowCount();

				$donnees_pages = $liste_pages->fetchAll();
			    ?>

				<div class="accueil_jeu">
					<h4 class="titre_jeu_index"><?= stripslashes($donnees_jeu['titre']); ?></h4>
					<img src="<?= $donnees_jeu['theme']; ?>" class="vignette_jeu"/>
					<?php include('../fichiers_externes/notes.php');
					use_note($pdo,$id_jeu,'jeux');?>
					
					<?php if(!empty($donnees_jeu['presentation'])){ ?>
						<h5>Présentation</h5>
						<p><?= sanitize($donnees_jeu['presentation']); ?></p>
					<?php } ?>

					<?php if ($taille_liste_pages >= 1) { ?>
						<h5 class="titre_mobile">Derniers articles</h5>
						<div id="total_last_article">
							<div id="last_article" style="background:url('<?= $donnees_pages[0]['image']; ?>');">
								<span class="onglet"><?= $donnees_pages[0]['name_onglet']; ?></span>
								<img src="http://pixsector.com/cache/a8009c95/av8a49a4f81c3318dc69d.png" class="left_arrow"/>
								<img src="http://pixsector.com/cache/81183b13/avcc910c4ee5888b858fe.png" class="right_arrow"/>
								<span class="title_last_art"><?= $donnees_pages[0]['nom']; ?></span>
							</div>
							<div id="button"><?php $i = 0; while($i < $taille_liste_pages){ ;?><span class="button_js butnum<?= $i; ?>" style="color:#A9A9A9;">•</span><?php if($i != $taille_liste_pages){ echo " "; } $i++; } ?></div>
						</div>

						<h5 class="titre_mobile">Retrouvez tous les articles</h5>
							<form id="recherche_page">
								<span class="glyphicon glyphicon-search" style="color:#757779"></span>
							    <input type="text" id="recherche" placeholder="Recherchez une page"/>
							</form>

							<div id="onglet">
								<center><span class="titre" style="text-align:left;" class="<?= $id_jeu ?>"><img src="https://zupimages.net/up/18/25/es4a.png" style="padding-right:10px;font-size:21px;"/>Catégories</span></center>

								<?php  
								$recup_all_category = $pdo->prepare("SELECT DISTINCT O.nom AS name_onglet FROM billets_jeux_pages P INNER JOIN billets_jeux_cat O ON P.num_onglet = O.id WHERE jeux_id = ? ORDER BY name_onglet");
								$recup_all_category->execute(array($id_jeu));

								$i = 0;
								$parcours_category = $recup_all_category->fetchAll();
								while($i < $recup_all_category->rowCount()){ ?>
									<span class="<?= ($i == 0) ? "cat_active" : "name_cat" ?>"><?= $parcours_category[$i]['name_onglet']; ?></span>
								<?php $i++; } ?>
							</div>

							<?php 
								$i = 0;
								$first_cat = $pdo->prepare("SELECT P.*,O.nom AS name_onglet 
									FROM billets_jeux_pages P 
									INNER JOIN billets_jeux_cat O 
									ON P.num_onglet = O.id 
									WHERE O.nom = ? AND jeux_id = ? 
									ORDER BY P.type_art DESC, date_post DESC 
									LIMIT 0,10");
								$first_cat->execute(array($parcours_category[0]['name_onglet'],$id_jeu));
							?>

							<div id="liste_pages"> 
								<span class="entete_liste_page"><b>Pages de :</b> <span class="titre_name_cat"><?= $parcours_category[0]['name_onglet']; ?></span></span>
								<?php while ($all_page = $first_cat->fetch()){ 
									if($all_page['type_art'] == null){ ?>
										<div class="name_page bloc_page">
											<a href="<?= traduire_nom(stripslashes($donnees_jeu['titre'])); ?>/<?= traduire_nom(stripslashes($all_page['nom']));?>"><span class="titre_post_page"><?= $all_page['nom']; ?></span></a>
											<span class="date_time_post_page"><?= write_date($all_page['date_post'], $all_page['member_post']); ?></span>
										</div>
									<?php } else { ?>
										<div class="pit_mess bloc_page">
											<a href="<?= traduire_nom(stripslashes($donnees_jeu['titre'])); ?>/<?= traduire_nom(stripslashes($all_page['nom']));?>"><span class="titre_pit_mess">[Post-It] <?= $all_page['nom']; ?></span></a>
											<span class="date_time_post_page"><?= write_date($all_page['date_post'],$all_page['member_post']); ?></span>
										</div>
									<?php }
								} ?>
								<p><i>Limitation à 10 articles</i></p>
							</div>

						<?php } else { ?>
							<div class='alert alert-warning' role='alert'><center><b>Info : </b>Il n'y a pas encore de page pour ce jeu.</center></div>
						<?php } ?>
		
					<h5>Navigation rapide</h5>
					<center><p><i>Envie de nous aider ? Postulez en tant que rédacteur !</i></p></center>
				</div>
			<?php } else { 
				echo 'Vous ne pouvez pas accéder a cette page.';
			} ?>

		<?php } else { ?>
			<h2 class="titre_principal_news">Nos Jeux Vidéos</h2>
			<hr>
		 	<?php include("../elements/messages.php"); ?>

		 	<div id="conteneur_dossiers">
		  		<?php $req2 = $pdo->query("SELECT * FROM billets_jeux ORDER BY id DESC");
		  		while ($donnees = $req2->fetch()) { ?>
				<div class="gallery">
  					<a href="../jeux_video/<?= traduire_nom(stripslashes($donnees['titre'])); ?>">
   						<img src="<?= $donnees['vignette']; ?>" />
  					</a>
  					<div class="desc"><?= stripslashes($donnees['titre']); ?></div>
				</div>
  				<?php } $req->closeCursor(); ?>
			</div>

		<?php } ?>

		<script type="text/javascript" src="<?= $ok_page; ?>../fichiers_externes/function_redac.js"></script>
		<script type="text/javascript" src="<?= $ok_page; ?>../fichiers_externes/script.js"></script>

		<?php include('../elements/footer.php'); ?>
	</div>
	</body>
</html>