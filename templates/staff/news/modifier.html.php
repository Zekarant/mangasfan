<div class="container-fluid contenu">
	<div class="row">
		<?php include('elements/navigation_news.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Modifier une news - Mangas'Fan</h2>
			<hr>
			<a href="index.php" class="btn btn-outline-primary btn-sm">Retourner à l'index de la rédaction</a>
			<hr>
			<?php if(!empty($errors)): ?>
				<div class='alert alert-warning' role='alert'>
					<h4>Oups ! On a un problème chef...</h4>
					<hr>
					<p>On a un petit problème chef, il semblerait que vous ayez oublié les détails suivants :</p>
					<ul><?php foreach($errors as $error): ?>
					<li><?= $error; ?></li>
					<?php endforeach; ?></ul>
				</div>
			<?php endif; ?>
			<form method="POST" action="">
				<div class="row">
					<div class="col-lg-6">
						<label>Modifier le titre de la news :</label>
						<input type="text" name="modif_titre" class="form-control" value="<?= $news['title']; ?>">
						<br/>
						<label>Modifier la description de la news :</label>
						<input type="text" name="modif_description" class="form-control" value="<?= $news['description_news']; ?>">
						<br/>
						<label>Modifier l'image de la news :</label>
						<input type="text" name="modif_image" class="form-control" value="<?= $news['image']; ?>">
						<a href="<?= $news['image']; ?>" target="_blank">Voir l'image de news utilisée</a>
						<br/><br/>
						<label>Catégorie de la news : </label>
						<select name="modif_categorie" class="form-control">
							<option value="Site" <?= (($news['category'] == "Site") ? "selected" : "" ) ?>>Site</option>
							<option value="Jeux Vidéo" <?= (($news['category'] == "Jeux Vidéo") ? "selected" : "" ) ?>>Jeux Vidéo</option>
							<option value="Mangas" <?= (($news['category'] == "Mangas") ? "selected" : "" ) ?>>Mangas</option>
							<option value="Anime" <?= (($news['category'] == "Anime") ? "selected" : "" ) ?>>Anime</option>
							<option value="Autres" <?= (($news['category'] == "Autres") ? "selected" : "" ) ?>>Autres</option>
						</select>
						<br/>
						<label>Modifier les mots-clés de la news :</label>
						<input type="text" name="modif_keywords" class="form-control" <?php if(empty($news['keywords'])){ ?>placeholder="Aucun mot clé" <?php } ?> value="<?= $news['keywords']; ?>">
					</div>
					<div class="col-lg-6">
						<label>Modifier les sources de la news :</label>
						<input type="text" name="modif_sources" class="form-control" <?php if(empty($news['sources'])){ ?>placeholder="Aucune source" <?php } ?> value="<?= $news['sources']; ?>">
						<br/>
						<label>Modifier la visibilité de la news :</label> 
						<?php if ($news['visibility'] == 0) {
							echo "News visible sur l'index";
						} else {
							echo "News cachée aux visiteurs";
						} ?>
						<select name="modif_visibilite" class="form-control">
							<?php if($news['visibility'] == 0){ ?>
								<option value="0" selected="selected">Visible</option>
								<option value="1">Cachée</option>
							<?php } else { ?>
								<option value="0">Visible</option>
								<option value="1" selected="selected">Cachée</option>
							<?php } ?>
						</select>
						<br/>
						<label>Auteur original de la news : (Non modifiable)</label>
						<input type="text" name="auteur" class="form-control" value="<?= $news['username']; ?>" readonly>
						<br/>
						<label>Programmer la news : 
							<?php if (date('Y-m-d H:i:s') <= $news['create_date']){ 
								echo $news['create_date']; 
							}
							?></label> 
							<input type="datetime-local" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($news['create_date'])); ?>" name="programmation_news"/>
						</div>
					</div>
					<br/>
					<label>Modifier le contenu de la news :</label>
					<textarea name="modif_contenu" class="form-control" id="contenu_redac">
						<?= $news['contenu']; ?>
					</textarea>
					<input type="submit" class="btn btn-sm btn-info" name="valider_news" value="Modifier la news" />
				</form>
			</div>
		</div>
	</div>