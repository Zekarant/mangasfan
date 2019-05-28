<?php 
require_once '../inc/base.php';

$term = $_GET['term'];

$requete = $pdo->prepare('SELECT * FROM billets_jeux_pages WHERE nom LIKE :term AND jeux_id = 3'); // j'effectue ma requête SQL grâce au mot-clé LIKE
$requete->execute(array('term' => '%'.$term.'%'));

$array = array(); // on créé le tableau

while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données
{
	if{
		"ok"
	}
array_push($array, $donnee['nom']); // et on ajoute celles-ci à notre tableau
}

echo json_encode($array); // il n'y a plus qu'à convertir en JSON]