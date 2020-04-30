<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_animation.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre" id="points">Gestion des points</h2>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<th>Membre concerné</th>
						<th>Action désirée</th>
						<th>Nombre de points</th>
						<th>Validation</th>
					</thead>
					<tbody>
						<form method="POST" action="">
							<tr>
								<td>
									<select class="form-control" name="membre_point">
										<option value="all_membres" selected="selected">Tous les membres</option>
										<?php foreach($membres as $membre): ?>
											<option value="<?= \Rewritting::sanitize($membre['id_user']); ?>"><?= \Rewritting::sanitize($membre['username']); ?> - <?= \Rewritting::sanitize($membre['points']); ?> point(s)</option>
										<?php endforeach; ?>
									</select>
								</td>
								<td>
									<select class="form-control" name="choix_points">
										<option value="attribuer" selected="selected">Attribuer</option>
										<option value="retrait">Retirer</option>
									</select>
								</td>
								<td><input type="number" name="points" class="form-control" placeholder="Entrer le nombre de points" required></td>
								<td><input type="submit" name="new_points" class="btn btn-outline-success" value="Valider"></td>
							</tr>
						</form>
					</tbody>
				</table>
			</div>
			<h2 class="titre" id="stats">Membres avec le plus de points</h2>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Membre</th>
							<th>Points</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($classements as $classement): ?>
							<tr>
								<td><a href="../../membres/profil-<?= \Rewritting::sanitize($classement['id_user']) ?>"><?= \Rewritting::sanitize($classement['username']) ?></a></td>
								<td><?= \Rewritting::sanitize($classement['points']) ?> points</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<h2 class="titre" id="billet">Billet d'animation</h2>
			<?php if (!empty($error) && $error != "") { ?>
				<div class="alert alert-warning" role="alert">
					<?= $error ?>
				</div>
			<?php } ?>
			<form method="POST" action="">
				<label>Visibilité :</label>
				<?php if($animation['visibility'] == 0){ ?>
					<select class="form-control" name="visibilite">
						<option value="0" selected="selected">Visible</option>
						<option value="1">Caché</option>
					</select>
				<?php } else { ?>
					<select class="form-control" name="visibilite">
						<option value="0">Visible</option>
						<option value="1" selected="selected">Caché</option>
					</select>
				<?php } ?>
				<br/>
				<label>Message :</label>
				<textarea class="form-control" name="contenu_animation" placeholder="Rédigez ici le contenu de l'animation qui apparaîtra sur la page compte du site."><?= \Rewritting::sanitize($animation['contenu']); ?></textarea>
				<input type="submit" name="new_animation" class="btn btn-outline-info" value="Ajouter">
			</form>
		</div>
	</div>
</div>