<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
if (!isset($_SESSION['auth'])) {
  header('Location: ../erreurs/erreur_403.php');
  exit();
}
if (isset($_SESSION['auth']) && $utilisateur['grade'] != 6 && $utilisateur['grade'] <= 7) {
  header('Location: ../erreurs/erreur_403.php');
  exit();
}
$selectionner_mangas = $pdo->prepare('SELECT * FROM billets_mangas WHERE id = ?');
$selectionner_mangas->execute(array($_GET['id_mangas']));
$suppression_mangas = $selectionner_mangas->fetch();
if(!empty($_POST['supprimer_news'])){
  $modification = $pdo->prepare('DELETE FROM billets_mangas WHERE id = ?');
  $modification->execute(array($_GET['id_mangas']));
}     
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title>Supprimer un manga/anime - Mangas'Fan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../images/favicon.png" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
 <div class="container-fluid">
  <div class="row">
    <div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
      <?php include('../elements/navredac_v.php'); ?>
    </div>
    <div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
      <?php include ('../elements/nav_redac.php'); ?>
      <section class="marge_page">
        <h3 class="titre_principal_news">
          Supprimer un mangas
        </h3>
        <a href="index.php" class="btn btn-primary btn-sm">
          Retourner à l'index de la rédaction
        </a>
        <br/><br/>
        <?php if (isset($_POST['supprimer_news'])) { ?>
         <div class="alert alert-success" role="alert">
          Le manga/anime a bien été supprimé !
        </div>
      <?php } ?>
      <div class="alert alert-info" role="alert">
        <strong>Attention :</strong> Le processus de suppression de mangas est définitif ! Une fois le manga supprimé, il est impossible de le récupérer. Soyez donc sûr de votre coup avant d'envisager une quelconque suppression.
      </div>
      <form method="POST" action="">
        <input type="submit" class="btn btn-sm btn-info" name="supprimer_news" value="Supprimer le mangas">
      </form>
    </section>
  </div>
</div>
</div>
<?php include('../elements/footer.php') ?>
</body>
</html>
