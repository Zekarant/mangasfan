<h2 class="titre">Administration de ma galerie</h2>
<hr>
<div class="d-flex justify-content-center">
	<a href="index.php" class="btn btn-sm btn-outline-info">Index des galeries</a>
	<a href="voirgalerie.php?id=<?= \Rewritting::sanitize($utilisateur['id_user']) ?>" class="btn btn-sm btn-outline-info">Voir ma galerie</a>
	<a href="ajouter.php" class="btn btn-sm btn-outline-success">Ajouter une nouvelle image</a>
</div>
<hr>
<div class="alert alert-info" role="alert">
	<?php if ($countGalerie == 0) {
		echo "Vous n'avez actuellement aucun article sur votre galerie.";
	} elseif ($countGalerie == 1) {
		echo "Vous avez actuellement 1 article sur votre galerie.";
	} else {
		echo "Vous avez actuellement " . count($countGalerie) . " articles sur votre galerie.";
	} ?>
</div>
<?php if($countGalerie != 0){ ?>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<th>Titre de l'image</th>
				<th>Date de l'image</th>
				<th>Status de l'image</th>
				<th>Modifier</th>
				<th>Supprimer</th>
			</thead>
			<tbody>
				<?php foreach ($galerie as $galeries): ?>
					<tr>
						<td><?= \Rewritting::sanitize($galeries['title_image']) ?></td>
						<td><?= \Users::dateAnniversaire(\Rewritting::sanitize($galeries['date_image'])) ?></td>
						<td>
							<?php if ($galeries['rappel_image'] == 0 && $galeries['nsfw_image'] == 0) { ?>
								<span class="badge badge-success">Visible par les autres personnes.</span>
							<?php } elseif ($galeries['rappel_image'] == 0 && $galeries['nsfw_image'] == 1) { ?>
								<span class="badge badge-success">Visible par les personnes avec NSFW activé.</span>
							<?php } else { ?>
								<span class="badge badge-warning">Cachée : Rappel reçu pour cette image.</span>
							<?php } ?>
						</td>
						<td><a href="modifier.php?galerie=<?= \Rewritting::sanitize($galeries['id_image']); ?>" class="btn btn-sm btn-outline-primary">Modifier l'image</a></td>
						<td>
							<form method="POST" action="supprimer.php?galerie=<?= \Rewritting::sanitize($galeries['id_image']); ?>">
								<input type="submit" name="supprimer_image" onclick="return window.confirm(`Voulez-vous supprimer cette image ?`)" class="btn btn-outline-danger btn-sm" value="Supprimer l'image">
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php } ?>