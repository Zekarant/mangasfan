<h2 class="titre">News du site</h2>
<hr class="tiret_news">
<div class="container">
	<div class="row">
		<?php foreach ($billets as $billet) : ?>
			<div class="col-lg-4 news">
				<div class="effet_news">
					<img src="<?= $billet['theme']; ?>" class="image_news" alt="Image - <?= $billet['titre']; ?>" />
					<p class="text">
						<a href="commentaire/<?= $billet['slug'] ?>">
							<span class="btn btn-outline-light">Voir la news</span><br/>
							<?php if (isset($_SESSION)) { ?>
								<a href="index.php?controller=billet&task=delete&id=<?= $billet['id_news'] ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer cet article ?!`)"><span class="btn btn-sm btn-outline-warning">Supprimer</span></a>
							<?php } ?>
						</a>
					</p>
				</div>
				<p class="titre_news">
					<a href="commentaire/<?= $billet['slug'] ?>"><?= $billet['titre']; ?></a>
				</p>
				<p class="description_news"><?= $billet['description_news']; ?></p>
				<div class="bloc_auteur">
					<span class="auteur_news"><?= $billet['username']; ?></span>
					<span class="date_news">Le <?= date('d M Y à H:i', strtotime($billet['date_creation'])); ?></span>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
