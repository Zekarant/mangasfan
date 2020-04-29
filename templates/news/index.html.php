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
<a href="#" class="d-flex justify-content-center"><img src="https://www.mangasfan.fr/images/test.png" /></a>
<br/>
<h2 class="text-center">L'équipe de Mangas'Fan</h2>
<hr>
<div class="table-responsive">
	<table class="table">
		<thead>
			<th>Pseudo</th>
			<th>Rang</th>
			<th>Manga préféré</th>
			<th>Date d'inscription</th>
		</thead>
		<tbody>
			<?php foreach ($staff as $equipe): ?>
				<tr>
					<td><a href="#"><?= \Rewritting::sanitize($equipe['username']) ?></a></td>
					<td><span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($equipe['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($equipe['grade']), \Rewritting::sanitize($equipe['sexe']), \Rewritting::sanitize($equipe['stagiaire']), \Rewritting::sanitize($equipe['chef'])) ?></span></td>
					<td><?= \Rewritting::sanitize($equipe['manga']) ?></td>
					<td><?= date('d/m/Y', strtotime(\Rewritting::sanitize($equipe['confirmed_at']))); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>