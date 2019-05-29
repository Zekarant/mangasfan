<?php 
session_start();
include('base.php');
include('functions.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
  $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $user->execute(array($_SESSION['auth']['id']));
  $utilisateur = $user->fetch(); 
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Mangas'Fan - Mot de passe oublié</title>
  <link rel="icon" href="../images/favicon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
  <div id="bloc_page">
    <?php include('../elements/header.php'); ?>
   <section class="marge_page">
    <div class="titre_principal_news">
      Mot de passe oublié
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
        <div style='background-color: #333333; padding: 5px; border-top: 3px solid #DDDDDD; color: white; text-align: center;'>Mangas'Fan © 2017 - 2019. Développé par Zekarant et Nico. Tous droits réservés.
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
  <?php include('../elements/footer.php') ?>
</div>
</body>
</html>
