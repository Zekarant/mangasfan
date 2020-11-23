<?php if ($sousCategories != ""): ?>
	<table class="table">
		<tr class="table-info">
			<th>Autres catégories de "<?= \Rewritting::sanitize($categorie['name']); ?>"</th>
			<th>Topics</th>
			<th>Dernier topic</th>
		</tr>
		<?php foreach ($sousCategories as $sousCategorie): ?>
			<tr>
				<td>
					<h5><a href="<?= \Rewritting::sanitize($categorie['slug']) . "/" . \Rewritting::sanitize($sousCategorie['slug']); ?>"><?= \Rewritting::sanitize($sousCategorie['name']); ?></a></h5>
				</td>
				<td>2 topics</td>
				<td>TOPIC - Posté par <a href="#">Admin</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<hr>
<?php endif; ?>
<h2><?= \Rewritting::sanitize($categorie['name']); ?></h2>
<hr>
<table class="table">
	<tr class="table-info">
		<th>Messages du topic "<?= \Rewritting::sanitize($categorie['name']); ?>"</th>
		<th>Réponses</th>
		<th>Dernière réponse</th>
		<th>Crée par</th>
	</tr>
	<?php if ($topics == NULL) { ?>
		<td>Aucun message</td>
	<?php } else {
	foreach ($topics as $topic): ?>
		<tr>
			<td>
				<a href="voir_messages.php?id_category=<?= \Rewritting::sanitize($categorie['id']); ?>&souscategory=<?= \Rewritting::sanitize($categorie['id']); ?>&id_message=<?= \Rewritting::sanitize($topic['id_topic']); ?>"><?= \Rewritting::sanitize($topic['titre']); ?></a>
				<p><em><?= \Rewritting::sanitize(substr($topic['contenu'] , 0, 100)); ?>...</em></p>
			</td>
			<td>5 messages</td>
			<td><a href="#">Admin</a> - Le XX/XX/XXXX</td>
			<td><a href="#">Admin</a></td>
		</tr>
	<?php endforeach;
	} ?>
</table>