<?php 
session_start();
include('../membres/base.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
  $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $user->execute(array($_SESSION['auth']['id']));
  $utilisateur = $user->fetch(); 
}
include('../membres/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rédiger une news - Mangas'Fan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../images/favicon.png" />
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
      image_class_list: [
      {title: 'Image news', value: 'image_tiny'},
      ],
      content_css: [
      '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
      '//www.tinymce.com/css/codepen.min.css']
    });
  </script>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>
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
  else { ?>
   <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
        <nav>
          <center>
            <h5 style="padding-top: 15px;">Bienvenue <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));?> !</h5>
            <hr>
            <?php 
            if (!empty($utilisateur['avatar'])){
              if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
                <img src="https://www.mangasfan.fr/membres/images/avatars/<?php echo $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
                <?php } } ?><br/><br/>
                <p>Status : <?php echo statut(sanitize($utilisateur['grade'])); ?></p>
                <hr>
                <a href="../staff_index.php" class="btn btn-sm btn-info">Retournez à l'index staff</a>
              </center>
              <ul class="nav flex-column">
                <?php if($utilisateur['grade'] == 5){ ?>
                  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Newseurs</span>
                  </h6>
                  <li class="nav-item">
                    <a class="nav-link" href="https://www.mangasfan.fr/redaction/rediger_news.php">» Rédiger une news</a>
                  </li>
                <?php } elseif ($utilisateur['grade'] == 6) { ?>
                  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Rédacteurs</span>
                  </h6>
                  <li class="nav-item">
                    <a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_jeux.php"> » Gestion des jeux</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_mangas.php">» Gestion des mangas/animes</a>
                  </li>
                <?php } elseif ($utilisateur['grade'] >= 9) {?>
                  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Administration</span>
                  </h6>
                  <li class="nav-item">
                    <a class="nav-link" href="https://www.mangasfan.fr/redaction/rediger_news.php">» Rédiger une news</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_jeux.php"> » Gestion des jeux</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_mangas.php">» Gestion des mangas/animes</a>
                  </li>
                <?php } ?>  
              </ul>
            </nav>
          </div>
          <div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
            <?php include ('../elements/nav_redac.php'); ?>
            <section class="marge_page">
              <?php 
              if($utilisateur['grade'] >= 5){ 
                ?>
                <h3 class="titre_principal_news">
                  Rédiger une nouvelle news
                </h3>
                <center>
                  <a href="redac.php" class="btn btn-primary btn-sm">
                    Retourner à l'index de la rédaction
                  </a>
                  <a href="../hebergeur/index.php" class="btn btn-primary btn-sm">
                    Accéder à l'hébergeur d'images
                  </a>
                </center>
                <br/><br/>
               <?php 
               $admin = $pdo->prepare('SELECT id, username FROM users WHERE username = "Équipe du site"');
               $admin->execute();
               $id_admin = $admin->fetch();
               if (isset($_POST['valider']) AND !empty($_POST['valider'])) { 
                if(isset($_POST['titre']) AND strlen($_POST['titre']) >= 4 AND strlen($_POST['titre']) <= 80){
                  if(isset($_POST['description']) AND strlen($_POST['description']) >= 20 AND strlen($_POST['description']) <= 200){
                    if(isset($_POST['image']) AND !empty($_POST['image'])){
                      if(isset($_POST['contenu_news']) AND strlen($_POST['contenu_news']) >= 100){
                        if (isset($_POST['programmation_news']) AND !empty($_POST['programmation_news']) AND $_POST['auteur'] == 0) {
                          $ajouter_news = $pdo->prepare('INSERT INTO billets(titre, description, keywords, theme, contenu, date_creation, auteur, categorie, sources, visible) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                          $ajouter_news->execute(array($_POST['titre'], $_POST['description'], $_POST['keywords'], $_POST['image'], $_POST['contenu_news'], $_POST['programmation_news'], $utilisateur['username'], $_POST['categorie'], $_POST['sources'], $_POST['visible']));
                          ?>
                          <div class='alert alert-success' role='alert'>
                            C'est validé ! La news a bien été postée sur le site !
                          </div>
                          <?php
                        } elseif (isset($_POST['programmation_news']) AND !empty($_POST['programmation_news']) AND $_POST['auteur'] == 1) {
                          $ajouter_news = $pdo->prepare('INSERT INTO billets(titre, description, keywords, theme, contenu, date_creation, auteur, categorie, sources, visible) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
                          $ajouter_news->execute(array($_POST['titre'], $_POST['description'], $_POST['keywords'], $_POST['image'], $_POST['contenu_news'], $_POST['programmation_news'], $id_admin['username'], $_POST['categorie'], $_POST['sources'], $_POST['visible']));
                          ?>
                          <div class='alert alert-success' role='alert'>
                            C'est validé ! La news a bien été postée sur le site !
                          </div>
                          <?php
                        }
                        elseif (empty($_POST['programmation_news']) AND $_POST['auteur'] == 1) {
                          $ajouter_news = $pdo->prepare('INSERT INTO billets(titre, description, keywords, theme, contenu, date_creation, auteur, categorie, sources, visible) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)');
                          $ajouter_news->execute(array($_POST['titre'], $_POST['description'], $_POST['keywords'], $_POST['image'], $_POST['contenu_news'], $id_admin['username'], $_POST['categorie'], $_POST['sources'], $_POST['visible']));
                          ?>
                          <div class='alert alert-success' role='alert'>
                            C'est validé ! La news a bien été postée sur le site !
                          </div>
                          <?php
                        }
                        else
                        {
                          $ajouter_news = $pdo->prepare('INSERT INTO billets(titre, description, keywords, theme, contenu, date_creation, auteur, categorie, sources, visible) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)');
                          $ajouter_news->execute(array($_POST['titre'], $_POST['description'], $_POST['keywords'], $_POST['image'], $_POST['contenu_news'], $utilisateur['username'], $_POST['categorie'], $_POST['sources'], $_POST['visible']));
                          ?>
                          <div class='alert alert-success' role='alert'>
                            C'est validé ! La news a bien été postée sur le site !
                          </div>
                          <?php
                        }
                      } else {
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

                    } else {
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
                  } else {
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
                } else {
                  ?>
                  <div class='alert alert-warning' role='alert'>
                    <h4 class="alert-heading">Erreur de titre</h4>
                    <hr>
                    La news n'a pas été posté pour l'une des raisons suivantes :
                    <br/><br/>
                    <ul>
                      <li>Vous n'avez pas renseigné de titre.</li>
                      <li>Le titre doit posséder entre <strong>4 et 80 caractères</strong>.</li>
                    </ul>
                  </div>
                  <?php
                }
              }
              ?>
              <form method="POST" action="">
                <div class="container">
                  <div class="row">
                    <div class="col-md-6">
                      <label>Titre de la news : </label>
                      <input type="text" name="titre" class="form-control" placeholder="Entrez le titre de la news : il doit être explicite." />
                      <br/>
                      <label>Description de la new :</label>
                      <input type="text" name="description" class="form-control" placeholder="Entrez une courte description de la news pour la résumer !" />
                      <br/>
                      <label>Image de la news (200*200) : </label>
                      <input type="text" name="image" class="form-control" placeholder="Lien de l'image" />
                      <br/>
                      <label>Catégorie de la news : </label>
                      <select name="categorie" class="form-control">
                        <option value="Site">Site</option>
                        <option value="Jeux vidéo">Jeux vidéo</option>
                        <option value="Mangas">Mangas</option>
                        <option value="Anime">Anime</option>
                        <option value="Autres">Autres</option>
                      </select>
                      <br/>
                      <label>Mots-clés (TRES IMPORTANT) : </label>
                      <input type="text" name="keywords" class="form-control" placeholder="Vos mots-clés, séparés par une virgule" />
                    </div>
                    <div class="col-md-6">
                      <label>Sources : </label>
                      <input type="text" name="sources" class="form-control" placeholder="Sources" />
                      <br/>
                      <label>Visibilité de la news :</label> 
                      <select class="form-control" name="visible" placeholder="Voulez-vous rendre la news visible aux gens ?">
                        <option value="0">Visible</option>
                        <option value="1">Cachée</option>
                      </select>
                      <br/>
                      <label>Auteur de la news :</label> 
                      <select class="form-control" name="auteur">
                        <option value="0"><?php echo $utilisateur['username']; ?></option>
                        <option value="1"><?php echo $id_admin['username']; ?></option>
                      </select>
                      <br/>
                      <label>Programmer la news :</label> 
                      <input type="datetime-local" class="form-control" name="programmation_news"/>
                    </div>
                  </div>
                  <br/>
                  <label>Contenu de la new :</label>
                  <textarea name="contenu_news" id="contenu_redac"></textarea>
                  <input type="submit" class="btn btn-sm btn-info" name="valider" value="Publier la news" />
                </form>
              <?php } else { ?>
               <div class='alert alert-danger' role='alert'>
                Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
    <?php include('../elements/footer.php'); ?>
  </body>
  </html>