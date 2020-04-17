<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_admin.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Gestion des maintenances</h2>
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
							if ($maintenances['active_maintenance'] == 0) {
								$couleur = "success";
							} else {
								$couleur = "warning";
							} ?>
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
		</div>
	</div>
</div>