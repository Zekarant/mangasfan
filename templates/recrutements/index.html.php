<h2 class="titre">Index des recrutements</h2>
<hr>
<?php if(isset($_SESSION['auth']) && $utilisateur['grade'] >= 7){ ?>
	<div class="d-flex justify-content-center">
		<a href="gestion-recrutements.php" class="btn btn-outline-info">Gérer les recrutements</a>
	</div>
	<hr>
<?php } ?>
<div class="alert alert-info" type="alert">
	Nous sommes toujours à la recherche de nouvelles personnes pour nous aider de manière <strong>bénévole</strong> sur Mangas'Fan ! Retrouvez ci-dessous la liste des recrutements disponibles et venez postuler pour nous aider !
</div>
<div class="container-fluid">
	<div class="row justify-content-center">
<?php foreach ($recrutements as $recrutement) { ?>
	<div class="card card-recrutements mb-3" style="max-width: 20rem;">
		<div class="card-header text-white bg-<?= \Rewritting::sanitize($recrutement['color']) ?>">Recrutement - <?= \Rewritting::sanitize($recrutement['name_animation']) ?></div>
		<div class="card-body">
			<p class="card-text"><?= \Rewritting::sanitize($recrutement['description']) ?></p>
			<a href="recrutements-<?= \Rewritting::sanitize($recrutement['link']) ?>.php" class="btn btn-outline-info">Postuler pour le rôle</a>
		</div>
	</div>
<?php } ?>
</div>
</div>