<?php
	$user_id = $_GET['id'];
	$token = $_GET['token'];
	require 'base.php';
	$req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
	$req->execute([$user_id]);
	$user = $req->fetch();
	session_start();

if($user && $user['confirmation_token'] == $token ){
	    $enregistrement = $pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?');
	    $enregistrement->execute([$user_id]);
	    $credit_token = $pdo->prepare('UPDATE users SET points=points+50 WHERE username= ?');
      	$credit_token->execute(array($user['username']));
	    $_SESSION['flash']['success'] = "<div class='alert alert-success' role='alert'>Votre compte a bien été validé. Vous pouvez désormais vous connecter comme bon vous semble. De plus, pour vous souhaitez la bienvenue, vous avez obtenu 50 Mangas'Points ! Bonne visite sur Mangas'Fan !</div>";
	    $_SESSION['auth'] = $user;
	    header('Location: compte.php');
}
else
{
	    $_SESSION['flash']['danger'] = "<div class='alert alert-danger' role='alert'>Ce lien n'est plus valide, votre compte ne peut donc pas être validé !</div>";
	    header('Location: connexion.php');
}
