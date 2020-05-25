<div class="d-flex justify-content-left">
	<a href="../<?= \Rewritting::sanitize($article['slug']) ?>" class="btn btn-sm btn-outline-info">Retourner sur "<?= \Rewritting::sanitize($article['titre']) ?>"</a>
	<?php if ($utilisateur['grade'] >= 5) { ?>
		<a href="../../staff/redaction/modification-mangas/<?= \Rewritting::sanitize($article['slug']) ?>" style="margin-left: 10px;" class="btn btn-sm btn-outline-info">Accéder à la rédaction de "<?= \Rewritting::sanitize($article['titre']) ?>"</a>
		<a href="../../staff/redaction/modification-mangas/<?= \Rewritting::sanitize($article['slug']) ?>/<?= \Rewritting::sanitize($article['slug_article']) ?>" style="margin-left: 10px;" class="btn btn-sm btn-outline-info">Modifier l'article "<?= \Rewritting::sanitize($article['name_article']) ?>"</a>
	<?php } ?>
</div>
<hr>
<h2 class="titre" id="title"><?= \Rewritting::sanitize($article['name_article']) ?> - <?= \Rewritting::sanitize($article['titre']) ?></h2>
<hr>
<?= htmlspecialchars_decode(htmlspecialchars_decode(\Rewritting::sanitize($article['contenu']))) ?>
<hr>
<div class="d-flex justify-content-center">
	<a href="../<?= \Rewritting::sanitize($article['slug']) ?>" class="btn btn-outline-info">Retourner sur "<?= \Rewritting::sanitize($article['titre']) ?>"</a>
	<a href="#title" class="btn btn-outline-info" style="margin-left: 20px;">Aller en haut de page</a>
</div>