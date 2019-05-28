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
	<title>Mangas'Fan - Administration du blog de <?php echo sanitize($utilisateur['username']); ?></title>
  <link rel="icon" href="../images/favicon.png"/>
	<script src='http://use.edgefonts.net/butcherman.js'></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
     <link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
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
        <div id="titre_news">Mes articles <span class="couleur_mangas">de</span> <span class="couleur_fans">blogs</span></div> <br/>
        <center><a href="index.php" class="btn btn-primary">Index des blogs</a> <a href="voir_blog.php" class="btn btn-primary">Voir mon blog</a> <a href="ajouter.php" class="btn btn-success">Ajouter un article</a></center><br/>
        <?php
        $recuperation = $pdo->prepare('SELECT id, titre, auteur, image, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%i\') AS date_creation_fr FROM billets_blogs ORDER BY date_creation DESC');
          $recuperation->execute(); 
          $articles = $pdo->prepare('SELECT * FROM billets_blogs WHERE auteur = ?');
          $articles->execute(array($_SESSION['auth']['username']));
            if($articles->rowCount() < 1){
                echo "<div class='alert alert-danger' role='alert'>Votre blog ne contient aucun article, n'hésitez pas à en rediger !</div>";
            }
            else {
                echo "<div class='alert alert-info' role='alert'>Il y a actuellement " . $articles->rowCount() . " article(s) sur votre blog.</div>";
            } ?>
        <table class="table table-striped">
              <thead>
              <tr>
                  <th>Titre de l'article</th>
                  <th>Date de l'article</th>
                  <th>Modifier</th>
                  <th>Supprimer</th>
              </tr>
              </thead>
              <?php
        while ($modification = $recuperation->fetch())
        {
          $username = $modification['auteur'];
          if (isset($_SESSION['auth']) AND $_SESSION['auth']['username'] == $username) {?>
          
<tr>
        <td><?php echo sanitize($modification['titre']);?></td>
        <td><?php echo sanitize($modification['date_creation_fr']);?></td>
        <td><a href="modifier.php?billets=<?php echo intval($modification['id']); ?>">Modifier l'article</a></td>
        <td><a href="supprimer.php?billets=<?php echo intval($modification['id']); ?>">Supprimer un article</a></td>
    </tr>
          <?php
        }
      }?>
      </table>
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
