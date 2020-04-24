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
						<?php if ($utilisateur['chef'] == 1 && ($utilisateur['grade'] >= 6 || $utilisateur['grade'] == 4)) { ?>
							<th>Validation</th>
						<?php } ?>
					</thead>
					<tbody>
						<?php foreach ($news as $new): ?>
							<tr>
								<td>
									<a href="../../commentaire.php?id=<?= \Rewritting::sanitize($new['id_news']) ?>" target="_blank"><?= \Rewritting::sanitize($new['title'] )?></a> <?php if ($new['validation'] == 1) {
										echo " - News en attente de validation";
									} elseif ($new['visibility'] == 1) {
										echo " - News non visible sur l'index";
									} ?></td>
									<td><a href="#" style="color: <?= \Color::rang_etat(\Rewritting::sanitize($new['grade'])) ?>"><?= \Rewritting::sanitize($new['username']) ?></a><?php if($new['stagiaire'] == 1){ echo " (Stagiaire)"; } ?></td>
									<td><?= date('d/m/Y', strtotime(\Rewritting::sanitize($new['create_date']))); ?></td>
									<td>
										<?php if ($utilisateur['stagiaire'] == 1 && $new['author'] != $utilisateur['id_user']) { ?>
											<button class="btn btn-outline-secondary">Modifier</button>
										<?php } else { ?>
											<a href="modifier_news.php?id_news=<?= \Rewritting::sanitize($new['id_news']) ?>" class="btn btn-outline-info">Modifier</a>
										<?php } ?>
									</td>
									<?php if ($utilisateur['chef'] == 1 || $utilisateur['grade'] >= 6) { ?>
										<td>
											<form method="POST" action="">
												<button type="submit" class="btn btn-outline-danger" name="suppression_news" value="<?= \Rewritting::sanitize($new['id_news']) ?>" onclick="return window.confirm(`Voulez-vous supprimer cette news ?`)">Supprimer</button>
											</form>
										</td>
									<?php } else {
										if ($new['demande'] == 0) { ?>
											<td><button class="btn btn-outline-danger">Demander une suppression</button></td>
										<?php } else { ?>
											<td><button class="btn btn-outline-secondary">Demande en attente</button></td>
										<?php }
									} ?>
									<?php if ($new['validation'] == 1 && $utilisateur['chef'] == 1 && ($utilisateur['grade'] >= 6 || $utilisateur['grade'] == 4)) { ?>
										<td>
											<form method="POST" action="">
												<button type="submit" class="btn btn-outline-success" name="valider_news" value="<?= \Rewritting::sanitize($new['id_news']) ?>" onclick="return window.confirm(`Voulez-vous valider cette news ?`)">Valider</button>
											</form></td>
										<?php } elseif ($new['validation'] == 0 && $utilisateur['chef'] == 1 && ($utilisateur['grade'] >= 6 || $utilisateur['grade'] == 4)) { ?>
											<td></td>
										<?php }  ?>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>