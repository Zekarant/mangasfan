<?php
session_start();
include('functions.php');
include('base.php');

// S'il est déjà connecté
if(isset($_SESSION['auth'])){
	header('Location: compte.php');
	exit();
}

// Préparation de la requête pour les mp
$envoie_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');

// Si le formulaire n'est pas vide et qu'il ne manque pas d'informations pour le champ username et mot de passe
if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){
	// On récupère l'utilisateur s'il a confirmé son compte
	$req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
	$req->execute(['username' => $_POST['username']]);
	$user = $req->fetch();

	// Si le pseudo est erroné, on affiche un message d'erreur
	if(!$user) $_SESSION['flash']['danger'] = "<div class='alert alert-danger' role='alert'>Identifiant ou mot de passe incorrect</div>";
	// On vérifie l'équivalence des mots de passe
	elseif(password_verify($_POST['password'], $user['password'])){
		// On gère le cas où le membre qui tente une connexion est en réalité un membre qui était bannis
		if ($user['grade'] <= 1){
			$_SESSION['auth'] = $user;
			// On supprime le bannisement si la date de fin est inférieur à celle du jour
			$supprime_ban = $pdo->prepare("DELETE FROM bannissemment WHERE id_membre = ? AND date_de_fin < ?");
			$supprime_ban->execute();

			// Si la date est bien inférieur alors on lui envoie un MP et on met à jour son grade
			if($supprime_ban->rowcount() > 0){
				$changement_grade = $pdo->prepare('UPDATE users SET grade = 2 WHERE id = ?')->execute(array($user['id']));
				$texte_deban = "
				<p>Cher membre de Mangas'Fan,<br/>
				Si vous recevez ce message privé, c'est que votre bannissement est arrivé à sa fin et que vous avez retrouvé un accès normal à votre compte.
				<br/>
				A l'avenir, nous vous demandons de bien vouloir respecter les règles de Mangas'Fan sous peine de recevoir un avertissement ou un nouveau bannissement qui peut être temporaire ou définitif !</p>
				<hr>
				<p>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
				~ L'équipe de Mangas'Fan</p>";
				$envoie_mp->execute(array($user['id'], "Votre compte a été débanni !", $texte_deban, time()));
				header('Location: compte.php');
			} else{
				header('Location: ../bannis.php');
				exit();
			}
		} else { // Les membres de bases
			$_SESSION['auth'] = $user;
            $_SESSION['flash']['success'] =  "<div class='alert alert-success' role='alert'>Vous êtes maintenant connecté à Mangas'Fan</div>";
            // Si l'utilisateur a cochée la case, on enregistre son pseudo dans les cookies
            if ($_POST['connexion_maintenue']){ 
            	setcookie('username', $user['id'], time() + 365*24*3600, "/", "www.mangasfan.fr", false, true);
   				setcookie('hash_pass', $user['password'], time() + 365*24*3600, "/", "www.mangasfan.fr", false, true);
            }

            // On récupère les informations liée aux badges du membre en question
            $verifier_badges = $pdo->prepare('SELECT * FROM badges_dons WHERE id_user = ?');
            $verifier_badges->execute(array($user['id']));

			// On empile dans un tableau tous les id des badges que le membre possède
			$liste_badge = array();
			while($verification_badges = $verifier_badges->fetch()) $liste_badge[] = $verification_badges['id_badge'];

			// On prépare notre requête permettant d'ajouter un badge
			$ajouter_badge = $pdo->prepare('INSERT INTO badges_dons(id_badge, id_user, attribued_at) VALUES (?, ?, NOW())');

			// Badge lorsqu'on se connecte pour la première fois sur le forum
			if (!in_array(1, $liste_badge)){
				$ajouter_badge->execute(array(1, $user['id']));
				$text_badge = "
				<p>Cher membre de Mangas'Fan,<br/>
				Félicitation ! Vous avez validé une première étape ! En effet, vous avez <strong>validé votre adresse Mail et donc votre inscription</strong>.
				Cette manipulation vous permet donc de débloquer le badge <strong>« L'inscrit » </strong>qui est maintenant disponible dans la rubrique « Mes badges ».<br/>
				Continuez de vous investir dans la vie du site !</p>
				<hr>
				<p>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
				~ L'équipe de Mangas'Fan</p>";
				$envoie_mp->execute(array($user['id'], "Vous avez obtenu un badge !", $text_badge, time()));	
			}

			// Badge lorsqu'on a remplis l'intégralité du profil
			if (!in_array(3, $liste_badge) && $user['avatar'] && $user['description'] && $user['manga'] && $user['anime'] && $user['date_anniv']) {
				$ajouter_badge->execute(array(3, $user['id']));
				$text_badge = "
				<p>Cher membre de Mangas'Fan,<br/>
				Félicitation ! Vous avez validé une nouvelle étape ! En effet, vous avez <strong>remplit l'intégralité de votre profil</strong>.
				Cette manipulation vous permet donc de débloquer le badge <strong>« Le profileur » </strong>qui est maintenant disponible dans la rubrique « Mes badges ».<br/>
				Continuez de vous investir dans la vie du site !</p>
				<hr>
				<p>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
				~ L'équipe de Mangas'Fan</p>";
				$envoie_mp->execute(array($user['id'], "Vous avez obtenu un badge !", $text_badge, time()));
			}
			header('Location: compte.php');
			exit();
		}
	} else { // Les informations sont erronées
		$_SESSION['flash']['danger'] = "<div class='alert alert-danger' role='alert'>Votre pseudo ou votre mot de passe est incorrect. Veuillez recommencer !</div>";
	}
}
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
			<?php include('../elements/header.php'); ?>
			<section class="marge_page">
				<?php include("../elements/messages.php"); ?>
				<h2 class="titre_principal_news">Connexion à mon compte</h2><br/>
				<form method="POST" action="">
					<div class="row">
						<div class="col-md-6">
							<label>Pseudo ou Mail : </label>
							<input name="username" class="form-control" type="text" placeholder="Entrez votre pseudo" required>
							<br/>
							<label>Mot de passe : </label>
							<input type="password" name="password" placeholder="Entrez votre mot de passe" class="form-control" />
							<br/>
							<div class="alert alert-info" role="alert">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="connexion_maintenue" name="connexion_maintenue">
								<label class="form-check-label" for="gridCheck1">
									Cocher la case pour rester connecté
								</label>
							</div>
						</div>
							<br/>
							<input type="submit" name="" class="btn btn-info" value="Connexion">
						</div><br/><br/>
						<div class="col-md-6">
							<div class="alert alert-info" role="alert">
							  <h4 class="alert-heading">Information importante !</h4>
							  <p>Chers membres,<br/>
							  	En cochant la case « Rester connecté », vous acceptez <strong>l'utilisation des cookies</strong> qui vous permettront de rester connecté au site sans avoir à retaper vos identifiants. Merci de noter que si vous ne voulez pas être connecté de façon constante au site, vous n'avez pas à cocher cette option.</p>
							  <hr>
							  <p>En cas de quelconque problème concernant les cookies, n'hésitez pas à envoyer un mail à l'équipe d'administration de Mangas'Fan : contact@mangasfan.fr</p>
							  <p>En cas de perte de mot de passe : <a href="forget.php" class="btn btn-sm btn-outline-warning">Mot de passe oublié</a></p>
							</div>
						</div>
					</div>
				</form>
			</section>
			<?php include('../elements/footer.php'); ?>
	</body>
	</html>
