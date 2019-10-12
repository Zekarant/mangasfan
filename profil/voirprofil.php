<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
include('../membres/bbcode.php');
$var = (int) $_GET['membre'];
$se = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$se->bindValue(':id', $var, PDO::PARAM_INT);
$se->execute();
$re = $se->fetch();
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8" />
  <title>Profil de <?php echo sanitize($re['username']); ?> - Mangas'Fan</title>
  <link rel="icon" href="../images/favicon.png"/>
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-129397962-1');
  </script>
  <meta property="og:site_name" content="mangasfan.fr"/>
  <meta property="og:url" content="https://www.mangasfan.fr/profil/voirprofil.php?membre=<?php echo sanitize($re['id']); ?>&action=consulter" />
  <meta property="og:title" content="Mangas'Fan - Profil de <?php echo sanitize($re['username']); ?>" />
  <meta property="og:description" content="Consulter le profil de <?php echo sanitize($re['username']); ?>" />
  <meta property="og:image" content="<?php if (!empty($re['avatar'])){if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $re['avatar'])) { ?>https://www.mangasfan.fr/inc/images/avatars/<?php echo sanitize($re['avatar']); ?><?php } else { ?><?php echo sanitize($re['avatar']); ?><?php } } ?>">
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@Mangas_Fans" />
  <meta name="twitter:creator" content="@Mangas_Fans" />
  <meta name="twitter:title" content="Mangas'Fan - Profil de <?php echo sanitize($re['username']); ?>">
  <meta name="twitter:description" content="Consulter le profil de <?php echo sanitize($re['username']); ?>">
  <meta name="twitter:image" content="<?php if (!empty($re['avatar'])){if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $re['avatar'])) { ?>https://www.mangasfan.fr/inc/images/avatars/<?php echo sanitize($re['avatar']); ?><?php } else { ?><?php echo sanitize($re['avatar']); ?><?php } } ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
  <?php include('../elements/header.php'); ?>
  <section class="marge_page">
    <h3>Profil de <?php echo rang_etat(sanitize($re['grade']), sanitize($re['username']));?></h3>
    <hr>
    <div id="view_profil">
      <div class="element_profil">
        <?php if (!empty($re['avatar'])){
          if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $re['avatar'])) { ?>
            <center>
              <img src="../membres/images/avatars/<?php echo sanitize($re['avatar']); ?>" alt="avatar" style="max-height: 285px; max-width: 205px;" title="Avatar de <?php echo sanitize($re['username']); ?>"/>
            </center> <!-- via fichier -->
          <?php } else { ?>
            <center>
              <img src="<?php echo sanitize($re['avatar']); ?>" alt="avatar" style="max-height: 285px; max-width: 205px;" title="Avatar de <?php echo sanitize($re['username']); ?>"/>
            </center><br/> <!-- via site url -->
          <?php } 
        } ?>
      </div>
      <div class="element_profil">
        Sa description :<br/><br/>
        <i><?php if($re['description'] == NULL){ echo "Ce membre n'a pas renseigné sa description."; } else { ?>« <?php 
          echo nl2br(sanitize($re['description'])); ?> » <?php } ?></i><br/><br/>
          Son rang : <?php if($re['chef'] != 0){ echo chef(sanitize($re['chef'])); } else { echo statut(sanitize($re['grade'])); } ?><br/><br/>
          Son manga préféré : <?php  if($re['manga'] == ""){ echo'Non renseigné';} else {echo sanitize($re['manga']);} ?><br/><br/>
          Son anime préféré : <?php if($re['anime'] == ""){ echo'Non renseigné';} else {echo sanitize($re['anime']);} ?><br/><br/>
          Son rôle sur le site : <?php if($re['grade'] >= 3 AND $re['role'] == NULL){ echo "Ce membre n'a pas renseigné son rôle."; } elseif ($re['grade'] >= 3 AND $re['role'] != NULL) {
           echo sanitize($re['role']); } else { echo "Ce membre n'est pas du staff."; } ?><br/><br/>
           Son site web : <?php if($re['site'] == ""){ echo'Non renseigné';} else {echo '<a href="'.sanitize($re['site']).'" target="_blank">Voir son site web</a>';} ?><br/><br/>
           Nombre de points : <?php echo sanitize($re['points']); ?> points<br/><br/>
           Nombre d'avertissements : <?php echo sanitize($re['avertissements']); ?><br/><br/>
         </div>
       </div>
       <?php
       if (isset($_SESSION['auth']) AND $utilisateur['grade'] >= 9) {
        ?>
        <br/>
        <h4>Modération de <?php echo rang_etat(sanitize($re['grade']), sanitize($re['username']));?></h4>
        <hr>
        <div class="alert alert-info" role="alert">
          <strong>Avertissement :</strong> Si vous regardez cette zone, c'est que vous allez apporter des modifications au compte « <strong><i><?php echo sanitize($re['username']); ?></i></strong> ». Veuillez à contrôler vos modifications avant de les valider !
        </div>
        <div class="container">
          <div class="row">
            <div class="col-md-7">
              <h4>Outils de modération</h4>
              <?php 
              if (!empty($_POST['new_grade'])) { 
                $changer_grade = $pdo->prepare('UPDATE users SET grade = ? WHERE id = ?');
                $changer_grade->execute(array($_POST['grades'], $re['id']));
                ?>
                <div class='alert alert-success' role='alert'>
                  Le grade de <?php echo $re['username']; ?> a bien été modifié !
                </div>
              <?php } ?>
              <form method="POST" action="">
                <label>Modifier le grade du membre :</label>
                <select name="grades" class="form-control">
                  <option value="2" selected="selected">Membre</option>
                  <option value="3">Animateur</option>
                  <option value="4">Community Manager</option>
                  <option value="5">Newseur</option>
                  <option value="6">Rédacteur</option>
                </select>
                <input type="submit" name="new_grade" value="Valider" class="btn btn-sm btn-info"/>
              </form>
              <hr>
              <h4>Sanctionner un membre</h4>
              <?php  if (!empty($_POST['valider_avertissement'])) { 
                if ($re['avertissements'] == 0) {
                  $avertissement_1 = $pdo->prepare('UPDATE users SET avertissements = 1 WHERE id = ?');
                  $avertissement_1->execute(array($re['id']));
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
                  $premier_mp->execute(array($re['id'], "Vous avez reçu un avertissement !", $text_avertissement, time()));
                  ?>
                  <div class='alert alert-warning' role='alert'>
                    Le membre <strong><?php echo sanitize($re['username']); ?></strong> a reçu son premier avertissement. MP automatique envoyé.
                  </div>
                <?php } elseif ($re['avertissements'] == 1) {
                        $avertissement_2 = $pdo->prepare('UPDATE users SET avertissements = 2 WHERE id = ?');
                        $avertissement_2->execute(array($re['id']));
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
                        $deuxième_mp->execute(array($re['id'], "Vous avez reçu un avertissement !", $text_avertissement_2, time()));
                        ?> 
                        <div class='alert alert-warning' role='alert'>
                          Le membre <strong><?php echo sanitize($re['username']); ?></strong> a reçu son deuxième avertissement. Automatiquement banni au prochain.
                        </div>
                        <?php
                      } elseif ($re['avertissements'] == 2) {
                        $avertissement_2 = $pdo->prepare('UPDATE users SET avertissements = 3 WHERE id = ?');
                        $avertissement_2->execute(array($re['id']));
                        $auto_bannissement = $pdo->prepare('UPDATE users SET grade = 1 WHERE id = ?');
                        $auto_bannissement->execute(array($re['id']));
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
                        $dernier_mp->execute(array($re['id'], "Vous avez reçu un avertissement !", $text_avertissement_3, time()));
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
                        $ban_mp->execute(array($re['id'], "Vous avez reçu un bannissement définitif !", $text_bannissement, time()));
                        $inserer_bannissement = $pdo->prepare('INSERT INTO bannissement(raison, date_de_fin, id_membre) VALUES(?, ?, ?)');
                        $inserer_bannissement->execute(array($_POST['contenu_sanction'], "2090-01-01", $re['id']));
                      $inserer_archive_ban = $pdo->prepare('INSERT INTO archives_ban(raison, date_ajout, date_de_fin, id_membre, modo) VALUES(?, NOW(), ?, ?, ?)');
                      $inserer_archive_ban->execute(array($_POST['contenu_sanction'], "2090-01-01", $re['id'], $utilisateur['id']));
                        ?> 
                        <div class='alert alert-warning' role='alert'>
                          Le membre <strong><?php echo sanitize($re['username']); ?></strong> a reçu son troisième avertissement et est donc banni définitivement du site !
                        </div>
                        <?php
                      }

              } ?>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bannissements">
                  Avertir le membre
                </button>
                <div class="modal fade" id="bannissements" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Attribuer un avertissement à <?php echo rang_etat(sanitize($re['grade']), sanitize($re['username']));?></h5>
                      </div>
                      <div class="modal-body">
                        <?php if ($re['avertissements'] == 1) { ?>
                          <div class='alert alert-warning' role='alert'>
                            <strong>Attention :</strong> Ce membre possède <strong>1 avertissement</strong> sur son compte.
                          </div>
                        <?php } elseif ($re['avertissements'] == 2) { ?>
                          <div class='alert alert-danger' role='alert'>
                            <strong>Attention :</strong> Ce membre possède déjà <strong>2 avertissements</strong> sur son compte.
                          </div>
                        <?php } ?>
                        <form method="POST" action="">
                          <label>Motif : </label>
                          <textarea class="form-control" rows="10" name="contenu_sanction" placeholder="Ecrivez ici le motif de l'avertissement"></textarea>
                          <input type="submit" name="valider_avertissement" value="Valider" class="btn btn-sm btn-info"/>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="card">
                  <div class="card-header">
                    Récapitulatif des actions effectuées
                  </div>
                  <div class="card-body">
                    <?php 
                    $actions = $pdo->prepare('SELECT id, username, avertissements FROM users WHERE id = ?');
                    $actions->execute(array($re['id']));
                    $afficher = $actions->fetch();
                    ?>
                    <p>Nombre d'avertissements : <?php if ($afficher['avertissements'] == 0) {
                      echo "Aucun avertissement.";
                    } else {
                      echo sanitize($afficher['avertissements']) . " avertissement(s).";
                    } ?></p>
                    Bannissements :
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                      Liste des bannissements
                    </button>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Bannissements de <?php echo rang_etat(sanitize($re['grade']), sanitize($re['username']));?></h5>
                          </div>
                          <div class="modal-body">
                           <?php
                           $bannissement = $pdo->prepare('SELECT b.id, b.raison, b.id_membre, b.date_de_fin, b.modo, b.date_ajout, u.username, u.grade FROM archives_ban b LEFT JOIN users u ON b.modo = u.id WHERE id_membre = ? ORDER BY date_ajout DESC');
                           $bannissement->execute(array($re['id']));
                           if ($bannissement->rowCount() > 0) {
                             while ($afficher_ban = $bannissement->fetch()) {
                              ?>
                              <p>Bannissement reçu le <strong><?php echo date('d F Y', strtotime(htmlspecialchars($afficher_ban['date_ajout']))); ?></strong> <?php if ( date("Y-m-d") >= $afficher_ban['date_de_fin']) {
                                echo "- Bannissement expiré.";
                              } else { echo " - Expire le " .  date('d/m/Y', strtotime(htmlspecialchars($afficher_ban['date_de_fin']))); } ?></p>
                              <p><strong>Motif du bannissement :</strong> <i>« <?php echo sanitize($afficher_ban['raison']); ?> »</i>.</p>
                              <p><i>Attribué par <?php echo rang_etat(sanitize($afficher_ban['grade']), sanitize($afficher_ban['username'])); ?></i>.</p>
                              <hr>
                            <?php } } else { echo "Vous n'avez aucun bannissement ! Vous êtes un vrai fan, félicitations !"; }?>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br/><br/>
                    <p><s>Galerie : <a href="#" target="_blank">Accéder à la galerie de <?php echo sanitize($re['username']); ?></a></s></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="container">
            <div class="row">
              <div class="col-md-7">
              </div>
              <div class="col-md-5">
                <div class="card">
                  <div class="card-header">
                    Informations du membre
                  </div>
                  <div class="card-body">
                    <p>Adresse mail : <i><?php echo sanitize($re['email']); ?></i></p>
                    <p>Date de naissance : <i><?php if ($re['date_anniv'] != NULL) { $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
                    $date_anniversaire = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2})#",function ($key) use ($liste_mois){ 
                      return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; }, $re['date_anniv']);
                      echo sanitize($date_anniversaire); } else { echo "Non renseigné"; }?></i></p>
                      <p>Date d'inscription : <i><?php $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
                      $date_inscription = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2})#",function ($key) use ($liste_mois){ 
                        return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1] . ' à '; }, $re['confirmed_at']);
                        echo sanitize($date_inscription); ?></i></p>
                        <p>Description : <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal1">
                          Description du membre
                        </button>
                        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Description</h5>
                              </div>
                              <div class="modal-body">
                               <?php if ($re['description'] != NULL) {
                                echo "« <i>" . bbcode(sanitize($re['description'])) . "</i> »";
                              } else {
                                echo "Ce membre n'a renseigné aucune description.";
                              }
                              ?>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            </div>
                          </div>
                        </div>
                      </div></p>
                      <p>Grade : <i><?php echo statut(sanitize($re['grade'])); ?></i></p>
                      <p>Manga : <i><?php if ($re['manga'] != NULL) {
                        echo sanitize($re['manga']);
                      } else {
                        echo "Non renseigné";
                      } 
                      ?></i></p>
                      <p>Anime : <i><?php  if ($re['anime'] != NULL) {
                        echo sanitize($re['anime']);
                      } else {
                        echo "Non renseigné";
                      } 
                      ?></i></p>
                      <p>Rôle : <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal2">
                        Rôle du membre
                      </button>
                      <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Rôle du membre (Si staff)</h5>
                            </div>
                            <div class="modal-body">
                             <?php if($re['grade'] >= 3 AND $re['role'] == NULL){ echo "Ce membre n'a pas renseigné son rôle."; } elseif ($re['grade'] >= 3 AND $re['role'] != NULL) {
                               echo sanitize($re['role']); } else { echo "Ce membre n'est pas du staff."; } ?>
                             </div>
                             <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                            </div>
                          </div>
                        </div>
                      </div></p>
                      <p>Site : <?php 
                      if ($re['site'] != NULL) {
                        ?>
                        <a href="<?php echo sanitize($re['site']); ?>" target="_blank"><?php echo sanitize($re['site']); ?></a>
                      <?php } else {
                        echo "Non renseigné";
                      } 
                      ?></p>
                      <p>Mangas'Points : <?php echo sanitize($re['points']); ?> points</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </section>
        <?php include('../elements/footer.php'); ?>
      </body>
      </html>
