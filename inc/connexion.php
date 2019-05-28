<?php
session_start();

	require_once 'functions.php';
	require_once 'base.php';
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

		//on l'ajoute dans les connectés du QEEL
		$temps_actuel = date("U"); //timestamp (en secondes)
		$ip_user = $_SERVER['REMOTE_ADDR']; //affiche IP user

		$membre_exist = $pdo->prepare('SELECT * FROM qeel WHERE membre = ?');
		$membre_exist->execute(array($user['username']));
		$membre_existe = $membre_exist->rowCount();


		if ($membre_existe == 0){ // le membre n'est pas encore dans la BDD du qeel
			$ip_exist = $pdo->prepare('SELECT * FROM qeel WHERE ip_user = ?');
			$ip_exist->execute(array($ip_user));
			$ip_existe = $ip_exist->rowCount();

			if($ip_existe == 0){ // si l'user n'est pas dans la liste
				$add_ip = $pdo->prepare('INSERT INTO qeel(membre,membre_id,ip_user,time_co,connexion) VALUES(?,?,?,?,0)');
				$add_ip->execute(array($user['username'],$user['id'],$ip_user,$temps_actuel));
			} else { // si l'user est dans la liste, alors on passe a une autre vérification
				$ip_identique = $pdo->prepare('SELECT * FROM qeel WHERE ip_user = ? AND membre is NOT NULL');
				$ip_identique->execute(array($ip_user));
				$ip_identique_non_invite = $ip_identique->rowCount();
				if ($ip_identique_non_invite == 0){ // dans ce cas, l'adresse ip correspond à un invité, on mets donc à jour
					$update_ip = $pdo->prepare('UPDATE qeel SET membre = ?,membre_id = ?,time_co = ?, connexion = ? WHERE ip_user = ?');
					$update_ip->execute(array($user['username'],$user['id'],$temps_actuel,0,$ip_user));
				} else { // dans l'autre cas, un membre qui s'est co (ou anciennement co) possède la même ip (admettons des personnes de la même famille), on ne veut pas l'écraser, on ajoute un deuxième membre avec cette ip
					$add_ip = $pdo->prepare('INSERT INTO qeel(membre,membre_id,ip_user,time_co,connexion) VALUES(?,?,?,?,0)');
					$add_ip->execute(array($user['username'],$user['id'],$ip_user,$temps_actuel));
				}
			}
		} else { // le membre est déjà dans le QEEL, on le modifie seulement
			$update_membre = $pdo->prepare('UPDATE qeel SET time_co = ?, connexion = ?,ip_user = ? WHERE membre = ? AND membre is NOT NULL');
			$update_membre->execute(array($temps_actuel,0,$ip_user,$user['username']));

			// on supprime l'ip de l'invité
			$supprime_ip_invite = $pdo->prepare("DELETE FROM qeel WHERE membre = ? AND ip_user = ?");
			$supprime_ip_invite->execute(array(null,$ip_user));
		}

		header('Location: compte.php');
		exit();
	}}
	else
	{
		$_SESSION['flash']['danger'] = "<div class='alert alert-danger' role='alert'>Votre pseudo ou votre mot de passe est incorrect. Veuillez recommencer !</div>";
	}
}
include('../theme_temporaire.php');
?>
<!doctype html>
<html lang="fr">
	<head>
		<title>Mangas'Fan - Connexion</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="icon" href="../images/favicon.png"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
		<script src='http://use.edgefonts.net/nosifer.js'></script>
        <script src='http://use.edgefonts.net/emilys-candy.js'></script>
        <script src='http://use.edgefonts.net/butcherman.js'></script>
	  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  	<link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>">
	  	<link rel="stylesheet" type="text/css" href="../overlay.css">
	</head>
	<body>
		<div id="bloc_page">
		<header>
	<div id="banniere_image">
	<div id="titre_site">
            <span class="couleur_mangas"><?php echo $titre_1; ?></span><?php echo $titre_2; ?><span class="couleur_fans">F</span>AN
          </div>
          <div class="slogan_site"><?php echo $slogan; ?></div>
        <?php include("../elements/navigation.php") ?>
	<h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
	<h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
	<div class="bouton_fofo"><a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a></div>
   <?php include('../elements/header.php'); ?>
</div>
</header>
		<section class="marge_page">
			<?php include("../elements/messages.php"); ?>
			<div id="titre_news">Connexion à <span class="couleur_mangas">mon</span><span class="couleur_fans"> compte</span></div><br/>
			<form method="POST" action="" class="contact-form row">
     			<div class="col-md-6">
					<label for="exampleInputEmail1">Pseudo ou Mail : </label>
					<input id="name" name="username" class="input-text js-input" type="text" required>
					<br/>
      			</div>
  				<div class="col-md-6">
					<label for="exampleInputPassword1">Mot de passe : </label>
					<input type="password" name="password" class="input-text js-input" id="exampleInputPassword1" />
				</div>
				<span class="envoi_contact">
				<button class="btn btn-success" type="submit">Je me connecte</button>
			</span>
			</form><br/>
			<div style="color: black!important;">Vous avez oublié votre mot de passe ? N'hésitez pas à le récupérer <a href="forget.php">ici</a></div>
			</section>
		<div id="banniere_reseaux">
            <div id="twitter"><?php include('../elements/twitter.php') ?></div>
            <div id="facebook"><?php include('../elements/facebook.php') ?></div>
            <div id="discord"><?php include('../elements/discord.php') ?></div>
	        </div>
			<?php include('../elements/footer.php'); ?>
		</div>
	</body>
</html>
