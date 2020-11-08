<h5>News concernée : <?= \Rewritting::sanitize($commentary['title']) ?></h5>
<form action="edit_comment.php?id=<?= \Rewritting::sanitize($commentary['id_commentary']) ?>&news=<?= \Rewritting::sanitize($commentary['id_news']) ?>" method="POST">
	<textarea class="form-control" name="commentaire"><?= \Rewritting::sanitize($commentary['commentary']) ?></textarea>
	<input type="submit" class="btn btn-outline-info" name="valider" value="Modifier mon commentaire">
</form>