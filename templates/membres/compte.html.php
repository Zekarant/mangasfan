<h2 class="titre">Profil de <span style="color: <?= \Color::rang_etat($utilisateur['grade']) ?>"><?= \Rewritting::sanitize($utilisateur['username']) ?></span></h2>
<hr>
<?php if ($utilisateur['grade'] == 0) { ?>
	<div class="alert alert-danger" role="alert">
		<h5 class="alert-heading">Vous êtes actuellement banni du site !</h5>
		<hr>
		<p>Cher <?= \Rewritting::sanitize($utilisateur['username']) ?>,</p>
		<p>Si ce message s'affiche, c'est que votre compte a été banni des services de Mangas'Fan et qu'il comporte donc des restrictions.</p>
		<p>Les fonctionnalités suivantes vous sont donc interdites d'accès pour le moment :
			<ul>
				<li>Vous ne pouvez plus envoyer, ni répondre aux MP du site.</li>
				<li>Vous ne pouvez plus commenter les news, les articles et les dessins des galeries.</li>
				<li>Vous ne pouvez plus poster d'images sur les galeries. Cependant, les éditions et suppressions restent disponibles.</li>
				<li>Votre pseudo apparait désormais en noir sur le site.</li>
			</ul></p>
			<p>Veuillez noter, cher membre, que ce bannissement est conservé par l'équipe du site, et que nous pourrons très bien utiliser ce dernier si vous veniez à recidiver. Par ailleurs, vous avez certainement reçu un message privé vous indiquant la raison et la durée du bannissement.</p>
			<p>Nous sommes sincèrement désolés d'en être arrivés là. Sachez que si nous avons commis une erreur, vous pouvez nous contacter <a href="mailto:contact@mangasfan.fr">ici</a>.</p>
		</p>
	</div>
<?php } ?>
<div class="container">
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-header">
					Modifier mes informations - Mangas'Fan
				</div>
				<div class="card-body">
					<form method="POST" action="">
						<div class="row">
							<label class="col-lg-4">Modifier mon mot de passe :<br/>
								<small>
									<ul>
										<li>1 majuscule et un chiffre obligatoire</li>
										<li>8 caractères minimum</li>
									</ul>
								</small>
							</label>
							<div class="col-lg-8">
								<input type="password" name="oldpassword" class="form-control" placeholder="Saisissez votre ancien mot de passe" />
								<input type="password" name="password" class="form-control" placeholder="Saisissez votre nouveau mot de passe" />
								<input type="password" name="password_confirm" class="form-control" placeholder="Saisissez à nouveau votre mot de passe pour confirmer" />
								<input type="submit" class="btn btn-sm btn-info" name="changer_mdp" value="Changer mon mot de passe">
							</div>
						</div>
					</form><br/>
					<form method="POST" action="" enctype="multipart/form-data">
						<div class="row">
							<label class="col-lg-4">Sélectionner un avatar :<br/>
								<small>Pensez à vider le cache après le changement (Ctrl + F5)</small>
							</label>
							<div class="col-lg-8">
								<input type="file" name="avatar" class="file btn btn-info"/><br/>
								<input type="submit" name="valider_avatar" class="btn btn-sm btn-info" value="Choisir ce fichier comme avatar" />
							</div>
						</div>
					</form><hr>
					<?php if ($utilisateur['date_anniversaire'] == NULL) { ?>
						<form method="POST" action="">
							<div class="row">
								<label class="col-md-4">Ma date d'anniversaire :</label>
								<div class="col-md-8">
									<em>Attention : une fois validée, vous ne pourrez plus la changer.</em>
									<input type="date" name="date_anniv" class="form-control" placeholder="Changer ma date d'anniversaire" />
									<input type="submit" name="valider_anniv" class="btn btn-sm btn-info" value="Renseigner ma date d'anniversaire" />
								</div>
							</div>
						</form>
						<hr>
					<?php } ?>
					<form method="POST" action="">
						<div class="row">
							<label class="col-lg-4">Modifier mon adresse mail :</label>
							<div class="col-lg-8">
								<input type="email" name="email" class="form-control" value="<?= \Rewritting::sanitize($utilisateur['email']) ?>" />
							</div>
						</div><br/>
						<div class="row">
							<label class="col-lg-4">Modifier mon sexe :</label>
							<div class="col-lg-8">
								<select name="sexe" class="form-control">
									<?php if ($utilisateur['sexe'] == 0 || $utilisateur['sexe'] > 2) { ?>
										<option value="0" selected>Homme</option>
										<option value="1">Femme</option>
										<option value="2">Autre</option>
									<?php } elseif ($utilisateur['sexe'] == 1) { ?>
										<option value="0">Homme</option>
										<option value="1" selected>Femme</option>
										<option value="2">Autre</option>
									<?php } else { ?>
										<option value="0">Homme</option>
										<option value="1">Femme</option>
										<option value="2"selected>Autre</option>
									<?php } ?>
								</select>
							</div>
						</div><br/>
						<div class="row">
							<label class="col-lg-4">Modifier ma description :<br/><button class="btn btn-sm btn-danger" type="reset">Réinitialiser</button></label>
							<div class="col-lg-8">
								<textarea name="description" class="form-control" rows="10" cols="70"><?= \Rewritting::sanitize($utilisateur['description']) ?></textarea>
							</div>
						</div><br/>
						<?php if($utilisateur['grade'] >= 2){ ?>
							<div class="row">
								<label class="col-lg-4">Modifier mon rôle :<br/><button class="btn btn-sm btn-danger" type="reset">Réinitialiser</button></label>
								<div class="col-lg-8">
									<textarea name="role" class="form-control" rows="10" cols="30"><?= \Rewritting::sanitize($utilisateur['role']) ?></textarea>
								</div>
							</div><br/>
						<?php } ?>
						<div class="row">
							<label class="col-lg-4">Manga favori :</label>
							<div class="col-lg-8">
								<input type="text" name="manga" class="form-control" value="<?= \Rewritting::sanitize($utilisateur['manga']) ?>" />
							</div>
						</div><br/>
						<div class="row">
							<label class="col-lg-4">Anime favori :</label>
							<div class="col-lg-8">
								<input type="text" name="anime" class="form-control" value="<?= \Rewritting::sanitize($utilisateur['anime']) ?>" />
							</div>
						</div>
						<br/>
						<div class="row">
							<label class="col-lg-4">Modifier mon site internet :</label>
							<div class="col-lg-8">
								<input type="url" name="site" class="form-control" value="<?= \Rewritting::sanitize($utilisateur['site']) ?>" />
							</div>
						</div><br/>
						<div class="informations_compte">
							<input type="submit" name="valider_information" value="Modifier mes informations" class="btn btn-outline-info">
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="card">
				<div class="card-header">
					Récapitulatif de mon compte - Mangas'Fan
				</div>
				<div class="card-body">
					<div class="avatar_site">
						<img src="images/avatars/<?= \Rewritting::sanitize($utilisateur['avatar']) ?>" />
						<div class="informations_compte">
							<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($utilisateur['grade']) ?>;"><?= Color::getRang($utilisateur['grade'], $utilisateur['sexe'], $utilisateur['stagiaire'], $utilisateur['chef']) ?></span>
						</div>
					</div>
					<hr>
					<div class="informations_compte">
						<h3><?= \Rewritting::sanitize($utilisateur['username']) ?></h3>
					</div>
					<p>Inscrit le <?= date('d/m/Y à H:i', strtotime(\Rewritting::sanitize($utilisateur['confirmed_at']))); ?>.</p>
					<p>Mon adresse mail : <em><?= \Rewritting::sanitize($utilisateur['email']); ?></em></p>
					<p>Mon sexe : <em><?= \Users::sexe(\Rewritting::sanitize($utilisateur['sexe'])); ?></em></p>
					<p>Manga préféré : <em><?= \Rewritting::sanitize($utilisateur['manga']); ?></em></p>
					<p>Anime préféré : <em><?= \Rewritting::sanitize($utilisateur['anime']); ?></em></p>
					<p>Date d'anniversaire : <em><?= \Rewritting::sanitize(\Users::dateAnniversaire($utilisateur['date_anniversaire'])); ?></em></p>
					<p>Description : <em>« <?= nl2br(\Rewritting::sanitize($utilisateur['description'])); ?> »</em></p>
					<?php if ($utilisateur['grade'] >= 2){ ?>
						<p>Rôle : <em>« <?= nl2br(\Rewritting::sanitize($utilisateur['role'])); ?> »</em></p>
					<?php } ?>
					<p>Site web : <em><a href="<?= \Rewritting::sanitize($utilisateur['site']); ?>" target="_blank"><?= \Rewritting::sanitize($utilisateur['site']); ?></a></em></p>
					<p>Mangas'Points : <em><?= \Rewritting::sanitize($utilisateur['points']); ?> points.</em></p>
				</div>
			</div>
		</div>
	</div>
</div>