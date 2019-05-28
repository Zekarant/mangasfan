<?php
require_once 'functions.php';
require_once 'base.php';
require_once 'bbcode.php';
session_start();
if(!empty($_POST)){
  $errors = array();


  if(empty($_POST['username']) OR !preg_match('/^[-a-zA-Z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+$/', $_POST['username'])){
   $errors['username'] = "Vous n'avez entré aucun pseudo ou alors il contient un caractère non autorisé. (Accents,  ect...) Merci de recommencer !";
 }
 else {
  $req = $pdo->prepare('SELECT id FROM users WHERE username = ?');
  $req->execute([$_POST['username']]);
  $user = $req->fetch();
  if ($user) {
    $errors['username'] = "Ce pseudo est déjà utilisé par un autre membre !";
  }
}

if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
  $errors['email'] = "Vous n'avez pas indiqué d'adresse Mail !";
} 
else {
 $req = $pdo->prepare('SELECT id FROM users WHERE email = ?');
 $req->execute([$_POST['email']]);
 $user = $req->fetch();
 if ($user) {
   $errors['email'] = "Ce mail est déjà utilisé par un autre membre !";
 }
}

if(empty($_POST['password']) || $_POST['password'] !=$_POST['password_confirm']){
  $errors['password'] = "Les deux mots de passe ne correpondent pas !";
}

if(empty($errors)){
  $avatar_defaut = 'https://mangasfan.fr/inc/images/avatars/avatar_defaut.png';
  $grade = '2';
     // On enregistre les informations dans la base de données
  $req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?, avatar = ?, grade = ?");
     // On ne sauvegardera pas le mot de passe en clair dans la base mais plutôt un hash
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
     // On génère le token qui servira à la validation du compte
  $token = str_random(60);
  $req->execute([$_POST['username'], $password, $_POST['email'], $token, $avatar_defaut, $grade]);
  $user_id = $pdo->lastInsertId();
     // On envoit l'email de confirmation
  mail($_POST['email'], 'Confirmation de votre compte Mangas\'Fan', "Vous venez de valider le formulaire d'inscription sur Mangas'Fan, cependant, ce compte est n'est pas encore activé !<br /> Pour pouvoir profiter de ce dernier, vous devez l'activer via le lien ci-dessous. Une fois ceci fait, vous pourrez vous connecter avec l'identifiant et le mot de passe que vous avez entré lors de l'inscription !\n\nhttps://mangasfan.fr/inc/confirm.php?id=$user_id&token=$token");
     // On redirige l'utilisateur vers la page de login avec un message flash
  echo ' <script>location.href="connexion.php";</script> ';
  $_SESSION['flash']['success'] = "<div class='alert alert-success' role='alert'>Un email de confirmation vous a été envoyé afin de valider votre compte, veuillez vérifier vos spams aussi au cas où vous ne l'auriez pas reçu.</div>";
  exit;
}
}
include('../theme_temporaire.php');
?>
<!doctype html>
<html lang="fr">
<head>
 <title>Mangas'Fan - Inscription</title>
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
    <?php require_once ('bbcode.php'); ?>
    <div id="titre_news">Formulaire d'inscription <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fans</span></div><br/>
    <?php
    if (!empty($errors)): ?>
      <div class='alert alert-warning' role='alert'>
        <center><b><h4>Oups ! Il semble y avoir des erreurs !</h4></b></center>
        <p>Vous n'avez pas rempli le formulaire correctement ! Voici les erreurs constatées :<br/></p><br/>
        <ul><?php foreach($errors as $error): ?>
        <li><?= $error; ?></li>
        <?php endforeach; ?></ul>
        </div>

    <?php endif; ?>

    <form action="" method="POST">
      <label for="">Pseudo : </label>
      <input type="text" name="username" class="form-control" placeholder="Entrez un pseudo" required /><br/>
      <label for="">Email : </label>
      <input type="text" name="email" class="form-control" placeholder="Entrez une adresse Mail valide" required /><br/>
      <label for="">Mot de passe : </label>
      <input type="password" name="password" class="form-control" placeholder="Saisir votre mot de passe" /><br/>
      <label for="">Confirmation du mot de passe  : </label>
      <input type="password" name="password_confirm" class="form-control" placeholder="Confirmer votre mot de passe" /><br/>

      <button class="btn btn-success" type="submit"><span class="glyphicon glyphicon-ok-sign"></span> Je valide le formulaire et souhaite m'inscrire</button>

    </form>
  </section>
  <div id="banniere_reseaux">
    <div id="twitter"><?php include('../elements/twitter.php') ?></div>
    <div id="facebook"><?php include('../elements/facebook.php') ?></div>
    <div id="discord"><?php include('../elements/discord.php') ?></div>
  </div>
  <?php include('../elements/footer.php') ?>
</body>
</html>