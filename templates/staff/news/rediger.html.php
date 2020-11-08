<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_news.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Rédiger une news - Mangas'Fan</h2>
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
			<?php if($utilisateur['stagiaire'] == 1){ ?>
				<div class='alert alert-warning' role='alert'>
					<strong>Attention : </strong> Etant donné que vous êtes un membre stagiaire de l'équipe, votre news ne sera pas postée directement et devra être validée par le chef de l'équipe des Newseurs.
				</div>
			<?php } ?>
			<div class='alert alert-primary' role='alert'>
				<strong>Information : </strong> Si vous souhaitez prévisualiser la news, il est conseillé d'ouvrir la fenêtre dans un nouvel onglet (Ctrl -> Clique gauche). Sinon, une fois sur la page, faites retour arrière.
			</div>
			<form method="POST" action="">
				<div class="row">
					<div class="col-lg-6">
						<label>Titre de la news : </label>
						<input type="text" name="titre" class="form-control" placeholder="Entrez le titre de la news : il doit être explicite." value="<?php if(isset($_POST['titre'])){ echo \Rewritting::sanitize($_POST['titre']); } ?>" />
						<br/>
						<label>Description de la new :</label>
						<input type="text" name="description" class="form-control" placeholder="Entrez une courte description de la news pour la résumer !" value="<?php if(isset($_POST['description'])){ echo \Rewritting::sanitize($_POST['description']); } ?>" />
						<br/>
						<label>Image de la news (200*200) : </label>
						<input type="url" name="image" class="form-control" placeholder="Lien de l'image" value="<?php if(isset($_POST['image'])){ echo \Rewritting::sanitize($_POST['image']); } ?>"/>
						<br/>
						<label>Catégorie de la news : </label>
						<select name="categorie" class="form-control">
							<?php if(isset($_POST['categorie'])) { ?>
								<option value="<?= \Rewritting::sanitize($_POST['categorie']); ?>"><?= \Rewritting::sanitize($_POST['categorie']); ?></option>
							<?php } ?>
							<option value="Site">Site</option>
							<option value="Jeux vidéo">Jeux vidéo</option>
							<option value="Mangas">Mangas</option>
							<option value="Anime">Anime</option>
							<option value="Autres">Autres</option>
						</select>
						<br/>
						<label>Mots-clés : <strong>Les séparer par des virgules</strong></label>
						<input type="text" name="keywords" class="form-control" placeholder="Vos mots-clés, séparés par une virgule" value="<?php if(isset($_POST['keywords'])){ echo \Rewritting::sanitize($_POST['keywords']); } ?>"/>
					</div>
					<div class="col-lg-6">
						<label>Sources : </label>
						<input type="text" name="sources" class="form-control" placeholder="Sources" value="<?php if(isset($_POST['sources'])){ echo \Rewritting::sanitize($_POST['sources']); } ?>"/>
						<br/>
						<label>Visibilité de la news :</label> 
						<select class="form-control" name="visible" placeholder="Voulez-vous rendre la news visible aux gens ?">
							<option value="0">Visible</option>
							<option value="1">Cachée</option>
						</select>
						<br/>
						<label>Auteur de la news :</label> 
							<input type="text" name="auteur" class="form-control" value="<?= \Rewritting::sanitize($utilisateur['username']); ?>" readonly>
						<br/>
						<label>Programmer la news :</label> 
						<input type="datetime-local" class="form-control" name="programmation_news" value="<?php if(isset($_POST['programmation_news'])){ echo \Rewritting::sanitize($_POST['programmation_news']); } ?>"/>
					</div>
				</div>
				<br/>
				<label>Contenu de la new :</label>
					<textarea name="contenu_news"><?php if(isset($_POST['contenu_news'])){ echo \Rewritting::sanitize($_POST['contenu_news']); } ?></textarea>
					<hr>
					<input type="submit" class="btn btn-sm btn-outline-info" name="valider" value="Poster la news"/>
					<input type="submit" class="btn btn-sm btn-outline-primary" name="preview" value="Prévisualiser la news"/>
			</form>
		</div>
	</div>
</div>