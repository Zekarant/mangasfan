<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_news.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Pannel des news - Mangas'Fan</h2>
			<div class="table table-responsive">
				<table class="table table-striped">
					<thead>
						<th>Titre de la news</th>
						<th>Auteur</th>
						<th>Date</th>
						<th>Modification</th>
						<?php if ($utilisateur['chef'] == 0 && $utilisateur['grade'] < 6) { ?>
							<th>Demande de suppression</th>
						<?php } else { ?>
							<th>Suppression</th>
						<?php } ?>
					</thead>
					<tbody>
						<?php foreach ($news as $new): ?>
							<tr>
								<td><a href="../../commentaire.php?id=<?= \Rewritting::sanitize($new['id_news']) ?>" target="_blank"><?= \Rewritting::sanitize($new['title'] )?></a></td>
								<td><a href="#" style="color: <?= \Color::rang_etat(\Rewritting::sanitize($new['grade'])) ?>"><?= \Rewritting::sanitize($new['username']) ?></a></td>
								<td><?= date('d/m/Y', strtotime(\Rewritting::sanitize($new['create_date']))); ?></td>
								<td><a href="modifier_news.php?id_news=<?= \Rewritting::sanitize($new['id_news']) ?>" class="btn btn-outline-info">Modifier</a></td>
								<?php if ($utilisateur['chef'] == 1 || $utilisateur['grade'] >= 6) { ?>
									<td><a href="../../index.php?controller=news&task=delete&id=<?= \Rewritting::sanitize($new['id_news']) ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer cet article ?!`)"><span class="btn btn-outline-danger">Supprimer</span></a></td>
								<?php } else {
									if ($new['demande'] == 0) { ?>
										<td><button class="btn btn-outline-danger">Demander une suppression</button></td>
									<?php } else { ?>
										<td><button class="btn btn-outline-secondary">Demande en attente</button></td>
									<?php }
								} ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>