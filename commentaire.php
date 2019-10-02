	<?php
	session_start();
	include('membres/base.php');
	include('membres/functions.php');

	$news_id = ($_GET['billet'] !== null) ? $_GET['billet'] : null;
	if(is_string($news_id) && !is_null($news_id))
	{
	$recup_id = $news_id;
	$news_id = null;
	$recup_news = $pdo->query('SELECT id, titre FROM billets');
	while($parcours_news = $recup_news->fetch()){
		if(traduire_nom(stripslashes($parcours_news['titre'])) == $recup_id){
			$news_id = $parcours_news['id'];
		}
	}
	}
	var_dump($news_id);
	$req = $pdo->prepare('SELECT *, billets.description AS description_billet, billets.id AS id_billet, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin\') AS date_creation_fr FROM billets LEFT JOIN users ON users.username = billets.auteur WHERE billets.id = ?');
	$req->execute(array($news_id));
	$donnees = $req->fetch();
	?>
	<!DOCTYPE html>
	<html lang="fr">
	<head>
	<meta charset="utf-8" />
	<title><?php echo htmlspecialchars($donnees['titre']); ?> - Mangas'Fan</title>
	<link rel="icon" href="../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<meta name="description" content="<?php echo htmlspecialchars($donnees['description_billet']);  ?>"/>
	<?php if(!empty($donnees['keywords'])){ ?>
		<meta name="keywords" content="<?php echo htmlspecialchars($donnees['keywords']); ?>"/>
	<?php } ?>
	<meta property="og:site_name" content="mangasfan.fr"/>
	<meta property="og:url" content="https://www.mangasfan.fr/commentaire/<?= sanitize(traduire_nom($donnees['titre'])); ?>" />
	<meta property="og:title" content="<?php echo htmlspecialchars($donnees['titre']); ?> - Mangas'Fan" />
	<meta property="og:description" content="<?php echo htmlspecialchars($donnees['description_billet']); ?>" />
	<meta property="og:image" content="<?php echo htmlspecialchars($donnees['theme']); ?>" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="@MangasFanOff" />
	<meta name="twitter:creator" content="@MangasFanOff" />
	<meta name="twitter:title" content="<?php echo htmlspecialchars($donnees['titre']); ?>">
	<meta name="twitter:description" content="<?php echo htmlspecialchars($donnees['description_billet']); ?>">
	<meta name="twitter:image" content="<?php echo htmlspecialchars($donnees['theme']); ?>">
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-129397962-1');
	</script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
	<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="../style.css">
	</head>
	<body>
		<?php include('elements/header.php'); ?>
		<section class="marge_page">
			<div class="news_deux">
				<h1 class="titre_principal_news">
					<?php echo sanitize($donnees['titre']); ?>	
				</h1>
				<div class="contenu_commentaire">
					<?php
					echo htmlspecialchars_decode(sanitize($donnees['contenu']));
					?>
				</div>
				<?php if(!empty($donnees['sources'])){?>
					<div class="sources">
						<em>Sources : <?php echo sanitize($donnees['sources']); ?></em>
					</div>
				<?php } ?>
				<div class="auteur_news_commentaire">
					Publié par <?php echo rang_etat($donnees['grade'], sanitize($donnees['auteur'])); ?> <em>le <?php echo sanitize($donnees['date_creation_fr']); ?></em>
				</div>				
			</div>
			<h3 id="titre_commentaire">
				<span class="couleur_mangas">ESPACE</span> <span class="couleur_fans">COMMENTAIRES</span>
			</h3>
			<hr class="ligne_commentaire">
			<?php 
			$req2 = $pdo->prepare('SELECT id, auteur, commentaire, date_commentaire AS date_commentaire_fr FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire DESC');
			$req2->execute(array($news_id));
			if (!empty($_POST['comme']))
			{
				//on recupère les variables propres
				$id_billet = $news_id;
				$commentaire = $_POST['comme'];
				$pseudo = $_SESSION['auth']['username'];
				//on enregistre le commentaire en fonction de l'id du billet
				$comme_input = $pdo->prepare("INSERT INTO commentaires (`id_billet`, `auteur`, `commentaire`, `date_commentaire`) VALUES (?,?,?, NOW()) ");
				$comme_input->execute(array(addslashes($id_billet),addslashes($pseudo), $commentaire));
				echo "<div class='alert alert-success' role='alert'>Votre commentaire a bien été posté.</div>";
				echo "<meta http-equiv=\"refresh\" content=\"0; URL=\">";
			}
			?>
			<?php 
			if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){
				$grade_membre2 = $pdo->prepare("SELECT * FROM users WHERE id = ?");
				$grade_membre2->execute(array($_SESSION['auth']['id']));
				$grade_membre = $grade_membre2->fetch();
				include('membres/bbcode.php'); 
				if ($grade_membre['grade'] >= 2)
					{ ?>
						<form action="" name="formulaire_commentaire_supp" method="post">
							<div class="row">
								<div class="offset-1 col-md-2" id="entete_commentaire">
									<center>
										<label name="label_commentaire" for="comme">Ajouter un commentaire :<br /><br/>
											<a href="../inc/bbcode_active.html" class="lien_bbcode" target="blank">Voici la liste des bbcodes possibles</a></label>
										</center>
									</div>
									<div class="col-md-9">
										<textarea name="comme" class="form-control" rows="10" cols="70" required="required" placeholder="Écrivez-ici votre commentaire. (BBCodes utilisable)"></textarea>
										<input type="hidden" name="id_billet" value="<?php if(isset($_GET['billet'])){ echo htmlspecialchars($_GET['billet']); } ?>">
										<center><button name="valider" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-pencil"></span> Envoyer le commentaire</button></center>
									</div>

								</div>
							</form>
							<hr>
						<?php }
						elseif ($grade_membre['grade'] <= 1)
							{ ?>
								<div class='alert alert-warning' role='alert'>Étant banni, vous pouvez seulement lire les commentaires mais vous ne pouvez pas en poster.</div>
							<?php }
						}
						else
							{ ?>
								<div class='alert alert-danger' role='alert'>
									Vous devez être connecté pour pouvoir poster un commentaire ! Cependant, tu peux te <a href="../membres/connexion.php">connecter</a> ou tu peux <a href="../membres/inscription.php">t'inscrire</a> !
								</div>
							<?php } ?>
							<?php 
							while ($donnees2 = $req2->fetch())
							{
								$info_user = $pdo->prepare("SELECT id, username, grade, avatar FROM users WHERE username = ?");
								$info_user->execute(array($donnees2['auteur']));
								while ($user = $info_user->fetch())
									{ ?>
										<div class="espace_commentaire">
											<table name="tableau_commentaire_news">
												<tr name="commentaire_news_tr">
													<td name="colonne_commentaire" align="center" width="100px">
														<?php 
														if (!empty($user['avatar'])){
															if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", htmlspecialchars($user['avatar']))) 
															{ 
																$image_avatar =  '/membres/images/avatars/'.htmlspecialchars($user['avatar']);
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
																<?php echo rang_etat($user['grade'], sanitize($donnees2['auteur'])); ?>
															</a>
														</span>
													</td>
													<td name="colonne_commentaire">
														<span class="pointe"></span>
														<span class="contenu">
															<span style="display:block;padding-bottom:10px;">
																<?php echo htmlspecialchars_decode(sanitize($donnees2['commentaire'])); ?>
															</span>
															<span class="bottom">
																<hr style="margin-bottom: 10px!important">
																<?php $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
																$date_commentaire_fr = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})#",function ($key) use ($liste_mois){ 
																	return 'Posté le '.$key[3].' '.$liste_mois[$key[2]-1].' '.$key[1].' à '.$key[4].'h '.$key[5].'min '.$key[6]; },$donnees2['date_commentaire_fr']);
																	echo sanitize($date_commentaire_fr); ?>
																</span>
															</span>
															<?php
															if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
															{
																if (!empty($_POST['suppr']) AND isset($_POST['suppr']))
																{
							//on recupere l'id du commentaire
																	$id_comme = stripslashes(htmlspecialchars($_POST['id_suppr']));
							//on supprime le commentaire selectionné
																	$suppr_comme = $pdo->prepare("DELETE FROM commentaires WHERE id = ?");
																	$suppr_comme->execute(array($id_comme));
																	echo "<meta http-equiv=\"refresh\" content=\"0; URL=\">";
																} 
																$grade_membre = $pdo->prepare("SELECT * FROM users WHERE id = ?");
																$grade_membre->execute(array($_SESSION['auth']['id']));
																$suppression = $grade_membre->fetch();
																if ($suppression['grade'] >= 9 OR $suppression['username'] == $user['username'])
																	{ ?>
																		<span class="bouton_app">
																			<form method="POST" name="formulaire_commentaire_supp" action="">
																				<input type="hidden" name="id_suppr" value="<?php echo sanitize($donnees2['id']);?>">
																				<input type="submit" class="btn btn-danger" name="suppr" value="Supprimer">
																			</form>	
																		</span>
																		<?php 
																	}
																}
																?>
															</td>
														</tr>
													</table>
												</div>
											<?php }
										}		
										?>
									</section>
									<?php include('elements/footer.php') ?>
							</body>
							</html>