<?php
  session_start(); 
  require_once '../inc/base.php';
  include('../inc/data/maintenance_galeries.php');
  if ($utilisateur['grade'] == 1) 
    {
      echo '<script>location.href="../bannis.php";</script>';
    }
  include('../inc/functions.php'); 
  include('../theme_temporaire.php');
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Mangas'Fan - Index des galeries</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <link rel="icon" href="../images/favicon.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo $lienCss; ?>">
    <link rel="stylesheet" href="../overlay.css" />
  </head>
  <body>
    <div id="bloc_page">
    <header>
      <div id="banniere_image">
      <div id="titre_site"><span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN</div>
      <div class="slogan_site">Votre référence Mangas</div>
            <?php include("../elements/navigation.php") ?>
      <h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
      <h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
      <div class="bouton_fofo"><a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a></div>
       <?php include('../elements/header.php'); 
       include('../inc/data/maintenance_galeries.php');?>
     </div>
   </header>
   <section class="marge_page">
  <div id="titre_news">Index <span class="couleur_mangas">des</span> <span class="couleur_fans">galeries</span></div><br/>
  <div class='alert alert-info' role='alert'>
    Bienvenue sur l'index des galeries de Mangas'Fan ! Vous retrouverez sur cette page toutes les dernières créations des membres du site, leurs dessins, ou leurs images qu'ils ont crée eux-mêmes.
  </div>
  <div id="conteneur_galerie">
      <?php 
      $recuperer = $pdo->prepare('SELECT id, filename, titre, titre_image, texte, auteur, DATE_FORMAT(date_image, \'%d/%m/%Y à %Hh%imin\') AS date_image_fr from galerie ORDER BY date_image DESC');
      $recuperer->execute();
      while ($afficher_galerie = $recuperer->fetch()) { 
        ?>
        <div class="card" id="card-galerie">
          <div id="image" style="background-image: url('https://www.mangasfan.fr/galeries/images/<?php echo sanitize($afficher_galerie['filename']); ?>');"></div>
          <hr>
          <div class="card-body">
          <h5 class="card-title"><?php echo sanitize($afficher_galerie['titre']); ?></h5>
              <p class="card-text"><?php if(!empty($afficher_galerie['titre_image'])){ echo sanitize($afficher_galerie['titre_image']);} else { echo "Aucune description pour cette image.";} ?></p>
          </div>
          <div class="card-footer">
            <small class="text-muted">
              <center>
                <u><a href="commentaires.php?galerie=<?php echo sanitize($afficher_galerie['id']); ?>">Voir l'image</a></u>
              </center>
              Posté par <?php echo sanitize($afficher_galerie['auteur']); ?> le <?php echo sanitize($afficher_galerie['date_image_fr']); ?></small>
          </div>
        </div>
      <?php }
      ?>
    </div>
  </section>
<?php include('../elements/footer.php') ?>
</div>
</body>
</html>