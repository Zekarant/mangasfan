<h2 class="titre">Profil de <?= \Rewritting::sanitize($profil['username']) ?></h2>
<hr>
<div class="media">
	<img src="images/avatars/<?= \Rewritting::sanitize($profil['avatar']); ?>" class="align-self-center mr-3" alt="avatar" style="max-height: 285px; max-width: 205px;" title="Avatar de <?= \Rewritting::sanitize($profil['username']); ?>"/>
	<div class="media-body">
		<h5 class="mt-0">Description du membre</h5>
		<?php if ($profil['description'] == NULL) { ?>
			<p>Aucune description</p>
		<?php } else { ?>
			<p><em><?= \Rewritting::sanitize($profil['description']) ?></em></p>
		<?php } ?>
		<h5 class="mt-0">Rang du membre</h5>
		<p><span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($profil['grade']) ?>;"><?= Color::getRang($profil['grade'], $profil['sexe'], $profil['stagiaire'], $profil['chef']) ?></span></p>
		<h5 class="mt-0">Manga & anime favori</h5>
		<p>Manga favori : <strong><?= \Rewritting::sanitize($profil['manga']) ?></strong></p>
		<p>Anime favori : <strong><?= \Rewritting::sanitize($profil['anime']) ?></strong></p>
		<?php if($profil['grade'] >= 2){ ?>
			<h5 class="mt-0">Rôle dans le staff</h5>
			<p><em><?= \Rewritting::sanitize($profil['role']) ?></em></p>
		<?php } if($countGalerie != 0){ ?>
			<h5 class="mt-0">Galerie</h5>
			<p><a href="/galeries/voirgalerie.php?id=<?= \Rewritting::sanitize($profil['id_user']) ?>">Accéder à la galerie de ce membre</a></p>
		<?php } ?>
		<h5 class="mt-0">Site Internet</h5>
		<p><a href="<?= \Rewritting::sanitize($profil['site']) ?>">Consulter le site Internet</a></p>
		<h5 class="mt-0">Mangas'Points</h5>
		<p>Ce membre possède <?= \Rewritting::sanitize($profil['points']); ?> Mangas'Points.</p>
	</div>
