<h2 class="titre">Liste des membres</h2>
<hr>
<nav>
	<ul class="pagination justify-content-center">
		<li class="page-item disabled">
			<a class="page-link" href="#" tabindex="-1">Pages :</a>
		</li>
		<?php for ($i = 1; $i < $nb_pages; $i++) {
			if ($i == $page) { ?>
				<li class="page-item">
					<a class="page-link" href="#"><?= $i; ?></a>
				</li>
			<?php } else { ?>
				<li class="page-item">
					<a class="page-link" href="<?= "?page=" . $i; ?>#membres"><?= $i?></a>
				</li>
			<?php }
		} ?>
	</ul>
</nav>
<div class="table-responsive">
	<table class="table">
		<thead>
			<th>Avatar</th>
			<th>Pseudonyme</th>
			<th>Inscription</th>
			<th>Rang</th>
			<th>Manga favori</th>
			<th>Anime favori</th>
			<th>Voir le profil</th>
		</thead>
		<tbody>
			<?php foreach ($members as $member): ?>
				<tr>
					<td><img src="/membres/images/avatars/<?= \Rewritting::sanitize($member['avatar']) ?>" alt="Avatar de <?= \Rewritting::sanitize($member['username']) ?>" style="width: 75px;" /></td>
					<td><?= \Rewritting::sanitize($member['username']) ?></td>
					<td><?= date('d/m/Y', strtotime(\Rewritting::sanitize($member['confirmed_at']))); ?></td>
					<td><span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($member['grade']) ?>;"><?= Color::getRang($member['grade'], $member['sexe'], $member['stagiaire'], $member['chef']) ?></span></td>
					<td><?= \Rewritting::sanitize($member['manga']) ?></td>
					<td><?= \Rewritting::sanitize($member['anime']) ?></td>
					<td><a href="../membres/profil-<?= \Rewritting::sanitize($member['id_user']) ?>">Consulter</a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>