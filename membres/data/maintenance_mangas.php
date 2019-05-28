<?php 
$recuperer = $pdo->prepare('SELECT maintenance_mangas FROM maintenance');
$recuperer->execute();
$maintenance_mangas = $recuperer->fetch();
if ($maintenance_mangas['maintenance_mangas'] == 1 AND $utilisateur['grade'] < 3) {
   header('Location: https://www.mangasfan.fr/maintenance.php');
   exit();
}

?>