<?php
session_start(); 
include('../membres/base.php');
include('../membres/data/maintenance_galeries.php');
if ($utilisateur['grade'] == 1){
      echo '<script>location.href="../bannis.php";</script>';
}
include('../membres/functions.php'); 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Galerie de <?php echo ($utilisateur['username']); ?> - Mangas'Fan</title>
  <link rel="icon" href="../images/favicon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
	<div id="bloc_page">
		<?php include('../elements/header.php'); ?>
      <section class="marge_page">
        <?php if (isset($_SESSION['auth'])){ ?>
        <div class="titre_principal_news">
          Galerie de <?php echo ($utilisateur['username']); ?>
        </div><br/>
        <div class='alert alert-info' role='alert'>
          Ceci est la page où sont réunis toutes les images de votre galerie. Vous pouvez donc consulter toutes les images que vous avez postées. En cliquant sur l'une d'entre elle, vous pourrez accéder aux liens pour modifier directement votre post.
        </div><br/>
        <?php
        $recuperation = $pdo->prepare('SELECT id, filename, titre, texte, DATE_FORMAT(date_image, \'%d/%m/%Y à %Hh%i\') AS date_image_fr, auteur FROM galerie ORDER BY date_image DESC');
          $recuperation->execute();
          $articles = $pdo->prepare('SELECT * FROM galerie WHERE auteur = ?');
          $articles->execute(array($_SESSION['auth']['username']));
            if($articles->rowCount() < 1){
                echo "<div class='alert alert-danger' role='alert'>Votre galerie ne contient aucune image, n'hésitez pas à en publier !</div>";
            } ?>
      <div id="conteneur_galerie">
              <?php
        while ($modification = $recuperation->fetch())
        {
          if (isset($_SESSION['auth']) AND $_SESSION['auth']['username'] == $modification['auteur']) {?>
           <div class="card" id="card-galerie">
          <div class="image">
            <img src="../galeries/images/<?php echo sanitize($modification['filename']); ?>" class="image_galeries" />
          </div>
          <hr>
          <div class="card-body">
          <h5 class="card-title"><?php echo sanitize($modification['titre']); ?></h5>
          </div>
          <div class="card-footer">
            <small class="text-muted">
              <center>
                <u><a href="commentaires.php?galerie=<?php echo sanitize($modification['id']); ?>">Voir l'image</a></u>
              </center>
              Posté le <?php echo sanitize($modification['date_image_fr']); ?></small>
          </div>
        </div>
          <?php
        }
      }?>
    </div>
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
