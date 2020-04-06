<?php
session_start(); 
include('../membres/base.php');
include('../membres/functions.php'); 
include('../membres/data/maintenance_galeries.php');
if (!isset($_SESSION['auth'])) {
  header("Location: ../");
  exit();
}
if ($utilisateur['grade'] == 1){
  header("Location: ../bannis.php");
  exit();
}
$verification = $pdo->prepare('SELECT auteur, rappel FROM galerie WHERE id = ? ORDER BY id DESC');
$verification->execute(array($_GET['galerie']));
$verifier = $verification->fetch();
if (isset($_SESSION['auth']) && $utilisateur['id'] != $verifier['auteur']) {
  header("Location: ..");
  exit();
}
if (isset($_POST['supprimer_image'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] > 1 && $utilisateur['grade'] <= 10) {
      if ($utilisateur['id'] == $verifier['auteur']) {
        $supprimer_image = $pdo->prepare("DELETE FROM galerie WHERE id = ?");
        $supprimer_image->execute(array($_GET['galerie']));
        header('Location: administration_galerie.php');
        die();
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Galeries - Supprimer une image</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="icon" href="../images/favicon.png"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include('../elements/header.php'); ?>
  <section>
    <h1 class="titre_principal_news">Supprimer une image</h1>
    <hr>
    <div class='alert alert-warning' role='alert'>
      <strong>Attention :</strong> Lorsque vous cliquerez sur le bouton « Supprimer cette image », l'image sera immédiatement ! Si vous vous trouvez sur cette page c'est que vous avez déjà pour idée de supprimer cette image donc attention, toute suppression est définitive !
    </div>
    <form method="POST" action="">
      <input type="submit" name="supprimer_image" class="btn btn-outline-danger" value="Supprimer l'image">
    </form>
  </section>
  <?php include('../elements/footer.php'); ?>
</body>
</html>
