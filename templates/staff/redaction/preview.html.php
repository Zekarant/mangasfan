<div class='alert alert-primary' role='alert'>
	<strong>Attention : </strong> Comme cette page est la même que celle de la rédaction, que seule l'interface a changé, utilisez la flèche de retour arrière du navigateur pour éditer votre article. <strong>Rien de votre article ne sera perdu en faisant retour.</strong>
</div>
<hr>
<h2 class="titre"><?= \Rewritting::sanitize($titre) ?></h2>
<hr>
<?= htmlspecialchars_decode(\Rewritting::sanitize($contenu)) ?>