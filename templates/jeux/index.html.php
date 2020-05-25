<?php if ($utilisateur['grade'] >= 5) { ?>
	<a href="../staff/redaction" target="_blank" class="btn btn-outline-info">Accéder au pannel de rédaction</a>
	<hr>
<?php } ?>
<h2 class="titre">Accueil des jeux vidéo</h2>
<hr>
<form method="POST" action="">
	<div class="row">
		<div class="col-lg-8 d-flex justify-content-center">
			<input type="text" name="search" class="form-control" placeholder="Saisir le nom du jeu recherché">
			<input type="submit" name="search_ok" class="btn btn-outline-success" value="Rechercher">	
		</div>
		<div class="col-lg-4">
			<nav>
				<ul class="pagination justify-content-center">
					<li class="page-item disabled">
						<a class="page-link" href="#" tabindex="-1">Pages :</a>
					</li>
					<?php for ($i = 1; $i < $nb_pages; $i++) {
						if ($i == $page) { ?>
							<li class="page-item">
								<a class="page-link" href="#"><?= $i; ?></a>
							</li>
						<?php } else { ?>
							<li class="page-item">
								<a class="page-link" href="<?= "?page=" . $i; ?>#jeux"><?= $i?></a>
							</li>
						<?php }
					} ?>
				</ul>
			</nav>
		</div>
	</div>
</form>
<hr>
<div class="container-fluid">
	<div class="row d-flex justify-content-center">
		<?php foreach ($allJeux as $jeux): ?>
			<div class="col-sm-2">
				<div class="card card-jeux" style="width: 100%;">
					<a href="<?= \Rewritting::sanitize($jeux['slug']) ?>">
						<img src="<?= \Rewritting::sanitize($jeux['banniere_jeu']) ?>" class="card-img-top" style="object-fit: cover; height: 27rem;">
					</a>
					<div class="card-body">
						<h5 class="card-title text-center"><?= \Rewritting::sanitize($jeux['name_jeu']) ?></h5>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>