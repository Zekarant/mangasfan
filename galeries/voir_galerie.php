<?php
session_start(); 
include('../membres/base.php');
include('../membres/data/maintenance_galeries.php');
include('../membres/functions.php'); 
if (isset($_SESSION['auth']) && $utilisateur['grade'] == 1){
  header("Location: ../bannis.php");
  exit();
}
$current_url = $_SERVER['REQUEST_URI'];
if(strpos($current_url,'galeries/voir_galerie.php'))
{
  $variable = $_GET['galerie'];
  $galerie = $pdo->prepare("SELECT id FROM users WHERE id = ?");
  $galerie->execute(array($variable));
  $membre_galerie = $galerie->fetch();
  header("Status: 301 Moved Permanently", false, 301);
  header("Location: ../galeries/members/galerie-".$membre_galerie['id']."");
  die();
}
$recuperation_galerie = $pdo->prepare('SELECT g.auteur, u.username FROM galerie g LEFT JOIN users u ON u.id = g.auteur WHERE g.auteur = ? ORDER BY date_image DESC');
$recuperation_galerie->execute(array($_GET['galerie']));
if ($recuperation_galerie->rowCount() == 0) {
  header('Location: ../..');
  $_SESSION['flash']['success'] = "<div class='alert alert-warning' role='alert'>La galerie de ce membre n'existe pas ! Il n'a posté aucune image dessus !</div>";
}
$galerie = $recuperation_galerie->fetch();
if (!isset($_SESSION['auth'])) {
  $affichage_galerie = $pdo->prepare('SELECT g.id, g.filename, g.titre, g.titre_image, g.texte, g.auteur, g.date_image, g.rappel, g.nsfw, u.username, u.galerie FROM galerie g LEFT JOIN users u ON u.id = g.auteur WHERE g.nsfw = 0 AND g.auteur = ? ORDER BY date_image DESC');
  $affichage_galerie->execute(array($_GET['galerie']));
} else {
  if ($utilisateur['galerie'] == 1 || $utilisateur['grade'] >= 7) {
    $affichage_galerie = $pdo->prepare('SELECT g.id, g.filename, g.titre, g.titre_image, g.texte, g.auteur, g.date_image, g.rappel, g.nsfw, u.username, u.galerie FROM galerie g LEFT JOIN users u ON u.id = g.auteur WHERE g.auteur = ? ORDER BY date_image DESC');
    $affichage_galerie->execute(array($_GET['galerie']));
  } else {
    $affichage_galerie = $pdo->prepare('SELECT g.id, g.filename, g.titre, g.titre_image, g.texte, g.auteur, g.date_image, g.rappel, g.nsfw, u.username, u.galerie FROM galerie g LEFT JOIN users u ON u.id = g.auteur WHERE g.nsfw = 0 AND g.auteur = ? ORDER BY date_image DESC');
    $affichage_galerie->execute(array($_GET['galerie']));
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title>Galerie de <?= sanitize($galerie['username']); ?>- Mangas'Fan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="icon" href="../../images/favicon.png"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../../style.css">
</head>
<body>
  <?php include('../elements/header.php'); ?>
  <section>
    <h1 class="titre_principal_news">Galerie de <?= sanitize($galerie['username']); ?></h1>
    <hr>
    <div class='alert alert-info' role='alert'>
      <strong>Information : </strong> Cette personne a posté <?= $affichage_galerie->rowCount(); ?> image(s) sur sa galerie ! N'hésitez pas à laisser des petits commentaires gentils !
    </div>
    <div class="container">
      <div class="row">
        <?php while($afficher = $affichage_galerie->fetch()){
          if ($afficher['rappel'] == NULL || $afficher['nsfw'] == 0) { ?>
            <div class="col-md-4">
              <div class="card" id="card-galerie">
                <div class="image">
                  <img src="../../galeries/images/<?= sanitize($afficher['filename']); ?>" alt="<?= sanitize($afficher['titre']); ?> de <?= sanitize($afficher['username']); ?>" class="image_galeries" />
                </div>
                <div class="card-body">
                  <hr>
                  <h5 class="card-title"><?php if ($afficher['nsfw'] == 1) {
                    echo "[NSFW] ";
                  } echo sanitize($afficher['titre']); ?> - <a href="../<?= htmlspecialchars(traduire_nom($afficher['titre'])); ?>">Voir l'image</a></h5>
                  <hr>
                  <p class="card-text"><i><?php if(!empty($afficher['titre_image'])){ 
                    echo sanitize($afficher['titre_image']);
                  } else { 
                    echo "Aucune description pour cette image.";
                  } ?></i></p>
                </div>
                <div class="card-footer">
                  <small class="text-muted">
                    Posté par <a href="../../profil/profil-<?= sanitize($afficher['auteur']); ?>" target="_blank"><?= sanitize($afficher['username']); ?></a> le <?= date('d M Y à H:i', strtotime(htmlspecialchars($afficher['date_image']))); ?>
                  </small>
                </div>
              </div>
            </div>
          <?php } } ?>
        </div>
      </div>
    </section>
    <?php include('../elements/footer.php'); ?>
  </body>
  </html>