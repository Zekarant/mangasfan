<p><em>Votre localisation : <a href="../forum">Accueil du forum</a> -> <?= $sousForum['forum_name'] ?></em></p>
<hr>
<h2 class="titre"><?= \Rewritting::sanitize($sousForum['forum_name']) ?></h2>
<hr>
<a href="#" class="btn btn-outline-info">Poster un nouveau sujet</a>
<br/><br/>
<?php if ($sujets != NULL) { ?>
	<table class="table">
		<thead class="table-info">
			<th></th>
			<th><strong>Titre</strong></th>             
			<th><strong>Réponses</strong></th>
			<th><strong>Vues</strong></th>
			<th><strong>Auteur</strong></th>                       
			<th><strong>Dernier message</strong></th>
		</thead>
		<tbody>
			<?php foreach ($sujets as $sujet): ?>
				<tr>
					<td></td>
					<td>
						<a href="./voirtopic.php?t=<?= $sujet['id_topic'] ?>" title="Topic commencé à <?= date('H\hi \l\e d M y', strtotime($sujet['topic_posted'])) ?>">
							<?= stripslashes(htmlspecialchars($sujet['topic_titre'])) ?>
						</a>
					</td>
					<td class="nombremessages"><?= $sujet['topic_post'] ?></td>
					<td><?= $sujet['topic_vu'] ?></td>
					<td>
						<a href="../membres/profil-<?= $sujet['id_utilisateur_posteur'] ?>"><?= htmlspecialchars($sujet['membre_pseudo_createur']) ?></a>
					</td>
					<td><a href="./voirtopic.php?t=<?= $sujet['id_topic'] ?>"><?= stripslashes(htmlspecialchars($sujet['topic_titre'])) ?></a><br/>
						Par <a href="../membres/profil-<?= $sujet['id_utilisateur_derniere_reponse'] ?>"><?= htmlspecialchars($sujet['membre_pseudo_last_posteur']) ?></a>
						le <?= date('H\hi \l\e d M y', strtotime($sujet['date_created'])) ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<hr>
<?php } if ($sujetsNormaux == NULL) { ?>
	<div class="alert alert-info">
		Aucun sujet n'est actuellement posté dans ce forum !
	</div>
<?php } else { ?>
	<table class="table">
		<thead>
			<th></th>
			<th><strong>Titre</strong></th>             
			<th><strong>Réponses</strong></th>
			<th><strong>Vues</strong></th>
			<th><strong>Auteur</strong></th>                       
			<th><strong>Dernier message</strong></th>
		</thead>
		<tbody>
			<?php foreach ($sujetsNormaux as $sujet): ?>
				<tr>
					<td></td>
					<td>
						<a href="./voirtopic.php?t=<?= $sujet['id_topic'] ?>" title="Topic commencé à <?= date('H\hi \l\e d M y', strtotime($sujet['topic_posted'])) ?>">
							<?= stripslashes(htmlspecialchars($sujet['topic_titre'])) ?>
						</a>
					</td>
					<td class="nombremessages"><?= $sujet['topic_post'] ?></td>
					<td><?= $sujet['topic_vu'] ?></td>
					<td>
						<a href="../membres/profil-<?= $sujet['id_utilisateur_posteur'] ?>"><?= htmlspecialchars($sujet['membre_pseudo_createur']) ?></a>
					</td>
					<td><a href="./voirtopic.php?t=<?= $sujet['id_topic'] ?>"><?= stripslashes(htmlspecialchars($sujet['topic_titre'])) ?></a><br/>
						Par <a href="../membres/profil-<?= $sujet['id_utilisateur_derniere_reponse'] ?>"><?= htmlspecialchars($sujet['membre_pseudo_last_posteur']) ?></a>
						le <?= date('H\hi \l\e d M y', strtotime($sujet['date_created'])) ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php } ?>