<h2 class="titre">Galeries du site</h2>
<hr>
<div class='alert alert-info' role='alert'>
	Bienvenue sur l'accueil des galeries de Mangas'Fan ! Vous retrouverez sur cette page toutes les dernières créations des membres du site, leurs dessins, leurs fanarts ou leurs images qu'ils ont créés eux-mêmes.
	<?php if (isset($_SESSION['auth']) && $utilisateur['grade'] <= 7 && $utilisateur['nsfw'] == 0) {
		if ($utilisateur['date_anniversaire'] != NULL && $interval->format('%y') >= 18) { ?>
			<br/><br/>
			<strong>Activer le NSFW :</strong> Vous semblez avoir plus de 18 ans, vous pouvez donc activer le NSFW, pour se faire, vous avez juste à cliquer sur le bouton ci-dessous ! Cette option est activable et désactiable à tout moment !
			<form method="POST" action="">
				<input type="submit" name="activer_nsfw" class="btn btn-sm btn-outline-info" value="Activer mon accès NSFW">
			</form>
		<?php }
	} elseif (isset($_SESSION['auth']) && $utilisateur['grade'] <= 7 && $utilisateur['nsfw'] == 1) { ?>
		<br/><br/>
		<strong>Désactiver le NSFW :</strong> Vous ne voulez plus voir d'images un peu bizarres ? Vous pouvez désactiver le NSFW, et revenir à tout moment ! Cliquez juste sur le bouton ci-dessous :
		<form method="POST" action="">
			<input type="submit" name="desactiver_nsfw" class="btn btn-sm btn-outline-info" value="Désactiver mon accès NSFW">
		</form>
	<?php } ?>
</div>
<div class="container-fluid">
	<div class="row justify-content-center">
		<?php foreach($galeries as $galerie): 
			if ($galerie['rappel_image'] == 0){ ?>
					<div class="card card-galeries" style="width: 18rem;">
						<div class="image">
							<img src="../galeries/images/<?= \Rewritting::sanitize($galerie['filename']); ?>" alt="<?= \Rewritting::sanitize($galerie['title_image']); ?> de <?= \Rewritting::sanitize($galerie['username']); ?>" class="image_galeries" />
						</div>
						<div class="card-body">
							<hr>
							<h5 class="card-title text-center">
								<?php if ($galerie['nsfw_image'] == 1) {
									echo "[NSFW] ";
								} 
								echo \Rewritting::sanitize($galerie['title_image']); ?> - <a href="<?= \Rewritting::sanitize($galerie['slug']) ?>">Voir l'image</a>
							</h5>
							<hr>
							<p class="card-text">
								<em>
									<?php if(!empty($galerie['keywords_image'])){ 
										echo \Rewritting::sanitize($galerie['keywords_image']);
									} else { 
										echo "Aucune description pour cette image.";
									} ?>
								</em>
							</p>
						</div>
						<div class="card-footer">
                  			<small class="text-muted">
                    			Posté par <a href="../membres/profil-<?= \Rewritting::sanitize($galerie['auteur_image']); ?>"><?= \Rewritting::sanitize($galerie['username']); ?></a> le <?= \Users::dateAnniversaire(\Rewritting::sanitize($galerie['date_image'])); ?>
                  			</small>
               			</div>
					</div>
			<?php }
		endforeach; ?>
	</div>
</div>