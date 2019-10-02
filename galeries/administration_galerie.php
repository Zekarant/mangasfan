<?php
 session_start(); 
  require_once '../membres/base.php';
  include('../membres/data/maintenance_galeries.php');
  if ($utilisateur['grade'] == 1) 
    {
      echo '<script>location.href="../bannis.php";</script>';
    }
  include('../membres/functions.php'); 
?>
<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<title>Mangas'Fan - Administration de la galerie de <?php echo sanitize($utilisateur['username']); ?></title>
    <link rel="icon" href="../images/favicon.png"/>
  	<script src='http://use.edgefonts.net/butcherman.js'></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
		 <header>
      <?php include('../elements/navigation_principale.php'); ?>
      <h1 class="titre_site">Mangas'Fan</h1>
      <p class="slogan_site">
        Votre site web communautaire sur l'actualité des mangas et des animes. Retrouvez toutes nos pages et news !
      </p>
      <div class="bouton">
        <a href="https://www.twitter.com/MangasFanOff" target="_blank" class="btn btn-outline-light">
          Twitter
        </a>
      </div>
    </header>
      <section class="marge_page">
        <?php if (isset($_SESSION['auth'])){ ?>
        <div class="titre_principal_news">Mes images <span class="couleur_mangas">de</span> <span class="couleur_fans">galerie</span></div> <br/>
        <center><a href="index.php" class="btn btn-primary">Index des galeries</a> <a href="voir_galerie.php" class="btn btn-primary">Voir ma galerie</a> <a href="ajouter.php" class="btn btn-success">Ajouter une image</a></center><br/>
        <?php
        $recuperation = $pdo->prepare('SELECT id, filename, titre, titre_image, texte, auteur, DATE_FORMAT(date_image, \'%d/%m/%Y à %Hh%i\') AS date_image_fr FROM galerie ORDER BY date_image DESC');
          $recuperation->execute(); 
          $articles = $pdo->prepare('SELECT * FROM galerie WHERE auteur = ?');
          $articles->execute(array($_SESSION['auth']['username']));
            if($articles->rowCount() < 1){
                echo "<div class='alert alert-danger' role='alert'>Votre galerie ne contient aucune image, n'hésitez pas à en publier !</div>";
            }
            else {
                echo "<div class='alert alert-info' role='alert'>Il y a actuellement " . $articles->rowCount() . " image(s) sur votre galerie.</div>";
            } ?>
        <table class="table table-striped">
              <thead>
              <tr>
                  <th>Titre de l'image</th>
                  <th>Date de l'image</th>
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
        <td><?php echo sanitize($modification['date_image_fr']);?></td>
        <td><a href="modifier.php?galerie=<?php echo intval($modification['id']); ?>">Modifier l'article</a></td>
        <td><a href="supprimer.php?galerie=<?php echo intval($modification['id']); ?>">Supprimer un article</a></td>
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
</body>
</html>
