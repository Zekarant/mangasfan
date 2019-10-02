<?php
 session_start(); 
  require_once '../inc/base.php';
  include('../membres/data/maintenance_galeries.php');
  if ($utilisateur['grade'] == 1) 
    {
      echo '<script>location.href="../bannis.php";</script>';
    }
  include('../inc/functions.php'); 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Galeries - Supprimer une image</title>
  <link rel="icon" href="../images/favicon.png"/>
  <script src='http://use.edgefonts.net/butcherman.js'></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
</head>
<body>
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
      <section class="marge_page">
        <?php if (isset($_SESSION['auth'])){ ?>
        <?php
        $recuperation = $pdo->prepare('SELECT id, filename, titre, titre_image, texte, auteur FROM galerie WHERE id = ? ORDER BY id DESC');
        $recuperation->execute(array($_GET['galerie']));
        while ($modification = $recuperation->fetch()){
          ?>
           <div id="titre_news">Supprimer <span class="couleur_mangas">une</span> <span class="couleur_fans">image</span></div><br/>
        <div class='alert alert-danger' role='alert'>
   <b>Information importante :</b> A partir du moment où vous appuyez, la suppression est <b>imminente</b> ! Réfléchissez bien avant de cliquer sur supprimer.<br/>
  
  </div><br/>
          <?php
          if (isset($_SESSION['auth']) AND $_SESSION['auth']['username'] == $modification['auteur']) {?>
          <form action="" method="POST">
             Supprimer votre image : <input type="submit" class="btn btn-info" name="supp" value="Supprimer mon article">
          </form>
          <?php
        }
        else
        {
          echo "Cette page n'existe pas !";
        }
      }
        if (!empty($_POST)) 

    {
      $formulaire_modifié = $pdo->prepare('SELECT id, titre, auteur, titre_image, texte, DATE_FORMAT(date_image, \'%d/%m/%Y à %Hh%imin%ss\') AS date_image_fr FROM galerie ORDER BY date_image');
      $formulaire_modifié->execute();
      $ajouter = $pdo->prepare('DELETE FROM galerie WHERE id = ?');
      $ajouter->execute(array($_GET['galerie']));
      echo '<script>location.href="administration_galerie.php";</script>';
      echo "<div class='alert alert-success' role='alert'>Votre image a bien été supprimée !</div>";
}
        ?>
        <?php }
    else 
    {
       echo "<div class='alert alert-danger' role='alert'>Vous n'avez pas le droit d'accéder à cette page</div>";
      echo '<script>location.href="../index.php";</script>';
     } 
     ?>
      </section>
   <?php include('../elements/footer.php'); ?>
</body>
</html>
