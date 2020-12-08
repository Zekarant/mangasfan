<p>Votre localisation : <a href="../forum">Accueil du forum</a> -> <a href="voirforum.php?f=<?= \Rewritting::sanitize($topic['id_forum']) ?>"><?= \Rewritting::sanitize($topic['forum_name']) ?></a> -> <?= \Rewritting::sanitize($topic['topic_titre']) ?></p>
<hr>
<h2 class="titre"><?= \Rewritting::sanitize($topic['topic_titre']) ?> <?php if($topic['topic_locked'] == 1){ echo "- Topic verrouillé"; } ?></h2>
<hr>
<a href="#formulaireMessage" class="btn btn-outline-info">Répondre</a>
<?php if (isset($_SESSION['auth']) && $user['grade'] >= 7) { ?>
	<a href="supprimer_topic.php?t=<?= \Rewritting::sanitize($topic['id_topic']) ?>" class="btn btn-outline-danger">Supprimer le topic</a>
	<?php if ($topic['topic_locked'] == 1 && $topic['forum_locked'] == 0) { ?>
		<a href="status.php?topic=<?= \Rewritting::sanitize($topic['id_topic']) ?>&status=<?= \Rewritting::sanitize($topic['topic_locked']) ?>" onclick="return window.confirm(`Devérrouiller ce topic ?`)" class="btn btn-outline-success">Devérrouiller le topic</a>
	<?php } elseif ($topic['topic_locked'] == 0 && $topic['forum_locked'] == 0) { ?>
		<a href="status.php?topic=<?= \Rewritting::sanitize($topic['id_topic']) ?>&status=<?= \Rewritting::sanitize($topic['topic_locked']) ?>" onclick="return window.confirm(`Verrouiller ce topic ?`)" class="btn btn-outline-warning">Verrouiller le topic</a>
	<?php } ?>
	<hr>
	<form method="post" action="deplacer.php?t=<?= $topic['id_topic'] ?>">
		<select name="dest" class="form-control">               
			<?php foreach($forum as $forums): ?>
				<option value='<?= \Rewritting::sanitize($forums['forum_id']) ?>' id='<?= \Rewritting::sanitize($forums['forum_id']) ?>'><?= \Rewritting::sanitize($forums['forum_name']) ?></option>
			<?php endforeach; ?>
		</select>
		<input type="hidden" name="from" value='<?= $topic['id_forum'] ?>'>
		<input type="submit" class="btn btn-sm btn-outline-info" name="submit" value="Envoyer" />
	</form>
<?php } ?>
<br/><br/>
<?php foreach ($messages as $message): ?>
	<div class="card" id="<?= \Rewritting::sanitize($message['id_message']) ?>">
		<div class="card-header">
			<?= \Rewritting::sanitize($topic['topic_titre']) ?> - Posté le <?= Users::dateAnniversaire($message['date_created']) ?>
			<?php if (isset($_SESSION['auth']) && $user['id_user'] == $message['id_utilisateur_message']) { ?>
				<br/>
				<hr>
				<a href="editer.php?topic=<?= \Rewritting::sanitize($topic['id_topic']) ?>&message=<?= \Rewritting::sanitize($message['id_message']) ?>" class="btn btn-sm btn-outline-info">Modifier</a>
				<a href="supprimer.php?topic=<?= \Rewritting::sanitize($topic['id_topic']) ?>&message=<?= \Rewritting::sanitize($message['id_message']) ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer ce message ?!`)" class="btn btn-sm btn-outline-danger">Supprimer</a>
			<?php } elseif ((isset($_SESSION['auth']) && $user['id_user'] == $message['id_utilisateur_message']) || isset($_SESSION['auth']) && $user['grade'] >= 7) { ?>
				<br/>
				<hr>
				<a href="supprimer.php?topic=<?= \Rewritting::sanitize($topic['id_topic']) ?>&message=<?= \Rewritting::sanitize($message['id_message']) ?>" onclick="return window.confirm(`Êtes vous sur de vouloir supprimer ce message ?!`)" class="btn btn-sm btn-outline-danger">Supprimer</a>
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
<?php if (isset($_SESSION['auth']) && $user['grade'] != 0) {
	if ($topic['topic_locked'] == 1 && $user['grade'] >= 2) { ?>
		<form method="POST" action="" id="formulaireMessage">
			<label>Contenu du message :</label>
			<textarea name="contenuMessage" class="form-control" placeholder="Saisir ici votre réponse au topic."></textarea>
			<input type="submit" name="validerMessage" class="btn btn-outline-info" value="Poster mon message">
		</form> 
	<?php } else { ?>
		<div class="alert alert-info">
			Ce topic est verrouillé, vous ne pouvez pas y répondre !
		</div>
	<?php }
} else { ?>
	<div class="alert alert-info">
		Vous devez être connecté à votre compte pour pouvoir poster un message, n'hésitez pas à le créer !
	</div>
	<?php } ?>