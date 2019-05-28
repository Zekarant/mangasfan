<?php
  session_start();
  require_once '../inc/base.php';
  include('../theme_temporaire.php');
  if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
  include('../inc/functions.php');
?>
<!DOCTYPE HTML>
<html>
    <head>
      <meta charset="utf-8" />
      <title>Mangas'Fan - Supprimer une catégorie</title>
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
      	$crit = stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($_GET['id_onglet'])))));
      	$select_all_news = $pdo->query("SELECT * FROM billets_jeux_onglet WHERE id ='".$crit."'");
      	$new = $select_all_news->fetch();

      ?>
	   <center><div class="titre_commentaire_news">Suppression de<span class="couleur_mangas"> la</span><span class="couleur_fans"> page</p></span></div></center>
     <a href="redac.php">Retournez à l'index de la rédaction</a> - <a href="modif_news_jeux.php?id_news=<?php echo $new['billets_id'];?>">Retournez à l'administration du jeu</a>
    <?php 
      $suppression = false;
      if(!empty($_POST['supprimer_onglet'])){
        $position_onglet = $pdo->prepare("SELECT * FROM billets_jeux_onglet WHERE billets_id = ?");
        $position_onglet->execute(array($new['billets_id']));
        
        while($position_all = $position_onglet->fetch()){
          if ($position_all['position'] > $new['position']){
            $update = $pdo->prepare("UPDATE billets_jeux_onglet SET position = ? WHERE id = ?");
            $update->execute(array($position_all['id'], $position_all['position'] - 1));
          }
        }
    
        $delete_page_jeu = $pdo->prepare("DELETE FROM billets_jeux_pages WHERE onglet_id = ?");
        $delete_page_jeu->execute(array($crit));
        $delete_onglet = $pdo->prepare("DELETE FROM billets_jeux_onglet WHERE id = ?");
        $delete_onglet->execute(array($crit));
        $suppression = true;
    	  echo "<div class='alert alert-success' role='alert'>Votre onglet a bien été supprimé !</div>";
      } 
      elseif(isset($_POST['transfere_pages'])){
        $categorie = addslashes(htmlspecialchars($_POST['liste_onglets']));
        $transfere_page = $pdo->prepare("UPDATE billets_jeux_pages SET onglet_id = ? WHERE onglet_id = ?");
        $transfere_page->execute(array($crit));
        $onglet_id = $pdo->query("SELECT id FROM billets_jeux_onglet WHERE nom = ? AND billets_id = ?");
        $onglet_id->execute(array($categorie, $new['billets_id']));
        $onglet = $onglet_id->fetch();
        $transfere_page->execute(array($onglet['id']));
      }
       else {} ?><br/><br/>

  <?php if(!$suppression){ ?>
  <div class='alert alert-warning' role='alert'><b>Attention : </b>Supprimer un onglet possèdant des pages reviens a supprimer également les pages crées ! Après avoir valider la suppression, il vous sera impossible de récupérer votre page ! Veuillez transférer au besoin les pages, soit une par une, soit avec l'option présente en bas de page.</div>
  <div class="form-group" name="theme">
      <form method="POST" action="">
          <label for="exampleSelect1">Supprimer :</label>
          <input type="submit" class="btn btn-sm btn-info" name="supprimer_onglet" value="Supprimer l'onglet">
     </form>
  </div>

  <center><div class="titre_commentaire_news">Transfert <span class="couleur_mangas"> G</span><span class="couleur_fans">énéral</p></span></div></center>
  <div class='alert alert-warning' role='alert'><b>Attention : </b>Vous ne pouvez pas transférer une page d'un jeu a l'autre.</div>
    <div class="form-group" name="theme">
          <?php 
          $onglet_exist = $pdo->prepare("SELECT * FROM billets_jeux_onglet WHERE billets_id = ? AND id <> ?");
          $onglet_exist->execute(array($new['billets_id'], $crit));
          $onglet_existe = $onglet_exist->rowCount();

          if ($onglet_existe == 0){
            echo 'Il n\'existe aucun onglet autre que celui que vous souhaitez supprimer.';
          } else { ?>
          <form method="POST" action="">
            <label> Transférer vers l'onglet : </label>
            <select class="form-control" id="exampleSelect1" name="liste_onglets">
                <?php while($parcours_onglet = $onglet_exist->fetch()) { ?>
                <option><?php echo $parcours_onglet['nom'] ;?></option>
                <?php } ?>
            </select><br /><br />
            <?php } ?>

          <br /><br /><input type="submit" class="btn btn-sm btn-info" name="transfere_pages" value="Valider le transfère">
     </form>
  </div>

  <?php } ?>

<?php 
  } else {
  	echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
  } 
?>

</section>
    <?php include('../elements/footer.php') ?></center>
  </div>
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