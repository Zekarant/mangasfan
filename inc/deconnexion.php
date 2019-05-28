<?php
    session_start();
    require_once '../inc/base.php';
    if(isset($_SESSION['auth'])){ // Si tu es connecté on te déconnecte et on te redirige vers une page.
		$qeel = $pdo->query("SELECT * FROM users WHERE username = '".$_SESSION['auth']->username."'")->fetch();
		
		$update_rang_co = $pdo->prepare('UPDATE qeel SET connexion = ? WHERE membre = ?');
		$update_rang_co->execute(array(1,$qeel->username));

		session_destroy();
        // Supression des cookies de connexion automatique

        header('Location: ../index.php');
    }else{ // Dans le cas contraire on t'empêche d'accéder à cette page en te redirigeant vers la page que tu veux.
        header('Location: ../index.php');
 
    }
?>