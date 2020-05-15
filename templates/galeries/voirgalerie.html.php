<h2 class="titre">Galerie de <?= \Rewritting::sanitize($userGalerie['username']) ?> </h2>
<hr>
<div class="container-fluid">
	<div class="row justify-content-center">
		<?php foreach($galerie as $galeries): ?>
			<div class="card card-galeries" style="width: 18rem;">
				<div class="image">
					<img src="../../galeries/images/<?= \Rewritting::sanitize($galeries['filename']); ?>" alt="<?= \Rewritting::sanitize($galeries['title_image']); ?> de <?= \Rewritting::sanitize($galeries['username']); ?>" class="image_galeries" />
				</div>
				<div class="card-body">
					<hr>
					<h5 class="card-title text-center">
						<?php if ($galeries['nsfw_image'] == 1) {
							echo "[NSFW] ";
						} 
						echo \Rewritting::sanitize($galeries['title_image']); ?> - <a href="../<?= \Rewritting::sanitize($galeries['slug']) ?>">Voir l'image</a>
					</h5>
					<hr>
					<p class="card-text">
						<em>
							<?php if(!empty($galeries['keywords_image'])){ 
								echo \Rewritting::sanitize($galeries['keywords_image']);
							} else { 
								echo "Aucune description pour cette image.";
							} ?>
						</em>
					</p>
				</div>
				<div class="card-footer">
					<small class="text-muted">
						Posté par <a href="../../membres/profil-<?= \Rewritting::sanitize($galeries['auteur_image']); ?>"><?= \Rewritting::sanitize($galeries['username']); ?></a> le <?= \Users::dateAnniversaire(\Rewritting::sanitize($galeries['date_image'])); ?>
					</small>
				</div>
			</div>
			
		<?php endforeach; ?>
	</div>
</div>