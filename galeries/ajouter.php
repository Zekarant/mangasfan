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
	<title>Galeries - Ajouter une image</title>
  <link rel="icon" href="../images/favicon.png"/>
	<script src='http://use.edgefonts.net/butcherman.js'></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../style.css" />
  <script type="text/javascript" src="../redaction/tinymce/js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="../redaction/tinymce/js/tinymce/tinymce.js"></script>
        <script>
          tinymce.init({
          selector: 'textarea',
          height: 250,
          theme: 'modern',
          language: 'fr_FR',
          plugins: ['preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern'],
          toolbar: 'insert | undo redo |  formatselect | bold italic underline strikethrough backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat |',
          content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css']
        });
        </script>
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
    <?php if (!isset($_SESSION['auth'])){ 
      echo "<div class='alert alert-danger' role='alert'>Vous n'avez pas le droit d'accéder à cette page</div>";
      echo '<script>location.href="../index.php";</script>';
    }?>
    <div class="titre_principal_news">Ajouter <span class="couleur_mangas">une</span> <span class="couleur_fans">image</span></div><br/>
    <a href="administration_galerie.php" class="btn btn-primary">Retourner à l'administration de ma galerie</a><br/><br/>
    <div class='alert alert-warning' role='alert'>
       <b>Information importante :</b> Vous avez très certainement envie d'ajouter une image tout de suite, mais noter que pour que votre image soit ajoutée, il faut qu'elle respecte quelques contraintes pour permettre une bonne lecture :<br/><br/>
       - Votre titre doit comporter entre <b>4 et 50 caractères.</b><br/>
       - Les <b>mots clés</b> sont facultatifs. Si vous en mettez, ils doivent être séparés par une virgule !<br/>
       - Votre contenu doit faire au minimum <b>100 caractères</b>.<br/><br/>
       <b>Note :</b> Dans le cas où un de ces critères ne serait pas respecté, lorsque vous validerez votre formulaire, une erreur apparaitra vous indiquant ce que vous devez corriger.
    </div>
    <?php
       if(isset($_POST['valider']) AND !empty($_POST['valider'])){
        if(isset($_POST['titre']) AND strlen($_POST['titre']) >= 4 AND strlen($_POST['titre']) <= 50){
          if(isset($_FILES['image_galerie']) AND !empty($_FILES['image_galerie']['name'])){
            if (isset($_POST['contenu']) && !empty($_POST['contenu']) && strlen($_POST['contenu']) >= 20){
              $tailleMax = 2097152;
              $image = $_FILES['image_galerie']['name'];
              $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
              if($_FILES['image_galerie']['size'] <= $tailleMax) {
              $extensionUpload = strtolower(substr(strrchr($image, '.'), 1));
              if(in_array($extensionUpload, $extensionsValides)) {
              $chemin = "images/".$image;
              $resultat = move_uploaded_file($_FILES['image_galerie']['tmp_name'], $chemin);
              $updateavatar = $pdo->prepare('INSERT INTO galerie(filename, titre, titre_image, texte, date_image, auteur) VALUES(?, ?, ?, ?, NOW(), ?)')
              ;
              $updateavatar->execute(array($image, $_POST['titre'], $_POST['titre_image'], $_POST['contenu'], $utilisateur['id']));
              ?>
                <div class='alert alert-success' role='alert'>
                  Votre image a bien été ajoutée au site.
                </div>
              <?php
        } }}
        else { ?>
          <div class='alert alert-danger' role='alert'>
            Votre contenu doit posséder au moins 20 caractères.
          </div>
        <?php }
        }
        else { ?>
          <div class='alert alert-danger' role='alert'>
            Vous n'avez envoyé aucune image.
          </div>
        <?php }
        }
        else { ?>
          <div class='alert alert-danger' role='alert'>
            Votre titre doit contenir entre 4 et 50 caractères.
          </div>
        <?php }
       }
    ?>
    <div class="form-group">
    <form action="" method="POST" enctype="multipart/form-data">
      <label>Titre :</label> <input type="text" name="titre" class="form-control" placeholder="Votre titre"><br/>
      <label>Votre image :</label><br/>
      <input type="file" name="image_galerie" class="file btn btn-info"/><br/><br/>
      <label>Mots clés de l'image (Merci de les séparer par une virgule) :</label> <input type="text" name="titre_image" class="form-control" placeholder="Insérez les mots clés de votre image, ils serviront au référencement. Facultatif."><br/>
      <label>Contenu :</label> <textarea type="texterea" name="contenu" class="form-control" placeholder="Votre contenu"></textarea><br/>
      <input type="submit" name="valider" class="btn btn-sm btn-info" value="Publier mon image" />
    </form>
  </div>
  </section>
<?php include('../elements/footer.php'); ?>
</body>
</html>
