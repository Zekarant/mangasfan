<?php
  session_start();
  require_once '../inc/base.php';
  if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
  include('../theme_temporaire.php');
  include('../inc/functions.php');
?>
<!DOCTYPE html>
  <html lang="fr">
    <head>
      <meta charset="utf-8" />
      <title>Mangas'Fan - Modifer une news</title>
		  <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="../images/favicon.png" />
      <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
      <script type="text/javascript" src="tinymce/js/tinymce/tinymce.js"></script>
      <script>
        tinymce.init({
          selector: 'textarea',
          height: 250,
          theme: 'modern',
          language: 'fr_FR',
          plugins: ['print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help'],
          toolbar: 'insert | undo redo |  formatselect | bold italic underline backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
          content_css: [
          '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
          '//www.tinymce.com/css/codepen.min.css']
        });
      </script>
      <link rel="stylesheet" href="<?php echo $lienCss; ?>" />
    </head>
<body>
  <div id="bloc_page">
    <?php 
        if (!isset($_SESSION['auth'])){
          ?>
          <div class='alert alert-danger' role='alert'>
            Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
          </div>
          <?php
        }
        elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] < 5) {
          ?>
          <div class='alert alert-danger' role='alert'>
            Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
          </div>
          <?php
        }
        else {
         include('../elements/nav_redac.php');
         ?>
  	  <section class="marge_page">
        <?php 
          if($utilisateur['grade'] >= 5){ 
        ?>
          <h3 class="titre_pannel">
              Modifier <span class="couleur_mangas">une</span> <span class="couleur_fans">news</span>
          </h3>
          <center>
            <a href="redac.php" class="btn btn-primary btn-sm">
              Retourner à l'index de la rédaction
            </a>
            <a href="../hebergeur/index.php" class="btn btn-primary btn-sm">
              Accéder à l'hébergeur d'images
            </a>
            <a href="aides/aide_newseurs.php" class="btn btn-primary btn-sm" target="_blank">
              Accéder au fichier d'aide pour les news
            </a>
          </center>
          <?php
            if (isset($_POST['valider_news']) AND !empty($_POST['valider_news'])) {
              if(isset($_POST['modif_titre']) AND strlen($_POST['modif_titre']) >= 4 AND strlen($_POST['modif_titre']) <= 70){
                if(isset($_POST['modif_description']) AND strlen($_POST['modif_description']) >= 20 AND strlen($_POST['modif_description']) <= 200){
                  if(isset($_POST['modif_image']) AND !empty($_POST['modif_image'])){
                    if(isset($_POST['modif_contenu']) AND strlen($_POST['modif_contenu']) >= 100){
                      $modification = $pdo->prepare('UPDATE billets SET titre = ?, description = ?, theme = ?, contenu = ?, categorie = ?, sources = ?, visible = ? WHERE id = ?');
                      $modification->execute(array($_POST['modif_titre'], $_POST['modif_description'], $_POST['modif_image'], $_POST['modif_contenu'], $_POST['modif_categorie'], $_POST['modif_sources'], $_POST['modif_visibilité'], $_GET['id_news']));
                      ?>
                        <div class='alert alert-success' role='alert'>
                          Nous sommes bons ! La news a bien été modifiée !
                        </div>
                      <?php 
                  }
                  else
                  {
                    ?>
                      <div class='alert alert-warning' role='alert'>
                        <h4 class="alert-heading">Erreur de contenu</h4>
                        <hr>
                        La news n'a pas été posté pour l'une des raisons suivantes :
                        <br/><br/>
                        <ul>
                          <li>Vous n'avez pas renseigné de contenu.</li>
                          <li>Le contenu doit comporter <strong>minimum</strong> 100 caractères.</li>
                        </ul>
                      </div>
                    <?php
                  }
                }
                  else
                  {
                    ?>
                    <div class='alert alert-warning' role='alert'>
                      <h4 class="alert-heading">Erreur d'image</h4>
                      <hr>
                      La news n'a pas été posté pour l'une des raisons suivantes :
                      <br/><br/>
                      <ul>
                        <li>Vous n'avez pas renseigné « Image de news ».</li>
                      </ul>
                    </div>
                    <?php
                  }
                }
                else
                {
                  ?>
                    <div class='alert alert-warning' role='alert'>
                      <h4 class="alert-heading">Erreur de description</h4>
                      <hr>
                      La news n'a pas été posté pour l'une des raisons suivantes :
                      <br/><br/>
                      <ul>
                        <li>Vous n'avez pas renseigné de description.</li>
                        <li>La description doit posséder entre <strong>20 et 200 caractères</strong>.</li>
                      </ul>
                    </div>
                  <?php
                }
                }
                else
                {
                  ?>
                    <div class='alert alert-warning' role='alert'>
                      <h4 class="alert-heading">Erreur de titre</h4>
                      <hr>
                      La news n'a pas été posté pour l'une des raisons suivantes :
                      <br/><br/>
                      <ul>
                        <li>Vous n'avez pas renseigné de titre.</li>
                        <li>Le titre doit posséder entre <strong>4 et 70 caractères</strong>.</li>
                      </ul>
                    </div>
                  <?php 
                }
              }
               $donnees_news = $pdo->prepare('SELECT id, titre, contenu, description, theme, auteur, categorie, sources, visible FROM billets WHERE id = ?');
              $donnees_news->execute(array($_GET['id_news']));
              $news_ok = $donnees_news->fetch();
          ?>
          <form method="POST" action="">
            <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <label>Modifier le titre de la news :</label>
                  <input type="text" name="modif_titre" class="form-control" value="<?php echo sanitize($news_ok['titre']); ?>">
                  <br/>
                  <label>Modifier la description de la news :</label>
                  <input type="text" name="modif_description" class="form-control" value="<?php echo sanitize($news_ok['description']); ?>">
                  <br/>
                  <label>Modifier l'image de la news :</label>
                  <input type="text" name="modif_image" class="form-control" value="<?php echo sanitize($news_ok['theme']); ?>">
                  <a href="<?php echo sanitize($news_ok['theme']); ?>" target="_blank">Voir l'image de news utilisée</a>
                  <br/>
                  <label>Catégorie de la news : </label>
                  <select name="modif_categorie" class="form-control">
                    <option value="Site" <?php echo (($news_ok['categorie'] == "Site") ? "selected" : "" ) ?>>Site</option>
                    <option value="Jeux Vidéo" <?php echo (($news_ok['categorie'] == "Jeux Vidéo") ? "selected" : "" ) ?>>Jeux Vidéo</option>
                    <option value="Mangas" <?php echo (($news_ok['categorie'] == "Mangas") ? "selected" : "" ) ?>>Mangas</option>
                    <option value="Anime" <?php echo (($news_ok['categorie'] == "Anime") ? "selected" : "" ) ?>>Anime</option>
                    <option value="Autres" <?php echo (($news_ok['categorie'] == "Autres") ? "selected" : "" ) ?>>Autres</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Modifier les sources de la news :</label>
                  <input type="text" name="modif_sources" class="form-control" placeholder="<?php if(empty($news_ok['sources'])){ echo "Aucune source pour cette article."; } ?>" value="<?php echo sanitize($news_ok['sources']); ?>">
                  <br/>
                  <label>Modifier la visibilité de la news :</label> <i><?php if($news_ok['visible'] == 0) { echo "News visible"; } else { echo "News cachée."; } ?></i>
                  <select name="modif_visibilité" class="form-control">
                    <?php if($news_ok['visible'] == 0){ ?>
                      <option value="0" selected="selected">Visible</option>
                      <option value="1">Cachée</option>
                      <?php } else { ?>
                      <option value="0">Visible</option>
                      <option value="1" selected="selected">Cachée</option>
                    <?php } ?>
                  </select>
                  <br/>
                  <label>Auteur original de la news : (Non modifiable)</label>
                  <input type="text" name="auteur" class="form-control" value="<?php echo sanitize($news_ok['auteur']); ?>" readonly>
                  <?php ?>
                </div>
              </div>
              <br/>
              <label>Modifier le contenu de la news :</label>
              <textarea name="modif_contenu" class="form-control" id="contenu_redac">
                <?php echo sanitize($news_ok['contenu']); ?>
              </textarea>
              <input type="submit" class="btn btn-sm btn-info" name="valider_news" value="Modifier la news" />
            </div>
          </form>
        <?php } else { ?>
          <div class='alert alert-danger' role='alert'>
            Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
          </div>
        <?php } ?>
      </section>
    <?php } ?>
    <?php include('../elements/footer.php') ?>
  </div>
</body>
</html>
