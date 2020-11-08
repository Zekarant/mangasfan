<?php if ($animation['visibility'] == 0) { ?>
	<div class="alert alert-info" role="alert">
		<h4 class="alert-heading">Dernières informations</h4>
		<hr>
		<p><?= htmlspecialchars_decode(\Rewritting::sanitize($animation['contenu'])) ?></p>
	</div>
<?php } ?>
<h2 class="titre">Dernières actualités du site</h2>
<hr class="tiret_news">
<div class="container">
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
<a href="archives_news.php" class="d-flex justify-content-center"><img src="https://www.mangasfan.fr/images/test.png" class="image_archives" alt="Image archives" /></a>
<br/>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-6">
			<h3 class="text-center">Dernier mangas ajoutés</h3>
            <br/>
			<div class="row">
			<?php foreach ($mangas as $manga) { ?>
				<div class="col-sm-4">
				<div class="card card-jeux" style="width: 100%; margin-bottom: 20px;">
					<a href="/mangas/<?= \Rewritting::sanitize($manga['slug']) ?>">
						<img src="<?= \Rewritting::sanitize($manga['banniere']) ?>" class="card-img-top" style="object-fit: cover; height: 27rem;">
					</a>
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
		<div class="col-lg-6">
			<h3 class="text-center">Dernier animes ajoutés</h3>
            <br>
			<div class="row">
			<?php foreach ($animes as $anime) { ?>
				<div class="col-sm-4">
				<div class="card card-jeux" style="width: 100%; margin-bottom: 20px;">
					<a href="/animes/<?= \Rewritting::sanitize($anime['slug']) ?>">
						<img src="<?= \Rewritting::sanitize($anime['banniere']) ?>" class="card-img-top" style="object-fit: cover; height: 27rem;">
					</a>
					<div class="card-body">
						<h5 class="card-title text-center"><?= \Rewritting::sanitize($anime['titre']) ?>
							<?php if ($anime['publicAverti'] == 1) { ?>
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
