	<?php
	session_start();
	require_once '../inc/base.php';
	if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
		$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
		$user->execute(array($_SESSION['auth']['id']));
		$utilisateur = $user->fetch(); 
	}
	include('../theme_temporaire.php');
	include('../inc/functions.php');
	include('../inc/bbcode.php');
	$recuperer_informations = $pdo->prepare('SELECT * FROM users WHERE id = ?');
	$recuperer_informations->execute(array($_GET['membre']));
	$informations = $recuperer_informations->fetch();
	?>
	<!DOCTYPE html>
	<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Mangas'Fan - Fiche membre de <?php echo $informations['username']; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
		<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		<script src='http://use.edgefonts.net/nosifer.js'></script>
		<script src='http://use.edgefonts.net/emilys-candy.js'></script>
		<script src='http://use.edgefonts.net/butcherman.js'></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<link rel="icon" href="../images/favicon.png"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>">
	</head>
	<body>
		<div id="bloc_page">
			<?php 
			if (!isset($_SESSION['auth'])){
				?>
				<div class='alert alert-danger' role='alert'>
					Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
				</div>
			<?php } elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] < 10) {
				?>
				<div class='alert alert-danger' role='alert'>
					Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
				</div>
				<?php
			} else { 
				include('../elements/nav_admin.php');
				?>
				<section class="marge_page">
					<?php
					if($informations['grade'] > 11){
						?>
						<div class='alert alert-warning' role='alert'>
							Vous ne pouvez pas accéder aux informations de ce membre.
						</div>
						<?php
					} else {
						?>
						<h3 class="titre_pannel">
							Informations générales <span class="couleur_mangas">de</span> <span class="couleur_fans"><?php echo sanitize($informations['username']); ?></span>
						</h3>
						<div class="card-group">
							<div class="card">
								<div class="card-header text-center">
									Avatar
								</div>
								<div class="card-body text-center">
									<?php 
									if (!empty($informations['avatar'])){
										if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $informations['avatar'])) { ?>
											<img src="../inc/images/avatars/<?php echo $informations['avatar']; ?>" alt="avatar_admin" class="avatar_admin"/> <!-- via fichier -->
										<?php } else {
											?>
											<img src="<?php echo sanitize($informations['avatar']); ?>" alt="avatar_admin" class="avatar_admin"/><br/> <!-- via site url -->
										<?php } } ?>
									</div>
								</div>
								<div class="card">
									<div class="card-header text-center">
										Informations
									</div>
									<div class="card-body">
										Pseudo : <?php echo sanitize($informations['username']); ?>.<br/>
										Adresse mail : <?php echo sanitize($informations['email']); ?>.<br/>
										Date de naissance : <?php if ($informations['date_anniv'] != NULL) { $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
										$date_anniversaire = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2})#",function ($key) use ($liste_mois){ 
											return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; }, $informations['date_anniv']);
											echo sanitize($date_anniversaire); } else { echo "Ce membre n'a pas renseigné sa date de naissance."; }?><br/>
											Date d'inscription : <?php $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
											$date_inscription = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2})#",function ($key) use ($liste_mois){ 
												return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; }, $informations['confirmed_at']);
												echo sanitize($date_inscription); ?>.<br/>
												Description : <a href="javascript:document.getElementById('re001').style.display='block';document.getElementById('re001').releaseCapture();">
													<u>Voir la description</u>
												</a>
												ou 
												<a href="javascript:document.getElementById('re001').style.display='none';document.getElementById('re001').releaseCapture();">
													<u>Cacher la descritpion</u>
												</a><br/>
												<div id="re001" style="display: none;">
													<div style="border: 2px solid black; padding: 5px;">
														<?php 
														if ($informations['description'] != NULL) {
															echo bbcode(sanitize($informations['description']));
														} else {
															echo "Ce membre n'a renseigné aucune description.";
														}
														?><br/>
													</div>
												</div>
												Grade : <?php echo statut(sanitize($informations['grade'])); ?><br/>
												Mangas : <?php 
												if ($informations['manga'] != NULL) {
													echo sanitize($informations['manga']);
												} else {
													echo "Ce membre n'a pas renseigné son mangas favori.";
												} 
												?><br/>
												Anime : <?php 
												if ($informations['anime'] != NULL) {
													echo sanitize($informations['anime']);
												} else {
													echo "Ce membre n'a pas renseigné son anime favori.";
												} 
												?><br/>
												Rôle du membre : <?php 
												if ($informations['role'] != NULL) {
													echo sanitize($informations['anime']);
												} else {
													echo "Ce membre n'est pas du staff.";
												} 
												?><br/>
												Site du membre : <?php 
												if ($informations['site'] != NULL) {
													?>
													<a href="<?php echo sanitize($informations['site']); ?>"><?php echo sanitize($informations['site']); ?>
												</a>
												<?php
											} else {
												echo "Ce membre n'a pas renseigné son site internet.";
											} 
											?><br/>
											Mangas'Points : <?php echo sanitize($informations['points']); ?> points.<br/>
											Nombre d'animations remportées : <?php echo sanitize($informations['animation_gagne']); ?> remportées.<br/>
											Nombre d'avertissements : <?php if ($informations['avertissements'] > 0) {
											echo sanitize($informations['avertissements']);
											} else {
												echo "Ce membre n'a pas d'avertissements.";
											} ?>
										</div>
									</div>
								</div>
								<center>
									<a href="index.php">Retourner sur l'index de la modération</a>
								</center>
								<h3 class="titre_pannel">
									Modifier les informations <span class="couleur_mangas">de</span> <span class="couleur_fans"><?php echo sanitize($informations['username']); ?></span>
								</h3>
								<?php
								if(!empty($_POST['new_pseudo'])){
									$modifier_pseudo = $pdo->prepare('UPDATE users SET username = ? WHERE id = ?');
									$modifier_pseudo->execute(array($_POST['pseudo'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	Le pseudo du membre a bien été modifié !
					                </div>
								<?php } elseif (!empty($_POST['new_mail'])) {
									$modifier_mail = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
									$modifier_mail->execute(array($_POST['mail'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	Le mail du membre a bien été modifié !
					                </div>
								<?php } elseif (!empty($_POST['new_anniv'])) {
									$modifier_anniv = $pdo->prepare('UPDATE users SET date_anniv = ? WHERE id = ?');
									$modifier_anniv->execute(array($_POST['anniv'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	La date d'anniversaire du membre a bien été modifiée !
					                </div>
								<?php } elseif (!empty($_POST['new_avatar'])) {
									$avatar = 'https://mangasfan.fr/inc/images/avatars/avatar_defaut.png';
									$modifier_avatar = $pdo->prepare('UPDATE users SET avatar = ? WHERE id = ?');
									$modifier_avatar->execute(array($avatar, $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	L'avatar par défaut a été appliqué au membre !
					                </div>
								<?php } elseif (!empty($_POST['new_description'])) {
									$modifier_description = $pdo->prepare('UPDATE users SET description = ? WHERE id = ?');
									$modifier_description->execute(array($_POST['description'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	La description du membre a bien été modifiée !
					                </div>
								<?php } elseif (!empty($_POST['new_mangas'])) {
									$modifier_manga = $pdo->prepare('UPDATE users SET manga = ? WHERE id = ?');
									$modifier_manga->execute(array($_POST['mangas'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	Le mangas du membre a bien été modifié !
					                </div>
								<?php } elseif (!empty($_POST['new_anime'])) {
									$modifier_anime = $pdo->prepare('UPDATE users SET anime = ? WHERE id = ?');
									$modifier_anime->execute(array($_POST['anime'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	L'anime du membre a bien été modifié !
					                </div>
								<?php } elseif (!empty($_POST['new_role'])) {
									$modifier_role = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
									$modifier_role->execute(array($_POST['role'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	Le rôle du membre a bien été modifié !
					                </div>
								<?php } elseif (!empty($_POST['new_site'])) {
									$modifier_site = $pdo->prepare('UPDATE users SET site = ? WHERE id = ?');
									$modifier_site->execute(array($_POST['site'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	Le site du membre a bien été modifié !
					                </div>
								<?php } elseif (!empty($_POST['new_points'])) {
									$modifier_points = $pdo->prepare('UPDATE users SET points = ? WHERE id = ?');
									$modifier_points->execute(array($_POST['points'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	Les points du membre ont bien été modifiés !
					                </div>
								<?php } elseif (!empty($_POST['new_animations'])) {
									$modifier_animations = $pdo->prepare('UPDATE users SET animation_gagne = ? WHERE id = ?');
									$modifier_animations->execute(array($_POST['animations'], $informations['id']));
								?>
									<div class='alert alert-success' role='alert'>
					                	Le nombre d'animations gagnées du membre a bien été modifié !
					                </div>
								<?php } ?>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th class="tableau_mobile">Champs</th>
											<th>Modification</th>
											<th>Validation</th>
										</tr>
									</thead>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Pseudo</td>
											<td><input name="pseudo" type="text" class="form-control" placeholder="Modifier le pseudo" value="<?php echo $informations['username'];?>"></td>
											<td><input name="new_pseudo" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Mail</td>
											<td><input name="mail" type="text" class="form-control" placeholder="Modifier le mail" value="<?php echo $informations['email'];?>"></td>
											<td><input name="new_mail" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Date de naissance</td>
											<td><input name="anniv" type="text" class="form-control" placeholder="Modifier la date de naissance" value="<?php echo $informations['date_anniv'];?>"></td>
											<td><input name="new_anniv" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Avatar</td>
											<td><input name="avatar" type="text" class="form-control" placeholder="Réinitialiser l'avatar" value="<?php echo $informations['avatar'];?>" readonly></td>
											<td><input name="new_avatar" type="submit" value="Reinitialiser" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Description</td>
											<td><textarea name="description" class="form-control" placeholder="Modifier la description"><?php echo $informations['description'];?></textarea></td>
											<td><input name="new_description" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Mangas</td>
											<td><input name="mangas" type="text" class="form-control" placeholder="Modifier le mangas" value="<?php echo $informations['manga'];?>"></td>
											<td><input name="new_mangas" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Anime</td>
											<td><input name="anime" type="text" class="form-control" placeholder="Modifier l'anime" value="<?php echo $informations['anime'];?>"></td>
											<td><input name="new_anime" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Rôle</td>
											<td><input name="role" type="text" class="form-control" placeholder="Modifier le rôle" value="<?php echo $informations['role'];?>"></td>
											<td><input name="new_role" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Site</td>
											<td><input name="site" type="text" class="form-control" placeholder="Modifier le site" value="<?php echo $informations['site'];?>"></td>
											<td><input name="new_site" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Mangas'Points</td>
											<td><input name="points" type="text" class="form-control" placeholder="Modifier les points" value="<?php echo $informations['points'];?>"></td>
											<td><input name="new_points" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
									<form method="POST" action="">
										<tr>
											<td class="tableau_mobile">Animations</td>
											<td><input name="animations" type="text" class="form-control" placeholder="Modifier les animations" value="<?php echo $informations['animation_gagne'];?>"></td>
											<td><input name="new_animations" type="submit" value="Valider" class="btn btn-sm btn-info"></td>
										</tr>
									</form>
								</table>
							<?php } ?>
						</section>
					<?php } ?>
					<?php include('../elements/footer.php'); ?>
				</div>
			</body>
			</html>