<?php
try
{
	$pdo = new PDO('mysql:host=localhost;dbname=mangasfans;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch (Exception $erreur)
{
        die('Erreur : ' . $erreur->getMessage());
}

if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
$recuperer = $pdo->prepare('SELECT maintenance_site FROM maintenance');
$recuperer->execute();
$maintenance_site = $recuperer->fetch();
if ($maintenance_site['maintenance_site'] == 1 AND $utilisateur['grade'] < 3) {
   header('Location: https://www.mangasfan.fr/maintenance.php');
   exit();
}
?>