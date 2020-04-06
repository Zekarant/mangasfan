<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
$current_url = $_SERVER['REQUEST_URI'];
if(strpos($current_url,'/voirprofil.php'))
{
  $variable = intval($_GET['membre']);
  $billet = $pdo->prepare("SELECT id FROM users WHERE id = ?");
  $billet->execute(array($variable));
  $billet_title = $billet->fetch();
  header("Status: 301 Moved Permanently", false, 301);
  header("Location: ../profil/profil-".$billet_title['id']);
  die();
}
// Modification des informations
if (isset($_POST['new_grade'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      if ($_POST['chef'] == 1) {
        $changer_grade = $pdo->prepare('UPDATE users SET grade = ?, chef = ? WHERE id = ?');
        $changer_grade->execute(array($_POST['grades'], $_POST['grades'], $_GET['membre']));
        $texte = "Le grade a bien été modifié ! Et la personne devient chef de groupe";
        $couleur = "success";
      } else {
        $changer_grade = $pdo->prepare('UPDATE users SET grade = ?, chef = 0 WHERE id = ?');
        $changer_grade->execute(array($_POST['grades'], $_GET['membre']));
        $texte = "Le grade a bien été modifié !";
        $couleur = "success";
      }
    }
  }
}
// Modification avatar
if (isset($_POST['new_avatar'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $avatar = 'https://mangasfan.fr/membres/images/avatars/avatar_defaut.png';
      $modifier_avatar = $pdo->prepare('UPDATE users SET avatar = ? WHERE id = ?');
      $modifier_avatar->execute(array($avatar, $_GET['membre']));
      $texte = "L'avatar du membre a bien été réinitialisé !";
      $couleur = "success";
    }
  }
} 
// Attribution avertissements
$var = (int)$_GET['membre'];
$averto_membres = $pdo->prepare("SELECT id, avertissements FROM users WHERE id = :id");
$averto_membres->bindValue(':id', $var, PDO::PARAM_INT);
$averto_membres->execute();
$averto = $averto_membres->fetch();
if (isset($_POST['valider_avertissement'])) { 
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      if ($averto['avertissements'] == 0) {
        $avertissement_1 = $pdo->prepare('UPDATE users SET avertissements = 1 WHERE id = ?');
        $avertissement_1->execute(array($_GET['membre']));
        $note_avertissements = $pdo->prepare('INSERT INTO avertissements(date_ajout, motif, id_membre, id_modo) VALUES(NOW(), ?, ?, ?)');
        $note_avertissements->execute(array($_POST['contenu_sanction'], $_GET['membre'], $utilisateur['id']));
        $text_avertissement = "
        <p>Cher membre de Mangas'Fan,<br/>
        Si vous recevez ce message privé, c'est que votre comportement va à l'encontre des règles de notre site. Suite à votre acte, l'équipe de modération a décidé de vous donner <strong>un avertissement</strong> pour la raison suivante :</p>
        <p>« " . sanitize($_POST['contenu_sanction']) . " »<br/>
        Cet avertissement vous a été attribué par <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
        <hr>
        <p>Ceci est votre premier avertissement, votre compte sera définitivement banni au bout de 3 avertissements.
        <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
        ~ L'équipe de Mangas'Fan</p>";
        $premier_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
        $premier_mp->execute(array($_GET['membre'], "Vous avez reçu un avertissement !", $text_avertissement, time()));
        $texte = "Le membre a reçu son premier avertissement. MP automatique envoyé.";
        $couleur = "warning"; 
      } elseif ($averto['avertissements'] == 1) {
        $avertissement_2 = $pdo->prepare('UPDATE users SET avertissements = 2 WHERE id = ?');
        $avertissement_2->execute(array($_GET['membre']));
        $text_avertissement_2 = "
        <p>Cher membre de Mangas'Fan,<br/>
        Si vous recevez ce message privé, c'est que votre comportement va à l'encontre des règles de notre site. Suite à votre acte, l'équipe de modération a décidé de vous donner <strong>un deuxième avertissement</strong> pour la raison suivante :</p>
        <p>« " . sanitize($_POST['contenu_sanction']) . " »<br/>
        Cet avertissement vous a été attribué par <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
        <hr>
        <p>Ceci est votre deuxième avertissement, votre compte sera définitivement banni au bout de 3 avertissements. Nous vous conseillons de ne pas aller à l'encontre des règles du site une nouvelle fois sous peine de voir votre compte fermé.
        <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
        ~ L'équipe de Mangas'Fan</p>";
        $deuxième_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
        $deuxième_mp->execute(array($_GET['membre'], "Vous avez reçu un avertissement !", $text_avertissement_2, time()));
        $note_avertissements = $pdo->prepare('INSERT INTO avertissements(date_ajout, motif, id_membre, id_modo) VALUES(NOW(), ?, ?, ?)');
        $note_avertissements->execute(array($_POST['contenu_sanction'], $_GET['membre'], $utilisateur['id']));
        $texte = "Le membre a reçu son deuxième avertissement. Automatiquement banni au prochain.";
        $couleur = "warning";
      } elseif ($averto['avertissements'] == 2) {
        $avertissement_2 = $pdo->prepare('UPDATE users SET avertissements = 3 WHERE id = ?');
        $avertissement_2->execute(array($_GET['membre']));
        $auto_bannissement = $pdo->prepare('UPDATE users SET grade = 1 WHERE id = ?');
        $auto_bannissement->execute(array($_GET['membre']));
        $text_avertissement_3 = "
        <p>Cher membre de Mangas'Fan,<br/>
        Si vous recevez ce message privé, c'est que votre comportement va à l'encontre des règles de notre site. Suite à votre acte, l'équipe de modération a décidé de vous donner <strong>votre ultime avertissement</strong> pour la raison suivante :</p>
        <p>« " . sanitize($_POST['contenu_sanction']) . " »<br/>
        Cet avertissement vous a été attribué par <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
        <hr>
        <p>Ceci est donc votre troisième avertissement, vous allez donc recevoir un MP automatique vous indiquant votre bannissement du site définitif.
        <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera accordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
        ~ L'équipe de Mangas'Fan</p>";
        $note_avertissements = $pdo->prepare('INSERT INTO avertissements(date_ajout, motif, id_membre, id_modo) VALUES(NOW(), ?, ?, ?)');
        $note_avertissements->execute(array($_POST['contenu_sanction'], $_GET['membre'], $utilisateur['id']));
        $dernier_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
        $dernier_mp->execute(array($_GET['membre'], "Vous avez reçu un avertissement !", $text_avertissement_3, time()));
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
        $ban_mp->execute(array($_GET['membre'], "Vous avez reçu un bannissement définitif !", $text_bannissement, time()));
        $inserer_bannissement = $pdo->prepare('INSERT INTO bannissement(raison, date_de_fin, id_membre, modo) VALUES(?, ?, ?, ?)');
        $inserer_bannissement->execute(array($_POST['contenu_sanction'], "2090-01-01", $_GET['membre'], $utilisateur['id']));
        $inserer_archive_ban = $pdo->prepare('INSERT INTO archives_ban(raison, date_ajout, date_de_fin, id_membre, modo) VALUES(?, NOW(), ?, ?, ?)');
        $inserer_archive_ban->execute(array($_POST['contenu_sanction'], "2090-01-01", $averto['id'], $utilisateur['id']));
        $texte = "Le membre a reçu son troisième avertissement et est donc banni définitivement du site !";
        $couleur = "warning";
      }
    }
  }
}
// Attribution bannissements
if (isset($_POST['valider_bannissement'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      if (empty($_POST['date_bannissement']) OR empty($_POST['contenu_bannissement'])) {
        $couleur = "danger";
        $texte = "Vous avez oublié de renseigner la date ou le motif du bannissement du membre ! Veuillez recommencer !";
      } else {
        $archives_ban = $pdo->prepare('INSERT INTO bannissement(raison, date_ajout, date_de_fin, id_membre, modo) VALUES(?, NOW(), ?, ?, ?)');
        $archives_ban->execute(array($_POST['contenu_bannissement'], $_POST['date_bannissement'], $_GET['membre'], $utilisateur['id']));
        $inserer_bannissement = $pdo->prepare('INSERT INTO archives_ban(raison, date_ajout, date_de_fin, id_membre, modo) VALUES(?, NOW(), ?, ?, ?)');
        $inserer_bannissement->execute(array($_POST['contenu_bannissement'], $_POST['date_bannissement'], $_GET['membre'], $utilisateur['id']));
        $modifier_grade = $pdo->prepare('UPDATE users SET grade = 1 WHERE id = ?');
        $modifier_grade->execute(array($_GET['membre']));
        $text_bannissement = "
        <p>Cher membre de Mangas'Fan,<br/>
        Ce message privé est un message automatique envoyé à tous les membres ayant reçu un bannissement sur leur compte. Récemment, l'équipe de modération a décidé de vous adresser un bannissement pour la raison suivante : </p>
        <p>« " . $_POST['contenu_bannissement'] . " »<br/>
        Ce bannissement vous a été attribué par <strong>" . $utilisateur['username'] . "</strong>. Il durera jusqu'à " . $_POST['date_bannissement'] . "</p>
        <hr>
        <p>Comme le veut notre règlement, tous les membres ayant un total de 3 avertissements se verront refuser l'accès à la majorité de nos services et seront donc restreint au grade de bannis.
        <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera acpourcordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
        ~ L'équipe de Mangas'Fan</p>";
        $ban_mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
        $ban_mp->execute(array($_GET['membre'], "Vous avez reçu un bannissement définitif !", $text_bannissement, time()));
        $couleur = "warning";
        $texte = "Le membre a été banni du site !";
      }
    }
  }
}
// Modification informations compte
if (isset($_POST['changement_information'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $modifier_informations = $pdo->prepare('UPDATE users SET username = ?, email = ?, date_anniv = ?, manga = ?, anime = ?, site = ? WHERE id = ?');
      $modifier_informations->execute(array($_POST['pseudo'], $_POST['email'], $_POST['date_anniv'], $_POST['manga'], $_POST['anime'], $_POST['site'], $_GET['membre']));
      $couleur = "success";
      $texte = "Les informations ont bien été modifiées !";
    }
  }
}
$recuperer = $pdo->prepare('SELECT id, email FROM users WHERE id = ?');
$recuperer->execute(array($_GET['membre']));
$re = $recuperer->fetch();
// Désactivation du compte
if (isset($_POST['suspension'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $token = str_random(60);
      $email = $re['id'];
      $suspendre_compte = $pdo->prepare('UPDATE users SET confirmation_token = ?, confirmed_at = NULL WHERE id = ?');
      $suspendre_compte->execute(array($token, $_GET['membre']));
      mail($re['email'], "Désactivation de votre compte Mangas'Fan", "Votre compte vient d'être désactivé des services de Mangas'Fan. Cependant, vous pouvez réactiver votre compte !<br /> Pour pouvoir profiter de ce dernier à nouveau, vous devez l'activer via le lien ci-dessous. Une fois ceci fait, vous pourrez vous connecter avec l'identifiant et le mot de passe que vous avez entré lors de l'inscription !\n\nhttps://mangasfan.fr/membres/confirm.php?id=$email&token=$token");
      $couleur = "info";
      $texte = "Le compte a bien été suspendu ! Un email a été envoyé au membre pour que ce dernier puisse le réactiver !";
    }
  }
}
// Réactivation du compte
if (isset($_POST['reactivation'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $activer_compte = $pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?');
      $activer_compte->execute(array($re['id']));
      mail($re['email'], "Activation de votre compte Mangas'Fan", "Cher membre, si vous recevez cet email, c'est parce que votre compte a été activé par l'un des membres de notre administration. Pour simplifier, vous avez désormais accès aux services de Mangas'Fan en vous connectant avec les identifiants que vous avez renseigné lors de votre inscription sur le site !<br/>Nous espérons que vous trouverez votre bonheur sur Mangas'Fan ! A bientôt !");
      $couleur = "info";
      $texte = "Le compte a bien été activé ! Un email a été envoyé au membre pour l'informer de l'activation de ce dernier !";
    }
  }
}
// Suppression de compte
if (isset($_POST['suppression'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 9) {
      $suppression_compte = $pdo->prepare('DELETE FROM users WHERE id = ?');
      $suppression_compte->execute(array($_GET['membre']));
      header("Location: ../");
      die();
    }
  }
}
// Récupération avertissements
$virer_avertissements = $pdo->prepare('SELECT a.id AS id_averto, a.motif, a.date_ajout, a.id_membre, a.id_modo, u.id, u.username, u.grade FROM avertissements a LEFT JOIN users u ON a.id_modo = u.id WHERE a.id_membre = ?');
$virer_avertissements->execute(array($_GET['membre']));
if (isset($_POST['demande']) AND isset($_POST['averto'])) {
  if(isset($_SESSION['auth'])){
    if($utilisateur['grade'] >= 7){
      $etat = explode(' ', $_POST['averto']);
      if ($averto['avertissements'] == 1) {
        $mp_automatique = "
        <p>Cher membre de Mangas'Fan,<br/>
        Suite à une erreur de jugement, un membre de l'équipe de modération a décidé de vous <strong>retirer</strong> un de vos avertissements.</p>
        <p>
        Cet avertissement a été retiré par <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
        <hr>
        <p>Vous n'avez désormais plus d'avertissements sur votre compte, n'oubliez pas que vous serez définitivement bannis du site si vous obtenez 3 avertissements.
        <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera acpourcordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
        ~ L'équipe de Mangas'Fan</p>";
        $mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
        $mp->execute(array($_GET['membre'], "Un avertissement vous a été retiré !", $mp_automatique, time()));
        $supprimer_avertissements = $pdo->prepare('DELETE FROM avertissements WHERE id = ? AND id_membre = ?');
        $supprimer_avertissements->execute(array($etat[1], $_GET['membre']));
        $modifier_avertissements = $pdo->prepare('UPDATE users SET avertissements = 0 WHERE id = ?');
        $modifier_avertissements->execute(array($_GET['membre']));
        $couleur = "success";
        $texte = "L'avertissement a bien été retiré ! MP automatique envoyé.";
      } elseif ($averto['avertissements'] == 2) {
        $mp_automatique = "
        <p>Cher membre de Mangas'Fan,<br/>
        Suite à une erreur de jugement, un membre de l'équipe de modération a décidé de vous <strong>retirer</strong> un de vos avertissements.</p>
        <p>
        Cet avertissement a été retiré par <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
        <hr>
        <p>Vous avez encore 1 avertissement sur votre compte, n'oubliez pas que vous serez définitivement bannis du site si vous obtenez 3 avertissements.
        <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera acpourcordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
        ~ L'équipe de Mangas'Fan</p>";
        $mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
        $mp->execute(array($_GET['membre'], "Un avertissement vous a été retiré !", $mp_automatique, time()));
        $supprimer_avertissements = $pdo->prepare('DELETE FROM avertissements WHERE id = ? AND id_membre = ?');
        $supprimer_avertissements->execute(array($etat[1], $_GET['membre']));
        $modifier_avertissements = $pdo->prepare('UPDATE users SET avertissements = 1 WHERE id = ?');
        $modifier_avertissements->execute(array($_GET['membre']));
        $couleur = "success";
        $texte = "L'avertissement a bien été retiré ! MP automatique envoyé.";
      } elseif ($averto['avertissements'] == 3) {
        $mp_automatique = "
        <p>Cher membre de Mangas'Fan,<br/>
        Suite à une erreur de jugement, un membre de l'équipe de modération a décidé de vous <strong>retirer</strong> un de vos avertissements.</p>
        <p>
        Cet avertissement a été retiré par <strong>" . sanitize($utilisateur['username']) . "</strong>.</p>
        <hr>
        <p>Vous avez encore 2 avertissements sur votre compte, n'oubliez pas que vous serez définitivement bannis du site si vous obtenez 3 avertissements.
        <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera acpourcordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
        ~ L'équipe de Mangas'Fan</p>";
        $mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
        $mp->execute(array($_GET['membre'], "Un avertissement vous a été retiré !", $mp_automatique, time()));
        $supprimer_avertissements = $pdo->prepare('DELETE FROM avertissements WHERE id = ? AND id_membre = ?');
        $supprimer_avertissements->execute(array($etat[1], $_GET['membre']));
        $modifier_avertissements = $pdo->prepare('UPDATE users SET avertissements = 2, grade = 2 WHERE id = ?');
        $modifier_avertissements->execute(array($_GET['membre']));
        $couleur = "success";
        $texte = "L'avertissement a bien été retiré ! MP automatique envoyé.";
      }
    }
  }
}
// Enlever bannissement
$verifier_bannissement = $pdo->prepare('SELECT b.id, b.raison, b.id_membre, b.date_de_fin, b.modo, b.date_ajout, u.username, u.grade FROM bannissement b LEFT JOIN users u ON b.modo = u.id WHERE id_membre = ? ORDER BY date_ajout DESC');
$verifier_bannissement->execute(array($_GET['membre']));
if (isset($_POST['demande'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $supprimer_bannissement = $pdo->prepare('DELETE FROM bannissement WHERE id_membre = ?');
      $supprimer_bannissement->execute(array($_GET['membre']));
      $changer_role = $pdo->prepare('UPDATE users SET grade = 2 WHERE id = ?');
      $changer_role->execute(array($_GET['membre']));
      $mp_automatique = "
      <p>Cher membre de Mangas'Fan,<br/>
      Suite à une erreur de jugement, un membre de l'équipe de modération a décidé de vous <strong>retirer</strong> votre bannissement.</p>
      <p>Cet avertissement a été retiré par <strong>" . htmlspecialchars($utilisateur['username']) . "</strong>.</p>
      <hr>
      <p>Veuillez à ne plus vous faire bannir à l'avenir !
      <br/>Ce message privé est un message automatique, si vous décidez de répondre, aucune réponse ne vous sera acpourcordée. Si vous souhaitez obtenir des informations supplémentaires, merci de contacter l'équipe de modération.<br/>
      ~ L'équipe de Mangas'Fan</p>";
      $mp = $pdo->prepare('INSERT INTO forum_mp(mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu) VALUES(25, ?, ?, ?, ?, 1)');
      $mp->execute(array($_GET['membre'], "Un bannissement vous a été retiré !", $mp_automatique, time()));
      $couleur = "success";
      $texte = "Le bannissement a bien été retiré !";
    }
  }
}
// Modifier droit galerie
if(isset($_POST['sanction_galerie'])){
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $changer_droit = $pdo->prepare('UPDATE users SET galerie = 1 WHERE id = ?');
      $changer_droit->execute(array($_GET['membre']));
      $couleur = "success";
      $texte = "Le membre n'a plus le droit de poster sur sa galerie !";
    }
  }
}
if (isset($_POST['non_galerie'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
     $changer_droit = $pdo->prepare('UPDATE users SET galerie = 0 WHERE id = ?');
     $changer_droit->execute(array($_GET['membre']));
     $couleur = "success";
     $texte = "Le membre peut de nouveau poster sur sa galerie !";
   }
 }
}
// Récapitulatif
// Gestion des bannissements
$bannissement = $pdo->prepare('SELECT b.id, b.raison, b.id_membre, b.date_de_fin, b.modo, b.date_ajout, u.username, u.grade FROM archives_ban b LEFT JOIN users u ON b.modo = u.id WHERE id_membre = ? ORDER BY date_ajout DESC');
$bannissement->execute(array($_GET['membre']));
// Modification description
if (isset($_POST['description'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $changer_description = $pdo->prepare('UPDATE users SET description = ? WHERE id = ?');
      $changer_description->execute(array($_POST['description_membre'], $_GET['membre']));
      $texte = "La description du membre a bien été modifiée !";
      $couleur = "success";  
    }
  }                 
}
// Modification du rôle
if (isset($_POST['role'])) {
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      $changer_role = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
      $changer_role->execute(array($_POST['role_membre'], $_GET['membre']));
      $texte = "Le rôle du membre a bien été modifié !";
      $couleur = "success";
    }
  }
}
// Gestion animation
if(isset($_POST['new_points'])){
  if (isset($_SESSION['auth'])) {
    if ($utilisateur['grade'] >= 7) {
      if ($_POST['choix_points'] == "attribuer") {
        $ajoute_points = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
        $ajoute_points->execute(array($_POST['nombre_points'], $_GET['membre']));
        $couleur = "success";
        $texte = "Vous avez bien ajouté " . sanitize($_POST['nombre_points']) . " points au membre.";
      } else {
        $ajoute_points = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ?");
        $ajoute_points->execute(array($_POST['nombre_points'], $_GET['membre']));
        $couleur = "success";
        $texte = "Vous avez bien retiré " . sanitize($_POST['nombre_points']) . " points au membre.";
      }
    }
  }
}

