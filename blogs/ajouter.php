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
	<title>Blogs - Ajouter un article</title>
  <link rel="icon" href="../images/favicon.png"/>
	<script src='http://use.edgefonts.net/butcherman.js'></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
   <link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
    <div id="titre_news">Ajouter <span class="couleur_mangas">un</span> <span class="couleur_fans">article</span></div><br/>
        <div class='alert alert-warning' role='alert'>
   <b>Information importante :</b> Vous avez très certainement envie d'ajouter un article tout de suite, mais noter que pour que votre article soit ajouté, il faut qu'il respecte quelques contraintes :<br/><br/>
   - Votre titre doit comporter entre <b>4 et 50 caractères.</b><br/>
   - L'image est importante, vous n'êtes pas obligé de la créer, mais au moins mettre un lien !<br/>
   - Votre contenu doit faire au minimum <b>100 caractères</b>.<br/><br/>
   <b>Note :</b> Dans le cas où un de ces critères ne serait pas respecté, lorsque vous validerez votre formulaire, une erreur apparaitra vous indiquant ce que vous devez corriger.
  </div><br/>
    <?php
  if (!empty($_POST)) 
  {
    $formulaire = $pdo->prepare('SELECT titre, auteur, image, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM billets_blogs ORDER BY date_creation');
    if(isset($_POST['titre']) AND strlen($_POST['titre']) >= 4 AND strlen($_POST['titre']) <= 50){
      if (isset($_POST['image']) && !empty($_POST['image'])) {
        if (isset($_POST['contenu']) && !empty($_POST['contenu']) && strlen($_POST['contenu']) >= 100){
          $formulaire->execute();
          $ajouter = $pdo->prepare("INSERT INTO billets_blogs (titre, auteur, image, contenu, date_creation) VALUES (?, ?, ?, ?, NOW())");
          $ajouter->execute(array($_POST['titre'], $_SESSION['auth']['username'], $_POST['image'], $_POST['contenu']));
          echo "<div class='alert alert-success' role='alert'>Votre article a bien été ajouté !</div>";
        }
        else
        {
          echo "<div class='alert alert-danger' role='alert'>Le contenu doit posséder plus de 100 caractères..</div>";
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
    <form action="" method="POST">
      <label>Titre :</label> <input type="text" name="titre" class="form-control" placeholder="Votre titre"><br/>
      <label>Image : <i>350*125</i></label> <input type="text" name="image" class="form-control" placeholder="Votre image"><br/>
      <label>Contenu :</label> <textarea type="texterea" name="contenu" class="form-control" placeholder="Votre contenu"></textarea><br/>
      <input type="submit" class="btn btn-info" value="Valider l'article">
    </form>
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
