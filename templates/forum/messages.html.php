<h2><?= \Rewritting::sanitize($topic['titre']) ?></h2>
<hr>
<div class="card">
	<div class="card-header">
		<?= \Rewritting::sanitize($topic['titre']) ?> - Posté le <?= \Rewritting::sanitize($topic['date_creation']); ?> - ID Message : <?= $topic['id_message'] ?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-2 text-center" style="border-right: 1px solid <?= Color::rang_etat(\Rewritting::sanitize($topic['grade'])) ?>">
				<img src="../../../membres/images/avatars/<?= $topic['avatar']; ?>" width="160"/><br/>
				<h4><a href="../membres/profil-<?= \Rewritting::sanitize($topic['id_user']) ?>"><?= \Rewritting::sanitize($topic['username']); ?></a></h4>
				<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($topic['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($topic['grade']), \Rewritting::sanitize($topic['sexe']), \Rewritting::sanitize($topic['stagiaire']), \Rewritting::sanitize($topic['chef'])) ?></span>
				<hr>
				<p class="small">
					Manga préféré : <?= \Rewritting::sanitize($topic['manga']); ?><br/>
					Anime préféré : <?= \Rewritting::sanitize($topic['anime']); ?><br/>
					Points : <?= \Rewritting::sanitize($topic['points']); ?> points
				</p>
			</div>
			<div class="col-lg-10">
				<?= htmlspecialchars_decode(\Rewritting::sanitize($topic['contenu'])) ?>
			</div>
		</div>
	</div>
</div>
<br/>
<?php foreach ($messages as $message): ?>
	<div class="card">
		<div class="card-header">
			Re : <?= \Rewritting::sanitize($message['titre']) ?> - Posté le <?= \Rewritting::sanitize($message['date_created']); ?> - ID Message : <?= $topic['id_message'] ?>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-lg-2 text-center" style="border-right: 1px solid <?= Color::rang_etat(\Rewritting::sanitize($message['grade'])) ?>">
					<img src="../../../membres/images/avatars/<?= $message['avatar']; ?>" width="160"/><br/>
					<h4><a href="../membres/profil-<?= \Rewritting::sanitize($message['id_user']) ?>"><?= \Rewritting::sanitize($message['username']); ?></a></h4>
					<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($message['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($message['grade']), \Rewritting::sanitize($message['sexe']), \Rewritting::sanitize($message['stagiaire']), \Rewritting::sanitize($message['chef'])) ?></span>
					<hr>
					<p class="small">
						Manga préféré : <?= \Rewritting::sanitize($message['manga']); ?><br/>
						Anime préféré : <?= \Rewritting::sanitize($message['anime']); ?><br/>
						Points : <?= \Rewritting::sanitize($message['points']); ?> points
					</p>
				</div>
				<div class="col-lg-10">
					<?= htmlspecialchars_decode(\Rewritting::sanitize($message['contenu_message'])) ?>
				</div>
			</div>
		</div>
	</div>
	<br/>
<?php endforeach; ?>