<h2 class="titre">Profil de <?= \Rewritting::sanitize($profil['username']) ?></h2>
<hr>
<div class="media">
	<img src="images/avatars/<?= \Rewritting::sanitize($profil['avatar']); ?>" class="align-self-center mr-3" alt="avatar" style="max-height: 285px; max-width: 205px;" title="Avatar de <?= \Rewritting::sanitize($profil['username']); ?>"/>
	<div class="media-body">
		<h5 class="mt-0">Description du membre</h5>
		<p><em><?= \Rewritting::sanitize($profil['description']) ?></em></p>
		<h5 class="mt-0">Rang du membre</h5>
		<p><span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($profil['grade']) ?>;"><?= Color::getRang($profil['grade'], $profil['sexe'], $profil['stagiaire'], $profil['chef']) ?></span></p>
		<h5 class="mt-0">Manga & anime favori</h5>
		<p>Manga favori : <strong><?= \Rewritting::sanitize($profil['manga']) ?></strong></p>
		<p>Anime favori : <strong><?= \Rewritting::sanitize($profil['anime']) ?></strong></p>
		<?php if($profil['grade'] >= 2){ ?>
			<h5 class="mt-0">Rôle dans le staff</h5>
			<p><em><?= \Rewritting::sanitize($profil['role']) ?></em></p>
		<?php } ?>
		<h5 class="mt-0">Galerie</h5>
		<p>Les galeries n'étant pas encore implantée sur le site, l'accès à cette partie n'est actuellement pas disponible.</p>
		<h5 class="mt-0">Site Internet</h5>
		<p><a href="<?= \Rewritting::sanitize($profil['site']) ?>">Consulter le site Internet</a></p>
		<h5 class="mt-0">Mangas'Points</h5>
		<p>Ce membre possède <?= \Rewritting::sanitize($profil['points']); ?> Mangas'Points.</p>
	</div>
