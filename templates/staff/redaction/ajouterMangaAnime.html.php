<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_redaction.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Ajouter un nouveau jeu</h2>
			<a href="index.php" class="btn btn-sm btn-outline-info">Retourner sur l'index de rédaction</a>
			<hr>
			<div class="container-fluid">
				<form method="POST" action="">
					<div class="row">
						<div class="col-lg-2">
							<label>Titre :</label>
							<br/><br/><br/>
							<label>Image (Page d'index) :</label>
							<br/><br/><br/>
							<label>Cover (Page dans la page du manga/anime) :</label>
							<br/><br/><br/>
							<label>Type :</label>
							<br/><br/><br/>
							<label>Présentation (Facultatif) :</label>
						</div>
						<div class="col-lg-10">
							<input type="text" name="titre_manga" class="form-control" placeholder="Saisir le nom">
							<br/><br/>
							<input type="url" name="banniere_manga" class="form-control" placeholder="Lien de l'image qui apparaitra sur la page d'index">
							<br/><br/>
							<input type="url" name="cover_manga" class="form-control" placeholder="Lien de l'image qui apparaitra sur la page de présentation">
							<br/><br/>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="anime" checked>
								<label class="form-check-label" for="inlineRadio1">Anime</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="manga">
								<label class="form-check-label" for="inlineRadio2">Manga</label>
							</div>
							<br/><br/>
							<textarea name="presentation_manga"></textarea>
							<input type="submit" class="btn btn-outline-info" name="ajouter_manga" value="Ajouter">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>