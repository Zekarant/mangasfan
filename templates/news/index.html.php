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
								<a href="index.php?controller=news&task=delete&id=<?= $new['id_news'] ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer cet article ?!`)"><span class="btn btn-sm btn-outline-danger">Supprimer</span></a>
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
