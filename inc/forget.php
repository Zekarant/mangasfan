<?php 
session_start();
require_once 'base.php';
require_once 'functions.php';
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
  include('../theme_temporaire.php');
  ?>
  <!doctype html>
  <html lang="fr">
  <head>
    <meta charset="utf-8" />
    <title>Mangas'Fan - Mot de passe oublié</title>
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
    <link rel="stylesheet" type="text/css" href="../overlay.css" />
  </head>
  <body>
    <div id="bloc_page">
      <header>
       <div id="banniere_image">
         <div id="titre_site"><span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN</div>
         <div class="slogan_site">Votre référence Mangas</div>
         <?php include("../elements/navigation.php") ?>
         <h2 id="actu_moment"><?php echo $phrase_actu; ?>;</h2>
         <h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
         <div class="bouton_fofo"><a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a></div>
         <?php include('../elements/header.php'); ?>
       </div>
     </header>
     <section class="marge_page">
      <div id="titre_news">
        Mot de <span class="couleur_mangas">passe</span> <span class="couleur_fans">oublié</span>
      </div><br/>
  <?php 
  if (!empty($_POST['email'])){
    $req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if (isset($user['email'])) {
      $reset_token = str_random(60);
      $retour = $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?');
      $retour->execute([$reset_token, $user['id']]);
      $header="MIME-Version: 1.0\r\n";
      $header.='From:"Mangas\'Fan"<contact@mangasfan.fr>'."\n";
      $header.='Content-Type:text/html; charset="uft-8"'."\n";
      $header.='Content-Transfer-Encoding: 8bit';
      $title = "Réinitiatilisation de votre mot de passe Mangas'Fan";
      $message = "
      <html>
          <body>
            <div style='border: 2px solid black;'>
              <div align='center' style='background-image:linear-gradient(#BAC1C8, #474747);'>
                  <img src='https://zupimages.net/up/17/24/4kp2.png' style='width: 100%;'/>
                  <div style='font-family: Oswald; font-size: 23px; color: white;''>
                        Réinitiatilisation de votre mot de passe - <span style='color: #ff5980'>M</span>angas'<span style='color: #00daf9'>F</span>an
                      </div>
              <hr/>
              </div>
              <div style='padding: 5px;'>
      Bonjour,<br/><br/>
      Vous avez récemment demandé un nouveau de passe sur la plateforme Mangas'Fan pour les raisons suivantes :<br/>
      <ul>
        <li>Compte piraté.</li>
        <li>Mot de passe oublié.</i>
        <li>Changement de compte.</li>
        <li>Autre problème.</li>
      </ul>
      Afin de pouvoir réinitialiser votre mot de passe combien bon vous semble, voici un lien pour réinitialiser ce dernier :<br/><br/>
      Lien : https://mangasfan.fr/inc/reset.php?id={$user['id']}&token=$reset_token
      <br/><br/>
      En espérant que vous retrouverez l'usage de votre compte.<br/>
      En cas de problème, n'hésitez pas à envoyer un mail à : <b>contact@mangasfan.fr</b>.<br/><br/>

      Bonne visite sur Mangas'Fan !
      </div>
            <div align='center'>
              <div style='background-color: #333333; padding: 5px; border-top: 3px solid #DDDDDD; color: white; text-align: center;'>Mangas'Fan © 2017 - 2019. Développé par Zekarant, Nico et Lucryio. Tous droits réservés.
              </div>
            </div>
            </div>
          </body>
        </html>";
      mail($_POST['email'], $title, $message, $header);
      echo '<script>location.href="connexion.php";</script>';
      $_SESSION['flash']['success'] = "<div class='alert alert-success' role='alert'>Nous vous avons envoyé un Mail pour réinitaliser votre mot de passe, merci de suivre les étapes indiquées. Pensez à regarder dans vos spams.</div>";
    }
    else
    {
      echo "<div class='alert alert-danger' role='alert'>L'email que vous avez entré n'est pas enregistrée sur Mangas'Fan.</div>";
    }
  }
 ?>
      <form method="POST">
        <label>Mail : </label> <input type="email" class="form-control" name="email" placeholder="Entrez l'adresse mail de votre compte utilisée lors de l'inscription">
        <input type="submit" class="btn btn-info" value="Renvoyer un nouveau mot de passe">
      </form>
    </section>
    <div id="banniere_reseaux">
      <div id="twitter"><?php include('../elements/twitter.php') ?></div>
      <div id="facebook"><?php include('../elements/facebook.php') ?></div>
      <div id="discord"><?php include('../elements/discord.php') ?></div>
    </div>
    <?php include('../elements/footer.php') ?>
  </div>
</body>
</html>
