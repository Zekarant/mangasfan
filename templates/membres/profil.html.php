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
	<?php if (isset($_POST) AND $message != "") { ?>
		<div class="alert alert-<?= $couleur ?>" role="alert">
			<?= $message ?>
		</div>
	<?php } ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-7">
				Outils de modération
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
											echo "Aucun bannissement pour ce membre";
										} else {
											foreach ($recupererBannissement as $bannissement) {
												echo "Motif " . $bannissement['motif'];
											}
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
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
