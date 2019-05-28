<?php
    session_start();
    require_once '../inc/base.php';
    if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
  include('../inc/functions.php');
?>
<?php
 $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
 $allowedTags.='<li><ol><ul><span><div><br><ins><del>';  
 $elm1 = isset( $_POST['elm1'] ) ? $_POST['elm1'] : '';
// Should use some proper HTML filtering here.
  if($elm1!='') {
    $sHeader = '<h1>Ah, content is king.</h1>';
    $sContent = strip_tags(stripslashes($_POST['elm1']),$allowedTags);
} else {
    $sHeader = '<h1>Nothing submitted yet</h1>';
    $sContent = '<p>Start typing...</p>';
    $sContent.= '<p><img width="107" height="108" border="0" src="/mediawiki/images/badge.png"';
    $sContent.= 'alt="TinyMCE button"/>This rover has crossed over</p>';
  }
  include('../theme_temporaire.php');
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <title>Mangas'Fan - Rédaction</title>
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
    <link rel="stylesheet" href="<?php echo $lienCss; ?>" />
    <script>
     tinymce.init({
      selector: 'textarea',
      height: 250,
      language: 'fr_FR',
      theme: 'modern',
      plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
      toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
      image_advtab: true,
      entity_encoding : "raw",
      encoding: 'xml',
      extended_valid_elements : 'img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]',
      auto_focus: 'element1'
     });
    </script>
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
          Bienvenue sur le panneau de rédaction de <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fan</span>
        </h3>
        <div class='alert alert-info' role='alert'>
          <center>
            Information à tous les rédacteurs : Merci de consulter les fichiers aides situés dans chacunes de vos parties respectives et de ne pas communiquer vos accès à des tiers.
            </center>
        </div>
        <?php if(($utilisateur['grade'] == 5) || ($utilisateur['grade'] >= 9)){ ?>
          <h3 class="titre_pannel">
            News déjà présentes sur <span class="couleur_mangas">le</span> <span class="couleur_fans">site</span>
          </h3>
          <?php
            $news = $pdo->prepare("SELECT 
              b.id, 
              b.titre, 
              b.auteur, 
              DATE_FORMAT(b.date_creation, \"%d/%m/%Y\") AS date_news, 
              b.visible,
              u.username,
              u.grade
              FROM billets b 
              INNER JOIN 
              users u 
              ON 
              u.username = b.auteur 
              ORDER BY
              b.date_creation DESC");
            $news->execute();
          ?>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Titre de la news</th>
                <th class="tableau_mobile">Auteur</th>
                <th class="tableau_mobile">Date de la news</th>
                <th>Modification</th>
                <th>Suppression</th>
              </tr>
            </thead>
            <?php while($news_redac = $news->fetch()){ ?>
              <tr>
                <td><?php echo $news_redac['titre']; ?> <?php if($news_redac['visible'] == 1){ ?> - <strong>News cachée</strong> <?php } ?></td>
                <td class="tableau_mobile"><?php echo rang_etat($news_redac['grade'], $news_redac['auteur']); ?></td>
                <td class="tableau_mobile"><?php echo $news_redac['date_news']; ?></td>
                <td><a class="btn btn-outline-info" href="modif_news.php?id_news=<?php echo $news_redac['id'];?>">Modifier</a></td>
                <td><a class="btn btn-outline-danger" href="supp_news.php?id_news=<?php echo $news_redac['id'];?>">Supprimer</a></td>
              </tr>
            <?php } ?>
          </table>
        <?php }
        if(($utilisateur['grade'] == 8) || ($utilisateur['grade'] >= 9)){ ?>
          <h3 class="titre_pannel">
            Jeux déjà présents sur <span class="couleur_mangas">le</span> <span class="couleur_fans">site</span>
          </h3>
          <?php 
          $jeux = $pdo->prepare('SELECT id, titre, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date_jeux FROM billets_jeux ORDER BY id DESC'); 
          $jeux->execute(); ?>
          <table class="table table-striped">
            <thead>
                  <tr>
                    <th>Titre du jeu</th>
                    <th class="tableau_mobile">Date de création</th>
                    <th>Modification</th>
                    <th>Suppression</th>
                  </tr>
            </thead>
            <?php while ($jeux_redac = $jeux->fetch()){ ?>
              <tr>
                <td><?php echo $jeux_redac['titre']; ?></td>
                <td class="tableau_mobile"><?php echo $jeux_redac['date_jeux']; ?></td>
                <td><a class="btn btn-outline-info" href="modif_jeux/<?= traduire_nom(stripslashes($jeux_redac['titre']));?>">Modifier</a></td>
                <td><a class="btn btn-outline-danger" href="supp_news_jeux.php?id_jeux=<?php echo $jeux_redac['id'];?>">Supprimer</a></td>
              </tr>
            <?php } ?>
          </table>
          <?php } if(($utilisateur['grade'] == 6) || ($utilisateur['grade'] == 7) || ($utilisateur['grade'] >= 9)){ ?>
          <h3 class="titre_pannel">
            Mangas/Animes déjà présents sur <span class="couleur_mangas">le</span> <span class="couleur_fans">site</span>
          </h3>
          <?php 
          $mangas = $pdo->prepare('SELECT id, titre, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date_mangas FROM billets_mangas ORDER BY id DESC'); 
          $mangas->execute(); ?>
          <table class="table table-striped">
            <thead>
                  <tr>
                    <th>Titre du mangas/anime</th>
                    <th class="tableau_mobile">Date de création</th>
                    <th>Modification</th>
                    <th>Suppression</th>
                  </tr>
            </thead>
            <?php while ($mangas_redac = $mangas->fetch()){ ?>
              <tr>
                <td><?php echo $mangas_redac['titre']; ?></td>
                <td class="tableau_mobile"><?php echo $mangas_redac['date_mangas']; ?></td>
                <td><a class="btn btn-outline-info" href="modif_mangas/<?= traduire_nom(stripslashes($mangas_redac['titre']));?>">Modifier</a></td>
                <td><a class="btn btn-outline-danger" href="supp_news_mangas.php?id_mangas=<?php echo $mangas_redac['id'];?>">Supprimer</a></td>
              </tr>
            <?php } ?>
          </table>
          <?php } } ?>
      </section>
      <?php include('../elements/footer.php') ?></center>
    </div>
  </body>
</html>