<?php
    try{
        $pdo = new PDO('mysql:host=localhost;dbname=mangasfans;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $erreur){
            die('Erreur : ' . $erreur->getMessage());
    }

    $session_active = (isset($_SESSION['auth'])) ? $_SESSION['auth'] : false;
    $recup_cookie_pseudo = (isset($_COOKIE['username'])) ? $_COOKIE['username'] : false;
    $recup_cookie_password = (isset($_COOKIE['hash_pass'])) ? $_COOKIE['hash_pass'] : false;
    
    // Si la session n'est pas ouverte et qu'il existe bien un cookie
    if(!$session_active && $recup_cookie_pseudo && $recup_cookie_password){
        $req = $pdo->prepare('SELECT * FROM users WHERE password = :password AND id= :username AND confirmed_at IS NOT NULL');
        $req->execute(['username' => $recup_cookie_pseudo, 'password' => $recup_cookie_password]);
        $utilisateur = $req->fetch();
        if($req->rowCount() > 0) $_SESSION['auth'] = $utilisateur;
    } elseif(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
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