<h2 class="titre">News du site</h2>
<hr class="tiret_news">
<div class="container">
	<div class="row">
		<?php foreach ($news as $new) : ?>
			<div class="col-lg-4 news">
				<div class="effet_news">
					<img src="<?= $new['image']; ?>" class="image_news" alt="Image - <?= $new['title']; ?>" />
					<p class="text">
						<a href="commentaire/<?= $new['slug'] ?>">
							<span class="btn btn-outline-light">Voir la news</span><br/>
								<a href="index.php?controller=news&task=delete&id=<?= $new['id_news'] ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer cet article ?!`)"><span class="btn btn-sm btn-outline-warning">Supprimer</span></a>
						</a>
					</p>
				</div>
				<p class="titre_news">
					<a href="commentaire/<?= $new['slug'] ?>"><?= $new['title']; ?></a>
				</p>
				<p class="description_news"><?= $new['description']; ?></p>
				<div class="bloc_auteur">
					<span class="auteur_news"><?= $new['username']; ?></span>
					<span class="date_news">Le <?= date('d M Y à H:i', strtotime($new['create_date'])); ?></span>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
