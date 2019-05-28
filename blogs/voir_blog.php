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
	<title>Blogs - Blog de <?php echo ($utilisateur['username']); ?></title>
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
        <div id="titre_news">Blog <span class="couleur_mangas">de</span> <span class="couleur_fans"><?php echo ($utilisateur['username']); ?></span></div><br/>
        <div class='alert alert-info' role='alert'>
   Ceci est la page où sont réunis tous les articles de votre blog. Vous pouvez donc consulter tous les articles que vous avez rédigés. En cliquant sur l'un d'entre eux, vous pourrez apercevoir les liens pour modifier directement votre article.
  </div><br/>
        <?php
        $recuperation = $pdo->prepare('SELECT id, titre, auteur, image, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%i\') AS date_creation_fr FROM billets_blogs ORDER BY date_creation DESC');
          $recuperation->execute();
          $articles = $pdo->prepare('SELECT * FROM billets_blogs WHERE auteur = ?');
          $articles->execute(array($_SESSION['auth']['username']));
            if($articles->rowCount() < 1){
                echo "<div class='alert alert-danger' role='alert'>Votre blog ne contient aucun article, n'hésitez pas à en rediger !</div>";
            } ?>
          <div id="billets_blogs">
              <?php
        while ($modification = $recuperation->fetch())
        {
          $username = $modification['auteur'];
          if (isset($_SESSION['auth']) AND $_SESSION['auth']['username'] == $username) {?>
          
          <div class="element_blogs">
            <img src="<?php echo htmlspecialchars($modification['image']); ?>" class="image_blogs"/>
            <div class="titre_billet">
            <a href="commentaires.php?billets=<?php echo intval($modification['id']); ?>"><?php echo sanitize($modification['titre']);?></a>

          </div><br/>
          <div class="poste_article">
            Article posté le <i><?php echo sanitize($modification['date_creation_fr']); ?></i> par <b><?php echo sanitize($modification['auteur']); ?></b><br/>
          </div>
        </div>
          <?php
        }
      }?></div>
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
