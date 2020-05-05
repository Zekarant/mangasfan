<h2 class="titre"><?= \Rewritting::sanitize($galerie['title_image']) ?> par <?= \Rewritting::sanitize($galerie['username']) ?></h2>
<hr>
<?php if(isset($_SESSION['auth']) AND $utilisateur['id_user'] != $galerie['auteur_image']){ ?>
	<div class="d-flex justify-content-center">
		<a href="modifier.php?galerie=<?= \Rewritting::sanitize($galerie['id_image']); ?>" class="btn btn-primary btn-sm">Modifier la description de l'image ou le contenu</a>
		<a href="supprimer.php?galerie=<?= \Rewritting::sanitize($galerie['id_image']); ?>" class="btn btn-danger btn-sm">Supprimer l'image de la galerie</a>
	</div>
	<hr>
<?php } if($utilisateur['grade'] >= 7) { ?>
	<div class="d-flex justify-content-center">
		<form method="POST" action="">
			<input type="submit" name="valider_rappel" onclick="return window.confirm(`Voulez-vous suspendre cette image des galeries en envoyant un rappel ?`)" class="btn btn-sm btn-warning" value="Envoyer un rappel pour cette image">
		</form>
	</div>
	<hr>
<?php } ?>
<img src="../galeries/images/<?= \Rewritting::sanitize($galerie['filename']) ?>" class="img-fluid text-center" alt="<?= \Rewritting::sanitize($galerie['title_image']); ?> - <?= \Rewritting::sanitize($galerie['username']); ?>">
