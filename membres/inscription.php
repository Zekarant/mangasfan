<?php
session_start();
include('base.php');
include('functions.php');
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
  $avatar_defaut = 'https://mangasfan.fr/membres/images/avatars/avatar_defaut.png';
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
  mail($_POST['email'], 'Confirmation de votre compte Mangas\'Fan', "Vous venez de valider le formulaire d'inscription sur Mangas'Fan, cependant, ce compte est n'est pas encore activé !<br /> Pour pouvoir profiter de ce dernier, vous devez l'activer via le lien ci-dessous. Une fois ceci fait, vous pourrez vous connecter avec l'identifiant et le mot de passe que vous avez entré lors de l'inscription !\n\nhttps://mangasfan.fr/membres/confirm.php?id=$user_id&token=$token");
     // On redirige l'utilisateur vers la page de login avec un message flash
  echo ' <script>location.href="connexion.php";</script> ';
  $_SESSION['flash']['success'] = "<div class='alert alert-success' role='alert'>Un email de confirmation vous a été envoyé afin de valider votre compte, veuillez vérifier vos spams aussi au cas où vous ne l'auriez pas reçu.</div>";
  exit;
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Mangas'Fan - Inscription</title>
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
    <div class="titre_principal_news">Formulaire d'inscription <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fans</span></div><br/>
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
      <div class='alert alert-info' role='alert'>
        En vous inscrivant sur Mangas'Fan, vous certifiez avoir prit connaissance des <a href="../mentions_legales.php" target="_blank"> mentions légales</a> du site.
      </div>
      <input class="btn btn-info" type="submit" value="M'inscrire">
    </form>
  </section>
  <?php include('../elements/footer.php'); ?>
</body>
</html>