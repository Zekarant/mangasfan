<?php
  session_start();
  require_once '../inc/base.php';
  include('inc/functions.php');
  if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
?>

<!DOCTYPE HTML>
  <html>
    <head>
        <meta charset="utf-8" />
        <title>Mangas'Fan - Modifier un jeux</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
        <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="icon" href="../images/favicon.png"/>
        <link rel="stylesheet" href="../style.css" />
      </head>
      <body>
        <div id="bloc_page">
        <?php 

          if($utilisateur['grade'] >= 4){
              $id_cat_page = stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($_GET['id'])))));
              $type = stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($_GET['type'])))));
              $genre = stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($_GET['genre'])))));

              if ($type == 'up'){
                $valeur = -1;
              } else {
                $valeur = 1;
              }

              if ($genre == 'page'){
                $select_all_news = $pdo->prepare("SELECT * FROM billets_jeux_pages WHERE id = ?");  
                $select_all_news->execute(array($id_cat_page));
                if ((int) $id_cat_page > 0 && $select_all_news->rowCount() != 0 && $type == 'up' || $type == 'down'){
                  $new = $select_all_news->fetch();

                  $modif_position_page_sup = $pdo->prepare("UPDATE billets_jeux_pages SET position = ? WHERE position = '$new['position']' + '$valeur' AND onglet_id = '$new['onglet_id']'");
                  $modif_position_page_sup->execute(array($new['position']));

                  $modif_position_page = $pdo->prepare("UPDATE billets_jeux_pages SET position = ? WHERE id = '$id_cat_page'");
                  $modif_position_page->execute(array(($new['position']) + $valeur));

                  $select_cat = $pdo->query("SELECT * FROM billets_jeux_onglet WHERE id = '$new['onglet_id']'")->fetch();

                  header("Location: modif_news_jeux.php?id_news=".$select_cat['billets_id']);

                } else {
                  echo"<div class='alert alert-danger' role='alert'>Page introuvable</div>";
                }

              } elseif ($genre == 'categorie'){
                $select_all_news = $pdo->query("SELECT * FROM billets_jeux_onglet WHERE id ='".$id_cat_page."'");  
                if ((int) $id_cat_page > 0 && $select_all_news->rowCount() != 0 && $type == 'up' || $type == 'down'){
                  $new = $select_all_news->fetch();

                  $modif_position_onglet_sup = $pdo->prepare("UPDATE billets_jeux_onglet SET position = ? WHERE position = '$new['position']' + '$valeur' AND billets_id = '$new['billets_id']'");
                  $modif_position_onglet_sup->execute(array($new['position']));

                  $modif_position_onglet = $pdo->prepare("UPDATE billets_jeux_onglet SET position = ? WHERE id = '$id_cat_page'");
                  $modif_position_onglet->execute(array($new['position'] + $valeur));

                  header("Location: modif_news_jeux.php?id_news=".$new['billets_id']);
              } else {
                echo"<div class='alert alert-danger' role='alert'>Page introuvable</div>";
              }

            } else {
              echo"<div class='alert alert-danger' role='alert'>Page introuvable</div>";
            }
                
          } else {
             echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
          }

        ?>
      </div>
      </body>
    </html>
