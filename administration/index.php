<?php
/* Pensez à vérifier la validité des liens des badges, le "inscrit" n'est plus valable ! */
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
<html>
<head>
  <meta charset="utf-8" />
  <title>Mangas'Fan - Administration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="icon" href="../images/favicon.png"/>
  <link rel="stylesheet" type="text/css" href="../style.css">
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
  elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] < 10) {
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
                <p>Status : <?php echo statut(sanitize($utilisateur['grade'])); ?></p>
                <hr>
                <a href="../staff_index.php" class="btn btn-sm btn-info">Retournez à l'index staff</a>
              </center>

              <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Administration</span>
              </h6>
              <ul class="nav flex-column">
                <li class="nav-item">
                  <a class="nav-link active" href="#maintenances">  
                    » Maintenances
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#grades">
                    » Gestion des grades
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#sanctions">
                    » Sanctions
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#avertis">
                    » Membres avertis
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#bannis">
                    » Membres bannis
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#fiches">
                    » Fiches des membres
                  </a>
                </li>
              </ul>
              <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                <span>Autres liens du pannel</span>
                <a class="d-flex align-items-center text-muted" href="#">
                </a>
              </h6>
              <ul class="nav flex-column mb-2">
                <li class="nav-item">
                  <a class="nav-link" href="../membres/liste_membres.php">
                    » Liste des membres
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="envoi_newsletter.php">
                    » Newsletter
                  </a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
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
            <br/>
            <div id="grades">
              <div class="container">
                <div class="row">
                  <div class="col-md-6">
                    <h3 class="titre_secondaire">
                      Modifier le grade
                    </h3>
                    <?php 
                    if (!empty($_POST['new_grade'])) { 
                      if ($_POST['chef'] == 1 AND $_POST['grades'] == 2) {
                      ?>
                      <div class='alert alert-warning' role='alert'>
                        <strong>Erreur :</strong> Vous avez décidé de mettre un membre chef de groupe des <strong>« Membres » </strong> ! Il ne peut y avoir de chef pour ce groupe !
                      </div>
                      <?php } elseif ($_POST['chef'] == 1 AND $_POST['grades'] != 2) {
                        $changer_grade = $pdo->prepare('UPDATE users SET grade = ?, chef = ? WHERE id = ?');
                        $changer_grade->execute(array($_POST['grades'], $_POST['grades'], $_POST['membre']));
                        $pseudo_membre = $pdo->prepare("SELECT username, grade FROM users WHERE id = ?");
                        $pseudo_membre->execute(array($_POST['membre']));
                        $pseudo_grade = $pseudo_membre->fetch();
                        ?>
                        <div class='alert alert-success' role='alert'>
                          Le grade de <strong><?php echo sanitize($pseudo_grade['username']); ?></strong> a bien été modifié ! Il devient chef du groupe <?php echo htmlspecialchars(chef($pseudo_grade['grade'])); ?>
                        </div>
                        <?php } else {
                          $changer_grade = $pdo->prepare('UPDATE users SET grade = ?, chef = 0 WHERE id = ?');
                          $changer_grade->execute(array($_POST['grades'], $_POST['membre']));
                          $pseudo_membre = $pdo->prepare("SELECT username, grade FROM users WHERE id = ?");
                          $pseudo_membre->execute(array($_POST['membre']));
                          $pseudo_grade = $pseudo_membre->fetch();
                        ?>
                        <div class='alert alert-success' role='alert'>
                          Le grade de <strong><?php echo sanitize($pseudo_grade['username']); ?></strong> a bien été modifié ! Grade donné : <?php echo htmlspecialchars(statut($pseudo_grade['grade'])); ?>
                        </div>
                        <?php } } ?>
                    <form method="POST" action="">
                      <label>Membre concerné : </label>
                      <select name="membre" class="form-control">
                        <?php
                        $liste_membres = $pdo->prepare('SELECT id, username, grade, chef FROM users WHERE grade <= 10 ORDER BY grade DESC');
                        $liste_membres->execute();
                        while ($membres = $liste_membres->fetch()) { ?>
                          <option value="<?php echo $membres['id']; ?>">
                            <?php echo sanitize($membres['username']); ?> - (<?php echo statut($membres['grade']); ?>) <?php if($membres['chef'] != 0){ ?> - Chef de groupe <?php } ?>
                          </option>
                        <?php } ?>
                      </select>
                      <br/>
                      <label>Promouvoir en chef de groupe :</label>
                      <select name="chef" class="form-control">
                        <option value="0" selected="selected">Non</option>
                        <option value="1">Oui</option>
                      </select>
                      <br/>
                      <label>Selectionner le grade :</label>
                      <select name="grades" class="form-control">
                        <option value="2" selected="selected">Membre</option>
                        <option value="3">Animateur</option>
                        <option value="4">Community Manager</option>
                        <option value="5">Newseur</option>
                        <option value="6">Rédacteur</option>
                        <option value="9">Modérateur</option>
                        <option value="10">Développeur</option>
                        <option value="11">Administrateur</option>
                      </select>
                      <input type="submit" name="new_grade" value="Valider" class="btn btn-sm btn-info"/>
                    </form>
                  </div>
                  <div class="col-md-6">
                    <h3 class="titre_secondaire">
                      Bannir/Avertir un membre
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
                        $text_bannissement = "
                        <p>Cher membre de Mangas'Fan,<br/>
                        Si vous recevez ce message privé, c'est que votre comportement va à l'encontre des CGU de notre site. Suite à votre acte, l'équipe de modération a décidé de vous donner <strong>un bannissement</strong> pour la raison suivante :</p>
                        <p>« " . $_POST['contenu_sanction'] . " »<br/>
                        Cet avertissement vous a été attribué par <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
                        <p>Ce bannissement prendra fin le " . sanitize($_POST['date_bannissement']) . ".</p>
                        <hr>
                        <p>Recevoir un bannissement à durée déterminée ou indeterminée vous empêche d'accéder à de nombreuses fonctionnalités de notre site.
                        <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
                        ~ L'équipe de Mangas'Fan</p>";
                        $ban_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
                        $ban_mp->execute(array($_POST['membre_sanction'], "Vous avez été banni du site !", $text_bannissement, time()));
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
                        $membres_sanction = $pdo->prepare('SELECT id, username, grade, avertissements FROM users WHERE grade >= 2 AND grade <= 10 ORDER BY username ASC');
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
            </div>
            <br/>
            <div id="avertis">
              <h3 class="titre_principal_news">
                Membres ayant un avertissement
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
          </div>
          <br/>
          <div id="bannis">
            <h3 class="titre_principal_news">
              Membres actuellement bannis
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
              ?> 
              <div class='alert alert-warning' role='alert'>
                Le membre <strong><?php echo sanitize($bannissement[1]); ?></strong> a été débanni !
              </div>
            <?php } ?>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Membre</th>
                  <th class="tableau_mobile">Motif</th>
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
                  <td class="tableau_mobile"><?php echo $bannis['raison']; ?></td>
                  <td><?php echo $bannis['date_de_fin']; ?></td>
                  <td>
                    <form method="POST" action="">
                      <input type="hidden" class="btn btn-sm btn-outline-warning" name="debannir" value="Débannir <?php echo $bannis['username']; ?>" />
                      <button type="submit" class="btn btn-sm btn-outline-warning" name="deban" value="Débannir <?php echo $bannis['id']; ?>">Débannir <?php echo $bannis['username']; ?></button>
                    </form>
                  </td>
                </tr>
              <?php } ?>
            </table>
          </div>
          <br/>
          <div id="fiches">
            <h3 class="titre_principal_news">
              Fiches des membres
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
                  $recuperer_membre = $pdo->prepare("SELECT id, username FROM users WHERE grade <= 10 ORDER BY username ASC LIMIT $limit_start, $pagination");
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
              </div>
              <br/>
            </div>
          </div>
        </div>
      <?php } ?>
        <?php include('../elements/footer.php') ?>
      </body>
      </html>