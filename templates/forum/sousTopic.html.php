<h2><?= \Rewritting::sanitize($sousCategorie['name']); ?></h2>
<hr>
<table class="table">
	<tr class="table-info">
		<th>Messages du topic "<?= \Rewritting::sanitize($sousCategorie['name']); ?>"</th>
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
				<a href="../voir_messages.php?id_category=<?= \Rewritting::sanitize($categorie['id']); ?>&souscategory=<?= \Rewritting::sanitize($sousCategorie['id']); ?>&id_message=<?= \Rewritting::sanitize($topic['id_topic']); ?>"><?= \Rewritting::sanitize($topic['titre']); ?></a>
				<p><em><?= \Rewritting::sanitize(substr($topic['contenu'], 0, 100)); ?>...</em></p>
			</td>
			<td>5 messages</td>
			<td><a href="#">Admin</a> - Le XX/XX/XXXX</td>
			<td><a href="#">Admin</a></td>
		</tr>
	<?php endforeach;
	} ?>
</table>