// Récupération profil membre
$var = (int)$_GET['membre'];
$recherche_membres = $pdo->prepare("SELECT id, username, email, date_anniv, chef, confirmation_token, avatar, description, sexe, grade, manga, anime, role, site, points, avertissements, galerie FROM users WHERE id = :id");
$recherche_membres->bindValue(':id', $var, PDO::PARAM_INT);
$recherche_membres->execute();
$membre = $recherche_membres->fetch();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Profil de <?= sanitize($membre['username']); ?> - Mangas'Fan</title>
  <link rel="icon" href="../images/favicon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-129397962-1');
  </script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
  <?php include('../elements/header.php');
  include('../membres/bbcode.php'); ?>
  <section>
    <h1>Profil de <?= rang_etat(sanitize($membre['grade']), sanitize($membre['username'])); ?></h1>
    <hr>
    <div class="media">
      <?php if (!empty($membre['avatar'])){ 
        if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $membre['avatar'])) { ?>
          <img src="../membres/images/avatars/<?php echo sanitize($membre['avatar']); ?>" class="align-self-center mr-3" alt="avatar" style="max-height: 285px; max-width: 205px;" title="Avatar de <?= sanitize($membre['username']); ?>"/>
        <?php } else { ?> 
          <img src="<?= sanitize($membre['avatar']); ?>" class="align-self-center mr-3" alt="avatar" style="max-height: 285px; max-width: 205px;" title="Avatar de <?= sanitize($membre['username']); ?>"/>
        <?php } } ?>
        <div class="media-body">
          <h5 class="mt-0">Description du membre</h5>
          <p><?php if($membre['description'] == NULL){ 
            echo "Ce membre n'a pas renseigné sa description."; 
          } else { ?>
            <?= nl2br(bbcode(sanitize($membre['description'])));
          } ?></p>
          <h5 class="mt-0">Rang du membre</h5>
          <p><?php if($membre['chef'] != 0){ 
            echo chef(sanitize($membre['chef'])); 
          } else { 
            echo statut($membre['grade'], $membre['sexe']); 
          } ?></p>
          <h5 class="mt-0">Manga & anime favori</h5>
          <p><?php if ($membre['manga'] != NULL) {
            echo "- Le manga favori de ce membre est <strong>" . sanitize($membre['manga']) . "</strong>.";
          } else {
            echo "Mince ! Ce membre ne veut pas nous dire quel est son manga préféré...";
          } ?></p>
          <p><?php if ($membre['anime'] != NULL) {
            echo "- L'anime favori de ce membre est <strong>" . sanitize($membre['anime']) . "</strong>.";
          } else {
            echo "Mince ! Ce membre ne veut pas nous dire quel est son anime préféré...";
          } ?></p>
          <h5 class="mt-0">Le rôle du membre sur le site</h5>
          <p><?php if($membre['grade'] >= 3 AND $membre['role'] == NULL){ 
            echo "Ce membre n'a pas renseigné son rôle."; 
          } elseif ($membre['grade'] >= 3 AND $membre['role'] != NULL) {
            echo sanitize($membre['role']); 
          } else { echo "Ce membre n'est pas du staff."; } ?></p>
          <h5 class="mt-0">Galerie du membre</h5>
          <p><a href="../galeries/members/galerie-<?= sanitize($membre['id']); ?>" target="_blank">Voir la galerie</a></p>
          <h5 class="mt-0">Site Internet</h5>
          <p><?php if($membre['site'] != NULL){ ?>
            <a href="<?= sanitize($membre['site']); ?>" target="_blank">Voir son site web</a>
          <?php } else {
            echo "Ce membre n'a pas renseigné son site web";
          } ?></p>
          <h5 class="mt-0">Mangas'Points</h5>
          <p>Ce membre possède <?= sanitize($membre['points']); ?> Mangas'Points.</p>
        </div>
      </div>
      <?php if((isset($_SESSION['auth']) && $utilisateur['grade'] >= 8) || (isset($_SESSION['auth']) && $utilisateur['grade'] == 7) || (isset($_SESSION['auth']) && $utilisateur['grade'] == 7 && $utilisateur['chef'] == 7)){ ?>
        <h1>Modération de <?= rang_etat(sanitize($membre['grade']), sanitize($membre['username'])); ?></h1>
        <hr>
        <div class="alert alert-info" role="alert">
          <strong>Avertissement :</strong> Si vous regardez cette zone, c'est que vous allez apporter des modifications au compte « <strong><i><?= sanitize($membre['username']); ?></i></strong> ». Veuillez à contrôler vos modifications avant de les valider !
        </div>
        <?php if(isset($_POST['description']) || isset($_POST['role']) || isset($_POST['new_grade']) || isset($_POST['valider_avertissement']) || isset($_POST['new_avatar']) || isset($_POST['valider_bannissement']) || isset($_POST['changement_information']) || isset($_POST['suspension']) || isset($_POST['reactivation']) || isset($_POST['suppression']) || isset($_POST['demande']) || isset($_POST['demande']) || isset($_POST['sanction_galerie']) || isset($_POST['non_galerie'])){ ?>
          <div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
            <?= sanitize($texte); ?>
          </div>
        <?php } ?>
        <div class="container">
          <div class="row">
            <div class="col-md-7">
              <h4>Outils de modération</h4>
              <form method="POST" action="">
                <div class="row">
                  <div class="col-md-6">
                    <label>Modifier le grade du membre :</label>
                    <select name="grades" class="form-control">
                      <option value="2" selected="selected">Membre</option>
                      <option value="3">Animateur</option>
                      <option value="4">Community Manager</option>
                      <option value="5">Newseur</option>
                      <option value="6">Rédacteur</option>
                      <?php if ($utilisateur['grade'] >= 7) { ?>
                        <option value="7">Modérateur</option>
                      <?php } if ($utilisateur['grade'] >= 8) { ?>
                        <option value="8">Développeur</option>
                        <option value="9">Administrateur</option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Promouvoir en chef de groupe :</label>
                    <select name="chef" class="form-control">
                      <option value="0" selected="selected">Non</option>
                      <?php if ($utilisateur['grade'] >= 8) { ?>
                        <option value="1">Oui</option>
                      <?php } ?>
                    </select>
                    <input type="submit" name="new_grade" value="Valider" class="btn btn-sm btn-info"/>
                  </div>
                </div>
              </form>
              <hr>
              <h4>Sanctionner un membre</h4>
              <?php if($membre['avertissements'] < 3) { ?>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#avertissements">
                  Avertir le membre
                </button>
                <div class="modal fade" id="avertissements" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Attribuer un avertissement à <?= rang_etat(sanitize($membre['grade']), sanitize($membre['username']));?></h5>
                      </div>
                      <div class="modal-body">
                        <?php if ($membre['avertissements'] == 1) { ?>
                          <div class='alert alert-warning' role='alert'>
                            <strong>Attention :</strong> Ce membre possède <strong>1 avertissement</strong> sur son compte.
                          </div>
                        <?php } elseif ($membre['avertissements'] == 2) { ?>
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
              <?php } if($membre['grade'] != 1){ ?>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#bannissements">
                  Bannir le membre
                </button>
                <div class="modal fade" id="bannissements" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Donner un bannissement à <?php echo rang_etat(sanitize($membre['grade']), sanitize($membre['username']));?></h5>
                      </div>
                      <div class="modal-body">
                        <form method="POST" action="">
                          <label>Date de fin du bannissement :</label>
                          <input type="date" name="date_bannissement" class="form-control">
                          <br/>
                          <label>Motif :</label>
                          <textarea class="form-control" rows="10" name="contenu_bannissement" placeholder="Ecrivez ici le motif du bannissement"></textarea>
                          <input type="submit" name="valider_bannissement" value="Valider" class="btn btn-sm btn-info"/>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } if ($membre['galerie'] == 0) { ?>
                <form method="POST" action="">
                  <input type="submit" name="sanction_galerie" class=" btn btn-sm btn-danger" value="Empêcher de poster sur sa galerie">
                </form>
              <?php } else { ?>
                <form method="POST" action="">
                  <input type="submit" name="non_galerie" class=" btn btn-sm btn-success" value="Autoriser à poster sur sa galerie">
                </form>
              <?php } ?>
              <br/><br/>
              <h4>Outils de modification du compte</h4>
              <hr>
              <div class="alert alert-warning" role="alert">
                <strong>Avertissement :</strong> Vous êtes dans des outils vous donnant la possibilité de modifier directement les informations du membre ou encore de suspendre son compte du site, veillez à faire attention en utilisant ces outils de modération.
              </div>
              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modification_infos">
                Modifier les informations du membre
              </button>
              <div class="modal fade" id="modification_infos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Modifier les informations de <?php echo rang_etat(sanitize($membre['grade']), sanitize($membre['username']));?></h5>
                    </div>
                    <div class="modal-body">
                      <div class="container">
                        <div class="alert alert-info" role="alert">
                          <strong>Avertissement :</strong> Pour changer la description et le rôle, allez dans les onglets situés dans le récapitulatif à droite.
                        </div>
                        <form method="POST" action="">
                          <div class="row">
                            <div class="col-md-6">
                              <label>1. Pseudo</label>
                              <input name="pseudo" type="text" class="form-control" placeholder="Modifier le pseudo" value="<?php echo sanitize($membre['username']); ?>">
                              <br/>
                              <label>2. Adresse Mail</label>
                              <input name="email" type="email" class="form-control" placeholder="Modifier le mail" value="<?php echo sanitize($membre['email']); ?>">
                              <br/>
                              <label>3. Date de naissance</label>
                              <input name="date_anniv" type="date" class="form-control" placeholder="Modifier la date de naissance" value="<?php if(isset($membre['date_anniv'])) { echo sanitize($membre['date_anniv']); } ?>">
                            </div>
                            <div class="col-md-6">
                              <label>4. Manga</label>
                              <input name="manga" type="text" class="form-control" placeholder="Modifier le manga" value="<?php echo sanitize($membre['manga']); ?>">
                              <br/>
                              <label>5. Anime</label>
                              <input name="anime" type="text" class="form-control" placeholder="Modifier l'anime" value="<?php echo sanitize($membre['anime']); ?>">
                              <br/>
                              <label>6. Site internet</label>
                              <input name="site" type="url" class="form-control" placeholder="Modifier le site" value="<?php echo sanitize($membre['site']); ?>">
                            </div>
                          </div>
                          <input name="changement_information" type="submit" value="Valider les informations ci-dessus" class="btn btn-sm btn-info">
                        </form>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    </div>
                  </div>
                </div>
              </div>
              <?php if ($membre['confirmation_token'] == NULL) { ?>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#suspension_compte">
                  Suspendre le compte du membre
                </button>
                <div class="modal fade" id="suspension_compte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Désactiver le compte de <?php echo rang_etat(sanitize($membre['grade']), sanitize($membre['username']));?></h5>
                      </div>
                      <div class="modal-body">
                        <div class="container">
                          <div class="alert alert-info" role="alert">
                            <strong>Avertissement :</strong> La suspension du compte enverra un email à l'utilisateur en lui demandant de réactiver son compte, le désactiver est seulement en cas d'urgence.
                          </div>
                          <form method="POST" action="">
                            <input type="submit" name="suspension" class="btn btn-danger" value="Suspendre le compte du membre">
                          </form>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } else { ?>
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#activation_compte">
                  Activer le compte du membre
                </button>
                <div class="modal fade" id="activation_compte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Activer le compte de <?php echo rang_etat(sanitize($membre['grade']), sanitize($membre['username']));?></h5>
                      </div>
                      <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                          <strong>Attention :</strong> En cliquant sur ce bouton, vous activerez automatiquement le compte de <?php echo sanitize($membre['username']); ?>, si vous faites cela, il n'aura plus besoin de l'activer automatiquement, faites attention !
                        </div>
                        <form method="POST" action="">
                          <input type="submit" name="reactivation" class="btn btn-info" value="Réactiver le compte">
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
              <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#reinitialiser_avatar">
                Remettre l'avatar par défaut au membre
              </button>
              <div class="modal fade" id="reinitialiser_avatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Supprimer l'avatar de <?php echo rang_etat(sanitize($membre['grade']), sanitize($membre['username']));?></h5>
                    </div>
                    <div class="modal-body">
                      <div class="container">
                        <div class="container">
                          <div class="row">
                            <div class="col-md-5">
                              <form method="POST" action="">
                                <label>Reinitialiser l'avatar du membre : </label>
                                <input name="new_avatar" type="submit" value="Reinitialiser" class="btn btn-sm btn-info">
                              </form>
                            </div>
                            <div class="col-md-7">
                              <?php if (!empty($membre['avatar'])){
                                if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $membre['avatar'])) { ?>
                                  <img src="https://www.mangasfan.fr/membres/images/avatars/<?= sanitize($membre['avatar']); ?>" alt="Avatar de <= sanitize($membre['username']); ?>" class="avatar_profil" /> 
                                <?php } else {
                                  ?>
                                  <img src="<?= sanitize($membre['avatar']); ?>" alt="Avatar de <?= sanitize($membre['username']); ?>" class="avatar_profil" /><br/> <!-- Avatar par défaut -->
                                <?php } } ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php if ($utilisateur['grade'] >= 9) { ?>
                  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#suppression_compte">
                    Supprimer le compte du membre
                  </button>
                  <div class="modal fade" id="suppression_compte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Supprimer le compte de <?php echo rang_etat(sanitize($membre['grade']), sanitize($membre['username']));?></h5>
                        </div>
                        <div class="modal-body">
                          <div class="alert alert-info" role="alert">
                            <strong>Attention :</strong> En cliquant sur ce bouton, vous supprimerez définitivement le compte de <?php echo sanitize($membre['username']); ?>, si vous faites cela, il ne sera plus possible de revenir en arrière ! Soyez certains de votre action.
                          </div>
                          <form method="POST" action="">
                            <input type="submit" name="suppression" class="btn btn-danger" value="Supprimer le compte">
                          </form>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } if ($membre['avertissements'] != 0) { ?>
                  <hr>
                  <h5>Avertissements</h5>
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Motif</th>
                        <th>Date</th>
                        <th>Attribution</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while($afficher = $virer_avertissements->fetch()){ ?>
                        <tr>
                          <td><?= sanitize($afficher['motif']); ?></td>
                          <td><?= date('d M Y', strtotime(htmlspecialchars($afficher['date_ajout']))); ?></td>
                          <td><?= rang_etat(sanitize($afficher['grade']), sanitize($afficher['username'])); ?></td>
                          <td>
                            <form method="POST" action="">
                              <input type="hidden" class="btn btn-sm btn-outline-warning" name="averto" value="Supprimer <?= $afficher['id_averto']; ?>" />
                              <input type="submit" name="demande" class="btn btn-outline-danger" value="Supprimer" />
                            </form>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                <?php } ?>
                <hr>
                <h5>Bannissements en cours</h5>
                <?php if($verifier_bannissement->rowCount() <= 0) { ?>
                  <div class="alert alert-success" role="alert">
                    Ce membre ne possède aucun bannissement !
                  </div>
                <?php } else { ?>
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Motif</th>
                        <th>Date</th>
                        <th>Attribution</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php while ($afficher = $verifier_bannissement->fetch()) { ?>
                        <tr>
                          <td><?= sanitize($afficher['raison']); ?></td>
                          <td><?= date('d M Y', strtotime(htmlspecialchars($afficher['date_ajout']))); ?></td>
                          <td><?= rang_etat(sanitize($afficher['grade']), sanitize($afficher['username'])); ?></td>
                          <td>
                            <form method="POST" action="">
                              <input type="submit" name="demande" class="btn btn-outline-danger" value="Supprimer" />
                            </form>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                <?php } ?>
              </div>
              <div class="col-md-5">
                <div class="card">
                  <div class="card-header">
                    Récapitulatif des actions effectuées
                  </div>
                  <div class="card-body">
                    <p>Nombre d'avertissements : <?php if ($membre['avertissements'] == 0) {
                      echo "Aucun avertissement.";
                    } else {
                      echo sanitize($membre['avertissements']) . " avertissement(s).";
                    } ?></p>
                    Bannissements :
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                      Historique des bannissements
                    </button>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Historique des bannissements de <?= rang_etat(sanitize($membre['grade']), sanitize($membre['username']));?></h5>
                          </div>
                          <div class="modal-body">
                           <?php if ($bannissement->rowCount() > 0) {
                             while ($afficher_ban = $bannissement->fetch()) {
                              ?>
                              <p>Bannissement reçu le <strong><?= date('d F Y', strtotime(htmlspecialchars($afficher_ban['date_ajout']))); ?></strong> <?php if(date("Y-m-d") >= $afficher_ban['date_de_fin']) {
                                echo "- Bannissement expiré.";
                              } else { 
                                echo " - Expire le " .  date('d/m/Y', strtotime(sanitize($afficher_ban['date_de_fin']))); 
                              } ?></p>
                              <p><strong>Motif du bannissement :</strong> <i>« <?php echo sanitize($afficher_ban['raison']); ?> »</i>.</p>
                              <p><i>Attribué par <?= rang_etat(sanitize($afficher_ban['grade']), sanitize($afficher_ban['username'])); ?></i>.</p>
                              <hr>
                            <?php } } else { 
                              echo "Vous n'avez aucun bannissement ! Vous êtes un vrai fan, félicitations !";
                            } ?>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br/><br/>
                    <p>Galerie : <a href="../galeries/members/galerie-<?= sanitize($membre['id']); ?>" target="_blank">Accéder à la galerie de <?= sanitize($membre['username']); ?></a></p>
                  </div>
                </div>
                <div class="card">
                  <div class="card-header">
                    Informations du membre
                  </div>
                  <div class="card-body">
                    <p>Adresse mail : <?= sanitize($membre['email']); ?><br/>
                      <?php if($membre['confirmation_token'] == NULL){ 
                        echo "<i>>> Compte confirmé</i>"; 
                      } else { 
                        echo "<i>>> Ce compte n'a pas été confirmé</i>"; 
                      } ?></p>
                      <p>Date de naissance : <?php if ($membre['date_anniv'] != NULL) { 
                        $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
                        $date_anniversaire = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2})#",function ($key) use ($liste_mois){ 
                          return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; }, $membre['date_anniv']);
                        echo sanitize($date_anniversaire); 
                      } else { 
                        echo "Non renseigné"; 
                      } ?></p>
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
                             <?php if ($membre['description'] != NULL) {
                              echo "« <i>" . bbcode(sanitize($membre['description'])) . "</i> »";
                              ?>
                              <hr>
                              <h5>Modifier la description</h5>
                              <form method="POST" action="">
                                <textarea class="form-control" name="description_membre" rows="5"><?= bbcode(sanitize($membre['description'])); ?></textarea>
                                <input type="submit" name="description" class="btn btn-primary">
                              </form>
                            <?php } else {
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
                    <p>Sexe : <?php if($membre['sexe'] != NULL){ 
                      echo sanitize($membre['sexe']); 
                    } else { 
                      echo "Non renseigné."; 
                    } ?>
                  </p>
                  <p>Grade : <?php if($membre['chef'] != 0){ 
                    echo chef(sanitize($membre['chef'])); 
                  } else { 
                    echo statut($membre['grade'], $membre['sexe']); 
                  } ?>
                </p>
                <p>Manga : <?php if($membre['manga'] != NULL) {
                  echo sanitize($membre['manga']);
                } else {
                  echo "Non renseigné";
                } 
                ?></p>
                <p>Anime : <?php  if($membre['anime'] != NULL) {
                  echo sanitize($membre['anime']);
                } else {
                  echo "Non renseigné";
                } 
                ?></p>
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
                        <?php if($membre['grade'] >= 3 AND $membre['role'] == NULL){ echo "Ce membre n'a pas renseigné son rôle."; } elseif ($membre['grade'] >= 3 AND $membre['role'] != NULL) {
                          echo sanitize($membre['role']); ?>
                          <hr>
                          <h5>Modifier le rôle</h5>
                          <form method="POST" action="">
                            <textarea class="form-control" name="role_membre" rows="5"><?= sanitize($membre['role']); ?></textarea>
                            <input type="submit" name="role" class="btn btn-primary">
                          </form>
                        <?php } else { 
                          echo "Ce membre n'est pas du staff."; 
                        } ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                      </div>
                    </div>
                  </div>
                </div></p>
                <p>Site : <?php if ($membre['site'] != NULL) { ?>
                  <a href="<?= sanitize($membre['site']); ?>" target="_blank"><?= sanitize($membre['site']); ?></a>
                <?php } else {
                  echo "Non renseigné";
                } 
                ?></p>
                <p>Mangas'Points : <?= sanitize($membre['points']); ?> points</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } if(isset($_SESSION['auth']) && $utilisateur['grade'] == 3 || $utilisateur['grade'] >= 7){ ?>
      <h1>Gérer l'animation de <?= rang_etat(sanitize($membre['grade']), sanitize($membre['username'])); ?></h1>
      <hr>
      <div class="alert alert-info" role="alert">
        Chers animateurs, veuillez à bien faire attention en utilisant ce pannel ! Ne vous trompez pas dans les points !
      </div>
      <h4>Gestion des points</h4>
      <?php if(isset($_POST['new_points']) || isset($_POST['valide_badges'])){ ?>
        <div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
          <?= sanitize($texte); ?>
        </div>
      <?php } ?>
      <table class="table">
        <thead>
          <tr>
            <th>Attribution</th>
            <th>Nombre de points à donner</th>
            <th>Action</th>
          </tr>
        </thead>
        <form method="POST" action="">
          <tr>
            <td>
              <select class="form-control" name="choix_points">
                <option value="attribuer" selected="selected">Attribuer</option>
                <option value="retrait">Retirer</option>
              </select>
            </td>
            <td><input type="number" name="nombre_points" class="form-control" placeholder="Entrer le nombre de points du membre"></td>
            <td><input type="submit" name="new_points" class="btn btn-outline-info" value="Valider"></td>
          </tr>
        </form>
      </table>
    <?php } ?>
  </section>
  <?php include('../elements/footer.php'); ?>
</body>
</html>