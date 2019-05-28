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
        <title>Mangas'Fan - Modifier un mangas</title>
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
        <script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="tinymce/js/tinymce/tinymce.js"></script>
        <script type="text/javascript">
          tinymce.init({
          selector: 'textarea',
          height: 250,
          language: 'fr_FR',
          theme: 'modern',
          plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code help wordcount'
          ],
          toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
          content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css']
        });
        </script>

    </head>
  <body>
    <div id="bloc_page">
    <?php
     if(($utilisateur['grade'] == 7) || ($utilisateur['grade'] >= 10)){
       $crit = stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($_GET['id_news'])))));
       $select_all_news = $pdo->prepare("SELECT * FROM billets_mangas WHERE id = ?");
       $select_all_news->execute(array($crit));
       $verif_page_existe = $select_all_news->rowCount();
       if ((int) $crit > 0 && $verif_page_existe != 0){ 
          $mangas = $select_all_news->fetch();
    ?>
    <?php include('../elements/nav_redac.php') ?>
      <center>
        <h2 class="titre_commentaire_news"><img src="https://zupimages.net/up/18/25/mjqz.png" alt="Modifier le mangas"/> Modifier <span class="couleur_mangas">le</span> <span class="couleur_fans">manga</span></h2>
      </center>
    <a href="redac.php" >Retournez à l'index de la rédaction</a>
      <?php 
      // déclaration des variables
      $message = '';
      $message_erreur = '';
      $message2 = '';
      $message_erreur2 = '';
      $message3 = '';
      $message_erreur3 = '';
      $contenu = '';
      $titre_page = '';

      if(!empty($_POST['valider_titre']))
        {
           $titre = addslashes(htmlspecialchars($_POST['new_titre']));
           $modif_cat = $pdo->prepare("UPDATE billets_mangas SET titre = ? WHERE id = ?");
           $modif_cat->execute(array($titre, $mangas['id']));
          echo "<div class='alert alert-success' role='alert'>Le titre du manga a bien été modifié !</div>";

          // on met à jour $mangas
          $select_all_news = $pdo->prepare("SELECT * FROM billets_manga WHERE id = ?");
          $select_all_news->execute(array($crit));
          $mangas = $select_all_news->fetch();
        }
          elseif(!empty($_POST['valider_theme']))
        {
           $image = addslashes(htmlspecialchars($_POST['new_image']));
           $modif_cat = $pdo->exec("UPDATE billets_mangas SET theme = ? WHERE id = ?");
           $modif_cat->execute(array($image, $mangas['id']));
           echo "<div class='alert alert-success' role='alert'>L'image du mangas a bien été modifié !</div>";

          // on met à jour $mangas
          $select_all_news = $pdo->prepare("SELECT * FROM billets_mangas WHERE id = ?");
          $select_all_news->execute(array($crit));
          $mangas = $select_all_news->fetch();

        }

        // Le cas des éléments facultatifs
        elseif(isset($_POST['valider_elts_fac'])){
            if(!empty($_POST['genre'])){
            $genre = addslashes(htmlspecialchars($_POST['genre']));
            $modif_genre = $pdo->prepare("UPDATE billets_mangas SET genre_mangas = ? WHERE id = ?");
            $modif_genre->execute(array($genre, $mangas['id']));
          } else {
            $modif_genre = $pdo->prepare("UPDATE billets_mangas SET genre_mangas = NULL WHERE id = ?");
            $modif_genre->execute(array($mangas['id']));
          }

          if(!empty($_POST['date_sortie'])){
            $date_sortie = addslashes(htmlspecialchars($_POST['date_sortie']));
            $modif_sortie = $pdo->prepare("UPDATE billets_mangas SET date_sortie = ? WHERE id = ?");
            $modif_sortie->execute(array($date_sortie, $mangas['id']));
          } else {
            $modif_sortie = $pdo->prepare("UPDATE billets_mangas SET date_sortie = NULL WHERE id = ?");
            $modif_sortie->execute(array($mangas['id']));
          }

          if(!empty($_POST['langue'])){
            $langue = addslashes(htmlspecialchars($_POST['langue']));
            $modif_langue = $pdo->prepare("UPDATE billets_mangas SET langue = ? WHERE id = ?");
            $modif_langue->execute(array($langue, $mangas['id']));
          } else {
            $modif_langue = $pdo->prepare("UPDATE billets_mangas SET langue = NULL WHERE id = ?");
            $modif_langue->execute(array($mangas['id']));
          }

          $message = "<div class='alert alert-success' role='alert'>Vos modifications ont été prise en compte.</div>";

          $select_all_news = $pdo->prepare("SELECT * FROM billets_mangas WHERE id = ?");
          $select_all_news->execute(array($crit));
          $mangas = $select_all_news->fetch();
        }  

        elseif (!empty($_POST['valider_onglet'])){
          $nom_categorie = addslashes(htmlspecialchars($_POST['new_onglet']));
          $categorie_deja_present = $pdo->prepare("SELECT * FROM billets_mangas_onglet WHERE billets_id = ? AND nom = ?");
          $categorie_deja_present->execute(array($crit, $nom_categorie));
          if ($categorie_deja_present->rowCount() == 0){
            $ajoute_categorie = $pdo->prepare('INSERT INTO billets_mangas_onglet(billets_id,nom,position) VALUES(?,?,?)');
            $derniere_categorie = $pdo->prepare("SELECT position FROM billets_mangas_onglet WHERE position = (SELECT MAX(position) FROM billets_mangas_onglet WHERE billets_id = ?)");
            $derniere_categorie->execute(array($crit));
            $dern_categorie = $derniere_categorie->fetch();
            $ajoute_categorie->execute(array($crit, $nom_categorie, $dern_categorie['position']+1));


            $message2 = "<div class='alert alert-success' role='alert'>Votre catégorie à été créée !</div>";

          } else {
            $message_erreur2 = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Vous ne pouvez pas remettre une catégorie déjà existante.</div>";
          }

        }

        elseif (isset($_POST['valider_page'])){
          $categorie = addslashes(htmlspecialchars($_POST['liste_categorie']));
          $titre_page = addslashes(htmlspecialchars($_POST['titre_page']));
          $contenu = addslashes(htmlspecialchars($_POST['en_attente']));
          $onglet_id = $pdo->prepare("SELECT id FROM billets_mangas_onglet WHERE nom = ? AND billets_id = ?");
          $onglet_id->execute(array($categorie, $crit));
          $onglet = $onglet_id->fetch();
          if ($categorie != '---'){
            if (!empty($_POST['titre_page'])){
              if (strlen($titre_page) > 4 AND strlen($titre_page) < 36){
                $titre_exist = $pdo->prepare("SELECT * FROM billets_mangas_pages WHERE onglet_id = ? AND nom = ?");
                $titre_exist->execute(array($onglet['id'], $titre_page));
                if ($titre_exist->rowCount() == 0){

                  if (!empty($_POST['en_attente'])){
                    $ajoute_page = $pdo->prepare('INSERT INTO billets_mangas_pages(onglet_id,nom,contenu,position) VALUES(?,?,?,?)');
              
                    $derniere_page = $pdo->prepare("SELECT position FROM billets_mangas_pages WHERE position = (SELECT MAX(position) FROM billets_mangas_pages WHERE onglet_id = ?)");
                    $derniere_page->execute(array($onglet['id']));

                    // il n'y a pas encore de page
                    if ($derniere_page->rowCount() == 0){
                      $last_page = 1;
                    } else {
                      $last_page0 = $derniere_page->fetch();
                      $last_page = $last_page0['position'] + 1;

                    }

                    $ajoute_page->execute(array($onglet['id'],$titre_page,$contenu,$last_page));
                    
                    $titre_page = '';
                    $contenu = '';

                    $message3 = "<div class='alert alert-success' role='alert'>Votre page a bien été crée !</div>";
                  } else {
                    $message_erreur3 = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Veuillez remplir le champ \"Texte\".</div>";
                  }
                 } else {
                  $message_erreur3 = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Le titre est déjà utilisé pour une autre page.</div>";
                }
              } else {
                $message_erreur3 = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Vous devez sélectionner un titre possédant entre 5 et 35 caractères.</div>";
              }
            } else {
              $message_erreur3 = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Veuillez remplir le champ \"Titre\".</div>";
            }
          } else {
            $message_erreur3 = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Vous devez sélectionner une catégorie proposée. S'il vous manque la catégorie correspondant, veuillez l'ajouter.</div>";
          }
        }

        else {

        }
    ?>
    <br/>
    <h3 class="titre_commentaire_news">Éléments <span class="couleur_mangas">généraux</span> </h3>
       <form method="POST" action="">
          <label>Modifier le titre du mangas : </label>
           <input type="text" class="form-control" name="new_titre" value="<?php echo $mangas['titre'];?>">
           <input type="hidden" name="id_news" value="<?php echo $mangas['id'];?>">
           <input type="submit" class="btn btn-sm btn-info" name="valider_titre" value="Modifier le titre">
       </form><br/>

       <form method="POST" action="">
          <label>Modifier l'image du mangas : </label>
           <input type="text" class="form-control" name="new_image" value="<?php echo htmlspecialchars($mangas['theme']);?>">
           <input type="hidden" name="id_news" value="<?php echo $mangas['id'];?>">
           <input type="submit" class="btn btn-sm btn-info" name="valider_theme" value="Modifier l'image">
      </form><br/>

      <h3 class="titre_commentaire_news">Éléments <span class="couleur_fans">facultatifs</span> </h3>
      <?php echo $message; echo $message_erreur; ?>
      <form method="POST" action="">
           <label>Genre :</label><input type="text" class="form-control" name="genre" value="<?php if($mangas['genre_mangas'] != NULL){echo htmlspecialchars($mangas['genre_mangas']);};?>">
           <p><span style="color:green;font-style:italic;"><b>Exemples :</b> mangas de Combat, RPG, mangas de rythme ...</span></p>
           <label>Date de sortie :</label><input type="text" class="form-control" name="date_sortie" value="<?php if($mangas['date_sortie'] != NULL){echo htmlspecialchars($mangas['date_sortie']);};?>">
           <p><span style="color:green;font-style:italic;"><b>Exemples :</b> 26 janvier 2018 (Japon) - 1er février 2018 (Monde)</span></p>
           <label>Langue :</label><input type="text" class="form-control" name="langue" value="<?php if($mangas['langue'] != NULL){ echo htmlspecialchars($mangas['langue']); };?>">
           <p><span style="color:green;font-style:italic;"><b>Exemples :</b> Multilingue, Anglais ...</span></p>

           <input type="hidden" name="id_news" value="<?php echo $mangas['id'];?>">
           <input type="submit" class="btn btn-sm btn-info" name="valider_elts_fac" value="Modifier les éléments facultatifs">
      </form>
      <hr />
      <center>
        <h2 class="titre_commentaire_news"><img src="https://zupimages.net/up/18/25/es4a.png" alt="Modifier la navigation"/> Modifier <span class="couleur_mangas">la</span> <span class="couleur_fans">navigation</span></h2></center>
        
        <h3 class="titre_commentaire_news">Modérer <span class="couleur_mangas">la navigation</span></h3>
        <table class="table table-striped">
         <thead>
              <tr>
                <th>Catégorie</th>
                <th>Page</th>
                <th>Modification</th>
                <th>Suppression</th>
                <th></th>
              </tr>
          </thead>
          <?php $liste_onglet = $pdo->prepare("SELECT * FROM billets_mangas_onglet WHERE billets_id = '$crit' ORDER BY position");
          $liste_onglet->execute(array($crit));

          $derniere_categorie = $pdo->prepare("SELECT position FROM billets_mangas_onglet WHERE position = (SELECT MAX(position) FROM billets_mangas_onglet WHERE billets_id = ?)");
          $derniere_categorie->execute(array($crit));
          $dern_categorie = $derniere_categorie->fetch();

           while ($parcours_categorie = $liste_onglet->fetch()) { ?>
              <tr>
                <td><?php echo stripslashes($parcours_categorie['nom']);?></td>
                <td></td>
                <td><b><a href="modif_categorie_mangas.php?id_onglet=<?php echo $parcours_categorie['id']; ?>">Modifier l'onglet</a></b></td>
                <td><b><a href="supprime_categorie_mangas.php?id_onglet=<?php echo $parcours_categorie['id'];?>">Supprimer la catégorie</a></b></td>
                <td></td>
                <td><?php if($parcours_categorie['position'] != 1){?><a href="modif_ordre_onglet_mangas.php?id=<?php echo $parcours_categorie['id']; ?>&type=up&genre=categorie"><img src="https://zupimages.net/up/18/25/pmcm.png" alt="Modifier categorie" /></a><?php } ?> <?php if($parcours_categorie['position'] != $dern_categorie['position']){ ?><a href="modif_ordre_onglet_mangas.php?id=<?php echo $parcours_categorie['id'];?>&type=down&genre=categorie"><img src="https://zupimages.net/up/18/25/z7h5.png" alt="Modifier la catégorie" /></a><?php } ?></td>
              </tr>
              <?php 
              $liste_page = $pdo->prepare("SELECT * FROM billets_mangas_pages WHERE onglet_id = ? ORDER BY position");
              $liste_page->execute(array($parcours_categorie['id']));

              $derniere_page = $pdo->prepare("SELECT position FROM billets_mangas_pages WHERE position = (SELECT MAX(position) FROM billets_mangas_pages WHERE onglet_id = ?)");
              $derniere_page->execute(array($parcours_categorie['id']));
              $dern_page = $derniere_page->fetch();
              while ($parcours_page = $liste_page->fetch()){ ?>
              <tr>
                <td></td>
                <td><?php echo stripslashes($parcours_page['nom']);?></td>
                <td><b><a href="modif_page_mangas.php?id_page=<?php echo $parcours_page['id'];?>">Modifier la page</a></b></td>
                <td><b><a href="supprime_page_mangas.php?id_news=<?php echo $parcours_page['id'];?>">Supprimer la page</a></b>
                </td>
                <td><?php if($parcours_page['position'] != 1){?><a href="modif_ordre_onglet_mangas.php?id=<?php echo $parcours_page['id']; ?>&type=up&genre=page"><img src="https://zupimages.net/up/18/25/4ap2.png" alt="Modifier l'ordre" /></a><?php } ?> <?php if($parcours_page['position'] != $dern_page['position']){ ?><a href="modif_ordre_onglet_mangas.php?id=<?php echo $parcours_page['id'];?>&type=down&genre=page"><img src="https://zupimages.net/up/18/25/va0b.png" alt="Modifier l'ordre" /></a><?php } ?></td>
                <td></td>
              </tr>
              <?php } ?>
          <?php } ?>
        </table>



        <h3 class="titre_commentaire_news">Création <span class="couleur_fans">d'une catégorie</span> </h3>
        <?php echo $message2; echo $message_erreur2; ?>
        <form method="POST" action="">
          <label> Nom de la catégorie : </label>
           <input type="text" class="form-control" name="new_onglet">
           <input type="hidden" name="id_news" value="<?php echo $mangas['id'];?>">
           <input type="submit" class="btn btn-sm btn-info" name="valider_onglet" value="Ajouter la catégorie">
      </form>

        <h3 class="titre_commentaire_news">Création <span class="couleur_mangas">d'une page</span></h3>
        <?php 
          $onglet_exist = $pdo->prepare("SELECT * FROM billets_mangas_onglet WHERE billets_id = ?");
          $onglet_exist->execute(array($crit));
          $onglet_existe = $onglet_exist->rowCount();

          if ($onglet_existe == 0){
            echo '<span style="padding-left:15px;">Il n\'existe aucun onglet actuellement. Veuillez en créer un pour pouvoir écrire une page</span>';
          } else { echo $message3; echo $message_erreur3;?>
          <form method="POST" action="">
            <label> Sélectionnez une catégorie : </label>
            <select class="form-control" id="exampleSelect1" name="liste_categorie">
                <option>---</option>
                <?php while($parcours_categorie = $onglet_exist->fetch()) { ?>
                <option><?php echo $parcours_categorie['nom'];?></option>
                <?php } ?>
            </select><br /><br />
            <label> Titre : </label>
            <input type="text" class="form-control" name="titre_page" value="<?php echo stripslashes($titre_page);?>"><br /><br />
            <label> Texte : </label>
            <textarea name="en_attente"><?php echo htmlspecialchars_decode(htmlspecialchars_decode($contenu));?></textarea>

            <input type="hidden" name="id_news" value="<?php echo $mangas['id'];?>"><br />
            <input type="submit" class="btn btn-sm btn-info" name="valider_page" value="Valider la page" />
          </form>
          <?php }
        ?>

        <?php include('../elements/footer.php') ?>

    <?php
      } else {
        echo"<div class='alert alert-danger' role='alert'>Cette page est inexistante.</div>";
      }
    
    } else {
       echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
    }
    ?>

</div>
  </body>
</html>

<style>
.form-control[name="genre"], .form-control[name="plateforme"], .form-control[name="mode_de_mangas"], .form-control[name="date_sortie"], .form-control[name="langue"], .form-control[name="pegi"], .form-control[name="new_onglet"]
,.form-control[name="titre_page"],.form-control[name="new_image"],.form-control[name="new_titre"]
{
  padding:none !important;
  width:30%;
  display:inline-block;
  margin-left:15px;
  margin-bottom:5px;
}

.form-control[name="new_image"]{
  width:50% !important;
}

.form-control[name="liste_categorie"], option {
  width:40%;
  display:inline-block;
}

hr {
  height: 1px;
  padding: 0;
  background-color: #ACACAC;
}

form, a[href="redac.php"] {
  padding-left:15px;
  padding-right:15px;
}

h3 {
  font-size:22px !important;
  border-bottom:2px solid grey;
  text-align:left !important;
  margin-left:15px;
  margin-right:15px;
  padding-bottom:10px;
}
</style>
