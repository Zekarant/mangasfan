<?php
session_start();
if(isset($_GET['id']) && isset($_GET['token'])){
    require 'base.php';
    require 'functions.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
    $req->execute([$_GET['id'], $_GET['token']]);
    $user = $req->fetch();
    if($user){
		$user2 = $user['id'];
        if(!empty($_POST)){
            if(!empty($_POST['password']) && $_POST['password'] == $_POST['password_confirm']){
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
				    $reset = $pdo->prepare("UPDATE users SET password = ?, reset_at = 'NULL', reset_token = 'NULL' WHERE reset_token = ? AND id = ?");
            $reset->execute(array($password, $_GET['token'], $_GET['id']));

                $_SESSION['flash']['success'] = "<div class='alert alert-success' role='alert'>Votre mot de passe a bien été modifié ! Vous allez être redirigé.</div>";
                $_SESSION['auth'] = $user;
                header('Location: compte.php');
                exit();
            }
        }
    }else{

        $_SESSION['flash']['error'] = "<div class='alert alert-danger' role='alert'>Ce lien n'est pas valide !</div>";
        header('Location: connexion.php');
        exit();
    }
}else{
    header('Location: connexion.php');
    exit();
}
include('../theme_temporaire.php');
?>
<!doctype html>
<html lang="fr">
  <head>
      <meta charset="utf-8" />
      <title>Mangas'Fan - Réinitialiser son mot de passe</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <link rel="icon" href="../images/favicon.png"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
      <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="<?php echo $lienCss; ?>">
      <link rel="stylesheet" href="../overlay.css" />
  </head>
  <body>
    <div id="bloc_page">
    <header>
    	<div id="banniere_image">
    	<div id="titre_site"><span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN</div>
    	<div class="slogan_site">Votre référence Mangas</div>
            <?php include("../elements/navigation.php") ?>
    	<h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
    	<h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
    	<div class="bouton_fofo"><a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a></div>
       <?php include('../elements/header.php'); ?>
     </div>
   </header>
	 <section class="marge_page">
      <div id="titre_news">
          Réinitialiser son mot <span class="couleur_mangas">de</span> <span class="couleur_fans">passe</span>
      </div><br/>
<form action="" method="POST">

  <label for="">Mot de passe : </label>
  <input type="password" name="password" class="form-control" placeholder="Entrez votre nouveau mot de passe" /><br/><br/>

  <label for="">Confirmation du mot de passe : </label>
  <input type="password" name="password_confirm" class="form-control" placeholder="Confirmez votre nouveau mot de passe" />
  <button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-info-sign"></span> Réinitialiser mon mot de passe</button>

</form>
<div id="banniere_reseaux">
            <div id="twitter"><?php include('../elements/twitter.php') ?></div>
            <div id="facebook"><?php include('../elements/facebook.php') ?></div>
            <div id="discord"><?php include('../elements/discord.php') ?></div>
	        </div>
</section>
<?php include('../elements/footer.php') ?>
</div>
</body>
</html>