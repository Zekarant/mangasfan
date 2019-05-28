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
    include('../inc/functions.php'); ?>
<!DOCTYPE html>
<html lang="fr">
    <head>
      <meta charset="utf-8" />
      <title>Mangas'Fan - Supprimer un mangas</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="../images/favicon.png" />
      <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
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
          <h3 class="titre_pannel">
            Supprimer <span class="couleur_mangas">un</span> <span class="couleur_fans">mangas</span>
          </h3>
          <a href="redac.php" class="btn btn-primary btn-sm">
                Retourner à l'index de la rédaction
          </a>
          <br/><br/>
          <?php 
            $selectionner_mangas = $pdo->prepare('SELECT * FROM billets_mangas WHERE id = ?');
            $selectionner_mangas->execute(array($_GET['id_mangas']));
            $suppression_mangas = $selectionner_mangas->fetch();
            if(!empty($_POST['supprimer_news']))
              {
                $modification = $pdo->prepare('DELETE FROM billets_mangas WHERE id = ?');
                $modification->execute(array($_GET['id_mangas']));
                ?>
                <div class='alert alert-success' role='alert'>
                  Le mangas a bien été supprimé !
                </div>
                <?php 
              }
          ?>
          <div class="alert alert-info" role="alert">
            <strong>Attention :</strong> Le processus de suppression de mangas est définitif ! Une fois le mangas supprimé, il est impossible de le récupérer. Soyez donc sûr de votre coup avant d'envisager une quelconque suppression.
          </div>
          <form method="POST" action="">
            <label>Supprimer :</label>
            <input type="submit" class="btn btn-sm btn-info" name="supprimer_news" value="Supprimer le mangas">
          </form>
        </section>
    <?php }?>
    <?php include('../elements/footer.php') ?></center>
  </div>
  </body>
</html>
