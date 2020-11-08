<div class='alert alert-primary' role='alert'>
	<strong>Attention : </strong> Comme cette page est la même que celle de la rédaction, que seule l'interface a changé, utilisez la flèche de retour arrière du navigateur pour éditer votre news. <strong>Rien de votre news ne sera perdu en faisant retour.</strong>
</div>
<hr>
<h2 class="titre"><?= \Rewritting::sanitize($titre) ?></h2>
<hr>
<?= htmlspecialchars_decode(\Rewritting::sanitize($contenu)) ?>
<hr>
<p class="small">Sources : <?= \Rewritting::sanitize($sources); ?></p>
<p class="auteur-news">Par <a href="../membres/profil-<?= \Rewritting::sanitize($utilisateur['id_user']) ?>"><?= \Rewritting::sanitize($utilisateur['username']) ?></a><?php if($utilisateur['stagiaire'] == 1){ echo " (stagiaire)"; } ?></small></p>
<div class="container">
	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header">
					A propos de l'auteur
				</div>
				<div class="bloc-auteur">
					<div class="row">
						<div class="col-lg-3">
							<img src="https://www.mangasfan.fr/membres/images/avatars/<?= \Rewritting::sanitize($utilisateur['avatar']); ?>" alt="Avatar de <?= \Rewritting::sanitize($utilisateur['username']) ?>" class="auteur-avatar" />
						</div>
						<div class="col-lg-9 a-propos-auteur">
							<h5><?= \Rewritting::sanitize($utilisateur['username']); ?></h5>
							<hr>
							<small><i>« <?= \Rewritting::sanitize($utilisateur['role']); ?> »</i></small>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header">
					A propos de la news
				</div>
				<div class="bloc-auteur">
					<?php if($categorie != "Site"){ 
						if($categorie == "Anime"){ ?>
							Cette news appartient à la catégorie « <strong><?= \Rewritting::sanitize($categorie); ?></strong> ».<br/><br/>
							Par ailleurs, nous avons une page concernée aux animes sur le site.
						<?php } elseif($categorie == "Mangas"){ ?>
							Cette news appartient à la catégorie « <strong><?= \Rewritting::sanitize($categorie); ?></strong> ».<br/><br/>
							Par ailleurs, nous avons une page concernée aux mangas sur le site.
						<?php } else { ?>
							Cette news appartient à la catégorie « <strong><?= \Rewritting::sanitize($categorie); ?></strong></a> ».<br/><br/>
							Par ailleurs, nous avons une page concernée aux jeux vidéo sur le site.
						<?php }
					}
					else { ?>
						Cette news appartient à la catégorie « <strong>Site</strong> »
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>