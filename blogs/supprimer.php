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
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Blogs - Supprimer un article</title>
  <link rel="icon" href="../images/favicon.png"/>
  <script src='http://use.edgefonts.net/butcherman.js'></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
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
      <section class="marge_page">
        <?php if (isset($_SESSION['auth'])){ ?>
        <?php
        $recuperation = $pdo->prepare('SELECT id, titre, auteur, image, contenu FROM billets_blogs WHERE id = ? ORDER BY id DESC');
        $recuperation->execute(array($_GET['billets']));
        while ($modification = $recuperation->fetch()){
          ?>
           <div id="titre_news">Supprimer <span class="couleur_mangas">un</span> <span class="couleur_fans">article</span></div><br/>
        <div class='alert alert-danger' role='alert'>
   <b>Information importante :</b> A partir du moment où vous appuyez, sa suppression est <b>imminente</b> ! Réfléchissez bien avant de cliquer sur supprimer.<br/>
  
  </div><br/>
          <?php $username = $modification->auteur;
          if (isset($_SESSION['auth']) AND $_SESSION['auth']['username'] == $username) {?>
          <form action="" method="POST">
             Supprimer votre article : <input type="submit" class="btn btn-info" name="supp" value="Supprimer mon article">
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
      $formulaire_modifié = $pdo->prepare('SELECT id, titre, auteur, image, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM billets_blogs ORDER BY date_creation');
      $formulaire_modifié->execute();
      $ajouter = $pdo->prepare('DELETE FROM billets_blogs WHERE id = ?');
      $ajouter->execute(array($_GET['billets']));
      echo "<div class='alert alert-success' role='alert'>Votre article a bien été supprimé !</div>";
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
  </div>
</body>
</html>
