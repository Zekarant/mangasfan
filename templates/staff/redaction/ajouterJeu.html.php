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
							<label>Titre du jeu :</label>
							<br/><br/><br/>
							<label>Image du jeu (Page d'index des jeux) :</label>
							<br/><br/><br/>
							<label>Cover du jeu (Page dans la page du jeu) :</label>
							<br/><br/><br/>
							<label>Présentation du jeu (Facultatif) :</label>
						</div>
						<div class="col-lg-10">
							<input type="text" name="titre_jeu" class="form-control" placeholder="Saisir le nom du jeu">
							<br/><br/>
							<input type="url" name="banniere_jeu" class="form-control" placeholder="Lien de l'image du jeu qui apparaitra sur la page d'index des jeux">
							<br/><br/>
							<input type="url" name="cover_jeu" class="form-control" placeholder="Lien de l'image du jeu qui apparaitra sur la page de présentation du jeu">
							<br/><br/>
							<textarea name="presentation_jeu"></textarea>
							<input type="submit" class="btn btn-outline-info" name="ajouter_jeu" value="Ajouter le jeu">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>