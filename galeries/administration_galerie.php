<?php
session_start(); 
include('../membres/base.php');
include('../membres/functions.php'); 
include('../membres/data/maintenance_galeries.php');
if (!isset($_SESSION['auth'])) {
  header("Location: ../");
  exit();
}
if (isset($_SESSION['auth']) && $utilisateur['grade'] == 1){
  header("Location: ../bannis.php");
  exit();
}
// Récupération images de la galerie
$recuperation_galerie = $pdo->prepare('SELECT g.id AS id_galerie, g.filename, g.titre, g.titre_image, g.texte, g.auteur, g.date_image, g.rappel, g.nsfw, u.id, u.username, u.galerie FROM galerie g LEFT JOIN users u ON u.id = g.auteur WHERE g.auteur = ? ORDER BY date_image DESC');
$recuperation_galerie->execute(array($utilisateur['id']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title>Administration de ma galerie - Mangas'Fan</title>
  <link rel="icon" href="../images/favicon.png"/>
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
    <h1 class="titre_principal_news">Les images postées - Mangas'Fan</h1>
    <hr>
    <center>
      <a href="index.php" class="btn btn-primary">Index des galeries</a>
      <a href="voir_galerie.php?galerie=<?= sanitize($utilisateur['id']); ?>" class="btn btn-primary">Voir ma galerie</a> 
    <?php if ($utilisateur['galerie'] == 0) { ?>
      <a href="ajouter.php" class="btn btn-success">Ajouter une image</a>
    <?php } ?>
    </center>
    <hr>
    <?php if ($recuperation_galerie->rowCount() == 0) { ?>
      <div class='alert alert-info' role='alert'>
        Vous n'avez actuellement publié aucune image sur votre galerie ! N'hésitez pas à faire partager votre talent aux autres !
      </div>
    <?php } else { ?>
      <div class='alert alert-info' role='alert'>
        Vous avez actuellement <strong><?= $recuperation_galerie->rowCount(); ?> article(s)</strong> sur votre galerie.
      </div>
      <table class="table table-striped">
        <thead>
          <th>Titre de l'image</th>
          <th>Date de l'image</th>
          <th>Status</th>
          <th>Modifier</th>
          <th>Supprimer</th>
        </thead>
        <tbody>
          <?php while($galerie = $recuperation_galerie->fetch()){ ?>
            <tr>
              <td><?= sanitize($galerie['titre']); ?></td>
              <td><?= date('d/m/Y à H:i', strtotime(sanitize($galerie['date_image']))); ?></td>
              <td><?php if ($galerie['rappel'] == NULL && $galerie['nsfw'] == 0) { ?>
                <span class="badge badge-success">Visible par les autres personnes.</span>
              <?php } elseif ($galerie['rappel'] == NULL && $galerie['nsfw'] == 1) { ?>
                <span class="badge badge-success">Visible par les personnes avec NSFW activé.</span>
              <?php } else { ?>
                <span class="badge badge-warning">Cachée : Rappel reçu pour cette image.</span>
              <?php } ?></td>
              <td><a href="modifier.php?galerie=<?= sanitize($galerie['id_galerie']); ?>" class="btn btn-outline-primary">Modifier l'image</a></td>
              <td><a href="supprimer.php?galerie=<?= sanitize($galerie['id_galerie']); ?>" class="btn btn-outline-danger">Supprimer l'image</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
  </section>
  <?php include('../elements/footer.php'); ?>
</body>
</html>
