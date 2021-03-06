
<div class="container">
	<h2 class="titrePrincipal">Dernières actualités du site</h2>
</div>
<hr class="tiret_news">
<div class="container">
	<div class="row news justify-content-center mt-5">
		<div class="col-lg-3">
			<div class="effet_newsLast">
				<img src="<?= \Rewritting::sanitize($new['image']); ?>" class="imageLast_news" alt="Image - <?= \Rewritting::sanitize($new['title']); ?>" />
				<p class="text">
					<a href="commentaire/<?= \Rewritting::sanitize($new['slug']) ?>">
						<span class="btn btn-outline-light">Voir la news</span><br/>
						<?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 6 && $utilisateur['chef'] == 1) { ?>
							<a href="index.php?controller=news&task=delete&id=<?= \Rewritting::sanitize($new['id_news']) ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer cet article ?!`)"><span class="btn btn-sm btn-outline-danger">Supprimer</span></a>
						<?php } ?>
					</a>
				</p>
			</div>
		</div>
		<div class="col-lg-6 text-left">
			<h2 class="titreLastNews"><a href="commentaire/<?= \Rewritting::sanitize($new['slug']) ?>"><?= \Rewritting::sanitize($new['title']); ?></a></h2>
			<br/>
			<p style="font-size: 14px;"><?= \Rewritting::sanitize($new['description']); ?></p>
			<div class="bloc_auteur pt-5">
				<span class="auteur_news"><?= \Rewritting::sanitize($new['username']); ?></span>
				<span class="date_news">Le <?= date('d/m/y à H:i', strtotime(\Rewritting::sanitize($new['create_date']))); ?></span>
			</div>
		</div>
	</div>
	<div class="row">
		<?php foreach ($news as $new) : ?>
			<div class="col-lg-4 news">
				<div class="effet_news">
					<img src="<?= \Rewritting::sanitize($new['image']); ?>" class="image_news" alt="Image - <?= \Rewritting::sanitize($new['title']); ?>" />
					<p class="text">
						<a href="commentaire/<?= \Rewritting::sanitize($new['slug']) ?>">
							<span class="btn btn-outline-light">Voir la news</span><br/>
							<?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 6 && $utilisateur['chef'] == 1) { ?>
								<a href="index.php?controller=news&task=delete&id=<?= \Rewritting::sanitize($new['id_news']) ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer cet article ?!`)"><span class="btn btn-sm btn-outline-danger">Supprimer</span></a>
							<?php } ?>
						</a>
					</p>
				</div>
				<p class="titre_news">
					<a href="commentaire/<?= \Rewritting::sanitize($new['slug']) ?>"><?= \Rewritting::sanitize($new['title']); ?></a>
				</p>
				<p class="description_news"><?= \Rewritting::sanitize($new['description']); ?></p>
				<div class="bloc_auteur">
					<span class="auteur_news"><?= \Rewritting::sanitize($new['username']); ?></span>
					<span class="date_news">Le <?= date('d M Y à H:i', strtotime(\Rewritting::sanitize($new['create_date']))); ?></span>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
<div class="container">
	<a href="archives_news.php" class="d-flex justify-content-center btn btn-outline-secondary">Accéder aux archives de news</a>
	<br/>
	<h3>Derniers mangas et animes ajoutés</h3>
	<hr>
	<div class="row">
		<div class="col-lg-12">
			<div class="row">
				<?php foreach ($mangas as $manga) { ?>
					<div class="col-sm-3">
						<div class="card card-jeux" style="width: 100%; margin-bottom: 20px;">
							<div class="bouton_mangasAnimes">
								<?php if ($manga['type'] == "anime") { ?>
									<span class="btn btn-sm btn-primary justify-content-right">Anime</span>
								<?php } else { ?>
									<span class="btn btn-sm btn-info justify-content-right">Manga</span>
								<?php } ?>
							</div>
							<?php if ($manga['type'] == "anime") { ?>
								<a href="/animes/<?= \Rewritting::sanitize($manga['slug']) ?>">
									<img src="<?= \Rewritting::sanitize($manga['banniere']) ?>" class="card-img-top" style="object-fit: cover; height: 27rem;">
								</a>
							<?php } else { ?>
								<a href="/mangas/<?= \Rewritting::sanitize($manga['slug']) ?>">
									<img src="<?= \Rewritting::sanitize($manga['banniere']) ?>" class="card-img-top" style="object-fit: cover; height: 27rem;">
								</a>
							<?php } ?>
							<div class="card-body">
								<h5 class="card-title text-center"><?= \Rewritting::sanitize($manga['titre']) ?>
								<?php if ($manga['publicAverti'] == 1) { ?>
									- <span class="badge badge-pill badge-danger">Public averti</span>
								<?php } ?>
							</h5>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
</div>
