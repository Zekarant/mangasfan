<h2 class="titre">Gestion des recrutements</h2>
<hr>
<a href="index.php" class="btn btn-outline-info">Retournez à l'index des recrutements</a>
<hr>
<div class="table-responsive">
	<table class="table">
		<thead>
			<th>Nom du recrutement</th>
			<th>Status du recrutement</th>
			<th>Action</th>
		</thead>
		<tbody>
			<?php foreach ($recrutement as $recrutements) {
				$couleur = !$recrutements['recrutement'] ? 'warning' : 'success'; ?>
				<form method="POST" action="">
					<tr class="table-<?= \Rewritting::sanitize($couleur) ?>">
						<td>
							<?php if($recrutements['recrutement'] == 0){ ?>
								Recrutement fermé
							<?php } else { ?>
								Recrutement en cours
							<?php } ?>
						</td>
						<td>Recrutement <?= \Rewritting::sanitize($recrutements['name_animation']) ?></td>
						<td>
							<?php if ($recrutements['recrutement'] == 0) { ?>
								<button type="submit" name="recrutement" class="btn btn-outline-success" value="<?= \Rewritting::sanitize($recrutements['link']) ?>">Ouvrir le recrutement</button>
							<?php } else { ?>
								<button type="submit" name="recrutement" class="btn btn-outline-warning" value="<?= \Rewritting::sanitize($recrutements['link']) ?>">Fermer le recrutement</button>
							<?php } ?>
						</td>
					</tr>
				</form>
			<?php } ?>
		</tbody>
	</table>
</div>