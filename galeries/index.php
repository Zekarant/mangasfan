<?php
session_start(); 
include('../membres/base.php');
include('../membres/functions.php'); 
include('../membres/data/maintenance_galeries.php');
if (isset($_SESSION['auth']) && $utilisateur['grade'] == 1){
  header("Location: ../bannis.php");
  exit();
}
// Gestion NSFW
$date = new DateTime($utilisateur['date_anniv']);
$date_deux = new DateTime(date(''));
$interval = $date->diff($date_deux);
$interval = date_diff($date, $date_deux);
if (isset($_POST['activer_nsfw'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] > 1 && $utilisateur['grade'] <= 12) {
      $autoriser = $pdo->prepare('UPDATE users SET galerie = 1 WHERE id = ?');
      $autoriser->execute(array($utilisateur['id']));
      $couleur = "success";
      $texte = "Vous avez désormais accès au NSFW";
    }
  }
} elseif(isset($_POST['desactiver_nsfw'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] > 1 && $utilisateur['grade'] <= 12) {
      $autoriser = $pdo->prepare('UPDATE users SET galerie = 0 WHERE id = ?');
      $autoriser->execute(array($utilisateur['id']));
      $couleur = "success";
      $texte = "Vous avez désactivé votre accès au NSFW";
    }
  }
}

if (!isset($_SESSION['auth'])) {
  $recuperer = $pdo->prepare('SELECT g.id, g.filename, g.titre, g.titre_image, g.texte, g.auteur, g.date_image, g.rappel, g.nsfw, u.username, u.galerie FROM galerie g LEFT JOIN users u ON u.id = g.auteur WHERE g.nsfw = 0 ORDER BY date_image DESC');
  $recuperer->execute();
} else {
  if ($utilisateur['galerie'] == 1 || $utilisateur['grade'] >= 7) {
    $recuperer = $pdo->prepare('SELECT g.id, g.filename, g.titre, g.titre_image, g.texte, g.auteur, g.date_image, g.rappel, g.nsfw, u.username, u.galerie FROM galerie g LEFT JOIN users u ON u.id = g.auteur ORDER BY date_image DESC');
    $recuperer->execute();
  } else {
    $recuperer = $pdo->prepare('SELECT g.id, g.filename, g.titre, g.titre_image, g.texte, g.auteur, g.date_image, g.rappel, g.nsfw, u.username, u.galerie FROM galerie g LEFT JOIN users u ON u.id = g.auteur WHERE g.nsfw = 0 ORDER BY date_image DESC');
    $recuperer->execute();
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Accueil des galeries - Mangas'Fan</title>
  <link rel="icon" href="../images/favicon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="icon" href="images/favicon.png"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-129397962-1');
  </script>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include('../elements/header.php'); ?>
  <section>
    <h1 class="titre_principal_news">Accueil des galeries - Artistes de Mangas'Fan</h1>
    <hr>
    <?php if(isset($_POST['activer_nsfw']) || isset($_POST['desactiver_nsfw'])){ ?>
      <div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
        <?= sanitize($texte); ?>
      </div>
    <?php } ?>
    <div class='alert alert-info' role='alert'>
      Bienvenue sur l'accueil des galeries de Mangas'Fan ! Vous retrouverez sur cette page toutes les dernières créations des membres du site, leurs dessins, leurs fanarts ou leurs images qu'ils ont créés eux-mêmes.
      <?php if (isset($_SESSION['auth']) && $utilisateur['grade'] <= 7 && $utilisateur['galerie'] == 0) {
        if ($utilisateur['date_anniv'] != NULL && $interval->format('%y') >= 18) { ?>
          <br/><br/>
          <strong>Activer le NSFW :</strong> Vous semblez avoir plus de 18 ans, vous pouvez donc activer le NSFW, pour se faire, vous avez juste à cliquer sur le bouton ci-dessous ! Cette option est activable et désactiable à tout moment !
          <form method="POST" action="">
            <input type="submit" name="activer_nsfw" class="btn btn-sm btn-outline-info" value="Activer mon accès NSFW">
          </form>
        <?php }
      } elseif (isset($_SESSION['auth']) && $utilisateur['grade'] <= 7 && $utilisateur['galerie'] == 1) { ?>
        <br/><br/>
        <strong>Désactiver le NSFW :</strong> Vous ne voulez plus voir d'images un peu bizarres ? Vous pouvez désactiver le NSFW, et revenir à tout moment ! Cliquez juste sur le bouton ci-dessous :
        <form method="POST" action="">
          <input type="submit" name="desactiver_nsfw" class="btn btn-sm btn-outline-info" value="Désactiver mon accès NSFW">
        </form>
      <?php } ?>
    </div>
    <div class="container">
      <div class="row">
        <?php while($galerie = $recuperer->fetch()){
          if ($galerie['rappel'] == NULL || $galerie['nsfw'] == 0) { ?>
            <div class="col-md-4">
              <div class="card" id="card-galerie">
                <div class="image">
                  <img src="../galeries/images/<?= sanitize($galerie['filename']); ?>" alt="<?= sanitize($galerie['titre']); ?> de <?= sanitize($galerie['username']); ?>" class="image_galeries" />
                </div>
                <div class="card-body">
                  <hr>
                  <h5 class="card-title"><?php if ($galerie['nsfw'] == 1) {
                    echo "[NSFW] ";
                  } echo sanitize($galerie['titre']); ?> - <a href="<?= htmlspecialchars(traduire_nom($galerie['titre'])); ?>">Voir l'image</a></h5>
                  <hr>
                  <p class="card-text"><i><?php if(!empty($galerie['titre_image'])){ 
                    echo sanitize($galerie['titre_image']);
                  } else { 
                    echo "Aucune description pour cette image.";
                  } ?></i></p>
                </div>
                <div class="card-footer">
                  <small class="text-muted">
                    Posté par <a href="../profil/profil-<?= sanitize($galerie['auteur']); ?>" target="_blank"><?= sanitize($galerie['username']); ?></a> le <?= date('d M Y à H:i', strtotime(htmlspecialchars($galerie['date_image']))); ?>
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