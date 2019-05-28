	<?php
	session_start(); 
	require_once '../inc/base.php';
	include('../inc/data/maintenance_blogs.php');
	  if ($utilisateur['grade'] == 1) 
	    {
	      echo '<script>location.href="../bannis.php";</script>';
	    }
	require_once('../inc/functions.php'); 
	include('../inc/bbcode.php');
	include('../theme_temporaire.php');
	$recuperation = $pdo->prepare('SELECT id, titre, auteur, image, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin\') AS date_creation_fr FROM billets_blogs WHERE id = ?');
					$recuperation->execute(array($_GET['billets']));
					$billets = $recuperation->fetch();
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Mangas'Fan - Article de blog</title>
		<link rel="icon" href="../images/favicon.png"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<script src='http://use.edgefonts.net/butcherman.js'></script>
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
	</head>
	<body>
		<div id="bloc_page">
			<header>
				<div id="banniere_image">
					<div id="titre_site"><span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN</div>
					<div class="slogan_site">Votre référence Mangas</div>
					<?php include("../elements/navigation.php") ?>
					<h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
					<h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
					<div class="bouton_fofo"><a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a></div>
					<?php include('../elements/header.php'); ?>
				</div>
			</header>
			<section class="marge_page">
				<?php
					if (isset($billets['auteur'])){
					?><?php $username = $billets['auteur'];
					if (isset($_SESSION['auth']) AND $_SESSION['auth']['username'] == $username) {?>
						<center><a href="index.php" class="btn btn-primary">Retournez sur l'index des blogs</a>
							<a href="modifier.php?billets=<?php echo intval($billets['id']); ?>" class="btn btn-primary">	Modifier l'article
						</a>
						<a href="supprimer.php?billets=<?php echo intval($billets['id']); ?>" class="btn btn-danger">Supprimer un article</a></center><br/>
					<?php }
					?>
					<div id="billets_auteur">
						<div class="titre_billet_commentaire">
							<?php echo sanitize($billets['titre']); ?>
						</div><br/><br/>
						<p> <?php 
							echo htmlspecialchars_decode(sanitize($billets['contenu'])); 
						?>
					</p>
					<div class="date_post">
						<em>Posté le <?php echo sanitize($billets['date_creation_fr']); ?> par <?php echo $username = sanitize($billets['auteur']); ?></em><br/>
					</div>
				</div>
				<h2>Commentaires <span class="couleur_mangas">de</span> <span class="couleur_fans">l'article</span></h2>
				<?php if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ ?>
							<form action="" name="formulaire_commentaire_supp" method="post">
							<center><label class="col-sm-2" name="label_commentaire" for="comme">Ajouter un commentaire :<br /><br/>
								<a href="../inc/bbcode_active.html" class="lien_bbcode" target="blank">Voici la liste des bbcodes possibles</a></label>
								<textarea name="comme" class="form-control" rows="10" cols="70" required="required" placeholder="Écrivez-ici votre commentaire."></textarea>
								<input type="hidden" name="id_billet" value="<?php echo sanitize($billets['id']);?>">
								<button name="valider" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-pencil"></span> Envoyer le commentaire</button>
							</center>
						</form><br/>
					<?php }
					else
					 {
					 	echo"<div class='alert alert-danger' role='alert'>Vous devez être connecté pour pouvoir poster un commentaire.</div>";
					 } 
	 // On récupère les données
				$commentaires = $pdo->prepare('SELECT id_billet, auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin\') AS date_commentaire_fr FROM commentaires_blogs WHERE id_billet = ? ORDER BY id DESC');
				$commentaires->execute(array($_GET['billets']));
				if (!empty($_POST['comme']))
				{
			//on recupère les variables propres
					$id_billet = $_POST['id_billet'];
					$commentaire = $_POST['comme'];
					$pseudo = $_SESSION['auth']['username'];
			//on enregistre le commentaire en fonction de l'id du billet
					$comme_input = $pdo->prepare("INSERT INTO commentaires_blogs (`id_billet`, `auteur`, `commentaire`, `date_commentaire`) VALUES (?,?,?, NOW()) ");
					$comme_input->execute(array(addslashes($id_billet),addslashes($pseudo), $commentaire));
					echo "<div class='alert alert-success' role='alert'>Votre commentaire a bien été posté.</div>";
					echo "<meta http-equiv=\"refresh\" content=\"0; URL=\">";
				}
				while ($affiche = $commentaires->fetch())
					{ 
						$info_user = $pdo->prepare("SELECT id, username, grade, avatar, testeurs, testeurs_deux FROM users WHERE username = ?");
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
									$image_avatar =  '/inc/images/avatars/'.$user['avatar'];
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
					<span class="glyphicon glyphicon-star" style="color: red; padding-bottom: 10px;" title="Fondateur de Mangas'Fan"></span>
						<?php } elseif ($user['grade'] == 11) { ?>
					<span class="glyphicon glyphicon-star" style="color: darkblue;padding-bottom: 10px;" title="Administrateur du site"></span>
						<?php }  elseif ($user['grade'] == 10) { ?>
					<span class="glyphicon glyphicon-star" style="color: #4080BF;padding-bottom: 10px;" title="Développeur du site"></span>
						<?php } elseif ($user['grade'] == 9) { ?>
					<span class="glyphicon glyphicon-star" style="color: #31B404;padding-bottom: 10px;" title="Modérateur du site"></span>
						<?php } elseif ($user['grade'] >= 5 AND $user['grade'] <= 8) { ?>
					<span class="glyphicon glyphicon-star" style="color: #40A497;padding-bottom: 10px;" title="Plumes du site"></span>
						<?php } elseif ($user['grade'] == 4) { ?>
					<span class="glyphicon glyphicon-star" style="color: #632569; padding-bottom: 10px;" title="Community Manager"></span>
						<?php } elseif ($user['grade'] == 3) { ?>
					<span class="glyphicon glyphicon-star" style="color: orange;padding-bottom: 10px;" title="Animateur du site"></span>
					<?php } ?>
					<?php if ($user['testeurs'] == 1){ ?>
					<span class="glyphicon glyphicon-heart" style="color: #6fb6bd;padding-bottom: 10px;" title="Partenaire officiel"></span>
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
	<?php }
			}
				else
					{
						echo "<div class='alert alert-danger' role='alert'>Cette page n'existe pas.</div>";
						echo '<script>location.href="index.php";</script>';
					}
					?>
				</section>
				<?php
				include('../elements/footer.php');
				?>
			</div>
		</body>
		</html>