<?php 
session_start();
include('../inc/functions.php');
include('../inc/base.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
  $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $user->execute(array($_SESSION['auth']['id']));
  $utilisateur = $user->fetch(); 
}
include('../theme_temporaire.php');
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8" />
  <title>Mangas'Fan - Modifier une page</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="icon" href="../images/favicon.png"/>
  <script src='http://use.edgefonts.net/nosifer.js'></script>
  <script src='http://use.edgefonts.net/emilys-candy.js'></script>
  <script src='http://use.edgefonts.net/butcherman.js'></script>
  <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
  <script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript" src="../tinymce/js/tinymce/tinymce.js"></script>
  <script>
    tinymce.init({
      selector: 'textarea',
      height: 250,
      theme: 'modern',
      language: 'fr_FR',
      plugins: ['preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern'],
      image_class_list: [
      {title: 'Image news', value: 'image_tiny'},
      ],
      toolbar: 'insert | undo redo |  formatselect | bold italic underline strikethrough backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat |',
      content_css: [
      '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
      '//www.tinymce.com/css/codepen.min.css']
    });
  </script>

</head>
<body>
  <?php
  $message = ''; 
  if($utilisateur['grade'] >= 4){
    $crit = stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($_GET['id_page'])))));
    $select_all_news = $pdo->query("SELECT * FROM billets_jeux_pages WHERE id ='".$crit."'")->fetch();
    $select_all = $pdo->query("SELECT * FROM billets_jeux_onglet WHERE id = '$select_all_news->onglet_id'");
    $new = $select_all->fetch();
    ?>
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
                <center>
                  <h2 class="titre_commentaire_news">Modifier <span class="couleur_mangas">la</span> <span class="couleur_fans">page</span></h2>
                </center>
                <a href="redac.php">Retournez à l'index de la rédaction</a> - <a href="modif_news_jeux.php?id_news=<?php echo $new['billets_id'];?>">Retournez à l'administration du jeu</a><br/><br/>
                <?php 
                if (isset($_POST['valider_page'])){
                  $categorie = addslashes(htmlspecialchars($_POST['liste_onglets']));
                  $titre_page = addslashes(htmlspecialchars($_POST['titre_page']));
                  $contenu = addslashes(htmlspecialchars($_POST['en_attente']));
                  if ($categorie != '---'){
                    if (!empty($_POST['titre_page'])){
                      if (strlen($titre_page) > 4 AND strlen($titre_page) < 36){
                        if (!empty($_POST['en_attente'])){
                          $modif_page = $pdo->prepare("UPDATE billets_jeux_pages SET onglet_id = ?, nom = ?,contenu = ? WHERE id = '$crit'");

                          $onglet_id = $pdo->query("SELECT id FROM billets_jeux_onglet WHERE nom = '$categorie' AND billets_id = '$crit'")->fetch();

                          $modif_page->execute(array($onglet_id['id'],$titre_page,$contenu));

                          $message = "<div class='alert alert-success' role='alert'>Votre page a bien été modifiée !</div>";

                        } else {
                          $message = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Veuillez remplir le champ \"Texte\".</div>";
                        }
                      } else {
                        $message = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Vous devez sélectionner un titre possédant entre 5 et 35 caractères.</div>";
                      }
                    } else {
                      $message = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Veuillez remplir le champ \"Titre\".</div>";
                    }
                  } else {
                    $message = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Vous devez sélectionner un onglet proposé. S'il vous en manque l'onglet correspondant, veuillez l'ajouter.</div>";
                  }
                } ?>
                <center>
                  <?php echo $message;?>
                  <form method="POST" action="">
                    <label> Sélectionnez un onglet : </label>
                    <select class="form-control" id="exampleSelect1" name="liste_onglets">
                      <option>---</option>
                      <?php $onglet_exist = $pdo->query("SELECT * FROM billets_jeux_onglet WHERE billets_id = '$crit'");
                      while($parcours_onglet = $onglet_exist->fetch()) { ?>
                        <option <?php if($parcours_onglet['id'] == $select_all_news['onglet_id']){ ?> selected="selected" <?php } ?> ><?php echo $parcours_onglet['nom'] ;?></option>
                      <?php } ?>
                    </select><br /><br />
                    <label> Titre : </label>
                    <input type="text" class="form-control" name="titre_page" value="<?php echo stripslashes($select_all_news['nom']); ?>"><br /><br />
                    <label> Texte : </label>
                    <textarea name="en_attente"><?php echo htmlspecialchars_decode(htmlspecialchars_decode($select_all_news['contenu']));?></textarea>
                    <input type="submit" class="btn btn-sm btn-info" name="valider_page" value="Valider la page">
                  </form></center>
                <?php } else {
                  echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
                } ?>
              </section>
            </div>
          </div>
        </div>
        <?php include('../elements/footer.php'); ?>
      </body>
      </html>

      <style>
        .form-control[name="new_onglet"],.form-control[name="titre_page"]{
          padding:none !important;
          width:30%;
          display:inline-block;
          margin-left:15px;
          margin-bottom:5px;
        }

        .form-control[name="liste_onglets"], option {
          width:40%;
          display:inline-block;
        }
      </style>