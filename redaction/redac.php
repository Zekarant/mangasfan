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
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title>Mangas'Fan - Rédaction</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../images/favicon.png" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript" src="tinymce/js/tinymce/tinymce.js"></script>
  <link rel="stylesheet" href="../style.css" />
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
                  <p>Status : <td><?php if($utilisateur['chef'] != 0){ echo chef(sanitize($utilisateur['chef'])); } else { echo statut(sanitize($utilisateur['grade'])); } ?></td></p>
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
                  <h3 class="titre_principal_news">
                    Bienvenue sur le panneau de rédaction de Mangas'Fan
                  </h3>
                  <div class='alert alert-info' role='alert'>
                    <center>
                      Information à tous les rédacteurs : Merci de consulter les fichiers aides situés dans chacunes de vos parties respectives et de ne pas communiquer vos accès à des tiers.
                    </center>
                  </div>
                  <?php if(($utilisateur['grade'] == 5) || ($utilisateur['grade'] >= 9)){ ?>
                    <h3 class="titre_secondaire">
                      News déjà présentes sur le site
                    </h3>
                    <?php
                            if (!empty($_POST['demande'])) {
                              $jugement = explode(' ', $_POST['demander']);
                              $examiner_jugement = $pdo->prepare('UPDATE billets SET demande = 1 WHERE id = ?');
                              $examiner_jugement->execute(array($jugement[1]));
                              $recuperation = $pdo->prepare('SELECT titre FROM billets WHERE id = ?');
                              $recuperation->execute(array($jugement[1]));
                              $afficher = $recuperation->fetch();
                              $chef_groupe = $pdo->prepare('SELECT * FROM users WHERE chef = 5');
                              $chef_groupe->execute();
                              $chef = $chef_groupe->fetch();
                          $text_avertissement_2 = '
                          <p>Cher ' . $chef['username'] . ',<br/>
                          Si vous recevez ce message privé, c\'est qu\'un membre de <strong>l\'équipe des newseurs</strong> a fait une demande pour une suppression d\'article.</p>
                          <p>L\'article en question est : « <strong>' . $afficher['titre'] . '</strong> »<br/>
                          Cette demande a été envoyée par <strong>' . rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username'])) . '</strong>.</p>
                          <hr>
                          <p>Pour confirmer cette suppression, cliquez sur le bouton vert, sinon, pour ne pas supprimer l\'article, cliquez sur le bouton rouge : <br/><a href="https://www.mangasfan.fr/redaction/supp_news.php?id_news=' . $jugement[1] . '" class="btn btn-outline-success target="_blank">Supprimer « ' . $afficher['titre'] . ' »</a><form method="POST" action=""><input type="hidden" class="btn btn-outline-danger" name="suppression" value="supprimer '. $jugement[1] .'" /><input type="submit" class="btn btn-outline-danger" name="demande_suppression" value="Annuler la suppression"></form></p>
                          <hr>
                          <p><small>Ce message est un message automatique envoyé au chef de groupe des newseurs, toute réponse à ce message ne sera donc pas consultée.</small></p>';
                          $deuxième_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, 1, ?, ?, ?, 1)');
                          $deuxième_mp->execute(array("Vous avez reçu une demande de suppression !", $text_avertissement_2, time()));
                            }
                           

                    if (!empty($_GET['page']) && is_numeric($_GET['page']))
                      $page = stripslashes($_GET['page']);
                    else
                      $page = 1;
                    $pagination = 10;
                          // Numéro du 1er enregistrement à lire
                    $limit_start = ($page - 1) * $pagination;
                    $nb_total = $pdo->prepare('SELECT COUNT(*) AS nb_total FROM billets');
                    $nb_total->execute();
                    $nb_total = $nb_total->fetchColumn();
                          // Pagination
                    $nb_pages = ceil($nb_total / $pagination);
                    $news = $pdo->prepare("SELECT 
                      b.id, 
                      b.titre, 
                      b.auteur, 
                      date_creation,
                      b.visible,
                      b.demande,
                      u.username,
                      u.grade
                      FROM billets b 
                      INNER JOIN 
                      users u 
                      ON 
                      u.username = b.auteur 
                      ORDER BY
                      b.date_creation DESC LIMIT $limit_start, $pagination");
                    $news->execute();
                    ?>
                    <nav>
                      <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                          <a class="page-link" href="#" tabindex="-1">Pages :</a>
                        </li>
                        <?php
                    // Boucle sur les pages
                        for ($i = 1; $i <= $nb_pages; $i++) {
                          if ($i == $page){
                            ?>
                            <li class="page-item">
                              <a class="page-link" href="#">
                                <?php echo $i; ?>
                              </a>
                            <?php } else { ?>
                              <li class="page-item">
                                <a class="page-link" href="<?php echo "?page=" . $i; ?>">
                                  <?php echo $i;?>
                                </a>
                              </li>
                            <?php } } ?>
                          </ul>
                        </nav>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Titre de la news</th>
                              <th class="tableau_mobile">Auteur</th>
                              <th class="tableau_mobile">Date</th>
                              <th>Modification</th>
                              <?php if(($utilisateur['grade'] >= 5 AND $utilisateur['chef'] >= 5) OR $utilisateur['grade'] >= 10) { ?>
                                <th>Suppression</th>
                              <?php } else { ?>
                                <th>Demander une suppression</th>
                              <?php } ?>
                            </tr>
                          </thead>
                          <?php while($news_redac = $news->fetch()){ ?>
                            <tr>
                              <td><?php echo $news_redac['titre']; ?> <?php if($news_redac['visible'] == 1){ ?> - <strong>News cachée</strong> <?php } ?> <?php if (date('Y-m-d H:i:s') <= $news_redac['date_creation']){ ?> - <strong>News programmée</strong> <?php } ?></td>
                              <td class="tableau_mobile"><?php echo rang_etat($news_redac['grade'], sanitize($news_redac['auteur'])); ?></td>
                              <td class="tableau_mobile"><?php echo date('d/m/Y', strtotime(sanitize($news_redac['date_creation']))); ?></td>
                              <td><a class="btn btn-outline-info" href="modif_news.php?id_news=<?php echo sanitize($news_redac['id']); ?>">Modifier</a></td>
                              <?php if(($utilisateur['grade'] >= 5 AND $utilisateur['chef'] >= 5) OR $utilisateur['grade'] >= 10) { ?>
                                 <td><a class="btn btn-outline-danger" href="supp_news.php?id_news=<?php echo sanitize($news_redac['id']); ?>">Supprimer</a></td>
                              <?php } else {
                                if ($news_redac['demande'] == 0) { ?>
                                  <td>
                                    <form method="POST" action="">
                                      <input type="hidden" class="btn btn-outline-danger" name="demander" value="supprimer <?php echo $news_redac['id']; ?>" />
                                      <input type="submit" name="demande" class="btn btn-outline-danger" value="Demander une suppression" />
                                     
                                    </form></td>
                                <?php } else { ?>
                                  <td><button class="btn btn-outline-secondary">Demande en attente</button></td>
                                <?php }
                               } ?>
                            </tr>
                          <?php } ?>
                        </table>
                        <br/>
                      <?php } if(($utilisateur['grade'] == 6) || ($utilisateur['grade'] >= 9)){ ?>
                        <br/>
                        <h3 class="titre_secondaire">
                          Jeux déjà présents sur le site
                        </h3>
                        <?php 
                        $jeux = $pdo->prepare('SELECT id, titre, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date_jeux FROM billets_jeux ORDER BY id DESC'); 
                        $jeux->execute(); ?>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Titre du jeu</th>
                              <th class="tableau_mobile">Date</th>
                              <th>Modification</th>
                              <th>Suppression</th>
                            </tr>
                          </thead>
                          <?php while ($jeux_redac = $jeux->fetch()){ ?>
                            <tr>
                              <td><?php echo $jeux_redac['titre']; ?></td>
                              <td class="tableau_mobile"><?php echo sanitize($jeux_redac['date_jeux']); ?></td>
                              <td><a class="btn btn-outline-info" href="modif_jeux/<?= traduire_nom(stripslashes($jeux_redac['titre']));?>">Modifier</a></td>
                              <td><a class="btn btn-outline-danger" href="supp_news_jeux.php?id_jeux=<?php echo $jeux_redac['id'];?>">Supprimer</a></td>
                            </tr>
                          <?php } ?>
                        </table>
                        <br/>
                      <?php } if(($utilisateur['grade'] == 6) || ($utilisateur['grade'] >= 9)){ ?>
                        <h3 class="titre_secondaire">
                          Mangas/Animes déjà présents sur le site
                        </h3>
                        <?php 
                        $mangas = $pdo->prepare('SELECT id, titre, DATE_FORMAT(date_creation, \'%d/%m/%Y\') AS date_mangas FROM billets_mangas ORDER BY id DESC'); 
                        $mangas->execute(); ?>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>Titre du mangas/anime</th>
                              <th class="tableau_mobile">Date</th>
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
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                <?php } ?>
                <?php include('../elements/footer.php') ?></center>
              </body>
              </html>