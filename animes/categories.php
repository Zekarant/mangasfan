<?php

session_start();

require_once('../libraries/autoload.php');
$controller = new \controllers\Animes();
$categories = $controller->categories();

foreach ($categories as $categorie){ ?>
	<div class="pit_mess bloc_page">
		<a href="<?= \Rewritting::sanitize($categorie['slug'])?>/<?= \Rewritting::sanitize($categorie['slug_article']) ?>" class="titre_pit_mess"><?= $categorie['name_article'] . "<br/>"; ?></a>
		<span class="date_time_post_page text-right"><?= \Users::dateAnniversaire($categorie['date_post']) ?> par <?= $categorie['username']; ?></span>
	</div>
<?php }

