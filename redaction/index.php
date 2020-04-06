<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
if (!isset($_SESSION['auth']) && $utilisateur['grade'] < 5) {
	header('Location: ../erreurs/erreur_403.php');
	exit();
}
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
$news = $pdo->prepare("SELECT 
  b.id, 
  b.titre, 
  b.auteur, 
  date_creation,
  b.visible,
  b.demande,
  u.id AS id_membre,
  u.username,
  u.grade
  FROM billets b 
  LEFT JOIN 
  users u 
  ON 
  u.id = b.auteur 
  ORDER BY
  b.date_creation DESC LIMIT 15");
$news->execute();
if (!empty($_GET['jeux']) && is_numeric($_GET['jeux'])){
  $pages = stripslashes($_GET['jeux']); 
} else { 
  $pages = 1;
}
$pagination = 10;
                        // Numéro du 1er enregistrement à lire
$limit_start_jeux = ($pages - 1) * $pagination;
$nb_total = $pdo->prepare('SELECT COUNT(*) AS nb_total FROM billets_jeux');
$nb_total->execute();
$nb_total = $nb_total->fetchColumn();
                                            // Pagination
$nb_pages_jeux = ceil($nb_total / $pagination);
$jeux = $pdo->prepare("SELECT id, titre, date_creation FROM billets_jeux ORDER BY id DESC LIMIT $limit_start_jeux, $pagination"); 
$jeux->execute();
if (!empty($_GET['mangas']) && is_numeric($_GET['mangas'])){
  $page = stripslashes($_GET['mangas']); 
} else { 
  $page = 1;
}
$pagination = 10;
                        // Numéro du 1er enregistrement à lire
$limit_start = ($page - 1) * $pagination;
$nb_total = $pdo->prepare('SELECT COUNT(*) AS nb_total FROM billets_mangas WHERE type = "mangas"');
$nb_total->execute();
$nb_total = $nb_total->fetchColumn();
$nb_pages = ceil($nb_total / $pagination);

$mangas = $pdo->prepare("SELECT id, titre FROM billets_mangas WHERE type = 'mangas' ORDER BY id DESC LIMIT $limit_start, $pagination"); 
$mangas->execute();


if (!empty($_GET['anime']) && is_numeric($_GET['anime'])){
  $pages = stripslashes($_GET['anime']); 
} else { 
  $pages = 1;
}
$pagination = 10;
                        // Numéro du 1er enregistrement à lire
$limit_start = ($pages - 1) * $pagination;
$nb_total = $pdo->prepare('SELECT COUNT(*) AS nb_total FROM billets_mangas WHERE type ="anime"');
$nb_total->execute();
$nb_total = $nb_total->fetchColumn();
$nb_pags = ceil($nb_total / $pagination);
$anime = $pdo->prepare("SELECT id, titre FROM billets_mangas WHERE type = 'anime' ORDER BY id DESC LIMIT $limit_start, $pagination"); 
$anime->execute();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title>Index de la rédaction - Mangas'Fan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../images/favicon.png" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
        <?php include('../elements/navredac_v.php'); ?>
      </div>
      <div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
        <?php include ('../elements/nav_redac.php'); ?>
        <h1 class="titre_principal_news">Index de la rédaction - Mangas'Fan</h1>
        <hr>
        <?php if(isset($_SESSION['auth']) && ($utilisateur['grade'] >= 5)){ ?>
          <h3 class="titre_secondaire">News du site</h3>
          <table class="table table-striped">
            <thead>
              <th>Titre de la news</th>
              <th class="tableau_mobile">Auteur</th>
              <th class="tableau_mobile">Date</th>
              <th>Modification</th>
              <?php if(isset($_SESSION['auth']) && ($utilisateur['grade'] >= 5 AND $utilisateur['chef'] >= 5) OR $utilisateur['grade'] >= 10) { ?>
                <th>Suppression</th>
              <?php } else { ?>
                <th>Demander une suppression</th>
              <?php } ?>
            </thead>
            <tbody>
              <?php while($news_redac = $news->fetch()){ ?>
                <tr>
                  <td><?= sanitize($news_redac['titre']);
                  if ($news_redac['visible'] == 1) { ?>
                    - <strong>News cachée</strong>
                  <?php } if (date('Y-m-d H:i:s') <= $news_redac['date_creation']){ ?>
                    - <strong>News programmée</strong>
                  <?php } ?>
                </td>
                <td class="tableau_mobile"><a href="../profil/profil-<?= sanitize($news_redac['id_membre']); ?>" target="_blank"><?= rang_etat($news_redac['grade'], sanitize($news_redac['username'])); ?></a></td>
                <td class="tableau_mobile"><?= date('d/m/Y', strtotime(sanitize($news_redac['date_creation']))); ?></td>
                <td><a class="btn btn-outline-info" href="modifier_news.php?id_news=<?= sanitize($news_redac['id']); ?>">Modifier</a></td>
                <?php if(($utilisateur['grade'] >= 5 AND $utilisateur['chef'] >= 5) OR $utilisateur['grade'] >= 10) { ?>
                 <td><a class="btn btn-outline-danger" href="supp_news.php?id_news=<?php echo sanitize($news_redac['id']); ?>">Supprimer</a></td>
               <?php } else {
                if ($news_redac['demande'] == 0) { ?>
                  <td>
                    <form method="POST" action="">
                      <input type="hidden" class="btn btn-outline-danger" name="demander" value="supprimer <?= sanitize($news_redac['id']); ?>" />
                      <input type="submit" name="demande" class="btn btn-outline-danger" value="Demander une suppression" />

                    </form></td>
                  <?php } else { ?>
                    <td><button class="btn btn-outline-secondary">Demande en attente</button></td>
                  <?php }
                } ?>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } if(isset($_SESSION['auth']) && $utilisateur['grade'] >= 5){ ?>
        <hr>
        <h3 class="titre_secondaire">Jeux déjà présents sur le site</h3>
        <nav>
          <ul class="pagination justify-content-center">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1">Pages :</a>
            </li>
            <?php for ($jeux_pages = 1; $jeux_pages <= $nb_pages_jeux; $jeux_pages++) {
              if ($jeux_pages == $pages) { ?>
                <li class="page-item">
                  <a class="page-link" href="#"><?= sanitize($jeux_pages); ?></a>
                </li>
              <?php } else { ?>
                <li class="page-item">
                  <a class="page-link" href="<?= "?jeux=" . sanitize($jeux_pages); ?>"><?= sanitize($jeux_pages);?></a>
                </li>
              <?php }
            } ?>
          </ul>
        </nav>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Titre du jeu</th>
              <th class="tableau_mobile">Date</th>
              <th>Modification</th>
              <th>Suppression</th>
            </tr>
          </thead>
          <tbody>
            <?php while($jeux_redac = $jeux->fetch()){ ?>
              <tr>
                <td><?= sanitize($jeux_redac['titre']); ?></td>
                <td class="tableau_mobile"><?= date('d/m/Y', strtotime(sanitize($jeux_redac['date_creation']))); ?></td>
                <td><a class="btn btn-outline-info" href="modif_jeux/<?= sanitize(traduire_nom($jeux_redac['titre']));?>">Modifier</a></td>
                <td><a class="btn btn-outline-danger" href="supp_news_jeux.php?id_jeux=<?= sanitize($jeux_redac['id']); ?>">Supprimer</a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } if(isset($_SESSION['auth']) && $utilisateur['grade'] >= 5){ ?>
        <hr>
        <h3 class="titre_secondaire">Mangas/animes déjà présents sur le site</h3>
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <nav>
                <ul class="pagination justify-content-center">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Pages :</a>
                  </li>
                  <?php for ($i = 1; $i <= $nb_pages; $i++) {
                    if ($i == $page) { ?>
                      <li class="page-item">
                        <a class="page-link" href="#"><?= sanitize($i); ?></a>
                      </li>
                    <?php } else { ?>
                      <li class="page-item">
                        <a class="page-link" href="<?= "?mangas=" . sanitize($i); ?>"><?= sanitize($i);?></a>
                      </li>
                    <?php }
                  } ?>
                </ul>
              </nav>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Titre du mangas</th>
                    <th>Modification</th>
                    <th>Suppression</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($mangas_redac = $mangas->fetch()){ ?>
                    <tr>
                      <td><?= sanitize($mangas_redac['titre']); ?></td>
                      <td><a class="btn btn-outline-info" href="modif_mangas/<?= sanitize(traduire_nom($mangas_redac['titre']));?>">Modifier</a></td>
                      <td><a class="btn btn-outline-danger" href="supp_news_mangas.php?id_mangas=<?= sanitize($mangas_redac['id']); ?>">Supprimer</a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
          </div>
          <div class="col-lg-6">
            <nav>
                <ul class="pagination justify-content-center">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Pages :</a>
                  </li>
                  <?php for ($i = 1; $i <= $nb_pags; $i++) {
                    if ($i == $pages) { ?>
                      <li class="page-item">
                        <a class="page-link" href="#"><?= sanitize($i); ?></a>
                      </li>
                    <?php } else { ?>
                      <li class="page-item">
                        <a class="page-link" href="<?= "?anime=" . sanitize($i); ?>"><?= sanitize($i);?></a>
                      </li>
                    <?php }
                  } ?>
                </ul>
              </nav>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Titre de l'anime</th>
                    <th>Modification</th>
                    <th>Suppression</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($anime_redac = $anime->fetch()){ ?>
                    <tr>
                      <td><?= sanitize($anime_redac['titre']); ?></td>
                      <td><a class="btn btn-outline-info" href="modif_mangas/<?= sanitize(traduire_nom($anime_redac['titre']));?>">Modifier</a></td>
                      <td><a class="btn btn-outline-danger" href="supp_news_mangas.php?id_mangas=<?= sanitize($anime_redac['id']); ?>">Supprimer</a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
               <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include('../elements/footer.php'); ?>
</body>
</html>