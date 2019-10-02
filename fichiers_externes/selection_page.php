<?php
	require_once '../membres/base.php';
	$id_jeu = $_GET['id_jeu'];
	$name_page = $_GET['name_page'];

	$recherche = $pdo->prepare("SELECT id FROM billets_jeux_pages WHERE nom = ? LIMIT 1");
	$recherche->execute(array($name_page));

	if($recherche->rowCount() > 0){
		$recherches = $recherche->fetch();
		echo $recherches['id'];
	}
