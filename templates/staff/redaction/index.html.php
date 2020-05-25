<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_redaction.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Index de la rédaction - Mangas'Fan</h2>
			<hr>
			<div class="d-flex justify-content-around">
				<a href="#jeux" class="btn btn-outline-info">Accéder aux jeux vidéo</a>
				<a href="#animes" class="btn btn-outline-info">Accéder aux animes</a>
				<a href="#mangas" class="btn btn-outline-info">Accéder aux mangas</a>
			</div>
			<hr>
			<h3 class="titre" id="jeux">Jeux vidéo - Mangas'Fan</h3>
			<nav>
				<ul class="pagination justify-content-center">
					<li class="page-item disabled">
						<a class="page-link" href="#" tabindex="-1">Pages :</a>
					</li>
					<?php for ($i = 1; $i <= $nb_pages; $i++) {
						if ($i == $page) { ?>
							<li class="page-item">
								<a class="page-link" href="#"><?= \Rewritting::sanitize($i); ?></a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link" href="<?= "?page=" . $i; ?>#jeux"><?= \Rewritting::sanitize($i) ?></a>
							</li>
						<?php }
					} ?>
				</ul>
			</nav>
			<div class="table-responsive">
				<table class="table">
					<thead>
						<th>Titre du jeu</th>
						<th>Date d'ajout</th>
						<th>Accéder au dossier du jeu</th>
						<th>Supprimer le jeu</th>
						<th>Voir le jeu</th>
					</thead>
					<tbody>
						<?php foreach ($allJeux as $jeux): ?>
							<tr>
								<td><strong><?= \Rewritting::sanitize($jeux['name_jeu']) ?></strong> - 
									<?php if ($jeux['nb_article'] == 0) {
										echo "Aucun article";
									}  elseif ($jeux['nb_article'] == 1) {
										echo "1 article";
									} else {
										echo \Rewritting::sanitize($jeux['nb_article']) . " articles";
									}?></td>
									<td><?= \Rewritting::sanitize(\Users::dateAnniversaire($jeux['date_ajout'])) ?></td>
									<td><a class="btn btn-outline-info" href="modification-jeux/<?= \Rewritting::sanitize($jeux['slug']) ?>">Accéder au dossier</a></td>
									<?php if ($utilisateur['chef'] == 1 || $utilisateur['grade'] >= 6) { ?>
										<td>
											<a class="btn btn-outline-danger" href="supprimer_jeux.php?id=<?= \Rewritting::sanitize($jeux['id_jeux']) ?>" onclick="return window.confirm(`Voulez-vous supprimer ce jeu ?`)">Supprimer</a>
										</td>
									<?php } else {
										if ($jeux['demande'] == 0) { ?>
											<td><button class="btn btn-outline-danger">Demander une suppression</button></td>
										<?php } else { ?>
											<td><button class="btn btn-outline-secondary">Demande en attente</button></td>
										<?php }
									} ?>
									<td><a href="../../jeux-video/<?= \Rewritting::sanitize($jeux['slug']) ?>" class="btn btn-outline-info">Voir <?= \Rewritting::sanitize($jeux['name_jeu']) ?></a></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<hr>
				<div class="row">
					<div class="col-lg-6">
						<h3 class="titre" id="mangas">Mangas référencés</h3>
						<nav>
							<ul class="pagination justify-content-center">
								<li class="page-item disabled">
									<a class="page-link" href="#" tabindex="-1">Pages :</a>
								</li>
								<?php for ($i = 1; $i <= $nb_pagesMangas; $i++) {
									if ($i == $page) { ?>
										<li class="page-item">
											<a class="page-link" href="#"><?= \Rewritting::sanitize($i); ?></a>
										</li>
									<?php } else { ?>
										<li class="page-item">
											<a class="page-link" href="<?= "?page=" . $i; ?>#mangas"><?= \Rewritting::sanitize($i) ?></a>
										</li>
									<?php }
								} ?>
							</ul>
						</nav>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<th>Titre du manga</th>
									<th>Date d'ajout</th>
									<th>Accéder au dossier du manga</th>
									<th>Supprimer le manga</th>
									<th>Voir le manga</th>
								</thead>
								<tbody>
									<?php foreach ($allMangas as $mangas): ?>
										<tr>
											<td><strong><?= \Rewritting::sanitize($mangas['titre']) ?></strong> - 
												<?php if ($mangas['nb_article'] == 0) {
													echo "Aucun article";
												}  elseif ($mangas['nb_article'] == 1) {
													echo "1 article";
												} else {
													echo \Rewritting::sanitize($mangas['nb_article']) . " articles";
												}?></td>
												<td><?= \Rewritting::sanitize(\Users::dateAnniversaire($mangas['date_creation'])) ?></td>
												<td><a class="btn btn-outline-info" href="modifier_mangas.php?id=<?= \Rewritting::sanitize($mangas['id']) ?>">Accéder au dossier</a></td>
												<?php if ($utilisateur['chef'] == 1 || $utilisateur['grade'] >= 6) { ?>
													<td>
														<a class="btn btn-outline-danger" href="supprimer.php?id=<?= \Rewritting::sanitize($mangas['id']) ?>" onclick="return window.confirm(`Voulez-vous supprimer ce manga ?`)">Supprimer</a>
													</td>
												<?php } else {
													if ($mangas['demande'] == 0) { ?>
														<td><button class="btn btn-outline-danger">Demander une suppression</button></td>
													<?php } else { ?>
														<td><button class="btn btn-outline-secondary">Demande en attente</button></td>
													<?php }
												} ?>
												<td><a href="../../mangas/<?= \Rewritting::sanitize($mangas['slug']) ?>" class="btn btn-outline-info">Voir <?= \Rewritting::sanitize($mangas['titre']) ?></a></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-lg-6">
							<h3 class="titre" id="animes">Animes référencés</h3>
							<nav>
							<ul class="pagination justify-content-center">
								<li class="page-item disabled">
									<a class="page-link" href="#" tabindex="-1">Pages :</a>
								</li>
								<?php for ($i = 1; $i <= $nb_pagesAnimes; $i++) {
									if ($i == $page) { ?>
										<li class="page-item">
											<a class="page-link" href="#"><?= \Rewritting::sanitize($i); ?></a>
										</li>
									<?php } else { ?>
										<li class="page-item">
											<a class="page-link" href="<?= "?page=" . $i; ?>#animes"><?= \Rewritting::sanitize($i) ?></a>
										</li>
									<?php }
								} ?>
							</ul>
						</nav>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<th>Titre de l'anime</th>
									<th>Date d'ajout</th>
									<th>Accéder au dossier de l'anime</th>
									<th>Supprimer l'anime</th>
									<th>Voir l'anime</th>
								</thead>
								<tbody>
									<?php foreach ($allAnimes as $anime): ?>
										<tr>
											<td><strong><?= \Rewritting::sanitize($anime['titre']) ?></strong> - 
												<?php if ($anime['nb_article'] == 0) {
													echo "Aucun article";
												}  elseif ($anime['nb_article'] == 1) {
													echo "1 article";
												} else {
													echo \Rewritting::sanitize($anime['nb_article']) . " articles";
												}?></td>
												<td><?= \Rewritting::sanitize(\Users::dateAnniversaire($anime['date_creation'])) ?></td>
												<td><a class="btn btn-outline-info" href="modification-animes/<?= \Rewritting::sanitize($anime['slug']) ?>">Accéder au dossier</a></td>
												<?php if ($utilisateur['chef'] == 1 || $utilisateur['grade'] >= 6) { ?>
													<td>
														<a class="btn btn-outline-danger" href="supprimer.php?id=<?= \Rewritting::sanitize($anime['id']) ?>" onclick="return window.confirm(`Voulez-vous supprimer cet anime ?`)">Supprimer</a>
													</td>
												<?php } else {
													if ($anime['demande'] == 0) { ?>
														<td><button class="btn btn-outline-danger">Demander une suppression</button></td>
													<?php } else { ?>
														<td><button class="btn btn-outline-secondary">Demande en attente</button></td>
													<?php }
												} ?>
												<td><a href="../../animes/<?= \Rewritting::sanitize($anime['slug']) ?>" class="btn btn-outline-info">Voir <?= \Rewritting::sanitize($anime['titre']) ?></a></td>
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