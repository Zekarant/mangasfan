<?php
  session_start(); 
  require_once '../inc/base.php';
  include('../inc/data/maintenance_blogs.php');
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
    <title>Mangas'Fan - Index des blogs</title>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
       <?php include('../elements/header.php'); ?>
     </div>
   </header>
   <section>
  <div id="titre_news">Index <span class="couleur_mangas">des</span> <span class="couleur_fans">blogs</span></div><br/>
  <div class='alert alert-info' role='alert'>
    Bienvenue sur l'index des blogs de Mangas'Fan ! Vous retrouverez sur cette page tous les derniers articles postés par les membres du site. Vous avez la possibilité de commenter ses derniers.
  </div>
      <?php
    $recuperation = $pdo->prepare('SELECT id, titre, auteur, image, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date_creation_fr FROM billets_blogs ORDER BY id DESC LIMIT 0, 30');
    $recuperation->execute();
  ?>
  <div id="billets_blogs">
        <?php while ($billets = $recuperation->fetch()) 
        { ?>
          <div class="element_blogs">
            <img src="<?php echo sanitize($billets['image']); ?>" class="image_blogs"/>
            <div class="titre_billet">
            » <a href="commentaires.php?billets=<?php echo intval($billets['id']); ?>"><?php echo sanitize($billets['titre']);?></a>

          </div><br/>
          <div class="poste_article">
            Article posté le <i><?php echo sanitize($billets['date_creation_fr']); ?></i> par <b><?php echo sanitize($billets['auteur']); ?></b><br/>
          </div>
        </div>
        <?php 
  }
   ?>
   </div>
  </section>
<?php include('../elements/footer.php') ?>
</div>
</body>
</html>