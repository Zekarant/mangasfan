<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_modo.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre" id="statistiques">Statistiques du site</h2>
			<hr>
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-4">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title">Derniers inscrits sur le site</h5>
								<hr>
								<p class="card-text">
									<?php foreach($users as $user): ?>
										<a href="../../membres/profil-<?= \Rewritting::sanitize($user['id_user']) ?>"><?= \Rewritting::sanitize($user['username']) ?></a> s'est inscrit avec l'adresse mail : <em><?= \Rewritting::sanitize($user['email']) ?></em>.<br/>
										<?php if ($user['confirmation_token'] != NULL) { ?>
											<small class="text-muted">
												<strong>Note :</strong> Cet utilisateur n'a pas validé son inscription.
											</small>
										<?php } else { ?>
											<small class="text-muted">
												<strong>Note :</strong> Inscrit le <?= \Rewritting::sanitize($user['date_inscription']); ?>.
											</small>
										<?php } ?>
										<hr>
									<?php endforeach; ?>
								</p>
							</div>
						</div>
					</div>
					<div class="col-lg-8">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title">Derniers commentaires postés sur le site</h5>
								<hr>
								<p class="card-text">
									<?php foreach($commentaires as $commentaire): ?>
										<p>Pseudonyme : <a href="../../membres/profil-<?= \Rewritting::sanitize($commentaire['id_user']) ?>"><?= \Rewritting::sanitize($commentaire['username']) ?></a></p>
										<p>Commentaire : <em>"<?= \Rewritting::sanitize($commentaire['commentary']) ?>".</em></p>
										<p>News concernée : <a href="../../commentaire.php?id=<?= \Rewritting::sanitize($commentaire['id_news']) ?>"><?= \Rewritting::sanitize($commentaire['title']) ?></a></p>
										<p>Commentaire posté le : <?= date('d/m/Y', strtotime(\Rewritting::sanitize($commentaire['posted_date']))); ?>.</p>
										<hr>
									<?php endforeach; ?>
								</p>
							</div>
						</div>
						<br/>
						<div class="card">
							<div class="card-body">
								<h5 class="card-title">Derniers commentaires postés sur les galeries</h5>
								<hr>
								<p class="card-text">
									<?php foreach($galeries as $commentaireGalerie): ?>
										<p>Pseudonyme : <a href="../../membres/profil-<?= \Rewritting::sanitize($commentaireGalerie['id_user']) ?>"><?= \Rewritting::sanitize($commentaireGalerie['username']) ?></a></p>
										<p>Commentaire : <em>"<?= \Rewritting::sanitize($commentaireGalerie['galery_commentary']) ?>".</em></p>
										<p>Galerie concernée : <a href="../../commentaire.php?id=<?= \Rewritting::sanitize($commentaireGalerie['id_image']) ?>"><?= \Rewritting::sanitize($commentaireGalerie['title_image']) ?></a></p>
										<p>Commentaire posté le : <?= date('d/m/Y', strtotime(\Rewritting::sanitize($commentaireGalerie['date_commentaire']))); ?>.</p>
										<hr>
									<?php endforeach; ?>
								</p>
							</div>
						</div>
					</div>
				</div>
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
											<td><a href="../../membres/profil-<?= \Rewritting::sanitize($membre['id_user']) ?>" class="btn btn-outline-info">Accéder au profil du membre</a></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<h2 class="titre" id="avertissements">Avertissements</h2>
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
				<h2 class="titre" id="bannissements">Bannissements</h2>
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
</div>