</div>
<?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 6 && $utilisateur['grade'] >= $profil['grade'] && $utilisateur['id_user'] != $profil['id_user']) { ?>
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
				<hr>
				<h3>Sanctionner un membre</h3>
				<hr>
				<?php if($countAvertissements< 3) { ?>
					<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#avertissements">
						Attribuer un avertissement
					</button>
					<div class="modal fade" id="avertissements" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Attribuer un avertissement à <span style="color: <?= \Color::rang_etat(\Rewritting::sanitize($profil['grade']));?>"><?= \Rewritting::sanitize($profil['username']) ?></span></h5>
								</div>
								<div class="modal-body">
									<?php if ($countAvertissements == 1) { ?>
										<div class='alert alert-warning' role='alert'>
											<strong>Attention :</strong> Ce membre possède <strong>1 avertissement</strong> sur son compte.
										</div>
									<?php } elseif ($countAvertissements == 2) { ?>
										<div class='alert alert-danger' role='alert'>
											<strong>Attention :</strong> Ce membre possède déjà <strong>2 avertissements</strong> sur son compte.
										</div>
									<?php } ?>
									<form method="POST" action="">
										<label>Motif : </label>
										<textarea class="form-control" rows="10" name="contenu_sanction" placeholder="Ecrivez ici le motif de l'avertissement"></textarea>
										<input type="submit" name="valider_avertissement" value="Valider" class="btn btn-sm btn-info"/>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
								</div>
							</div>
						</div>
					</div>
				<?php } if ($profil['grade'] != 0) { ?>
					<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bannissements">
						Bannir le membre
					</button>
					<div class="modal fade" id="bannissements" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Donner un bannissement à <span style="color: <?= \Color::rang_etat(\Rewritting::sanitize($profil['grade']));?>"><?= \Rewritting::sanitize($profil['username']) ?></span></h5>
								</div>
								<div class="modal-body">
									<form method="POST" action="">
										<label>Date de fin du bannissement :</label>
										<input type="date" name="date_bannissement" class="form-control">
										<br/>
										<label>Motif :</label>
										<textarea class="form-control" rows="10" name="contenu_bannissement" placeholder="Ecrivez ici le motif du bannissement"></textarea>
										<input type="submit" name="valider_bannissement" value="Valider" class="btn btn-sm btn-info"/>
									</form>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
								</div>
							</div>
						</div>
					</div>
				<?php } if($profil['galerie'] == 0){ ?>
					<form method="POST" action="">
						<input type="submit" name="sanction_galerie" class=" btn btn-sm btn-danger" value="Empêcher de poster sur sa galerie">
					</form>
				<?php } else { ?>
					<form method="POST" action="">
						<input type="submit" name="non_galerie" class=" btn btn-sm btn-success" value="Autoriser à poster sur sa galerie">
					</form>
				<?php } if ($utilisateur['grade'] >= 8 && $utilisateur['stagiaire'] == 0) { ?>
					<br/>
					<h3>Outils d'administration</h3>
					<hr>
					<div class="row">
						<div class="col-lg-5">
							<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modification_infos">
								Modifier les informations du membre
							</button>
							<div class="modal fade" id="modification_infos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Modifier les informations de <span style="color: <?= \Color::rang_etat(\Rewritting::sanitize($profil['grade']));?>"><?= \Rewritting::sanitize($profil['username']) ?></span></h5>
										</div>
										<div class="modal-body">
											<div class="container">
												<div class="alert alert-info" role="alert">
													<strong>Avertissement :</strong> Pour changer la description et le rôle, allez dans les onglets situés dans le récapitulatif à droite.
												</div>
												<form method="POST" action="">
													<div class="row">
														<div class="col-md-6">
															<label>1. Pseudo</label>
															<input name="pseudo" type="text" class="form-control" placeholder="Modifier le pseudo" value="<?= \Rewritting::sanitize($profil['username']); ?>">
															<br/>
															<label>2. Adresse Mail</label>
															<input name="email" type="email" class="form-control" placeholder="Modifier le mail" value="<?= \Rewritting::sanitize($profil['email']); ?>">
															<br/>
															<label>3. Date de naissance</label>
															<input name="date_anniv" type="date" class="form-control" placeholder="Modifier la date de naissance" value="<?php if(isset($profil['date_anniversaire'])) { echo \Rewritting::sanitize($profil['date_anniversaire']); } ?>">
														</div>
														<div class="col-md-6">
															<label>4. Manga</label>
															<input name="manga" type="text" class="form-control" placeholder="Modifier le manga" value="<?= \Rewritting::sanitize($profil['manga']); ?>">
															<br/>
															<label>5. Anime</label>
															<input name="anime" type="text" class="form-control" placeholder="Modifier l'anime" value="<?= \Rewritting::sanitize($profil['anime']); ?>">
															<br/>
															<label>6. Site internet</label>
															<input name="site" type="url" class="form-control" placeholder="Modifier le site" value="<?= \Rewritting::sanitize($profil['site']); ?>">
														</div>
													</div>
													<input name="changement_information" type="submit" value="Valider les informations ci-dessus" class="btn btn-sm btn-info">
												</form>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
										</div>
									</div>
								</div>
							</div>
							<?php if ($profil['confirmation_token'] == NULL) { ?>
								<form method="POST" action="">
									<input type="submit" name="suspension" class="btn btn-sm btn-danger" onclick="return window.confirm(`Êtes-vous sûr de vouloir désactiver le compte de ce membre ?`)" value="Désactiver le compte du membre">
								</form>
							<?php } else { ?>
								<form method="POST" action="">
									<input type="submit" name="reactivation" class="btn btn-sm btn-info" onclick="return window.confirm(`Êtes-vous sûr de vouloir activer le compte de ce membre ?`)" value="Activer le compte">
								</form>
							<?php } ?>
						</div>
						<div class="col-lg-7">
							<form method="POST" action="">
								<input type="submit" name="new_avatar" class="btn btn-sm btn-info" onclick="return window.confirm(`Êtes-vous sûr de vouloir remettre l'avatar par défaut à ce membre ?`)" value="Réinitialiser l'avatar du membre">
							</form>
							<form method="POST" action="">
								<input type="submit" name="suppression" class="btn btn-sm btn-danger" onclick="return window.confirm(`Êtes-vous sûr de vouloir le compte de ce membre ?`)" value="Supprimer le compte du membre">
							</form>
						</div>
					</div>
				<?php } if ($countAvertissements != 0) { ?>
					<hr>
					<h3>Avertissements</h3>
					<hr>
					<table class="table">
						<thead>
							<th>Motif</th>
							<th>Date d'attribution</th>
							<th>Attribué par</th>
						</thead>
						<tbody>
							<?php foreach($avertissement as $membreAverto): ?>
								<tr>
									<td><?= \Rewritting::sanitize($membreAverto['motif']) ?></td>
									<td><?= date('d/m/Y', strtotime(\Rewritting::sanitize($membreAverto['add_date']))) ?></td>
									<td style="color: <?= \Color::rang_etat($membreAverto['grade_modo']) ?>"><?= \Rewritting::sanitize($membreAverto['username_modo']) ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php } ?>
				<hr>
				<h3>Bannissements</h3>
				<hr>
				<?php if($recupererBannissement == 0) { ?>
					<div class="alert alert-success" role="alert">
						Ce membre ne possède aucun bannissement !
					</div>
				<?php } else { ?>
					<table class="table">
						<thead>
							<th>Motif</th>
							<th>Fin</th>
							<th>Attribution</th>
							<th>Status</th>
						</thead>
						<tbody>
							<?php foreach ($recupererBannissement as $bannissementAffichage) { ?>
								<tr>
									<td><?= \Rewritting::sanitize($bannissementAffichage['motif']) ?></td>
									<td><?= \Rewritting::sanitize(date('d/m/Y', strtotime($bannissementAffichage['begin_date']))) ?></td>
									<td>
										<span style="color: <?= Color::rang_etat($bannissementAffichage['grade_modo']) ?>"><?= \Rewritting::sanitize($bannissementAffichage['username_modo']) ?></span>
									</td>
									<td><?php if(date("Y-m-d") >= $bannissementAffichage['finish_date']) {
										echo "Bannissement expiré.";
									} else { ?>
										Expire le <?= date('d/m/Y', strtotime(\Rewritting::sanitize($bannissementAffichage['finish_date']))) ?>.
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>  
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
											<p><strong>Date d'attribution : </strong><?= \Rewritting::sanitize(date('d/m/Y', strtotime($bannissement['begin_date']))) ?>.</p>
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
					<p>Galerie du membre : 
						<?php if($countGalerie != 0){ ?>
							<a href="/galeries/voirgalerie.php?id=<?= \Rewritting::sanitize($profil['id_user']) ?>">Accéder à la galerie de ce membre</a>.
						<?php } else {
							echo "Pas d'images sur la galerie.";
						} ?><br/>
						<?php if ($profil['galerie'] == 0) {
							echo "Autorisé à poster sur sa galerie.";
						} else {
							echo "Ce membre n'est pas autorisé à poster sur sa galerie.";
						} ?>
					</p>
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
										<?php if ($profil['description'] == NULL) {
											echo "Aucune description";
										} else { ?>
											<p><em><?= \Rewritting::sanitize($profil['description']) ?></em></p>
										<?php } ?>
										<hr>
										<h5>Modifier la description</h5>
										<form method="POST" action="">
											<textarea class="form-control" name="description_membre" rows="5"><?php if ($profil['description'] == NULL) {
												echo "Aucune description";
											} else { ?>
												<p><em><?= \Rewritting::sanitize($profil['description']) ?></em></p>
												<?php } ?></textarea>
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
