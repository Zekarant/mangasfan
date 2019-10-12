<?php
header('Content-Type: application/json');
include('membres/base.php');
$discord = $pdo->prepare('SELECT username, avatar, description, anime, manga, pseudo_discord, id_discord FROM users WHERE pseudo_discord = ? AND id_discord = ?');
$discord->execute(array($_GET['name'], $_GET['id']));
if (isset($_GET['name']) AND isset($_GET['id'])) {
	while($results = $discord->fetch(PDO::FETCH_ASSOC)){
		$result = $results;
	}
	echo json_encode($result);
} else {
	echo "Erreur : Ce membre n'existe pas, ou ce compte n'est pas relié à Discord !";
}
?>