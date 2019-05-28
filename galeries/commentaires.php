<?php
	session_start(); 
	require_once '../inc/base.php';
	include('../inc/data/maintenance_galeries.php');
	  if ($utilisateur['grade'] == 1) 
	    {
	      echo '<script>location.href="../bannis.php";</script>';
	    }
	require_once('../inc/functions.php'); 
	include('../inc/bbcode.php');
	include('../theme_temporaire.php');
	$recuperation = $pdo->prepare('SELECT id, filename, titre, titre_image, texte, DATE_FORMAT(date_image, \'%d/%m/%Y à %Hh%imin\') AS date_image_fr, auteur FROM galerie WHERE id = ?');
	$recuperation->execute(array($_GET['galerie']));
	$galerie = $recuperation->fetch();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Mangas'Fan - <?php echo sanitize($galerie['titre']); ?></title>
		<link rel="icon" href="../images/favicon.png"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<script src='http://use.edgefonts.net/butcherman.js'></script>
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
		<script src="bootstrap/js/jquery.js"></script>
	    <script src="bootstrap/js/bootstrap.min.js"></script>
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	    <link rel="stylesheet" type="text/css" href="overlay.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />

			<?php if(!empty($galerie['titre_image'])){ ?>

		<meta name="keywords" content="<?php echo sanitize($galerie['titre_image']); ?>"/>

			<?php } ?>

	</head>

	<body>

		<div id="bloc_page">

			<header>

				<div id="banniere_image">

					<div id="titre_site">

	        			<span class="couleur_mangas"><?php echo $titre_1; ?></span><?php echo $titre_2; ?><span class="couleur_fans">F</span>AN

	      			</div>

	      			<div class="slogan_site"><?php echo $slogan; ?></div>

					<?php include("../elements/navigation.php") ?>

					<h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>

					<h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>

					<div class="bouton_fofo"><a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a></div>

					<?php include('../elements/header.php'); ?>

				</div>

			</header>

			<section class="marge_page">

				<?php if (!isset($galerie['id'])){ ?>

					<script>location.href="index.php";</script>

					<div class='alert alert-danger' role='alert'>

						<strong>Erreur : </strong>Cette page n'existe pas. Vous allez être rédirigé.

					</div>

				<?php } 

				if (isset($galerie['auteur']) AND $_SESSION['auth']['username'] == $galerie['auteur']){ ?>

					<center>

						<a href="index.php" class="btn btn-primary">

							Retournez sur l'index des galeries

						</a>

						<a href="modifier.php?galerie=<?php echo intval($galerie['id']); ?>" class="btn btn-primary">	

							Modifier la description de l'image ou le contenu

						</a>

						<a href="supprimer.php?galerie=<?php echo intval($galerie['id']); ?>" class="btn btn-danger">

							Supprimer l'image de la galerie

						</a>

					</center><br/>

					<hr>

				<?php }

				?>

				<center>

					<span class="titre_billet_commentaire">

							<?php echo sanitize($galerie['titre']); ?>

					</span> par <?php echo sanitize($galerie['auteur']); ?>

				</center>

				<hr>

				<center>

					<img src="images/<?php echo sanitize($galerie['filename']); ?>" alt="Image de galerie" id="image_galerie" />

				</center>

				<hr>

				<div class="titre_description_galerie">

					Concernant <span class="couleur_mangas">c</span>ette <span class="couleur_fans">i</span>mage :

				</div><br/>

					<p>

						<?php echo htmlspecialchars_decode(sanitize($galerie['texte'])); ?>

					</p>

					<br/>

				<div class="card-footer" style="border-bottom: 1px solid rgba(0,0,0,.125);">

	  				<center><small class="text-muted">Cette image a été postée par <strong><?php echo sanitize($galerie['auteur']); ?></strong> le <?php echo sanitize($galerie['date_image_fr']); ?>. Elle appartient à son auteur. Toute reproduction sans son accord peut entrainer des sanctions. © <?php echo sanitize($galerie['auteur']); ?></small></center>

				</div><br/><br/>

				<h2>Commentaires <span class="couleur_mangas">de</span> <span class="couleur_fans">l'article</span></h2>

				<?php if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ ?>

							<form action="" name="formulaire_commentaire_supp" method="post">

					<div class="row">

							<div class="col-md-2" id="entete_commentaire">

								<center>

									<label name="label_commentaire" for="comme">Ajouter un commentaire :<br /><br/>

								<a href="../inc/bbcode_active.html" class="lien_bbcode" target="blank">Voici la liste des bbcodes possibles</a></label>

								</center>

							</div>

							<div class="col-md-10">

								<textarea name="comme" class="form-control" rows="10" cols="70" required="required" placeholder="Écrivez-ici votre commentaire."></textarea>

								<input type="hidden" name="id_galerie" value="<?php echo sanitize($galerie['id']);?>">

								<center><button name="valider" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-pencil"></span> Envoyer le commentaire</button></center>

							</div>

					</div>

				</form><br/>

					<?php }

					else

					 {

					 	echo"<div class='alert alert-danger' role='alert'>Vous devez être connecté pour pouvoir poster un commentaire.</div>";

					 } 

	 // On récupère les données

				$commentaires = $pdo->prepare('SELECT id_galerie, auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin\') AS date_commentaire_fr FROM commentaires_galerie WHERE id_galerie = ? ORDER BY id DESC');

				$commentaires->execute(array($_GET['galerie']));

				if (!empty($_POST['comme']))

				{

			//on recupère les variables propres

					$id_galerie = $_POST['id_galerie'];

					$commentaire = $_POST['comme'];

					$pseudo = $_SESSION['auth']['username'];

			//on enregistre le commentaire en fonction de l'id du billet

					$comme_input = $pdo->prepare("INSERT INTO commentaires_galerie (`id_galerie`, `auteur`, `commentaire`, `date_commentaire`) VALUES (?,?,?, NOW()) ");

					$comme_input->execute(array(addslashes($id_galerie),addslashes($pseudo), $commentaire));

					echo "<div class='alert alert-success' role='alert'>Votre commentaire a bien été posté.</div>";

					echo "<meta http-equiv=\"refresh\" content=\"0; URL=\">";

				}

				while ($affiche = $commentaires->fetch())

					{ 

						$info_user = $pdo->prepare("SELECT id, username, grade, avatar FROM users WHERE username = ?");

						$info_user->execute(array($affiche['auteur']));

						while ($user = $info_user->fetch()){ ?>

						<div class="espace_commentaire">

				<table name="tableau_commentaire_news">

					<tr name="commentaire_news_tr">

						<td name="colonne_commentaire" align="center" width="100px">

							<?php 

							if (!empty($user['avatar'])){

								if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $user['avatar'])) 

								{ 

									$image_avatar =  '../inc/images/avatars/'.$user['avatar'];

								} 

								else 

								{

									$image_avatar = stripslashes(htmlspecialchars($user['avatar']));

								} 

								?>

							<div class="avatar" style="box-shadow: 0px 0px 2px 2px <?= avatar_color($user['grade']) ?>; background:url('<?= $image_avatar ?>');background-size:100px; background-position: center;"/>

							</div>

					<?php } ?>

						<span class="pseudo">

							<a href="https://www.mangasfan.fr/profil/voirprofil.php?m=<?php echo $user['id']; ?>" target="_blank">

								<?php echo rang_etat($user['grade'], $affiche['auteur']); ?>

							</a>

					</span> 

					<span class="grade">

						<?php if ($user['grade'] >= 12){ ?>

					<span class="fas fa-star" style="color: red; padding-bottom: 10px;" title="Fondateur de Mangas'Fan"></span>

						<?php } elseif ($user['grade'] == 11) { ?>

					<span class="fas fa-star" style="color: darkblue;padding-bottom: 10px;" title="Administrateur du site"></span>

						<?php }  elseif ($user['grade'] == 10) { ?>

					<span class="fas fa-star" style="color: #4080BF;padding-bottom: 10px;" title="Développeur du site"></span>

						<?php } elseif ($user['grade'] == 9) { ?>

					<span class="fas fa-star" style="color: #31B404;padding-bottom: 10px;" title="Modérateur du site"></span>

						<?php } elseif ($user['grade'] >= 5 AND $user['grade'] <= 8) { ?>

					<span class="fas fa-star" style="color: #40A497;padding-bottom: 10px;" title="Plumes du site"></span>

						<?php } elseif ($user['grade'] == 4) { ?>

					<span class="fas fa-star" style="color: #632569; padding-bottom: 10px;" title="Community Manager"></span>

						<?php } elseif ($user['grade'] == 3) { ?>

					<span class="fas fa-star" style="color: orange;padding-bottom: 10px;" title="Animateur du site"></span>

					<?php } ?>

					</span>

						</td>

							<td name="colonne_commentaire">

							<span class="pointe"></span>

							<span class="contenu">

								<span style="display:block;padding-bottom:10px;">

							<?php echo bbcode(sanitize($affiche['commentaire'])); ?>

						</span>

								<span class="bottom">

									<hr style="margin-bottom: 10px!important">

								<?php $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

								$date_commentaire_fr = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})#",function ($key) use ($liste_mois){ 

								return 'Posté le '.$key[3].' '.$liste_mois[$key[2]-1].' '.$key[1].' à '.$key[4].'h '.$key[5].'min '.$key[6]; },$affiche['date_commentaire_fr']);

								echo $date_commentaire_fr;?>

								</span>

							</span>

							<?php 

						

					}

						?>

					</td>

				</tr>

			</table>

		</div>

	<?php } ?>

				</section>

				<?php

				include('../elements/footer.php');

				?>

			</div>

	</body>

</html>