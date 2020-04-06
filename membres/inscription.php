<?php 
session_start();
include('base.php');
include('functions.php');
if (isset($_POST['validation'])) {
  $errors = array();
  if(empty($_POST['username']) OR !preg_match('/^[-a-zA-Z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+$/', $_POST['username'])){
    $errors[] = "Vous n'avez rentré aucun pseudonyme, veuillez recommencer.";
  } else {
    $req = $pdo->prepare('SELECT id, username FROM users WHERE username = ?');
    $req->execute([$_POST['username']]);
    $user = $req->fetch();
    if ($user['username'] == $_POST['username']){
      $errors[] = "Ce pseudonyme est déjà utilisé par un autre membre !";
    }
  }
  if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $errors[] = "Vous n'avez pas indiqué d'adresse email ou alors elle est invalide !";
  } else {
    $req = $pdo->prepare('SELECT id, email FROM users WHERE email = ?');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if ($user['email'] == $_POST['email']) {
     $errors[] = "Ce mail est déjà utilisé par un autre membre !";
   }
 }
 if(empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
  $errors[] = "Les deux mots de passe ne correpondent pas !";
}
if ($_POST['password'] == $_POST['password_confirm'] AND strlen($_POST['password']) < 8) {
  $errors[] = "Le mot de passe est trop court ! (Minimum 8 caractères)";
} 
if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $_POST['password'])) {
  $errors[] = "Le mot de passe doit contenir une majuscule et un chiffre !";
}
if(empty($errors) AND isset($_POST['validation'])){
  $url = "https://www.google.com/recaptcha/api/siteverify";
  $data = [
    'secret' => "6Ler5L8UAAAAAHXjiORtJoD2_vdZmgJq50AkDs5x",
    'response' => $_POST['token'],
  ];
  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data)
    )
  );
  $context  = stream_context_create($options);
  $response = file_get_contents($url, false, $context);
  $res = json_decode($response, true);
  if($res['success'] == true) {
    $avatar_defaut = 'https://mangasfan.fr/membres/images/avatars/avatar_defaut.png';
    $req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?, avatar = ?, grade = 2");
     // On ne sauvegardera pas le mot de passe en clair dans la base mais plutôt un hash
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
     // On génère le token qui servira à la validation du compte
    $token = str_random(60);
    $req->execute(array($_POST['username'], $password, $_POST['email'], $token, $avatar_defaut));
    $user_id = $pdo->lastInsertId();
    mail($_POST['email'], 'Confirmation de votre inscription sur Mangas\'Fan', "Vous venez de valider le formulaire d'inscription sur Mangas'Fan, cependant, ce compte est n'est pas encore activé !<br /> Pour pouvoir profiter de ce dernier, vous devez l'activer via le lien ci-dessous. Une fois ceci fait, vous pourrez vous connecter avec l'identifiant et le mot de passe que vous avez entré lors de l'inscription !\n\nhttps://mangasfan.fr/membres/confirm.php?id=$user_id&token=$token");
     // On redirige l'utilisateur vers la page de login avec un message flash
    echo ' <script>location.href="connexion.php";</script> ';
    $_SESSION['flash']['success'] = "<div class='alert alert-success' role='alert'>Un email de confirmation vous a été envoyé afin de valider votre compte, veuillez vérifier vos spams aussi au cas où vous ne l'auriez pas reçu.</div>";
    exit;
  } else { ?>
    <div class="alert alert-warning">
      <strong>Erreur !</strong> Veuillez recommencer.
    </div>
  <?php }
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>S'inscrire sur le site - Mangas'Fan</title>
  <link rel="icon" href="../images/favicon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=6Ler5L8UAAAAAGKWMuRQupAzeTSOSB6ivtFi1WV4"></script>
  <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
  <?php include('../elements/header.php'); ?>
  <section>
    <h1 class="titre_principal_news">Formulaire d'inscription à Mangas'Fan</h1>
    <hr>
    <div class='alert alert-info' role='alert'>
      Merci de noter que pour vous inscrire sur Mangas'Fan, votre mot de passe doit comporter une majuscule et un chiffre minimum !
    </div>
    <?php if(!empty($errors)): ?>
      <div class='alert alert-warning' role='alert'>
        <h4>Oups ! On a un problème chef...</h4>
        <hr>
        <p>On a un petit problème chef, il semblerait que vous ayez oublié les détails suivants :</p>
        <ul><?php foreach($errors as $error): ?>
        <li><?= $error; ?></li>
        <?php endforeach; ?></ul>
      </div>
    <?php endif; ?>
    <div class="container">
      <form method="POST" action="">
        <div class="row">
          <div class="col-md-6">
            <label>Pseudonyme :</label>
            <input type="text" name="username" class="form-control" placeholder="Saisissez votre pseudo">
            <br/>
            <label>Mot de passe :</label>
            <input type="password" name="password" class="form-control" placeholder="Saisissez votre mot de passe">
            <br/>
          </div>
          <div class="col-md-6">
            <label>Adresse Mail :</label>
            <input type="email" name="email" class="form-control" placeholder="Saisissez votre adresse mail" />
            <br/>
            <label>Confirmation du mot de passe :</label>
            <input type="password" name="password_confirm" class="form-control" placeholder="Retapez votre mot de passe" />
            <br/>
          </div>
          <div class="alert alert-info" role="alert">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
              <label class="form-check-label" for="invalidCheck2">
                En cochant cette case, vous acceptez les <a href="../mentions_legales.php" target="_blank">Conditions Générales d'Utilisation</a> de Mangas'Fan. Vous risquez une suspension en cas de non-respect des règles.
              </label>
            </div>
          </div>
          <input type="submit" name="validation" class="btn btn-info" value="Créer mon compte sur Mangas'Fan">
          <input type="hidden" id="token" name="token">
        </div>
      </form>
      <script>
        grecaptcha.ready(function() {
          grecaptcha.execute('6Ler5L8UAAAAAGKWMuRQupAzeTSOSB6ivtFi1WV4', {action: 'homepage'}).then(function(token) {
           document.getElementById("token").value = token;
         });
        });
      </script>
    </div>
  </section>
  <?php include('../elements/footer.php'); ?>
</body>
</html>