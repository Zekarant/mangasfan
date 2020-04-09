<h3>Modifier votre commentaire sur la news : </h3>
<h5>News concernée : <?= $commentary['title'] ?></h5>
<form action="edit_comment.php?id=<?= $commentary['id_commentary'] ?>&news=<?= $commentary['id_news'] ?>" method="POST">
	<textarea class="form-control" name="commentaire"><?= $commentary['commentary'] ?></textarea>
	<input type="submit" class="btn btn-outline-info" name="valider" value="Modifier mon commentaire">
</form>