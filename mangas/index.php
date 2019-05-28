<?php
	session_start();
	require_once '../membres/base.php';
	include('../membres/functions.php');
	//include('../inc/data/maintenance_mangas.php');
	$id_mangas = ($_GET['mangas'] !== null) ? $_GET['mangas'] : null;
	$id_page = ($_GET['page'] !== null) ? $_GET['page'] : null;
	$type_elt = "mangas";

	if((int) $id_mangas == 0 && !is_null($id_mangas)){
		$recuperation_id_mangas = $id_mangas;
		$recup_id = $pdo->query("SELECT id,titre FROM billets_mangas");
		$id_mangas = null;
		while($parcours_id = $recup_id->fetch()){
			if(traduire_nom($parcours_id['titre']) == $recuperation_id_mangas){
				$save_name_jeu = $id_mangas;
				$id_mangas = $parcours_id['id'];
			}
		}
	}

	if((int) $id_page == 0 && !is_null($id_page) && !is_null($id_mangas)){
		$recuperation_id_page = $id_page;
		$recup_page = null;
		$recup_id_page = $pdo->query("SELECT id,nom FROM billets_mangas_pages WHERE mangas_id = $id_mangas");
		while($parcours_id = $recup_id_page->fetch()){
			if(traduire_nom($parcours_id['nom']) == $recuperation_id_page){
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

	$req = $pdo->prepare('SELECT *, billets_mangas.id AS id_billet, DATE_FORMAT(billets_mangas.date_creation, \'%d %M %Y à %Hh %imin\') AS date_creation_fr FROM billets_mangas WHERE id = ? ORDER BY date_creation');
	$req->execute(array($id_mangas));
	$requete = $req->fetch();

	//include('../inc/bbcode.php'); 
	if (!is_null($id_mangas) && !is_null($id_page)){
		$id_jeu = $id_mangas;
		include('../commentaires/ttt_commentary.php');
	}

	//include('../theme_temporaire.php');
	?>

<html>
	<head>
		<meta charset="utf-8">
		<title>Mangas'Fan - Accueil des mangas</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<link rel="icon" href="<?= $ok_page; ?>../images/favicon.png"/>
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Marvel' rel='stylesheet' type='text/css' />
		<link href="https://fonts.googleapis.com/css?family=Patrick+Hand" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Stint+Ultra+Condensed" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Bangers" rel="stylesheet"/>
		<link rel="stylesheet" href="<?= $ok_page; ?>../bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?= $ok_page; ?>../style/index_jv.css">
		<link rel="stylesheet" href="<?= $ok_page; ?>../style/commentary_style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
		<link rel="stylesheet" href="<?= $ok_page; ?>../style.css">
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
		<?php if (!is_null($id_mangas) && !is_null($id_page)){
			$verif_jeu_exist = $pdo->prepare("SELECT * FROM billets_mangas WHERE id = ?");
			$verif_jeu_exist->execute(array($id_mangas));
			$verif_page_exist = $pdo->prepare("SELECT * FROM billets_mangas_pages WHERE id = ?");
			$verif_page_exist->execute(array($id_page));
			if ($verif_jeu_exist->rowCount() > 0 && $verif_page_exist->rowCount() > 0){ 
				$verif_page_existe = $verif_page_exist->fetch();?>

				<!-- Concernant le titre et le contenu d'une page -->
				<<h2 class="titre"><?= $verif_page_existe['nom'];?></h2>
				<div style="margin-left:10px;margin-right:10px;"><?= stripslashes(htmlspecialchars_decode($verif_page_existe['contenu']));?></div>

				<div>
					<?php include('../commentaires/commentary.php');?>
				</div>

			<?php } else {
				echo 'Vous ne pouvez pas accéder a cette page.';
			}

		} elseif (!is_null($id_mangas)){ // page du jeu en question
			$verif_jeu_exist = $pdo->prepare("SELECT * FROM billets_mangas WHERE id = ? LIMIT 1");
			$verif_jeu_exist->execute(array($id_mangas));

			$verif_jeu_existe = $verif_jeu_exist->rowCount();

			if ($verif_jeu_existe != 0){ 
				$liste_pages = $pdo->prepare("SELECT P.image, P.nom, O.nom AS name_onglet FROM billets_mangas_pages P INNER JOIN billets_mangas_cat O ON P.num_onglet = O.id WHERE P.mangas_id = ? ORDER BY date_post DESC");

				$donnees_jeu = $verif_jeu_exist->fetch();
				$liste_pages->execute(array($donnees_jeu['id']));
				$taille_liste_pages = $liste_pages->rowCount();

				$donnees_pages = $liste_pages->fetchAll();
			    ?>

				<div class="accueil_jeu">
					<h4><?= $donnees_jeu['titre']; ?></h4>
					<img src="<?= $donnees_jeu['theme']; ?>" class="vignette_jeu"/>
					<?php include('../fichiers_externes/notes.php');
					use_note($pdo,$id_mangas,'manga');?>
					
					<?php if(!empty($donnees_jeu['presentation'])){ ?>
						<h5>Présentation</h5>
						<p><?= str_replace('\n','<br />',htmlspecialchars($donnees_jeu['presentation'])); ?></p>
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
							<div id="button"><?php $i = 0; while($i < $taille_liste_pages && $i < 5){ ;?><span class="button_js butnum<?= $i; ?>" style="color:#A9A9A9;">• </span><?php $i++; } ?></div>
						</div>

						<h5 class="titre_mobile">Retrouvez tous les articles</h5>
							<form id="recherche_page">
								<span class="glyphicon glyphicon-search" style="color:#757779"></span>
							    <input type="text" id="recherche" placeholder="Recherchez une page"/>
							</form>

							<div id="onglet">
								<center><span class="titre" style="text-align:left;" class="<?= $id_mangas ?>"><img src="https://zupimages.net/up/18/25/es4a.png" style="padding-right:10px;font-size:21px;"/>Catégories</center>

								<?php  
								$recup_all_category = $pdo->prepare("SELECT DISTINCT O.nom AS name_onglet FROM billets_mangas_pages P INNER JOIN billets_mangas_cat O ON P.num_onglet = O.id WHERE mangas_id = ? ORDER BY name_onglet");
								$recup_all_category->execute(array($id_mangas));

								$i = 0;
								$parcours_category = $recup_all_category->fetchAll();
								while($i < $recup_all_category->rowCount()){?>
									<span class="<?= ($i == 0) ? "cat_active" : "name_cat" ?>"><?= $parcours_category[$i]['name_onglet']; ?></span>
								<?php $i++; } ?>
							</div>

							<?php 
								$i = 0;
								$first_cat = $pdo->prepare("SELECT P.*,O.nom AS name_onglet 
									FROM billets_mangas_pages P 
									INNER JOIN billets_mangas_cat O 
									ON P.num_onglet = O.id 
									WHERE O.nom = ? AND mangas_id = ? 
									ORDER BY P.type_art DESC, date_post DESC 
									LIMIT 0,10");
								$first_cat->execute(array($parcours_category[0]['name_onglet'],$id_mangas));
							?>

							<div id="liste_pages"> 
								<span class="entete_liste_page"><b>Pages de :</b> <span class="titre_name_cat"><?= $parcours_category[0]['name_onglet']; ?></span></span>
								<?php while ($all_page = $first_cat->fetch()){ 
									if($all_page['type_art'] == null){ ?>
										<div class="name_page bloc_page">
											<a href="<?= traduire_nom($donnees_jeu['titre']); ?>/<?= traduire_nom($all_page['nom']);?>"><span class="titre_post_page"><?= $all_page['nom']; ?></span></a>
											<span class="date_time_post_page"><?= write_date($all_page['date_post'], $all_page['member_post']); ?></span>
										</div>
									<?php } else { ?>
										<div class="pit_mess bloc_page">
											<a href="<?= traduire_nom($donnees_jeu['titre']); ?>/<?= traduire_nom($all_page['nom']);?>"><span class="titre_pit_mess">[Post-It] <?= $all_page['nom']; ?></span></a>
											<span class="date_time_post_page"><?= write_date($all_page['date_post'], $all_page['member_post']); ?></span>
										</div>
									<?php }
								} ?>
								<p><i>Limitation à 10 articles</i></p>
							</div>

						<?php } else { ?>
							<div class='alert alert-warning' role='alert'><center><b>Info : </b>Il n'y a pas encore de page pour ce manga.</center></div>
						<?php } ?>
		
					<h5>Navigation rapide</h5>
					<center><p><i>Envie de nous aider ? Postulez en tant que rédacteur !</i></p></center>
				</div>
			<?php } else { 
				echo 'Vous ne pouvez pas accéder a cette page.';
			} ?>

		<?php } else { ?>
			<h2 class="titre_principal_news">Nos Mangas/Animes
			</h2>
			<hr>
		 	<?php include("../elements/messages.php"); ?>
		 	<?php
         if (!empty($_GET['page']) && is_numeric($_GET['page']) )
         $page = stripslashes($_GET['page']);
         else
         $page = 1;
         $pagination = 20;
         // Numéro du 1er enregistrement à lire
         $limit_start = ($page - 1) * $pagination;
         $nb_total = $pdo->query('SELECT COUNT(*) AS nb_total FROM billets_mangas');
         $nb_total->execute();
         $nb_total = $nb_total->fetchColumn();
         // Pagination
         $nb_pages = ceil($nb_total / $pagination);

         echo '<table style="width:50%"><th style="width:33%"><span class="pagination_mobile_membres">[ Page :';
         // Boucle sur les pages
         for ($i = 1 ; $i <= $nb_pages ; $i++) {
         if ($i == $page )
         echo " $i";
         else
         echo " <a href=\"https://www.mangasfan.fr/mangas/p$i\">$i</a> ";
         }
         echo ' ]</span></th></table>'; 
    ?>
		 	<div id="conteneur_dossiers">
		  		 <?php
            $req = $pdo->query('SELECT *, billets_mangas.id AS id_billet, DATE_FORMAT(billets_mangas.date_creation, \'%d %M %Y à %Hh %imin\') AS date_creation_fr
				FROM billets_mangas
				ORDER BY date_creation DESC
				LIMIT ' . $limit_start . ', ' . $pagination . '
				');
            //rajouter le pseudo et une image ainsi que la catégorie de la news
            while ($donnees = $req->fetch())
            { ?>
				<div class="gallery">
  					<a href="../mangas/<?= traduire_nom($donnees['titre']);?>">
   						<img src="<?= $donnees['vignette']; ?>" />
  					</a>
  					<div class="desc"><?= $donnees['titre']; ?></div>
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