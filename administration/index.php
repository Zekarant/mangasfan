  <?php
  session_start();
  include('../membres/base.php'); 
  include('../membres/functions.php');
  if (!isset($_SESSION['auth'])) {
    header('Location: ../erreurs/erreur_403.php');
    exit();
  } elseif(isset($_SESSION['auth']) AND $utilisateur['grade'] < 10) {
    header('Location: ../erreurs/erreur_403.php');
    exit();
  }
  if (isset($_POST['chercher_membre'])) {
    if (isset($_SESSION['auth'])) {
      if ($utilisateur['grade'] >= 8) {
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
      if ($utilisateur['grade'] >= 8) {
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
  } ?>
  <!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="utf-8" />
    <title>Administration - Mangas'Fan</title>
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
        <div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important; border-right: 2px solid grey;">
          <?php include('../elements/navadmin_v.php'); ?>
        </div>
        <div class="col-sm-10" style="background-color: white; padding: 0px!important;">
          <?php include ('../elements/nav_admin.php'); ?>
          <div id="maintenances">
            <h3 class="titre_principal_news">
              Gestions des maintenances
            </h3>
            <?php 
            if (!empty($_POST['activer_site'])) {
              $update = $pdo->prepare('UPDATE maintenance SET maintenance_site = 1, maintenance_jeux = 1, maintenance_mangas = 1, maintenance_galeries = 1');
              $update->execute();
              ?>
              <div class='alert alert-warning' role='alert'>
                La maintenance a bien été activée sur <strong>l'ensemble du site</strong>.
              </div>
            <?php } elseif (!empty($_POST['desactiver_site'])) {
              $update = $pdo->prepare('UPDATE maintenance SET maintenance_site = 0, maintenance_jeux = 0, maintenance_mangas = 0, maintenance_galeries = 0');
              $update->execute();
              ?>
              <div class='alert alert-success' role='alert'>
                La maintenance a bien été désactivée sur <strong>l'ensemble du site</strong>.
              </div>
            <?php } elseif (!empty($_POST['activer_jeux'])) {
              $update = $pdo->prepare('UPDATE maintenance SET maintenance_jeux = 1');
              $update->execute();
              ?>
              <div class='alert alert-warning' role='alert'>
                La maintenance a bien été activée sur <strong>la partie des jeux vidéo</strong>.
              </div>
            <?php } elseif (!empty($_POST['desactiver_jeux'])) {
              $update = $pdo->prepare('UPDATE maintenance SET maintenance_jeux = 0');
              $update->execute();
              ?>
              <div class='alert alert-success' role='alert'>
                La maintenance a bien été désactivée sur <strong>la partie des jeux vidéo</strong>.
              </div>
            <?php } elseif (!empty($_POST['activer_mangas'])) {
              $update = $pdo->prepare('UPDATE maintenance SET maintenance_mangas = 1');
              $update->execute();
              ?>
              <div class='alert alert-warning' role='alert'>
                La maintenance a bien été activée sur <strong>la partie des mangas</strong>.
              </div>
            <?php } elseif (!empty($_POST['desactiver_mangas'])) {
              $update = $pdo->prepare('UPDATE maintenance SET maintenance_mangas = 0');
              $update->execute();
              ?>
              <div class='alert alert-success' role='alert'>
                La maintenance a bien été désactivée sur <strong>la partie des mangas</strong>.
              </div>
            <?php } elseif (!empty($_POST['activer_galeries'])) {
              $update = $pdo->prepare('UPDATE maintenance SET maintenance_galeries = 1');
              $update->execute();
              ?>
              <div class='alert alert-warning' role='alert'>
                La maintenance a bien été activée sur <strong>la partie des galeries</strong>.
              </div>
            <?php } elseif (!empty($_POST['desactiver_galeries'])) {
              $update = $pdo->prepare('UPDATE maintenance SET maintenance_galeries = 0');
              $update->execute();
              ?>
              <div class='alert alert-success' role='alert'>
                La maintenance a bien été désactivée sur <strong>la partie des galeries</strong>.
              </div>
            <?php } 
            $maintenances = $pdo->prepare('SELECT * FROM maintenance');
            $maintenances->execute();
            $maintenance = $maintenances->fetch();
            ?>
            <table class="table table-striped">
              <thread>
                <tr>
                  <th>Statut</th>
                  <th>Partie du site</th>
                  <th>Action</th>
                </tr>
              </thread>
              <tr>
                <?php
                if($maintenance['maintenance_site'] == 1) {
                  ?> 
                  <td class="table-warning">Site actuellement en maintenance.</td>
                  <td class="table-warning">Site complet.</td>
                  <td class="table-warning">
                    <form method="POST" action="">
                      <input type="submit" name="activer_site" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_site" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } else { ?>
                  <td class="table-success">Aucune maintenance sur le site.</td>
                  <td class="table-success">Site complet.</td>
                  <td class="table-success">
                    <form method="POST" action="">
                      <input type="submit" name="activer_site" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_site" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } ?>
              </tr>
              <tr>
                <?php if ($maintenance['maintenance_site'] == 1 AND $maintenance['maintenance_jeux'] == 1) {
                  ?>
                  <td class="table-warning">Les jeux sont actuellement en maintenance.</td>
                  <td class="table-warning">Partie jeux vidéo</td>
                  <td class="table-warning">
                    <form method="POST" action="">
                      <input type="submit" name="activer_jeux" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_jeux" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } elseif ($maintenance['maintenance_site'] == 1 AND $maintenance['maintenance_jeux'] == 0){
                  ?>
                  <td class="table-success">Aucune maintenance sur les jeux.</td>
                  <td class="table-success">Partie jeux vidéo.</td>
                  <td class="table-success">
                    <form method="POST" action="">
                      <input type="submit" name="activer_jeux" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_jeux" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } elseif ($maintenance['maintenance_site'] == 0 AND $maintenance['maintenance_jeux'] == 1) {
                  ?>
                  <td class="table-warning">Les jeux sont actuellement en maintenance.</td>
                  <td class="table-warning">Partie jeux vidéo.</td>
                  <td class="table-warning">
                    <form method="POST" action="">
                      <input type="submit" name="activer_jeux" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_jeux" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } else { ?>
                  <td class="table-success">Aucune maintenance sur les jeux.</td>
                  <td class="table-success">Partie jeux vidéo.</td>
                  <td class="table-success">
                    <form method="POST" action="">
                      <input type="submit" name="activer_jeux" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_jeux" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } ?>
              </tr>
              <tr>
                <?php if ($maintenance['maintenance_site'] == 1 AND $maintenance['maintenance_mangas'] == 1) {
                  ?>
                  <td class="table-warning">Les mangas sont actuellement en maintenance.</td>
                  <td class="table-warning">Partie mangas.</td>
                  <td class="table-warning">
                    <form method="POST" action="">
                      <input type="submit" name="activer_mangas" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_mangas" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } elseif ($maintenance['maintenance_site'] == 1 AND $maintenance['maintenance_mangas'] == 0){
                  ?>
                  <td class="table-success">Aucune maintenance sur les mangas.</td>
                  <td class="table-success">Partie mangas.</td>
                  <td class="table-success">
                    <form method="POST" action="">
                      <input type="submit" name="activer_mangas" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_mangas" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } elseif ($maintenance['maintenance_site'] == 0 AND $maintenance['maintenance_mangas'] == 1) {
                  ?>
                  <td class="table-warning">Les mangas sont actuellement en maintenance.</td>
                  <td class="table-warning">Partie mangas.</td>
                  <td class="table-warning">
                    <form method="POST" action="">
                      <input type="submit" name="activer_mangas" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_mangas" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } else { ?>
                  <td class="table-success">Aucune maintenance sur les mangas.</td>
                  <td class="table-success">Partie mangas.</td>
                  <td class="table-success">
                    <form method="POST" action="">
                      <input type="submit" name="activer_mangas" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_mangas" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } ?>
              </tr>
              <tr>
                <?php if ($maintenance['maintenance_site'] == 1 AND $maintenance['maintenance_galeries'] == 1) {
                  ?>
                  <td class="table-warning">Les galeries sont actuellement en maintenance.</td>
                  <td class="table-warning">Partie galeries.</td>
                  <td class="table-warning">
                    <form method="POST" action="">
                      <input type="submit" name="activer_galeries" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_galeries" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } elseif ($maintenance['maintenance_site'] == 1 AND $maintenance['maintenance_galeries'] == 0){
                  ?>
                  <td class="table-success">Aucune maintenance sur les galeries.</td>
                  <td class="table-success">Partie galeries.</td>
                  <td class="table-success">
                    <form method="POST" action="">
                      <input type="submit" name="activer_galeries" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_galeries" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } elseif ($maintenance['maintenance_site'] == 0 AND $maintenance['maintenance_galeries'] == 1) {
                  ?>
                  <td class="table-warning">Les galeries sont actuellement en maintenance.</td>
                  <td class="table-warning">Partie galeries.</td>
                  <td class="table-warning">
                    <form method="POST" action="">
                      <input type="submit" name="activer_galeries" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_galeries" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } else { ?>
                  <td class="table-success">Aucune maintenance sur les galeries.</td>
                  <td class="table-success">Partie galeries.</td>
                  <td class="table-success">
                    <form method="POST" action="">
                      <input type="submit" name="activer_galeries" class="btn btn-outline-warning" value="Activer">
                      <input type="submit" name="desactiver_galeries" class="btn btn-outline-success" value="Désactiver">
                    </form>
                  </td>
                <?php } ?>
              </tr>
            </table>
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
                      <?php $recuperer_membre = $pdo->prepare("SELECT id, username FROM users WHERE grade <= 10 ORDER BY username ASC LIMIT $limit_start, $pagination");
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
<?php include('../elements/footer.php'); ?>
</body>
</html>