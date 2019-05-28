<?php 
$recuperer = $pdo->prepare('SELECT maintenance_site, maintenance_galeries FROM maintenance');
$recuperer->execute();
$maintenance_galeries = $recuperer->fetch();
if ($maintenance_galeries['maintenance_site'] == 1 && $maintenance_galeries['maintenance_galeries'] == 1 AND $utilisateur['grade'] < 3) {
   header('Location: ../maintenance.php');
   exit();
} elseif ($maintenance_galeries['maintenance_site'] == 0 && $maintenance_galeries['maintenance_galeries'] == 1 AND $utilisateur['grade'] < 3) {
	header('Location: ../maintenance.php');
    exit();
}


?>