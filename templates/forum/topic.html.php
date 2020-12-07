<p>Votre localisation : <a href="../forum">Accueil du forum</a> -> <a href="voirforum.php?f=<?= $topic['id_forum'] ?>"><?= $topic['forum_name'] ?></a> -> <?= $topic['topic_titre'] ?></p>
<hr>
<h2 class="titre"><?= \Rewritting::sanitize($topic['topic_titre']) ?></h2>
<hr>
<a href="#formulaireMessage" class="btn btn-outline-info">Répondre</a>
<?php if (isset($_SESSION['auth']) && $user['grade'] >= 7) { ?>
	<a href="supprimer_topic.php?t=<?= \Rewritting::sanitize($topic['id_topic']) ?>" class="btn btn-outline-danger">Supprimer le topic</a>
<?php } ?>
<br/><br/>
<?php foreach ($messages as $message): ?>
	<div class="card" id="<?= \Rewritting::sanitize($message['id_message']) ?>">
	<div class="card-header">
		<?= \Rewritting::sanitize($topic['topic_titre']) ?> - Posté le <?= Users::dateAnniversaire($message['date_created']) ?>
		<?php if ((isset($_SESSION['auth']) && $user['id_user'] == $message['id_utilisateur_message']) || isset($_SESSION['auth']) && $user['grade'] >= 7) { ?>
			<br/>
			<hr>
			<a href="editer.php?topic=<?= \Rewritting::sanitize($topic['id_topic']) ?>&message=<?= \Rewritting::sanitize($message['id_message']) ?>" class="btn btn-sm btn-outline-info">Modifier</a>
			<a href="#" class="btn btn-sm btn-outline-danger">Supprimer</a>
		<?php } ?>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-lg-2 text-center" style="border-right: 1px solid <?= Color::rang_etat(\Rewritting::sanitize($message['grade'])) ?>">
				<img src="../membres/images/avatars/<?= \Rewritting::sanitize($message['avatar']); ?>" width="160"/><br/>
				<h4><a href="../membres/profil-<?= \Rewritting::sanitize($message['id_utilisateur']) ?>"><?= \Rewritting::sanitize($message['username']); ?></a></h4>
				<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($message['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($message['grade']), \Rewritting::sanitize($message['sexe']), \Rewritting::sanitize($message['stagiaire']), \Rewritting::sanitize($message['chef'])) ?></span>
				<hr>
				<p class="small">
					Manga préféré : <?= \Rewritting::sanitize($message['manga']); ?><br/>
					Anime préféré : <?= \Rewritting::sanitize($message['anime']); ?><br/>
					Nombre de messages : <?= \Rewritting::sanitize($message['nb_messages']); ?><br/>
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
<?php if ($user != NULL && $user['grade'] != 1){ ?>
	<form method="POST" action="" id="formulaireMessage">
		<label>Contenu du message :</label>
		<textarea name="contenuMessage" class="form-control" placeholder="Saisir ici votre réponse au topic."></textarea>
		<input type="submit" name="validerMessage" class="btn btn-outline-info" value="Poster mon message">
	</form>
<?php } else { ?>
	<div class="alert alert-info">
		Vous devez être connecté à votre compte pour pouvoir poster un message, n'hésitez pas à le créer !
	</div>
<?php } ?>