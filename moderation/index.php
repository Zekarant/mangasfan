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
  include('../inc/functions.php');
?>
<!DOCTYPE html>
  <html lang="fr">
    <head>
      <meta charset="utf-8" />
      <title>Mangas'Fan - Modération</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="../images/favicon.png" />
      <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <link rel="icon" href="../images/favicon.png"/>
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
        elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] < 9) {
          ?>
          <div class='alert alert-danger' role='alert'>
            Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
          </div>
          <?php
        }
        else { 
          include('../elements/nav_modo.php');
        ?>
          <section class="marge_page">
            <h3 class="titre_pannel">
              Bienvenue sur le panneau de modération de <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fan</span>
            </h3>
            <div class="container">
              <div class="row">
                <div class="col-sm-4">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Nombre <span class="couleur_mangas">de</span> <span class="couleur_fans">membres</span></h5>
                      <p class="card-text">
                        <?php 
                          $user = $pdo->prepare('SELECT * FROM users');
                          $user->execute();
                        ?>
                        Il y a actuellement <b><?php echo sanitize($user->rowCount()); ?></b>  membres inscrits sur Mangas'Fan.
                      </p>
                      <a href="../inc/liste_membres.php" class="btn btn-info btn-sm">Liste des membres</a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Nombre de <span class="couleur_mangas">mangas</span> <span class="couleur_fans">recensés</span></h5>
                      <p class="card-text">
                      <?php 
                        $user = $pdo->prepare('SELECT * FROM billets_mangas');
                        $user->execute();
                      ?>
                      Il y a actuellement <b><?php echo $user->rowCount(); ?></b> mangas recensés sur Mangas'Fan.
                      </p>
                      <a href="../mangas/index.php" class="btn btn-info btn-sm" role="button">Page des mangas</a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Nombre de <span class="couleur_mangas">jeux</span> <span class="couleur_fans">recensés</span></h5>
                      <p class="card-text">
                      <?php 
                        $user = $pdo->prepare('SELECT * FROM billets_jeux');
                        $user->execute();
                      ?>
                      Il y a actuellement <b><?php echo sanitize($user->rowCount()); ?></b> jeux recensés sur Mangas'Fan.
                      </p>
                      <a href="../jeux_video/index.php" class="btn btn-info btn-sm" role="button">Page des jeux</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
             <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <h3 class="titre_pannel">
                    Modifier <span class="couleur_mangas">le</span> <span class="couleur_fans">grade</span>
                  </h3>
                  <?php 
                   if (!empty($_POST['new_grade'])) { 
                      $changer_grade = $pdo->prepare('UPDATE users SET grade = ? WHERE id = ?');
                      $changer_grade->execute(array($_POST['grades'], $_POST['membre']));
                      $pseudo_membre = $pdo->prepare("SELECT username FROM users WHERE id = ?");
                      $pseudo_membre->execute(array($_POST['membre']));
                      $pseudo_grade = $pseudo_membre->fetch();
                    ?>
                    <div class='alert alert-success' role='alert'>
                      Le grade de <?php echo $pseudo_grade['username']; ?> a bien été modifié !
                    </div>
                    <?php } ?>
                  <form method="POST" action="">
                    <label>Membre concerné : </label>
                    <select name="membre" class="form-control">
                  <?php
                    $liste_membres = $pdo->prepare('SELECT id, username, grade FROM users WHERE grade <= 8 ORDER BY grade DESC');
                    $liste_membres->execute();
                    while ($membres = $liste_membres->fetch()) { ?>
                        <option value="<?php echo $membres['id']; ?>">
                        <?php echo sanitize($membres['username']); ?> - (<?php echo statut($membres['grade']); ?>)
                        </option>
                  <?php } ?>
                    </select>
                    <br/>
                    <label>Selectionner le grade :</label>
                    <select name="grades" class="form-control">
                      <option value="2" selected="selected">Membre</option>
                      <option value="3">Animateur</option>
                      <option value="4">Community Manager</option>
                      <option value="5">Newseur</option>
                      <option value="6">Rédacteur anime</option>
                      <option value="7">Rédacteur mangas</option>
                      <option value="8">Rédacteur jeux vidéos</option>
                    </select>
                    <input type="submit" name="new_grade" value="Valider" class="btn btn-sm btn-info"/>
                  </form>
                </div>
                <div class="col-md-6">
                  <h3 class="titre_pannel">
                    Bannir/Avertir <span class="couleur_mangas">un</span> <span class="couleur_fans">membre</span>
                  </h3>
                  <?php 
                   if (!empty($_POST['valider_sanction'])) { 
                    $pseudo_averti = $pdo->prepare("SELECT username, avertissements FROM users WHERE id = ?");
                    $pseudo_averti->execute(array($_POST['membre_sanction']));
                    $pseudo = $pseudo_averti->fetch();
                      if ($_POST['sanction'] == "Avertissement") {
                        $nombre_averto = (int)$_POST['sanction'];
                        if ($pseudo['avertissements'] == 0) {
                          $avertissement_1 = $pdo->prepare('UPDATE users SET avertissements = ? WHERE id = ?');
                          $avertissement_1->execute(array(($nombre_averto + 1), $_POST['membre_sanction']));
                          $text_avertissement = "
                          <p>Cher membre de Mangas'Fan,<br/>
                          Si vous recevez ce message privé, c'est que votre comportement va à l'encontre des règles de notre site. Suite à votre acte, l'équipe de modération a décidé de vous donner <strong>un avertissement</strong> pour la raison suivante :</p>
                          <p>« " . $_POST['contenu_sanction'] . " »<br/>
                          Cet avertissement vous a été attribué par <strong>" . $utilisateur['username'] . "</strong>.</p>
                          <hr>
                          <p>Ceci est votre premier avertissement, votre compte sera définitivement banni au bout de 3 avertissements.
                          <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
                          ~ L'équipe de Mangas'Fan</p>";
                          $premier_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
                          $premier_mp->execute(array($_POST['membre_sanction'], "Vous avez reçu un avertissement !", $text_avertissement, time()));
                        ?> 
                          <div class='alert alert-warning' role='alert'>
                            Le membre <strong><?php echo sanitize($pseudo['username']); ?></strong> a reçu son premier avertissement. MP automatique envoyé.
                          </div>
                        <?php
                        }
                        elseif ($pseudo['avertissements'] == 1) {
                          $avertissement_2 = $pdo->prepare('UPDATE users SET avertissements = ? WHERE id = ?');
                          $avertissement_2->execute(array(($nombre_averto + 2), $_POST['membre_sanction']));
                          $text_avertissement_2 = "
                          <p>Cher membre de Mangas'Fan,<br/>
                          Si vous recevez ce message privé, c'est que votre comportement va à l'encontre des règles de notre site. Suite à votre acte, l'équipe de modération a décidé de vous donner <strong>un deuxième avertissement</strong> pour la raison suivante :</p>
                          <p>« " . $_POST['contenu_sanction'] . " »<br/>
                          Cet avertissement vous a été attribué par <strong>" . $utilisateur['username'] . "</strong>.</p>
                          <hr>
                          <p>Ceci est votre deuxième avertissement, votre compte sera définitivement banni au bout de 3 avertissements. Nous vous conseillons de ne pas aller à l'encontre des règles du site une nouvelle fois sous peine de voir votre compte fermé.
                          <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
                          ~ L'équipe de Mangas'Fan</p>";
                          $deuxième_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
                          $deuxième_mp->execute(array($_POST['membre_sanction'], "Vous avez reçu un avertissement !", $text_avertissement_2, time()));
                          ?> 
                          <div class='alert alert-warning' role='alert'>
                            Le membre <strong><?php echo sanitize($pseudo['username']); ?></strong> a reçu son deuxième avertissement. Automatiquement banni au prochain.
                          </div>
                        <?php
                        }
                         elseif ($pseudo['avertissements'] == 2) {
                          $avertissement_2 = $pdo->prepare('UPDATE users SET avertissements = ? WHERE id = ?');
                          $avertissement_2->execute(array(($nombre_averto + 3), $_POST['membre_sanction']));
                          $auto_bannissement = $pdo->prepare('UPDATE users SET grade = 1 WHERE id = ?');
                          $auto_bannissement->execute(array($_POST['membre_sanction']));
                          $text_avertissement_3 = "
                          <p>Cher membre de Mangas'Fan,<br/>
                          Si vous recevez ce message privé, c'est que votre comportement va à l'encontre des règles de notre site. Suite à votre acte, l'équipe de modération a décidé de vous donner <strong>votre ultime avertissement</strong> pour la raison suivante :</p>
                          <p>« " . $_POST['contenu_sanction'] . " »<br/>
                          Cet avertissement vous a été attribué par <strong>" . $utilisateur['username'] . "</strong>.</p>
                          <hr>
                          <p>Ceci est donc votre troisième avertissement, vous allez donc recevoir un MP automatique vous indiquant votre bannissement du site définitif.
                          <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
                          ~ L'équipe de Mangas'Fan</p>";
                          $dernier_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
                          $dernier_mp->execute(array($_POST['membre_sanction'], "Vous avez reçu un avertissement !", $text_avertissement_3, time()));
                          $text_bannissement = "
                          <p>Cher membre de Mangas'Fan,<br/>
                          Ce message privé est un message automatique envoyé à tous les membres ayant reçu un total de 3 avertissements sur leur compte. Récemment, l'équipe de modération a décidé de vous adresser un troisième avertissement pour la raison suivante : </p>
                          <p>« " . $_POST['contenu_sanction'] . " »<br/>
                          Le dernier avertissement vous a été attribué par <strong>" . $utilisateur['username'] . "</strong>.</p>
                          <hr>
                          <p>Comme le veut notre règlement, tous les membres ayant un total de 3 avertissements se verront refuser l'accès à la majorité de nos services et seront donc restreint au grade de bannis.
                          <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera acpourcordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
                          ~ L'équipe de Mangas'Fan</p>";
                          $ban_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
                          $ban_mp->execute(array($_POST['membre_sanction'], "Vous avez reçu un bannissement définitif !", $text_bannissement, time()));
                           ?> 
                          <div class='alert alert-warning' role='alert'>
                            Le membre <strong><?php echo sanitize($pseudo['username']); ?></strong> a reçu son troisième avertissement et est donc banni définitivement du site !
                          </div>
                        <?php
                        }
                      }
                        else
                      {
                        $inserer_bannissement = $pdo->prepare('INSERT INTO bannissement(raison, date_de_fin, id_membre) VALUES(?, ?, ?)');
                        $inserer_bannissement->execute(array($_POST['contenu_sanction'], $_POST['date_bannissement'], $_POST['membre_sanction']));
                        $modifier_grade = $pdo->prepare('UPDATE users SET grade = 1 WHERE id = ?');
                        $modifier_grade->execute(array($_POST['membre_sanction']));
                        $inserer_archive_ban = $pdo->prepare('INSERT INTO archives_ban(raison, date_de_fin, id_membre) VALUES(?, ?, ?)');
                        $inserer_archive_ban->execute(array($_POST['contenu_sanction'], $_POST['date_bannissement'], $_POST['membre_sanction']));
                      ?>
                        <div class='alert alert-warning' role='alert'>
                          Le membre <strong><?php echo sanitize($pseudo['username']); ?></strong> a été banni !
                        </div>
                      <?php
                      }
                 }
                  ?>
                  <form method="POST" action="">
                    <label>Sanction choisie :</label>
                    <select name="sanction" class="form-control">
                      <option value="Avertissement">Avertissement</option>
                      <option value="Bannissement">Bannissement</option>
                    </select>
                    <br/>
                    <label>Membre concerné : </label>
                    <select name="membre_sanction" class="form-control">
                      <?php 
                      $membres_sanction = $pdo->prepare('SELECT id, username, grade, avertissements FROM users WHERE grade >= 2 AND grade <= 8 ORDER BY username ASC');
                      $membres_sanction->execute();
                      while($sanctionne = $membres_sanction->fetch()){
                      ?>
                        <option value="<?php echo sanitize($sanctionne['id']); ?>"><?php echo sanitize($sanctionne['username']); ?> - <?php echo sanitize($sanctionne['avertissements']); ?> avertissement(s)</option>
                      <?php } ?>
                    </select>
                    <br/>
                    <label>Durée de fin (Uniquement si bannissement) :</label>
                    <input type="date" name="date_bannissement" class="form-control">
                    <br/>
                    <label>Motif : </label>
                    <textarea class="form-control" rows="10" name="contenu_sanction" placeholder="Ecrivez ici le motif du bannissement ou celui de l'avertissement"></textarea>
                    <input type="submit" name="valider_sanction" value="Valider" class="btn btn-sm btn-info"/>
                  </form>
                </div>
              </div>
            </div>
            <h3 class="titre_pannel">
              Membres ayant <span class="couleur_mangas">un</span> <span class="couleur_fans">avertissement</span>
            </h3>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Membre</th>
                  <th>Avertissements</th>
                  <th>Grade</th>
                </tr>
            </thead>
            <?php 
              $membre_avertissement = $pdo->prepare('SELECT username, grade, avertissements FROM users WHERE avertissements >= 1 ORDER BY avertissements DESC');
              $membre_avertissement->execute();
              while($avertis = $membre_avertissement->fetch()){
            ?>
            <tr>
              <td><?php echo $avertis['username']; ?></td>
              <td><?php echo $avertis['avertissements']; ?>
              <?php if($avertis['avertissements'] == 3 AND $avertis['grade'] == 1){ 
              ?>
                - <i>(Membre banni)</i>
              <?php } elseif ($avertis['avertissements'] == 2) {
              ?>
               - <i>(A surveiller)</i>
              <?php } ?>
              </td>
              <td><?php echo statut($avertis['grade']); ?></td>
            </tr>
            <?php } ?>
            </table>
            
            <h3 class="titre_pannel">
              Membres <span class="couleur_mangas">actuellement</span> <span class="couleur_fans">bannis</span>
            </h3>
            <?php
            if(!empty($_POST['deban']) AND isset($_POST['deban'])){
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
                $enlever_bannissement = $pdo->prepare('UPDATE users SET grade = 2 WHERE id = ?');
                $enlever_bannissement->execute(array($plus_banni['id']));
                $suppression_bannissement = $pdo->prepare('DELETE FROM bannissement WHERE id_membre = ?');
                $suppression_bannissement->execute(array($plus_banni['id']));
                $text_deban = "
                          <p>Cher membre de Mangas'Fan,<br/>
                         Si vous recevez ce message privé, c'est que votre bannissement a été annulé par le membre <strong>" . $utilisateur['username'] . "</strong>.</p>
                          <p>A l'avenir, nous comptons sur vous pour ne pas enfraindre à nouveau le règlement sous peine de recevoir un nouveau bannissement.</strong></p>
                          <hr>
                          <p>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera acpourcordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
                          ~ L'équipe de Mangas'Fan</p>";
                          $deban_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
                          $deban_mp->execute(array($plus_banni['id'], "Votre bannissement a été annulé !", $text_deban, time()));
                           ?> 
                          <div class='alert alert-warning' role='alert'>
                            Le membre <strong><?php echo sanitize($plus_banni['username']); ?></strong> a été débanni !
                          </div>
                          <?php } ?>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Membre</th>
                  <th>Motif</th>
                  <th>Date de fin</th>
                  <th>Action</th>
                </tr>
            </thead>
            <?php 
              $membre_banni = $pdo->prepare('SELECT 
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
              while($bannis = $membre_banni->fetch()){

              ?>
            <tr>
              <td><?php echo $bannis['username']; ?></td>
              <td><?php echo $bannis['raison']; ?></td>
              <td><?php echo $bannis['date_de_fin']; ?></td>
              <td>
                <form method="POST" action="">
                   <input type="submit" class="btn btn-sm btn-outline-warning" name="deban" value="Débannir le membre" />
                </form>
              </td>
            </tr>
            <?php } ?>
            </table>
            <h3 class="titre_pannel">
              Fiches <span class="couleur_mangas">des</span> <span class="couleur_fans">membres</span>
            </h3>
            <?php
              if (!empty($_GET['page']) && is_numeric($_GET['page']) )
                $page = stripslashes($_GET['page']);
              else
               $page = 1;
               $pagination = 10;
               // Numéro du 1er enregistrement à lire
               $limit_start = ($page - 1) * $pagination;
               $nb_total = $pdo->query('SELECT COUNT(*) AS nb_total FROM users');
               $nb_total->execute();
               $nb_total = $nb_total->fetchColumn();
               // Pagination
               $nb_pages = ceil($nb_total / $pagination);
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
                      <?php echo $i; ?>
                    </a>
                  </li>
                <?php } } ?>
                </ul>
              </nav>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Membre</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <?php 
                  $recuperer_membre = $pdo->prepare("SELECT id, username FROM users WHERE grade <= 8 ORDER BY username ASC LIMIT $limit_start, $pagination");
                  $recuperer_membre->execute();
                  while($membres = $recuperer_membre->fetch()){
                ?>
                <tr>
                  <td><?php echo sanitize($membres['username']); ?></td>
                  <td><a href="fiche_membre.php?membre=<?php echo sanitize($membres['id']); ?>">Accéder à la fiche de ce membre</a></td>
                </tr>
                <?php
                  }
                ?>
              </table>

          </section>
        <?php } ?>
      <?php include('../elements/footer.php') ?>
    </div>
  </body>
</html>
