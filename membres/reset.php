<?php
session_start();
include('base.php');
include('functions.php');
if(isset($_GET['id']) && isset($_GET['token'])){
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
        setcookie('username', $user['id'], time() + 365*24*3600, null, null, false, true);
        setcookie('hash_pass', $user['password'], time() + 365*24*3600, null, null, false, true);
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
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Mangas'Fan - Réinitialiser son mot de passe</title>
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
  <link rel="stylesheet" href="../style.css" />
</head>
<body>
    <?php include('../elements/header.php'); ?>
   <section class="marge_page">
    <div class="titre_principal_news">
      Réinitialiser son mot de passe
    </div><br/>
    <form action="" method="POST">
      <label>Mot de passe :</label>
      <input type="password" name="password" class="form-control" placeholder="Entrez votre nouveau mot de passe" /><br/><br/>

      <label>Confirmation du mot de passe : </label>
      <input type="password" name="password_confirm" class="form-control" placeholder="Confirmez votre nouveau mot de passe" />
      <button class="btn btn-info" type="submit"><span class="glyphicon glyphicon-info-sign"></span> Réinitialiser mon mot de passe</button>
    </form>
  </section>
  <?php include('../elements/footer.php'); ?>
</body>
</html>