<?php 
	require_once '../inc/base.php';
	$recup_id = $pdo->prepare("SELECT id FROM billets_jeux WHERE titre = ?");
	$recup_id->execute(array($_GET['titre']));
	$elt = $recup_id->fetch();

	echo $elt['id'];