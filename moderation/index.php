<?php
session_start();
include('../membres/base.php'); 
include('../membres/functions.php');
if (!isset($_SESSION['auth'])) {
  header('Location: ../erreurs/erreur_403.php');
  exit();
} elseif(isset($_SESSION['auth']) AND $utilisateur['grade'] <= 6) {
  header('Location: ../erreurs/erreur_403.php');
  exit();
}
if (isset($_POST['chercher_membre'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $recuperer_membre = $pdo->prepare('SELECT id, username FROM users WHERE username = ?');
      $recuperer_membre->execute(array($_POST['pseudo_membre']));
      $membre = $recuperer_membre->fetch();
      if (isset($membre['id'])) {
        header('Location: ../profil/profil-' .sanitize($membre['id']));
        exit();
      } else {
        $couleur = "warning";
        $texte = "Ce membre n'existe pas !";
      }
    }
  }
}
if(!empty($_POST['deban']) AND isset($_POST['deban'])){
  if (isset($_SESSION['auth'])) {
    if($utilisateur['grade'] >= 7){
      $cherche_banni = $pdo->prepare('SELECT 
        u.id,
        u.username, 
        u.grade,
        b.raison,
        b.date_de_fin,
        b.id_membre
        FROM users u
        INNER JOIN
        bannissement b
        ON 
        u.id = b.id_membre
        WHERE u.grade = 1');
      $cherche_banni->execute();
      $plus_banni = $cherche_banni->fetch();
      $etat = explode(' ', $_POST['deban']);
      $bannissement = explode(' ', $_POST['debannir']);
      $enlever_bannissement = $pdo->prepare('UPDATE users SET grade = 2 WHERE username = ?');
      $enlever_bannissement->execute(array($bannissement[1]));
      $suppression_bannissement = $pdo->prepare('DELETE FROM bannissement WHERE id_membre = ?');
      $suppression_bannissement->execute(array($etat[1]));
      $text_deban = "
      <p>Cher membre de Mangas'Fan,<br/>
      Si vous recevez ce message privé, c'est que votre bannissement a été annulé par le membre <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
      <p>A l'avenir, nous comptons sur vous pour ne pas enfraindre à nouveau le règlement sous peine de recevoir un nouveau bannissement.</strong></p>
      <hr>
      <p>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera acpourcordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
      ~ L'équipe de Mangas'Fan</p>";
      $deban_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
      $deban_mp->execute(array($etat[1], "Votre bannissement a été annulé !", $text_deban, time()));
      $couleur = "warning";
      $texte = "Le membre " . sanitize($bannissement[1]) . " a été débanni !";
    }
  }
} 
$user = $pdo->prepare('SELECT id, username, email, confirmation_token, DATE_FORMAT(confirmed_at, \'%d %M %Y à %Hh %imin\') AS date_inscription FROM users ORDER BY id DESC LIMIT 10');
$user->execute();
$commentaires_site = $pdo->prepare('SELECT c.id_billet, c.id_membre, c.commentaire, c.date_commentaire, u.id AS id_membre, u.username, b.id, b.titre FROM commentaires c LEFT JOIN users u ON c.id_membre = u.id LEFT JOIN billets b ON b.id = c.id_billet ORDER BY date_commentaire DESC LIMIT 5');
$commentaires_site->execute();
$commentaires_galerie = $pdo->prepare('SELECT c.id_galerie, c.auteur, c.commentaire, c.date_commentaire, u.id AS id_membre, u.username, g.id, g.titre FROM commentaires_galerie c LEFT JOIN users u ON c.auteur = u.id LEFT JOIN galerie g ON g.id = c.id_galerie ORDER BY date_commentaire DESC LIMIT 5');
$commentaires_galerie->execute();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Modération - Mangas'Fan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../images/favicon.png"/>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
 <div class="container-fluid">
  <div class="row">
    <div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
      <?php include('../elements/navmodo_v.php'); ?>
    </div>
    <div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
      <?php include ('../elements/nav_modo.php'); ?>
      <h1 class="titre_principal_news" id="stats">Modération - Mangas'Fan</h1>
      <hr>
      <div class="container">
        <div class="row">
          <div class="col-sm-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Inscrits récents (10 derniers)</h5>
                <hr>
                <p class="card-text">
                  <?php while($inscrits = $user->fetch()){ ?>
                    <strong><a href="../profil/voirprofil.php?membre=<?= sanitize($inscrits['id']); ?>" target="_blank"><?= sanitize($inscrits['username']); ?></a></strong> s'est inscrit avec le mail <i><?= sanitize($inscrits['email']); ?></i> !
                    <?php if ($inscrits['confirmation_token'] != NULL) { ?>
                      <br/>
                      <small class="text-muted">
                        <i><strong>Note :</strong> Cet utilisateur n'a pas validé son inscription.</i>
                      </small>
                      <hr/>
                    <?php } else { ?>
                      <br/>
                      <small class="text-muted">
                        <i><b>Note :</b> Inscrit le <?= sanitize($inscrits['date_inscription']); ?></i>
                      </small>
                      <hr> 
                    <?php } 
                  } ?>
                </p>
              </div>
            </div>
          </div>
          <div class="col-sm-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Derniers commentaires sur le site (5 derniers)</h5>
                <hr>
                <p class="card-text">
                  <?php while ($commentaires = $commentaires_site->fetch()) { ?>
                    <p>Pseudo : <a href="../profil/profil-<?= sanitize($commentaires['id_membre']); ?>" target="_blank"><strong><?= sanitize($commentaires['username']); ?></strong></a></p>
                    <p>Commentaire posté : <i>« <?= sanitize($commentaires['commentaire']); ?> » </i></p>
                    <p>News : <b>« <a href="../commentaire/<?= sanitize(traduire_nom($commentaires['titre'])); ?>" target="_blank"><?= sanitize($commentaires['titre']); ?></a> »</b></p>
                    <p>Posté le : <?= date('d/m/Y à H:i', strtotime(sanitize($commentaires['date_commentaire']))); ?></p>
                    <hr>
                  <?php } ?>
                </p>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Derniers commentaires sur les galeries (5 derniers)</h5>
                <hr>
                <p class="card-text">
                  <?php while ($commentaires = $commentaires_galerie->fetch()) { ?>
                    <p>Pseudo : <a href="../profil/profil-<?= sanitize($commentaires['id_membre']); ?>" target="_blank"><strong><?= sanitize($commentaires['username']); ?></strong></a></p>
                    <p>Commentaire posté : <i>« <?= sanitize($commentaires['commentaire']); ?> » </i></p>
                    <p>Galerie : <b>« <a href="../galeries/commentaires.php?galerie=<?= sanitize($commentaires['id_galerie']); ?>" target="_blank"><?= sanitize($commentaires['titre']); ?></a> »</b></p>
                    <p>Posté le : <?= date('d/m/Y à H:i', strtotime(sanitize($commentaires['date_commentaire']))); ?></p>
                    <hr>
                  <?php } ?>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <h3 class="titre_principal_news">Gestion des membres</h3>
          <div class="row">
            <div class="col-md-6">
              <?php if (isset($_POST['chercher_membre'])) { ?>
                <div class='alert alert-<?= sanitize($couleur); ?>' role='alert'>
                  <?= sanitize($texte); ?>
                </div>
              <?php } ?>
              <div class="alert alert-info">
                En tapant le pseudo du membre ci-dessous, vous serez automatiquement redirigé sur la page de son profil afin de pouvoir modérer ce dernier. Si jamais, vous pouvez utiliser les liens à votre droite pour accéder à son profil.<br/><br/>
                <strong>ATTENTION :</strong> Les majuscules ne sont pas obligatoires.
              </div>
              <form method="POST" action="">
                <label>Pseudo du membre :</label>
                <input type="text" name="pseudo_membre" class="form-control" placeholder="Taper le pseudo du membre sans faute d'orthographe">
                <br/>
                <input type="submit" name="chercher_membre" class="btn btn-outline-info" value="Accéder au profil de ce membre">
              </form>
            </div>
            <div class="col-md-6">
              <?php if (!empty($_GET['page']) && is_numeric($_GET['page'])){
                $page = stripslashes($_GET['page']); 
              } else { 
                $page = 1;
              }
              $pagination = 10;
                        // Numéro du 1er enregistrement à lire
              $limit_start = ($page - 1) * $pagination;
              $nb_total = $pdo->prepare('SELECT COUNT(*) AS nb_total FROM users');
              $nb_total->execute();
              $nb_total = $nb_total->fetchColumn();
                                            // Pagination
              $nb_pages = ceil($nb_total / $pagination); ?>
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
                        <a class="page-link" href="<?= "?page=" . sanitize($i); ?>"><?= sanitize($i);?></a>
                      </li>
                    <?php }
                  } ?>
                </ul>
              </nav>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <th>Membre</th>
                    <th>Action</th>
                  </thead>
                  <tbody>
                    <?php $recuperer_membre = $pdo->prepare("SELECT id, username FROM users WHERE grade <= 7 ORDER BY username ASC LIMIT $limit_start, $pagination");
                    $recuperer_membre->execute();
                    while($membres = $recuperer_membre->fetch()){ ?>
                     <tr>
                      <td><?= sanitize($membres['username']); ?></td>
                      <td><a href="../profil/profil-<?= sanitize($membres['id']); ?>">Accéder au profil du membre</a></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="avertis">
      <h3 class="titre_principal_news">Membres ayant un avertissement</h3>
      <?php $membre_avertissement = $pdo->prepare('SELECT username, grade, avertissements, sexe FROM users WHERE avertissements >= 1 ORDER BY avertissements DESC');
      $membre_avertissement->execute();
      if($membre_avertissement->rowCount() == 0){ ?>
        <div class="alert alert-success">
          Il n'y a aucun membre avec un avertissement sur le site.  
        </div>
      <?php } else { ?>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Membre</th>
              <th>Avertissements</th>
              <th>Grade</th>
            </tr>
          </thead>
          <tbody>
            <?php while($avertis = $membre_avertissement->fetch()){ ?>
              <tr>
                <td><?= sanitize($avertis['username']); ?></td>
                <td><?= sanitize($avertis['avertissements']); ?>
                <?php if($avertis['avertissements'] == 3 AND $avertis['grade'] == 1){ 
                  ?>
                  - <i>(Membre banni)</i>
                <?php } elseif ($avertis['avertissements'] == 2) {
                  ?>
                  - <i>(A surveiller)</i>
                <?php } ?>
              </td>
              <td><?= statut($avertis['grade'], $avertis['sexe']); ?></td>
            </tr>
          <?php }
        } ?>
      </tbody>
    </table>
  </div>
  <hr>
  <div id="bannis">
    <?php if (isset($_POST['deban'])) { ?>
      <div class='alert alert-<?= sanitize($couleur); ?>' role='alert'>
        <?= sanitize($texte); ?>
      </div>
    <?php } ?>
    <h3 class="titre_principal_news">Membres actuellement bannis</h3>
    <?php $membre_banni = $pdo->prepare('SELECT 
      u.id,
      u.username, 
      u.grade,
      b.raison,
      b.date_de_fin,
      b.id_membre
      FROM users u
      INNER JOIN
      bannissement b
      ON 
      u.id = b.id_membre
      WHERE u.grade = 1');
    $membre_banni->execute(); 
    if ($membre_banni->rowCount() == 0) { ?>
      <div class="alert alert-success">
        Il n'y a aucun membre avec un bannissement sur le site.  
      </div>
    <?php } else { ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Membre</th>
            <th class="tableau_mobile">Motif</th>
            <th>Date de fin</th>
            <th>Action</th>
          </tr>
        </thead>
        <?php while($bannis = $membre_banni->fetch()){ ?>
          <tr>
            <td><?= sanitize($bannis['username']); ?></td>
            <td class="tableau_mobile"><?= sanitize($bannis['raison']); ?></td>
            <td><?= sanitize($bannis['date_de_fin']); ?></td>
            <td>
              <form method="POST" action="">
                <input type="hidden" class="btn btn-sm btn-outline-warning" name="debannir" value="Débannir <?= sanitize($bannis['username']); ?>" />
                <button type="submit" class="btn btn-sm btn-outline-warning" name="deban" value="Débannir <?= sanitize($bannis['id']); ?>">Débannir <?= sanitize($bannis['username']); ?></button>
              </form>
            </td>
          </tr>
        <?php }
      } ?>
    </table>
  </div>
</div>
</div>
</div>
<?php include('../elements/footer.php') ?>
</body>
</html>
