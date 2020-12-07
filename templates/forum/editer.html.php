<p><em>Votre localisation : <a href="../forum">Accueil du forum</a> -> <a href="voirforum.php?f=<?= \Rewritting::sanitize($topic['forum_id']) ?>"><?= \Rewritting::sanitize($topic['forum_name']) ?></a> -> <a href="voirtopic.php?t=<?= \Rewritting::sanitize($topic['id_topic']) ?>"><?= \Rewritting::sanitize($topic['topic_titre']) ?></a> -> Edition de mon message</em></p>
<hr>
<h2 class="titre">Editer mon message</h2>
<hr>
<form method="POST" action="">
	<label>Contenu de mon message :</label>
	<textarea name="newContenu"><?= \Rewritting::sanitize($topic['contenu']) ?></textarea>
	<input type="submit" name="validerNewMessage" class="btn btn-outline-info">
</form>