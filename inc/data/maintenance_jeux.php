<?php 
$recuperer = $pdo->prepare('SELECT maintenance_jeux FROM maintenance');
$recuperer->execute();
$maintenance_jeux = $recuperer->fetch();
if ($maintenance_jeux['maintenance_jeux'] == 1 AND $utilisateur['grade'] < 3) {
   header('Location: https://www.mangasfan.fr/maintenance.php');
   exit();
}
?>