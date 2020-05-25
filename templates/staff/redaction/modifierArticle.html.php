<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_redaction.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Modifier "<?= \Rewritting::sanitize($jeu['name_article']) ?>"</h2>
			<a href="../../modification-jeux/<?= \Rewritting::sanitize($jeu['slug']) ?>" class="btn btn-sm btn-outline-info">Retourner sur la page de "<?= \Rewritting::sanitize($jeu['name_jeu']) ?>"</a>
			<a href="../../../../jeux-video/<?= \Rewritting::sanitize($jeu['slug']) ?>/<?= \Rewritting::sanitize($jeu['slug_article']) ?>" target="_blank" class="btn btn-sm btn-outline-info">Accéder à "<?= \Rewritting::sanitize($jeu['name_article']) ?>"</a>
			<hr>
			<form method="POST" action="">
				<label>Titre : </label>
				<input type="text" class="form-control" name="titre_page" value="<?= \Rewritting::sanitize($jeu['name_article']); ?>"><br/>
				<label>Image sur le slider : <small><a href="<?= \Rewritting::sanitize($jeu['cover_image_article']); ?>" target="_blank">Voir l'image (Nouvel onglet)</a></small></label>
				<input type="text" class="form-control" name="image_page" value="<?= \Rewritting::sanitize($jeu['cover_image_article']); ?>"><br/>
				<label> Sélectionnez un onglet : </label>
				<select class="form-control" id="exampleSelect1" name="liste_onglets">
					<?php foreach($onglet AS $onglets) { ?>
						<option value="<?= \Rewritting::sanitize($onglets['id_category']) ?>" <?php if($onglets['id_category'] == $jeu['id_onglet']){ ?> selected="selected" <?php } ?>><?= \Rewritting::sanitize($onglets['name_category']) ;?></option>
					<?php } ?>
				</select><br/>
				<label>Visibilité : </label>
				<select name="modif_visibilite" class="form-control">
					<?php if($jeu['visibilite'] == 0){ ?>
						<option value="0" selected="selected">Visible</option>
						<option value="1">Cachée</option>
					<?php } else { ?>
						<option value="0">Visible</option>
						<option value="1" selected="selected">Cachée</option>
					<?php } ?>
				</select>
				<br/>
				<label>Texte : </label>
				<textarea name="en_attente"><?= htmlspecialchars_decode(\Rewritting::sanitize($jeu['contenu_article'])) ?></textarea>
				<input type="submit" class="btn btn-sm btn-info" name="valider_page" value="Valider la page">
			</form>
		</div>
	</div>
</div>