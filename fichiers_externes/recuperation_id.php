<?php 
	require_once '../membres/base.php';
	$recup_id = $pdo->prepare("SELECT id FROM billets_jeux WHERE titre = ?");
	$recup_id->execute(array($_GET['titre']));
	$elt = $recup_id->fetch();

	echo $elt['id'];