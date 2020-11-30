<p><em>Votre localisation : <a href="../forum">Accueil du forum</a> -> <a href="voirforum.php?f=<?= $topic['id_forum'] ?>"><?= $topic['forum_name'] ?></a> -> <?= $topic['topic_titre'] ?></em></p>
<hr>
<h2 class="titre"><?= \Rewritting::sanitize($topic['topic_titre']) ?></h2>
<hr>
<a href="#" class="btn btn-outline-info">Répondre</a>
<br/><br/>
<?php foreach ($messages as $message): ?>
	<div class="card">
	<div class="card-header">
		<?= \Rewritting::sanitize($topic['topic_titre']) ?> - Posté le <?= \Rewritting::sanitize($message['date_created']); ?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-2 text-center" style="border-right: 1px solid <?= Color::rang_etat(\Rewritting::sanitize($message['grade'])) ?>">
				<img src="../membres/images/avatars/<?= $message['avatar']; ?>" width="160"/><br/>
				<h4><a href="../membres/profil-<?= \Rewritting::sanitize($message['id_utilisateur']) ?>"><?= \Rewritting::sanitize($message['username']); ?></a></h4>
				<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($message['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($message['grade']), \Rewritting::sanitize($message['sexe']), \Rewritting::sanitize($message['stagiaire']), \Rewritting::sanitize($message['chef'])) ?></span>
				<hr>
				<p class="small">
					Manga préféré : <?= \Rewritting::sanitize($message['manga']); ?><br/>
					Anime préféré : <?= \Rewritting::sanitize($message['anime']); ?><br/>
					Points : <?= \Rewritting::sanitize($message['points']); ?> points
				</p>
			</div>
			<div class="col-lg-10">
				<?= htmlspecialchars_decode(\Rewritting::sanitize($message['contenu'])) ?>
			</div>
		</div>
	</div>
</div>
<br/>
<?php endforeach; ?>