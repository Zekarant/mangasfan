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
			<td>
				<?php
						$controller = new \models\Forum;
						$compter = $controller->nombreTopics($categorie['id']);
						if ($compter == 0) {
							echo "Aucun réponse";
						} elseif ($compter == 1) {
							echo "1 réponse";
						} else {
							echo $compter . " réponses";
						}
						?>
			</td>
			<td><?php $dernierPerso = $controller->chercherDernierMember($sousCategorie['id']); 
				if (isset($dernierPerso) AND $dernierPerso != NULL){ ?>
								Par <a href="../membres/profil-<?= $dernierPerso['id_user'] ?>"><?= $dernierPerso['username'] ?></a>
							<?php } else {
								echo "Aucun message";
							} ?></td>
			<td><a href="#"><?= \Rewritting::sanitize($topic['username']) ?></a></td>
		</tr>
	<?php endforeach;
	} ?>
</table>