<?php
    session_start();
    require_once '../membres/base.php';
    // Si tu es connecté on te déconnecte et on te redirige vers une page.
    if(isset($_SESSION['auth'])){ 
        setcookie('username', '', time() - 365*24*3600, "/", "www.mangasfan.fr", false, true);
    	setcookie('hash_pass', '', time() - 365*24*3600, "/", "www.mangasfan.fr", false, true);
		session_destroy();
        // Supression des cookies de connexion automatique
        header('Location: ../index.php');
    } else { // Dans le cas contraire on t'empêche d'accéder à cette page en te redirigeant vers la page que tu veux.
        header('Location: ../index.php');
    }
?>