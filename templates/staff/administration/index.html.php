<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_admin.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre" id="maintenances">Gestion des maintenances</h2>
			<hr>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<th>Status</th>
						<th>Partie du site</th>
						<th>Gérer la maintenance</th>
					</thead>
					<tbody>
						<?php foreach ($maintenance as $maintenances) {
							$couleur = !$maintenances['active_maintenance'] ? 'success' : 'warning'; ?>
							<form method="POST" action="">
								<tr class="table-<?= $couleur ?>">
									<td>
										<?php if($maintenances['active_maintenance'] == 0){ ?>
											Pas de maintenance
										<?php } else { ?>
											Maintenance en cours
										<?php } ?>
									</td>
									<td><?= \Rewritting::sanitize($maintenances['maintenance_area']) ?></td>
									<td>
										<?php if ($maintenances['active_maintenance'] == 0) { ?>
											<button type="submit" name="maintenance" class="btn btn-outline-warning" value="<?= \Rewritting::sanitize($maintenances['maintenance_area']) ?>">Activer la maintenance</button>
										<?php } else { ?>
											<button type="submit" name="maintenance" class="btn btn-outline-success" value="<?= \Rewritting::sanitize($maintenances['maintenance_area']) ?>">Désactiver la maintenance</button>
										<?php } ?>
									</td>
								</tr>
							</form>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<h2 class="titre" id="membres">Gestion des membres</h2>
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-6">
						<div class="alert alert-info">
							<strong>Information :</strong> En tapant le pseudo du membre ci-dessous, vous serez automatiquement redirigé sur la page de son profil afin de pouvoir modérer ce dernier.
						</div>
						<form method="POST" action="">
							<label>Pseudo du membre : <strong>Non fonctionnel</strong></label>
							<input type="text" name="username" class="form-control" placeholder="Saisir le pseudo du membre">
							<input type="submit" name="searchMember" class="btn btn-sm btn-outline-info" value="Rechercher le membre">
						</form>
					</div>
					<div class="col-lg-6">
						<nav>
							<ul class="pagination justify-content-center">
								<li class="page-item disabled">
									<a class="page-link" href="#" tabindex="-1">Pages :</a>
								</li>
								<?php for ($i = 1; $i <= $nb_pages; $i++) {
									if ($i == $page) { ?>
										<li class="page-item">
											<a class="page-link" href="#"><?= $i; ?></a>
										</li>
									<?php } else { ?>
										<li class="page-item">
											<a class="page-link" href="<?= "?page=" . $i; ?>#membres"><?= $i?></a>
										</li>
									<?php }
								} ?>
							</ul>
						</nav>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<th>Membre</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php foreach($membres as $membre): ?>
										<tr>
											<td><?= \Rewritting::sanitize($membre['username']) ?></td>
											<td><a href="#" class="btn btn-outline-info">Accéder au profil du membre</a></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<h2 class="titre" id="avertissements">Gestion des avertissements</h2>
			<?php if (empty($avertissements)) { ?>
				<div class="alert alert-info">
					Aucun membre de Mangas'Fan ne possède d'avertissement !
				</div>
			<?php } else { ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<th>Membre</th>
							<th>Motif de l'avertissement</th>
							<th>Date/Modérateur</th>
							<th>Action</th>
						</thead>
						<tbody>
							<?php foreach ($avertissements as $avertissement) { ?>
								<tr>
									<td><span style="color: <?= Color::rang_etat($avertissement['grade_banni']) ?>"><?= \Rewritting::sanitize($avertissement['username_banni']) ?></span></td>
									<td><?= \Rewritting::sanitize($avertissement['motif']) ?></td>
									<td>Attribué le <?= date("d/m/Y", strtotime(\Rewritting::sanitize($avertissement['add_date']))) ?> par <span style="color: <?= Color::rang_etat($avertissement['grade_modo']) ?>"><?= \Rewritting::sanitize($avertissement['username_modo']) ?></span></td>
									<td>
										<form method="POST" action="">
                    						<button type="submit" class="btn btn-outline-warning" name="delete_avertissement" value="<?= \Rewritting::sanitize($avertissement['id_avertissement']) ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer cet avertissement ?`)">Supprimer</button>
                  						</form>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } ?>
				<h2 class="titre" id="bannissements">Gestion des bannissements</h2>
			<?php if (empty($bannissements)) { ?>
				<div class="alert alert-info">
					Aucun membre de Mangas'Fan n'est banni !
				</div>
			<?php } else { ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<th>Membre</th>
							<th>Motif</th>
							<th>Attribué par</th>
							<th>Durée</th>
						</thead>
						<tbody>
							<?php foreach ($bannissements as $bannissement) { ?>
								<tr>
									<td><span style="color: <?= Color::rang_etat($bannissement['grade_banni']) ?>"><?= \Rewritting::sanitize($bannissement['username_banni']) ?></span></td>
									<td><?= \Rewritting::sanitize($bannissement['motif']) ?></td>
									<td><span style="color: <?= Color::rang_etat($bannissement['grade_modo']) ?>"><?= \Rewritting::sanitize($bannissement['username_modo']) ?></span></td>
									<td>Du <?= date("d/m/Y", strtotime(\Rewritting::sanitize($bannissement['begin_date']))) ?> au <?= date("d/m/Y", strtotime(\Rewritting::sanitize($bannissement['finish_date']))) ?>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } ?>
			</div>
		</div>