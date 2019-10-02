<?php
session_start();
include('../membres/base.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
  $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $user->execute(array($_SESSION['auth']['id']));
  $utilisateur = $user->fetch(); 
}
include('../membres/functions.php'); 
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Mangas'Fan - Supprimer une news</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../images/favicon.png" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="../style.css" />
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
  else {
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
            <h3 class="titre_principal_news">
              Supprimer une news
            </h3>
            <a href="redac.php" class="btn btn-primary btn-sm">
              Retourner à l'index de la rédaction
            </a>
            <br/><br/>
            <?php 
            $selectionner_news = $pdo->prepare('SELECT * FROM billets WHERE id = ?');
            $selectionner_news->execute(array($_GET['id_news']));
            $suppression_news = $selectionner_news->fetch();
            if(!empty($_POST['supprimer_news']))
            {
              $modification = $pdo->prepare('DELETE FROM billets WHERE id = ?');
              $modification->execute(array($_GET['id_news']));
              ?>
              <div class='alert alert-success' role='alert'>
                La news a bien été supprimée !
              </div>
              <?php 
            }
            ?>
            <div class="alert alert-info" role="alert">
              <strong>Attention :</strong> Le processus de suppression de news est définitif ! Une fois la news supprimée, il est impossible de la récupérer. Soyez donc sûr de votre coup avant d'envisager une quelconque suppression.
            </div>
            <form method="POST" action="">
              <label>Supprimer :</label>
              <input type="submit" class="btn btn-sm btn-info" name="supprimer_news" value="Supprimer la news">
            </form>
          </section>
          </div>
        </div>
      </div>
    <?php } ?>
    <?php include('../elements/footer.php') ?>
  </body>
  </html>
