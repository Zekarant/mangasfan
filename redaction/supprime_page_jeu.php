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
?>
<!DOCTYPE HTML>
<html>
    <head>
      <meta charset="utf-8" />
      <title>Mangas'Fan - Supprimer une page</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <link rel="icon" href="../images/favicon.png"/>
      <link rel="stylesheet" href="<?php echo $lienCss; ?>" />
    </head>
<body>
  <div id="bloc_page">
    <?php include('../elements/nav_redac.php') ?>
 <section class="marge_page">
    <?php
      if($utilisateur['grade'] >= 5){
      	$crit = stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($_GET['id_news'])))));
      	$select_all_news = $pdo->query("SELECT * FROM billets_jeux_pages WHERE id ='".$crit."'")->fetch();
        $select_all = $pdo->query("SELECT billets_id FROM billets_jeux_onglet WHERE id = '$select_all_news['onglet_id']'");
      	$new = $select_all->fetch();

      //on affiche toutes les infos de la news sauf la date (qui n'est pas modifiable car, inutile)
      ?>
	   <center><div class="titre_commentaire_news">Suppression de<span class="couleur_mangas"> la</span><span class="couleur_fans"> page</p></span></div></center>
     <a href="redac.php">Retournez à l'index de la rédaction</a> - <a href="modif_news_jeux.php?id_news=<?php echo $new['billets_id'];?>">Retournez à l'administration du jeu</a>
    <?php 
      $suppression = false;
        if(!empty($_POST['supprimer_page'])){
          $position_onglet = $pdo->query("SELECT * FROM billets_jeux_pages WHERE onglet_id = '$select_all_news['onglet_id']'");
          
          while($position_all = $position_onglet->fetch()){
            if ($position_all['position'] > $select_all_news['position']){
              $update = $pdo->prepare("UPDATE billets_jeux_pages SET position = ? WHERE id = '$position_all['id']'");
              $update->execute(array($position_all['position'] - 1));
            }
          }

    	   $delete_page_jeu = $pdo->exec("DELETE FROM billets_jeux_pages WHERE id = '$crit'");
         $suppression = true;
    	   echo "<div class='alert alert-success' role='alert'>Votre page a bien été supprimé !</div>";
      } else {} ?><br/><br/>

  <?php if(!$suppression){ ?>
  <div class='alert alert-warning' role='alert'><b>Attention : </b>Après avoir valider la suppression, il vous sera impossible de récupérer votre page !</div>
  <div class="form-group" name="theme">
      <form method="POST" action="">
          <label for="exampleSelect1">Supprimer :</label>
          <input type="submit" class="btn btn-sm btn-info" name="supprimer_page" value="Supprimer la page">
     </form>
  </div>
  <?php } ?>

<?php
	}
else
{
	echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
}
?>

</section>
    <?php include('../elements/footer.php') ?></center>
  </div>
  </body>
</html>
