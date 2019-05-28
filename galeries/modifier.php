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

<!DOCTYPE html>

<html>

<head>

	<meta charset="utf-8">

	<title>Galeries - Modifier une image</title>

  <link rel="icon" href="../images/favicon.png"/>

	<script src='http://use.edgefonts.net/butcherman.js'></script>

  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />

  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />

  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />

  <script src="bootstrap/js/jquery.js"></script>

  <script src="bootstrap/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />

  <script type="text/javascript" src="../redaction/tinymce/js/tinymce/tinymce.min.js"></script>

  <script type="text/javascript" src="../redaction/tinymce/js/tinymce/tinymce.js"></script>

        <script>

          tinymce.init({

          selector: 'textarea',

          height: 250,

          theme: 'modern',

          language: 'fr_FR',

          plugins: ['preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern'],

          toolbar: 'insert | undo redo |  formatselect | bold italic underline strikethrough backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat |',

          content_css: [

            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',

            '//www.tinymce.com/css/codepen.min.css']

        });

        </script>

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

        $recuperation = $pdo->prepare('SELECT id, filename, titre, titre_image, texte, auteur FROM galerie WHERE id = ? ORDER BY id DESC');

        $recuperation->execute(array($_GET['galerie']));

        while ($modification = $recuperation->fetch())

        {

          ?>

          <div id="titre_news">Modifier <span class="couleur_mangas">une</span> <span class="couleur_fans">image</span></div><br/>

          <a href="administration_galerie.php" class="btn btn-primary">Retourner à l'administration de ma galerie</a><br/><br/>

          <?php if (isset($_SESSION['auth']) AND $_SESSION['auth']['username'] == $modification['auteur']) {?>

          <form action="" method="POST">

             <label>Titre :</label><input type="text" class="form-control" name="titre" value="<?php echo sanitize($modification['titre']);?>"><br/>

              <label>Titre de l'image :</label><input type="text" class="form-control" name="titre_image" value="<?php echo sanitize($modification['titre_image']);?>"><br/>

             <label>Contenu :</label><textarea type="text" class="form-control" name="texte"><?php echo sanitize($modification['texte']);?></textarea><br/>

             <input type="submit" class="btn btn-info" value="Valider les modifications">

          </form>

          <?php

        }

        else

        { ?>

            <div class='alert alert-warning' role='alert'>Il semble qu'il y a un problème avec cette page. Retourner à <a href="index.php">l'index des galeries</a>.</div>

        <?php }

      }

        if (!empty($_POST)) 

    {

      $formulaire_modifié = $pdo->prepare('SELECT titre, titre_image, texte, DATE_FORMAT(date_image, \'%d/%m/%Y à %Hh%imin%ss\') AS date_image_fr FROM galerie ORDER BY date_image');

       if(isset($_POST['titre']) && !empty($_POST['titre']) AND strlen($_POST['titre']) >= 4 AND strlen($_POST['titre']) <= 50){

      if (isset($_POST['titre_image']) && !empty($_POST['titre_image'])) {

        if (isset($_POST['texte']) && !empty($_POST['texte']) && strlen($_POST['texte']) >= 100){

      $formulaire_modifié->execute();

      $ajouter = $pdo->prepare('UPDATE galerie SET titre = ?, titre_image = ?, texte = ? WHERE id = ?');

      $ajouter->execute(array($_POST['titre'], $_POST['titre_image'], $_POST['texte'], $_GET['galerie']));

      echo "<div class='alert alert-success' role='alert'>Votre image a bien été modifiée !</div>";

}

        else

        {

          echo "<div class='alert alert-danger' role='alert'>Le contenu doit posséder plus de 100 caractères.</div>";

        }

      }

      else

      {

        echo "<div class='alert alert-danger' role='alert'>Aucune image renseignée.</div>";

     }

   }

   else

   {

    echo "<div class='alert alert-danger' role='alert'>Le titre doit comporter entre 4 et 50 caractères.</div>";

  }

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