</div>
<?php if ($utilisateur['grade'] >= 6) { ?>
	<h3>Modération du membre</h3>
	<hr>
	<div class="alert alert-info" role="alert">
		<strong>Avertissement :</strong> Si vous regardez cette zone, c'est que vous allez apporter des modifications au compte « <strong><i><?= \Rewritting::sanitize($profil['username']); ?></i></strong> ». Veuillez à contrôler vos modifications avant de les valider !
	</div>
	<div class="container-fluid" id="moderation">
		<div class="row">
			<div class="col-lg-7">
				<h3>Outils de modération</h3>
				<hr>
				<form method="POST" action="">
					<div class="row">
						<div class="col-lg-6">
							<label>Sélectionner le grade du membre :</label>
							<select name="grades" class="form-control">
								<option value="1" <?= (($profil['grade'] == 1) ? "selected" : "" ) ?>>Membre</option>
								<option value="2" <?= (($profil['grade'] == 2) ? "selected" : "" ) ?>>Community Manager</option>
								<option value="3" <?= (($profil['grade'] == 3) ? "selected" : "" ) ?>>Animateur</option>
								<option value="4" <?= (($profil['grade'] == 4) ? "selected" : "" ) ?>>Newseur</option>
								<option value="5" <?= (($profil['grade'] == 5) ? "selected" : "" ) ?>>Rédacteur</option>
								<?php if ($utilisateur['grade'] >= 7) { ?>
									<option value="6" <?= (($profil['grade'] == 6) ? "selected" : "" ) ?>>Modérateur</option>
								<?php } if ($utilisateur['grade'] >= 8) { ?>
									<option value="7" <?= (($profil['grade'] == 7) ? "selected" : "" ) ?>>Développeur</option>
									<option value="8"<?= (($profil['grade'] == 8) ? "selected" : "" ) ?> >Administrateur</option>
								<?php } ?>
							</select>
							<input type="submit" name="grade" class="btn btn-sm btn-outline-primary" value="Modifier le grade du membre">
						</div>
						<div class="col-lg-6">
							<label>Position du membre dans le groupe :</label>
							<?php if ($profil['chef'] == 0 && $profil['stagiaire'] == 0) { ?>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="normal" checked>
									<label class="form-check-label" for="exampleRadios1">
										Normal
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="stagiaire">
									<label class="form-check-label" for="exampleRadios2">
										Stagiaire
									</label>
								</div>
								<div class="form-check disabled">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="chef">
									<label class="form-check-label" for="exampleRadios3">
										Chef de groupe
									</label>
								</div>
							<?php } elseif($profil['chef'] == 1 && $profil['stagiaire'] == 0) { ?>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="normal">
									<label class="form-check-label" for="exampleRadios1">
										Normal
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="stagiaire">
									<label class="form-check-label" for="exampleRadios2">
										Stagiaire
									</label>
								</div>
								<div class="form-check disabled">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="chef" checked>
									<label class="form-check-label" for="exampleRadios3">
										Chef de groupe
									</label>
								</div>
							<?php } else { ?>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="normal">
									<label class="form-check-label" for="exampleRadios1">
										Normal
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="stagiaire" checked>
									<label class="form-check-label" for="exampleRadios2">
										Stagiaire
									</label>
								</div>
								<div class="form-check disabled">
									<input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="chef">
									<label class="form-check-label" for="exampleRadios3">
										Chef de groupe
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
			<div class="col-lg-5">
				<div class="card">
					<div class="card-header">
						Récapitulatif des actions effectuées
					</div>
					<div class="card-body">
						<p>Nombre d'avertissements :
							<?php if($countAvertissements == 0){
								echo "Aucun avertissement.";
							} elseif ($countAvertissements == 1) {
								echo "1 avertissement.";
							} else {
								echo $countAvertissements . " avertissements.";
							} ?>
						</p>
						<p>Bannissements : <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
							Historique des bannissements
						</button>
						<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Historique des bannissements</h5>
									</div>
									<div class="modal-body">
										<?php if ($recupererBannissement == 0) {
											echo "Ce membre ne possède aucun bannissement.";
										} else {
											foreach ($recupererBannissement as $bannissement) { ?>
												<h5>
													Bannissement n°<?= \Rewritting::sanitize($bannissement['id_bannissement']) ?> -		<?php if(date("Y-m-d") >= $bannissement['finish_date']) {
														echo "Bannissement expiré.";
													} else { ?>
														Expire le <?= date('d/m/Y', strtotime(\Rewritting::sanitize($bannissement['finish_date']))) ?>.
													<?php } ?>
												</h5>
												<hr>
												<p><strong>Date d'attribution : </strong><?= \Rewritting::sanitize(date('d F Y', strtotime($bannissement['begin_date']))) ?>.</p>
												<p><strong>Motif du bannissement : </strong><?= \Rewritting::sanitize($bannissement['motif']) ?></p>
												<p><strong>Bannissement attribué par : </strong><span style="color: <?= Color::rang_etat($bannissement['grade_modo']) ?>"><?= \Rewritting::sanitize($bannissement['username_modo']) ?></span>.</p>
												<hr>
											<?php } 
										} ?>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
									</div>
								</div>
							</div>
						</div></p>
						<p>Galerie du membre : <em>En cours.</em></p>
					</div>
				</div>
				<br/>
				<div class="card">
					<div class="card-header">
						Informations du membre
					</div>
					<div class="card-body">
						<p>Adresse email : <?= \Rewritting::sanitize($profil['email']) ?><br/>
							<?php if ($profil['confirmation_token'] == NULL) { ?>
								<em>> Ce compte est confirmé.</em>
							<?php } else { ?>
								<em>> Ce compte n'a pas encore été confirmé.</em>
							<?php } ?>
						</p>
						<p>Date d'anniversaire : <?= \Rewritting::sanitize(\Users::dateAnniversaire($profil['date_anniversaire'])) ?>.</p>
						<p>Description : 
							<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal1">
								Description du membre
							</button>
							<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Description</h5>
										</div>
										<div class="modal-body">
											<?= \Rewritting::sanitize($profil['description']) ?>
											<hr>
											<h5>Modifier la description</h5>
											<form method="POST" action="">
												<textarea class="form-control" name="description_membre" rows="5"><?= \Rewritting::sanitize($profil['description']); ?></textarea>
												<input type="submit" name="description" class="btn btn-primary">
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
										</div>
									</div>
								</div>
							</div>
						</p>
						<p>Sexe : <?= \Rewritting::sanitize(\Users::sexe($profil['sexe'])) ?></p>
						<p>Grade : <span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($profil['grade']) ?>;"><?= Color::getRang($profil['grade'], $profil['sexe'], $profil['stagiaire'], $profil['chef']) ?></span></p>
						<p>Manga favori : <?= \Rewritting::sanitize($profil['manga']) ?></p>
						<p>Anime favori : <?= \Rewritting::sanitize($profil['anime']) ?></p>
						<p>Rôle : 
							<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal2">
								Rôle du membre
							</button>
							<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Rôle du membre</h5>
										</div>
										<div class="modal-body">
											<?= \Rewritting::sanitize($profil['role']); ?>
											<hr>
											<h5>Modifier le rôle</h5>
											<form method="POST" action="">
												<textarea class="form-control" name="role_membre" rows="5"><?= \Rewritting::sanitize($profil['role']); ?></textarea>
												<input type="submit" name="role" class="btn btn-primary">
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
										</div>
									</div>
								</div>
							</div>
						</p>
						<p>Site : <a href="<?= \Rewritting::sanitize($profil['site']) ?>" target="_blank"><?= \Rewritting::sanitize($profil['site']) ?></a></p>
						<p>Mangas'Points : <?= \Rewritting::sanitize($profil['points']) ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
