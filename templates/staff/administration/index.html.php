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
									<td><?= $maintenances['maintenance_area'] ?></td>
									<td>
										<?php if ($maintenances['active_maintenance'] == 0) { ?>
											<button type="submit" name="maintenance" class="btn btn-outline-warning" value="<?= $maintenances['maintenance_area'] ?>">Activer la maintenance</button>
										<?php } else { ?>
											<button type="submit" name="maintenance" class="btn btn-outline-success" value="<?= $maintenances['maintenance_area'] ?>">Désactiver la maintenance</button>
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
		</div>
	</div>