<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_admin.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Modifier le changelog du site</h2>
			<hr>
			<div class="text-center">
				<a href="#rediger" class="btn btn-outline-info">Rédiger un nouveau changelog</a>
				<a href="#modifier" class="btn btn-outline-info">Modifier un changelog existant</a>
			</div>
			<hr>
			<h3 class="titre" id="rediger">Rédiger un nouveau changelog</h3>
			<form method="POST" action="">
				<label>Titre du changelog :</label>
				<input type="text" name="titre-changelog" class="form-control" placeholder="Saisir le titre du changelog">
				<br/>
				<label>Contenu du changelog :</label>
				<textarea name="text-changelog"></textarea>
				<input type="submit" name="valider-changelog" class="btn btn-outline-info" value="Poster le changelog">
			</form>
			<hr>
			<h3 class="titre" id="modifier">Modifier les changelogs existants</h3>
			<table class="table">
				<thead>
					<th>Titre</th>
					<th>Date</th>
					<th>Action</th>
				</thead>
				<tbody>
					<?php foreach ($changelogs as $changelog): ?>
						<tr>
							<td><?= \Rewritting::sanitize($changelog['title_changelog']) ?></td>
							<td><?= date('d/m/Y', strtotime(\Rewritting::sanitize($changelog['date_changelog']))); ?></td>
							<td><a href="modifier_changelog.php?id=<?= \Rewritting::sanitize($changelog['id_changelog']) ?>" class="btn btn-outline-info">Modifier</a></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>