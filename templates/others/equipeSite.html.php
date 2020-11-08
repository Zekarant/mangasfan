<h2 class="titre">L'équipe du site</h2>
<hr>
<div class="card mb-5">
	<div class="card-header bg-danger">
		 <h5 class="card-title" style="color: white;">Équipe des administrateurs</h5>
		<hr>
		<p class="small" style="color: white;">Les administrateurs ont pour but de s'occuper de l'entiérité du site, ils gèrent la communauté, l'équipe, les partenaires, les mails envoyés. Ce sont les dirigeants de Mangas'Fan.</p>
	</div>
	<div class="card-block p-0">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Pseudo</th>
						<th>Rang</th>
						<th>Manga préféré</th>
						<th>Anime préféré</th>
						<th>Date d'inscription</th>
						<th>Sexe</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($administrateurs as $admin): ?>
						<tr>
							<td><a href="membres/profil-<?= \Rewritting::sanitize($admin['id_user']) ?>"><?= \Rewritting::sanitize($admin['username']) ?></a></td>
							<td><span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($admin['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($admin['grade']), \Rewritting::sanitize($admin['sexe']), \Rewritting::sanitize($admin['stagiaire']), \Rewritting::sanitize($admin['chef'])) ?></span></td>
							<td><?php if(!empty($admin['manga'])) {
								echo \Rewritting::sanitize($admin['manga']);
							} else { 
								echo "Non renseigné";
							} ?></td>
							<td><?php if(!empty($admin['anime'])) {
								echo \Rewritting::sanitize($admin['anime']);
							} else { 
								echo "Non renseigné";
							} ?></td>
							<td><?= \Users::dateAnniversaire($admin['confirmed_at']); ?></td>
							<td><?= \Users::sexe($admin['sexe']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="card mb-5">
	<div class="card-header bg-dev">
		 <h5 class="card-title" style="color: white;">Équipe des développeurs</h5>
		<hr>
		<p class="small" style="color: white;">Les développeurs sont les techniciens du site. Ce sont eux qui s'occupent de développer de nouvelles fonctionnalités, de corriger les différents bugs, que ce soit sur le site ou sur Discord.</p>
	</div>
	<div class="card-block p-0">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Pseudo</th>
						<th>Rang</th>
						<th>Manga préféré</th>
						<th>Anime préféré</th>
						<th>Date d'inscription</th>
						<th>Sexe</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($developpeurs as $dev): ?>
						<tr>
							<td><a href="membres/profil-<?= \Rewritting::sanitize($dev['id_user']) ?>"><?= \Rewritting::sanitize($dev['username']) ?></a></td>
							<td><span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($dev['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($dev['grade']), \Rewritting::sanitize($dev['sexe']), \Rewritting::sanitize($dev['stagiaire']), \Rewritting::sanitize($dev['chef'])) ?></span></td>
							<td><?php if(!empty($dev['manga'])) {
								echo \Rewritting::sanitize($dev['manga']);
							} else { 
								echo "Non renseigné";
							} ?></td>
							<td><?php if(!empty($dev['anime'])) {
								echo \Rewritting::sanitize($dev['anime']);
							} else { 
								echo "Non renseigné";
							} ?></td>
							<td><?= \Users::dateAnniversaire($dev['confirmed_at']); ?></td>
							<td><?= \Users::sexe($dev['sexe']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="card mb-5">
	<div class="card-header bg-modo">
		 <h5 class="card-title" style="color: white;">Équipe des modérateurs</h5>
		<hr>
		<p class="small" style="color: white;">Les modérateurs sont la police de Mangas'Fan, ils veillent à ce qu'il n'y ait pas de débordement au sein de la communauté, ils répondent aux membres et s'occupent de surveiller le site.</p>
	</div>
	<div class="card-block p-0">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Pseudo</th>
						<th>Rang</th>
						<th>Manga préféré</th>
						<th>Anime préféré</th>
						<th>Date d'inscription</th>
						<th>Sexe</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($moderateurs as $modo): ?>
						<tr>
							<td><a href="membres/profil-<?= \Rewritting::sanitize($modo['id_user']) ?>"><?= \Rewritting::sanitize($modo['username']) ?></a></td>
							<td><span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($modo['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($modo['grade']), \Rewritting::sanitize($modo['sexe']), \Rewritting::sanitize($modo['stagiaire']), \Rewritting::sanitize($modo['chef'])) ?></span></td>
							<td><?php if(!empty($modo['manga'])) {
								echo \Rewritting::sanitize($modo['manga']);
							} else { 
								echo "Non renseigné";
							} ?></td>
							<td><?php if(!empty($modo['anime'])) {
								echo \Rewritting::sanitize($modo['anime']);
							} else { 
								echo "Non renseigné";
							} ?></td>
							<td><?= \Users::dateAnniversaire($modo['confirmed_at']); ?></td>
							<td><?= \Users::sexe($modo['sexe']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="card mb-5">
	<div class="card-header bg-redacteur">
		 <h5 class="card-title">Équipe des rédacteurs</h5>
		<hr>
		<p class="small">Il s'agit des plûmes de Mangas'Fan, ils rédigent les articles complets sur les jeux, les mangas et les animes. Soyez sûrs qu'ils travaillent durement et qu'ils sont la fierté de Mangas'Fan.</p>
	</div>
	<div class="card-block p-0">
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Pseudo</th>
						<th>Rang</th>
						<th>Manga préféré</th>
						<th>Anime préféré</th>
						<th>Date d'inscription</th>
						<th>Sexe</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($redacteurs as $redac): ?>
						<tr>
							<td><a href="membres/profil-<?= \Rewritting::sanitize($redac['id_user']) ?>"><?= \Rewritting::sanitize($redac['username']) ?></a></td>
							<td><span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($redac['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($redac['grade']), \Rewritting::sanitize($redac['sexe']), \Rewritting::sanitize($redac['stagiaire']), \Rewritting::sanitize($redac['chef'])) ?></span></td>
							<td><?php if(!empty($redac['manga'])) {
								echo \Rewritting::sanitize($redac['manga']);
							} else { 
								echo "Non renseigné";
							} ?></td>
							<td><?php if(!empty($redac['anime'])) {
								echo \Rewritting::sanitize($redac['anime']);
							} else { 
								echo "Non renseigné";
							} ?></td>
							<td><?= \Users::dateAnniversaire($redac['confirmed_at']); ?></td>
							<td><?= \Users::sexe($redac['sexe']); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>