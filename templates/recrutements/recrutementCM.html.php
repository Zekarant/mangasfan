<h2 class="titre">Recrutements <?= \Rewritting::sanitize($recuperation['name_animation']) ?></h2>
<hr>
<div class="alert alert-info">
	<strong>Important :</strong> Sachez qu'en postulant à un rôle staff, vous vous engagez à respecter les conditions d'utilisation de Mangas'Fan.
</div>
<div class="container-fluid">
	<div class="card">
		<div class="card-body">
			<p>* = Champs obligatoires</p>
			<hr>
			<form method="POST" action="">
				<div class="row">
					<div class="col-md-4">
						<label>Pseudo : *</label>
						<input type="text" class="form-control" name="pseudo" placeholder="Saisir votre pseudo" required>
					</div>
					<div class="col-md-4">
						<label>Adresse email : *</label>
						<input type="email" class="form-control" name="email" placeholder="Saisir votre adresse email" required>
					</div>
					<div class="col-md-4">
						<label>Poste exact désiré : *</label>
						<select name="poste" class="form-control">
							<option value="Community Manager (Twitter)">Community Manager (Twitter)</option>
							<option value="Community Manager (Facebook)">Community Manager (Facebook)</option>
							<option value="Community Manager (Instagram)">Community Manager (Instagram)</option>
							<option value="Autre">Autre</option>
						</select>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-6">
						<label>Quelles sont vos motivations : *</label>
						<textarea type="text" class="form-control" rows="15" name="motivations" placeholder="Cette partie est très importante, elle nous permet de voir ce qui vous motive à intégrer l'équipe" required></textarea>
					</div>
					<div class="col-md-6">
						<label>Avez-vous une ou plusieurs expérience(s) dans la gestion des réseaux sociaux : *</label>
						<textarea type="text" class="form-control" rows="15" name="experiences" placeholder="Il ne s'agit pas d'une question pénalisante, nous avons seulement besoin de savoir si vous possédez de l'expérience dans le domaine de la rédaction" required></textarea>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-6">
						<label>Quelles sont vos disponibilités : *</label>
						<textarea type="text" class="form-control" rows="5" name="disponibilites" placeholder="Nous avons besoin de connaître la fréquence de votre disponibilité car il faut en effet être assez actif !" required></textarea>
					</div>
					<div class="col-md-6">
						<label>Un dernier mot (par exemple, pourquoi vous et pas un autre ?) :</label>
						<textarea type="text" class="form-control" rows="5" name="autre" placeholder="Si vous avez d'autres éléments à nous faire parvenir, n'hésitez pas, c'est ici ! Attention, la réponse à la question peut peser dans la balance"></textarea>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="alert alert-info" role="alert">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
							<label class="form-check-label" for="invalidCheck2">
								En cochant cette case, vous acceptez les <a href="../mentions_legales.php" target="_blank">Conditions Générales d'Utilisation</a> de Mangas'Fan. Indispensables si votre situation est favorable pour nous.
							</label>
						</div>
					</div>
				</div>
				<input type="submit" class="btn btn-info" name="envoyer" value="Valider le formulaire">
			</form>
		</div>
	</div>
</div>