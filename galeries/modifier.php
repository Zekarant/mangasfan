<?php
session_start(); 
include('../membres/base.php');
include('../membres/data/maintenance_galeries.php');
include('../membres/functions.php');
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
if (isset($_POST['modifier_image'])) {
   if(isset($_POST['titre']) && !empty($_POST['titre']) AND strlen($_POST['titre']) >= 4 AND strlen($_POST['titre']) <= 50){
    if (isset($_POST['texte']) && !empty($_POST['texte']) && strlen($_POST['texte']) >= 20){
      $modifier_image = $pdo->prepare('UPDATE galerie SET titre = ?, titre_image = ?, texte = ? WHERE id = ?');
      $modifier_image->execute(array($_POST['titre'], $_POST['titre_image'], $_POST['texte'], $_GET['galerie']));
      $couleur = "success";
      $texte = "Votre image a bien été modifiée.";
      if ($verifier['rappel'] != NULL) {
        $enlever_rappel = $pdo->prepare('UPDATE galerie SET rappel = NULL WHERE id = ?');
        $enlever_rappel->execute(array($_GET['galerie']));
        $couleur = "success";
        $texte = "Votre image a bien été modifiée. Et le rappel a bien été supprimé.";
      }
    } else {
      $couleur = "warning";
      $texte = "Le texte doit faire au moins 20 caractères.";
    }
  } else {
    $couleur = "warning";
    $texte = "Le titre doit faire au moins 4 caractères.";
  }
}
$recuperation = $pdo->prepare('SELECT id, filename, titre, titre_image, texte, auteur, rappel FROM galerie WHERE id = ? ORDER BY id DESC');
$recuperation->execute(array($_GET['galerie']));
$galerie = $recuperation->fetch();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Modifier <?= sanitize($galerie['titre']); ?> - Mangas'Fan</title>
  <link rel="icon" href="../images/favicon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="icon" href="images/favicon.png"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include('../elements/header.php'); ?>
  <section>
    <h1 class="titre_principal_news">Modifier <?= sanitize($galerie['titre']); ?></h1>
    <hr>
    <a href="administration_galerie.php" class="btn btn-primary">Retourner à l'administration de ma galerie</a><br/><br/>
    <?php if ($galerie['rappel'] != NULL) { ?>
      <br/><br/>
      <div class='alert alert-warning' role='alert'>
        <strong>Avertissement :</strong> Cette image est actuellement cachée sur l'index des news car elle a récemment fait l'objet d'un rappel. Merci de modifier ce qu'il faut avec la raison ci-dessous :<br/><hr>
        <strong>Raison du rappel :</strong> <?= sanitize($galerie['rappel']); ?><br/><br/>
        Si jamais nous apprenons que vous avez modifié cette image sans répondre aux critères du rappel, des sanctions seront appliquées à votre compte.
      </div>
    <?php } if(isset($_POST['modifier_image'])){ ?>
      <div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
        <?= sanitize($texte); ?>
      </div>
    <?php } ?>
    <div class="container">
      <form method="POST" action="">
        <div class="row">
          <div class="col-md-6">
            <label>Titre :</label>
            <input type="text" class="form-control" name="titre" value="<?= sanitize($galerie['titre']); ?>">
            <br/>
            <label>Mots-clés (Facultatif mais recommandés) :</label>
            <input type="text" class="form-control" name="titre_image" value="<?= sanitize($galerie['titre_image']);?>">
            <br/>
            <input type="submit" class="btn btn-info" name="modifier_image" value="Valider les modifications">
          </div>
          <div class="col-md-6">
            <label>Contenu :</label>
            <textarea type="text" class="form-control" rows="5" name="texte"><?= htmlspecialchars_decode(sanitize($galerie['texte'])); ?></textarea>
          </div>
        </div>
      </form>
    </div>
  </section>
</body>
<?php include('../elements/footer.php'); ?>
</html>