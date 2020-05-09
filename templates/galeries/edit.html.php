<h5>Galerie concernée : <?= \Rewritting::sanitize($commentary['title_image']) ?></h5>
<form action="edit_comment.php?id=<?= \Rewritting::sanitize($commentary['id_commentary_galery']) ?>&news=<?= \Rewritting::sanitize($commentary['id_image']) ?>" method="POST">
	<textarea class="form-control" name="commentaire"><?= \Rewritting::sanitize($commentary['galery_commentary']) ?></textarea>
	<input type="submit" class="btn btn-outline-info" name="valider" value="Modifier mon commentaire">
</form>