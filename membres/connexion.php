<?php
session_start();
include('functions.php');
include('base.php');
$req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
if(isset($_POST['username'])){
	$req->execute(['username' => $_POST['username']]); }
	$user = $req->fetch();
	if(isset($_SESSION['auth'])){
		header('Location: compte.php');
		exit();
	}
	if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){

		if($user == null){
			$_SESSION['flash']['danger'] = "<div class='alert alert-danger' role='alert'>Identifiant ou mot de passe incorrect</div>";
		}
		elseif(password_verify($_POST['password'], $user['password']))
		{
			if ($user['grade'] <= 1)
			{
				$recuperer_bannissement = $pdo->prepare('SELECT date_de_fin, id_membre FROM bannissement WHERE id_membre = ?');
				$recuperer_bannissement->execute(array($user['id']));
				$fin_de_bannissement = $recuperer_bannissement->fetch();
				if (date('Y-m-d') >= $fin_de_bannissement['date_de_fin']) {
					$changer_grade = $pdo->prepare('UPDATE users SET grade = 2 WHERE id = ?');
					$changer_grade->execute(array($user['id']));
					$effacer_bannissement = $pdo->prepare('DELETE FROM bannissement WHERE id_membre = ?');
					$effacer_bannissement->execute(array($user['id']));
					$texte_deban = "
					<p>Cher membre de Mangas'Fan,<br/>
					Si vous recevez ce message privé, c'est que votre bannissement est arrivé à sa fin et que vous avez retrouvé un accès normal à votre compte.
					<br/>
					A l'avenir, nous vous demandons de bien vouloir respecter les règles de Mangas'Fan sous peine de recevoir un avertissement ou un nouveau bannissement qui peut être temporaire ou définitif !</p>
					<hr>
					<p>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
					~ L'équipe de Mangas'Fan</p>";
					$premier_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
					$premier_mp->execute(array($user['id'], "Votre compte a été débanni !", $texte_deban, time()));
					header('Location: compte.php');
					$_SESSION['auth'] = $user;
				}
				else
				{
					$_SESSION['auth'] = $user;
					header('Location: ../bannis.php');
					exit();
				}
			}
			else
			{
				$_SESSION['auth'] = $user;
				$_SESSION['flash']['success'] =  "<div class='alert alert-success' role='alert'>Vous êtes maintenant connecté à Mangas'Fan</div>";
				header('Location: compte.php');
				exit();
			}}
			else
			{
				$_SESSION['flash']['danger'] = "<div class='alert alert-danger' role='alert'>Votre pseudo ou votre mot de passe est incorrect. Veuillez recommencer !</div>";
			}
		}
// include('../theme_temporaire.php');
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Mangas'Fan - Connexion</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link rel="icon" href="../images/favicon.png"/>
			<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
			<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
			<link rel="stylesheet" type="text/css" href="../style.css">
		</head>
		<body>
			<div id="bloc_page">
				<?php include('../elements/header.php'); ?>
				<section class="marge_page">
					<?php include("../elements/messages.php"); ?>
					<h2 class="titre_principal_news">Connexion à mon compte</h2><br/>
					<form method="POST" action="">
						<div class="row">
							<div class="col-md-6">
								<label>Pseudo ou Mail : </label>
								<input name="username" class="form-control" type="text" placeholder="Entrer votre pseudo" required>
								<br/>
							</div>
							<div class="col-md-6">
								<label>Mot de passe : </label>
								<input type="password" name="password" placeholder="Entrer votre mot de passe" class="form-control" />
								<input type="submit" name="" class="btn btn-info" value="Connexion">
							</div>
						</div>
					</form><br/>
					<div style="color: black!important;">Vous avez oublié votre mot de passe ? N'hésitez pas à le récupérer <a href="forget.php">ici</a></div>
				</section>
				<?php include('../elements/footer.php'); ?>
			</div>
		</body>
		</html>
