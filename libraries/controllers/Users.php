<?php 

namespace controllers;

class Users extends Controller {

	protected $modelName = \models\Users::class;

  /**
  * GESTION DE L'INSCRIPTION
  */
  public function inscription(){
    $error = '';
    $pageTitle = "S'inscrire";
    $style = '../css/commentaires.css';
    $controllerMaintenance = new \models\Administration();
    $maintenance = $controllerMaintenance->verifier("Membres");
    if ((!isset($_SESSION['auth']) OR $utilisateur['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
      \Http::redirect('/mangasfan/maintenance.php');
      exit();
    }
    if (isset($_SESSION['auth'])) {
      $_SESSION['flash-type'] = 'error-flash';
      $_SESSION['flash-message'] = 'Vous êtes déjà connecté ' . $_SESSION['auth']['username'] . ' ! Il est donc inutile de tenter de vous connecter !';
      \Http::redirect('../index.php');
    }
    $variables = ['pageTitle', 'style'];

    if (!empty($_POST)) {
      $error = array();
      if(!preg_match('/^[-a-zA-Z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+$/', $_POST['username'])){
        $error[] = "Le pseudo saisi contient des caractères incorrects. Veuillez recommencer.";
      }

      $user = $this->model->verificationInscription($_POST['username']);

      if ($user['username'] === $_POST['username']){
        $error[] = "Ce pseudo est déjà utilisé, vous ne pouvez donc pas l'utiliser.";
      }

      if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $error[] = "Vous ne semblez pas avoir indiqué d'adresse mail.";
      }

      if ($user['email'] === $_POST['email']) {
        $error[] = "Cette adresse mail est déjà utilisée. Veuillez recommencer.";
      }

      if($_POST['password'] != $_POST['password_confirm']){
        $error[] = "Les deux mots de passe saisis ne semblent pas correspondre, veuillez recommencer.";
      }

      if (strlen($_POST['password']) < 8) {
        $error[] = "Le mot de passe est trop court ! (Minimum 8 caractères)";
      } 
      if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $_POST['password'])) {
        $error[] = "Le mot de passe doit contenir au moins une majuscule, une miniscule, un chiffre et 8 caractères !";
      }
      if (empty($error)) {
        $avatar_defaut = 'avatar_defaut.png';
        $token = \Users::str_random(60);
        $description = "Aucune description.";
        $validation = $this->model->inscription($_POST['username'], $_POST['email'], $_POST['password'], $token, $description, $avatar_defaut);
        $header="MIME-Version: 1.0\r\n";
        $header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
        $demande = 'Content-Type:text/html; charset="utf-8"'."\n";
        $demande = '
        <html>
        <body>
        <div style="border: 1px solid black; font-family: \'Calibri\'">
        <div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
        <p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
        </div>
        <div style="padding: 10px;">
        <p>Cher ' . \Rewritting::sanitize($_POST['username']) . ',<br/><br/>
        Vous venez de créer votre compte sur la plateforme <a href="https://www.mangasfan.fr">Mangas\'Fan</a> et nous vous en remercions !<br/><br/>
        Cependant, vous ne pouvez pas encore accéder à votre compte car ce dernier n\'a pas encore été activé.<br/><br/>
        Pour procéder à l\'activation de votre compte, veuillez cliquer sur le lien ci-dessous.</p>
        <p>Nous restons à votre disposition en cas d\'interrogations supplémentaires !<br/>
        En espérant que vous vous plairez sur le site !<br/><br/>
        À bientôt sur Mangas\'Fan !
        </p><br/>
        <center><a href="https://btssioslam.nexgate.ch/membres/confirmation.php?id=' . $user_id = $this->model->returnId() . '&token=' . \Rewritting::sanitize($token) .'" style="text-decoration: none; color: #17a2b8; background-color: transparent; border-color: #17a2b8; font-weight: 400; border: 1px solid #17a2b8; padding: .375rem .75rem; font-size: 13px; line-height: 1.5; border-radius: .25rem; margin-top: 10px;">Confirmer mon compte</a> <a href="mailto:contact@mangasfan.fr" style="text-decoration: none; color: #17a2b8; background-color: transparent; border-color: #17a2b8; font-weight: 400; border: 1px solid #17a2b8; padding: .375rem .75rem; font-size: 13px; line-height: 1.5; border-radius: .25rem; margin-top: 10px;">Contacter l\'équipe du site</a></center>
        </div><br/>
        <div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
        </div>
        </div>
        </div>
        </body>
        </html>';
        mail($_POST['email'], 'Confirmation de votre inscription - Mangas\'Fan', $demande, $header);
        $_SESSION['flash-type'] = 'error-flash';
        $_SESSION['flash-message'] = 'Un mail de confirmation vous a été envoyé dans le but de valider votre compte ! Pensez à vérifier vos spams et attendez un peu, il peut mettre du temps à arriver !';
        $logs = new \models\Administration();
        $logs->insertLogs($user_id, "s'est inscrit sur le site", "Inscription");
        \Http::redirect('../index.php');

      }

      $variables = array_merge($variables, ['error', 'user']);
    }

    \Renderer::render('../templates/membres/inscription', '../templates/', compact($variables));

  }

  /**
  * CONFIRMATION DU COMPTE A L'AIDE DU LIEN ENVOYE PAR MAIL
  */
  public function confirmation(){
    if (!isset($_GET['id']) OR !isset($_GET['token'])) {
      $_SESSION['flash-type'] = 'error-flash';
      $_SESSION['flash-message'] = 'Vous n\'avez pas besoin d\'être sur cette page :c';
      \Http::redirect('../index.php');
    }
    $user_id = $_GET['id'];
    $token = $_GET['token'];
    $validation = $this->model->user($user_id);
    $controllerMaintenance = new \models\Administration();
    $maintenance = $controllerMaintenance->verifier("Membres");
    if ((!isset($_SESSION['auth']) OR $utilisateur['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
      \Http::redirect('/maintenance.php');
      exit();
    }
    if ($validation ['id_user'] == $user_id && $validation['confirmation_token'] == $token) {
      $this->model->confirmation($user_id);
      $_SESSION['flash-type'] = 'error-flash';
      $_SESSION['flash-message'] = 'Votre compte a bien été activé ! Vous pouvez maintenant vous connecter !';
      $logs = new \models\Administration();
      $logs->insertLogs($user_id, "a confirmé son compte sur le site", "Confirmation");
      \Http::redirect('../index.php');
    }
    $_SESSION['flash-type'] = 'error-flash';
    $_SESSION['flash-message'] = 'Nous avons eu un problème avec votre lien, veuillez contacter l\'équipe du site !';
    \Http::redirect('../index.php');
  }

  /**
  * PAGE DE CONNEXION
  */
  public function indexConnexion() {
    $controllerMaintenance = new \models\Administration();
    $maintenance = $controllerMaintenance->verifier("Membres");
    if ((!isset($_SESSION['auth']) OR $utilisateur['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
      \Http::redirect('/maintenance.php');
      exit();
    }
    $error = '';
    $pageTitle = 'Se connecter';
    $style = '../css/commentaires.css';
    if (isset($_SESSION['auth'])) {
      $_SESSION['flash-type'] = 'error-flash';
      $_SESSION['flash-message'] = 'Vous êtes déjà connecté ' . $_SESSION['auth']['username'] . ' ! Il est donc inutile de tenter de vous connecter !';
      \Http::redirect('compte.php');
    }
    $variables = ['pageTitle', 'style'];
    if (!empty($_POST)) {
      $users = $this->model->connexion($_POST['username']);
      if (!$users) {
        $_SESSION['flash-type'] = "error-flash";
        $_SESSION['flash-message'] = "Il semble que le pseudo que vous avez renseigné soit incorrect !";
        \Http::redirect('connexion.php');
      }
      if (password_verify($_POST['password'], $users['password'])) {
        if ($users['grade'] === 1) {
          echo "Banni";
        }
        $_SESSION['auth'] = $users;
        if ($_POST['connexion_maintenue']){ 
          setcookie('username', $users['username'], time() + 365*24*3600, "/", "localhost", false, true);
          setcookie('id_user', $users['id_user'], time() + 365*24*3600, "/", "localhost", false, true);
        }
        $logs = new \models\Administration();
        $logs->insertLogs($users['id_user'], "s'est connecté sur le site", "Connexion");
        \Http::redirect('compte.php');
      } 

      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Le mot de passe de passe renseigné est incorrect !";
      \Http::redirect('connexion.php');

      $variables = array_merge($variables, ['error', 'users']);
    }

    \Renderer::render('../templates/membres/connexion', '../templates/', compact($variables));
  }

  /** 
  * PAGE DE COMPTE DU MEMBRE
  */
  public function compte(){
    if (!isset($_SESSION['auth'])) {
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Vous ne pouvez pas accéder à cette page en tant qu'invité, merci de vous connecter !";
      \Http::redirect('connexion.php');
    }
    $utilisateur = $this->model->user($_SESSION['auth']['id_user']);
    $controllerMaintenance = new \models\Administration();
    $maintenance = $controllerMaintenance->verifier("Membres");
    if ((!isset($_SESSION['auth']) OR $utilisateur['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
      \Http::redirect('/maintenance.php');
      exit();
    }
    $pageTitle = 'Compte de ' . $utilisateur['username'];
    $style = '../css/commentaires.css';
    if (isset($_POST['changer_mdp'])) {
      Users::changerMdp($utilisateur);
    }
    if (isset($_POST['valider_anniv'])) {
      Users::dateAnniv($utilisateur);
    }
    if (isset($_POST['valider_avatar'])) {
      Users::modifierAvatar($utilisateur);
    }
    if (isset($_POST['valider_information'])) {
      Users::modifierInfos($utilisateur);
    }
    if (isset($_POST['demission'])) {
      Users::demission($utilisateur);
    }
    if (isset($_POST['supprimer_compte'])) {
      Users::suppressionCompte($utilisateur);
    }
    \Renderer::render('../templates/membres/compte', '../templates/', compact('utilisateur', 'pageTitle', 'style'));
  }

  /**
  * CHANGER LE MOT DE PASSE DU COMPTE DEPUIS LA PAGE DE CONNEXION
  * @param $utilisateur
  */
  public function changerMdp($utilisateur){
    if (empty($_POST['oldpassword']) || empty($_POST['password']) || empty($_POST['password_confirm'])) {
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Vous avez oublié de renseigner un des trois champs, veuillez recommencer !";
      \Http::redirect('compte.php');
    }

    if (!password_verify($_POST['oldpassword'], $utilisateur['password'])) {
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Votre mot de passe actuel ne correspond pas à celui saisi !";
      \Http::redirect('compte.php');
    }

    if ($_POST['password'] != $_POST['password_confirm']) {
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Les deux mots de passe ne correspondent pas, veuillez recommencer !";
      \Http::redirect('compte.php');
    }

    if(strlen($_POST['password']) < 8){
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Le nouveau mot de passe doit contenir au moins 8 caractères !";
      \Http::redirect('compte.php');
    }

    if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $_POST['password'])) {
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Le nouveau mot de passe doit contenir au moins un chiffre et une majuscule !";
      \Http::redirect('compte.php');
    }
    $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $changerMdp = $this->model->modifyPassword($newPassword, $utilisateur['id_user']);
    $_SESSION['flash-type'] = "error-flash";
    $_SESSION['flash-message'] = "Votre mot de passe a bien été modifié !";
    $logs = new \models\Administration();
    $logs->insertLogs($utilisateur['id_user'], "a changé son mot de passe sur le site", "Reset MDP");
    \Http::redirect('compte.php');

  }

  /**
  * MODIFICATION DE L'AVATAR DEPUIS LE COMPTE DU MEMBRE
  * @param $utilisateur
  */
  public function modifierAvatar($utilisateur){
    if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])) {
      $tailleMax = 2097152;
      $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
      if($_FILES['avatar']['size'] > $tailleMax) {
        $_SESSION['flash-type'] = "error-flash";
        $_SESSION['flash-message'] = "Votre avatar est trop gros, compressez-le ou veuillez en choisir un autre (2Mo max)";
        \Http::redirect('compte.php');
      }

      $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
      if(!in_array($extensionUpload, $extensionsValides)) {
        $_SESSION['flash-type'] = "error-flash";
        $_SESSION['flash-message'] = "Votre avatar doit être au format JPG, JPEG, GIF ou PNG.";
        \Http::redirect('compte.php');
      }

      $chemin = "images/avatars/".$utilisateur['id_user'].".".$extensionUpload;
      $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
      if($resultat) {
        $modifierAvatar = $this->model->modifierAvatar($utilisateur['id_user'], $extensionUpload);
        $_SESSION['flash-type'] = "error-flash";
        $_SESSION['flash-message'] = "Votre avatar a bien été modifié !";
        $logs = new \models\Administration();
        $logs->insertLogs($utilisateur['id_user'], "a modifié son avatar", "Avatar");
        \Http::redirect('compte.php');
      }

    }
  }

  /**
  * DEFINIR SA DATE D'ANNIVERSAIRE
  * @param $utilisateur
  */
  public function dateAnniv($utilisateur){
    if (empty($_POST['date_anniv'])) {
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Vous n'avez pas renseigné de date pour votre anniversaire.";
      \Http::redirect('compte.php');
    }
    if ($utilisateur['date_anniversaire'] != NULL) {
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Vous avez déjà renseigné une date d'anniversaire";
      \Http::redirect('compte.php');
    }
    $this->model->setDateAnniv($_POST['date_anniv'], $_SESSION['auth']['id_user']);
    $_SESSION['flash-type'] = "error-flash";
    $_SESSION['flash-message'] = "Votre date d'anniversaire a bien été renseignée !";
    \Http::redirect('compte.php');
  }

  /**
  * MODIFICATION DES INFORMATIONS DU MEMBRE
  * @param $utilisateur
  */

  public function modifierInfos($utilisateur){
    $users = $this->model->connexion($_POST['email']);
    if ($users['email'] === $_POST['email'] && $utilisateur['email'] != $_POST['email']) {
      $_SESSION['flash-type'] = "error-flash";
      $_SESSION['flash-message'] = "Ce mail est déjà utilisé ! Vous ne pouvez donc pas l'utiliser !";
      \Http::redirect('compte.php');
    }
    if (isset($_POST['role'])) {
      $role = $_POST['role'];
    } else {
      $role = "Aucun rôle";
    }
    $modifierInformations = $this->model->modifierInfos($_POST['email'], $_POST['sexe'], $_POST['description'], $role, $_POST['manga'], $_POST['anime'], $_POST['site'], $users['id_user']);
    $_SESSION['flash-type'] = "error-flash";
    $_SESSION['flash-message'] = "Vos informations ont bien été modifiées !";
    $logs = new \models\Administration();
    $logs->insertLogs($utilisateur['id_user'], "a modifié ses informations", "Compte");
    \Http::redirect('compte.php');
  }

  /**
  * OUBLIER SON MOT DE PASSE SANS ETRE CONNECTE
  */
  public function forget(){
    $controllerMaintenance = new \models\Administration();
    $maintenance = $controllerMaintenance->verifier("Membres");
    if ((!isset($_SESSION['auth']) OR $utilisateur['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
      \Http::redirect('/mangasfan/maintenance.php');
      exit();
    }
    $pageTitle = 'Demande de réinitialisation de mot de passe';
    $style = '../css/commentaires.css';
    $error = "";
    if (isset($_POST['valider'])) {
      $utilisateur = $this->model->forget($_POST['email']);
      if ($utilisateur['email']) {
        $reset_token = \Users::str_random(60);
        $reset = $this->model->sendReset($reset_token, $utilisateur['id_user']);
        $header="MIME-Version: 1.0\r\n";
        $header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
        $demande = 'Content-Type:text/html; charset="utf-8"'."\n";
        $demande = '
        <html>
        <body>
        <div style="border: 1px solid black; font-family: Calibri">
        <div style="padding: 10px; background-image: url(https://zupimages.net/up/20/15/80zr.png); border-bottom: 3px solid #b4b4b4">
        <p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
        </div>
        <div style="padding: 10px;">
        <p>Cher ' . \Rewritting::sanitize($utilisateur['username']) . ',<br/><br/>
        Nous avons bien reçu votre demande de réinitialisation de mot de passe, et nous sommes désolés que vous l\'ayez... Mais nous allons vous aider à le retrouver !<br/><br/>
        Pour réinitialiser votre mot de passe, nous allons vous donner un lien unique ci-dessous en rapport avec l\'adresse mail fournie.</p>
        <p>Nous restons à votre disposition en cas d\'interrogations supplémentaires !<br/>
        En espérant que vous vous plairez sur le site !<br/><br/>
        À bientôt sur Mangas\'Fan !
        </p><br/>
        <center><a href="https://btssioslam.nexgate.ch/index.php/membres/reset.php?id=' . \Rewritting::sanitize($utilisateur['id_user']) .'&token=' . \Rewritting::sanitize($reset_token) . '" style="text-decoration: none; color: #17a2b8; background-color: transparent; border-color: #17a2b8; font-weight: 400; border: 1px solid #17a2b8; padding: .375rem .75rem; font-size: 13px; line-height: 1.5; border-radius: .25rem; margin-top: 10px;">Réinitialiser mon mot de passe</a> <a href="mailto:contact@mangasfan.fr" style="text-decoration: none; color: #17a2b8; background-color: transparent; border-color: #17a2b8; font-weight: 400; border: 1px solid #17a2b8; padding: .375rem .75rem; font-size: 13px; line-height: 1.5; border-radius: .25rem; margin-top: 10px;">Contacter l\'équipe du site</a></center>
        </div><br/>
        <div style="background-image: url(https://zupimages.net/up/20/15/80zr.png); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
        </div>
        </div>
        </div>
        </body>
        </html>';
        mail($_POST['email'], 'Réinitialisation de votre mot de passe - Mangas\'Fan', $demande, $header);
        $_SESSION['flash-type'] = "error-flash";
        $_SESSION['flash-message'] = 'Nous vous avons bien envoyé un lien pour réinitialiser votre mot de passe ! Si le mail n\'arrive pas, attendez un peu !';
        $logs = new \models\Administration();
        $logs->insertLogs($utilisateur['id_user'], "a demandé à réinitialiser son mot de passe", "Réinitialisation");
        \Http::redirect('connexion.php');
      }
      $error = "L'adresse mail que vous avez renseigné ne semble pas exister !";
    }
    \Renderer::render('../templates/membres/forget', '../templates/', compact('pageTitle', 'style', 'error'));
  }

  /**
  * MODIFIER SON MOT DE PASSE AVEC LE LIEN RECU PAR MAIL
  */
  public function reset(){
    $controllerMaintenance = new \models\Administration();
    $maintenance = $controllerMaintenance->verifier("Membres");
    if ((!isset($_SESSION['auth']) OR $utilisateur['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
      \Http::redirect('/mangasfan/maintenance.php');
      exit();
    }
    $error = "";
    $pageTitle = 'Réinitialiser mon mot de passe';
    $style = '../css/commentaires.css';

    $variables = ['pageTitle', 'style'];

    if(isset($_GET['id']) && isset($_GET['token'])){
      $utilisateur = $this->model->reset($_GET['id'], $_GET['token']);
      if (!isset($utilisateur)) {
        $error = "Cet utilisateur n'existe pas !";
      }

      if (isset($_POST['valider'])) {
        if($_POST['password'] != $_POST['password_confirm']) {
          $error = "Les deux mots de passe ne correspondent pas.";
        }

        if (strlen($_POST['password']) < 8) {
          $error = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $_POST['password'])) {
          $error= "Le mot de passe doit contenir une majuscule et un chiffre !";
        }

        if (empty($error)) {
          $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
          $confirmerReset = $this->model->newPasswordReset($password, $_GET['token'], $_GET['id']);
          $_SESSION['flash-type'] = "error-flash";
          $_SESSION['flash-message'] = 'Votre mot de passe a bien été modifié ! Essayez de ne pas l\'oublier cette fois-ci !';
          $logs = new \models\Administration();
          $logs->insertLogs($utilisateur['id_user'], "a modifié son mot de passe", "Modification du mot de passe");
          \Http::redirect('../index.php');
        }

      }

      $variables = array_merge($variables, ['error']);
    }

    \Renderer::render('../templates/membres/reset', '../templates/', compact($variables));
  }

  /**
  * VERIFIER QUE L'UTILISATEUR
  */
  public function utilisateurConnecte(){
    if(!isset($_SESSION['auth']) && isset($_COOKIE['username']) && isset($_COOKIE['id_user'])){
      $utilisateur = $this->model->userCookies($_COOKIE['username'], $_COOKIE['id_user']);
      $_SESSION['auth'] = $utilisateur;
      return $utilisateur;
    } elseif(isset($_SESSION['auth'])) {
      $utilisateur = $this->model->user($_SESSION['auth']['id_user']);
      return $utilisateur;
    }
  }

  /**
  * DECONNEXION DU MEMBRE
  */
  public function deconnexion(){
    if (isset($_SESSION['auth'])) {
      setcookie('id_user', '', time() - 365*24*3600, "/", "localhost", false, true);
      setcookie('username', '', time() - 365*24*3600, "/", "localhost", false, true);
      session_destroy();
    }
    \Http::redirect('../index.php');
  }

  /**
  * LISTE DES MEMBRES
  */
  public function members(){
    $pageTitle = "Liste des membres";
    $style = "../css/commentaires.css";
    if (!empty($_GET['page']) && is_numeric($_GET['page'])){
      $page = stripslashes($_GET['page']); 
    } else { 
     $page = 1;
   }
   $pagination = 10;
   $limit_start = ($page - 1) * $pagination;
   $nb_total = $this->model->paginationCount();
   $nb_pages = ceil($nb_total / $pagination);
   $members = $this->model->allMembers($limit_start, $pagination);
   \Renderer::render('../templates/membres/members', '../templates/', compact('pageTitle', 'style', 'members', 'nb_pages', 'page'));
  }

  /**
  * DEMISSION DU MEMBRE
  * @param $utilisateur
  */
  public function demission($utilisateur){
    if (!isset($_SESSION['auth'])) {
      \Http::redirect('compte.php');
    }
    if ($utilisateur['grade'] < 2 && $utilisateur['grade'] > 9) {
      \Http::redirect('compte.php');
    }
    $this->model->demission($utilisateur['id_user']);
    \Http::redirect('compte.php');
  }

  /**
  * SUPPRESSION DU COMPTE DU MEMBRE
  * @param $utilisateur
  */
  public function suppressionCompte($utilisateur){
    if (!isset($_SESSION['auth'])) {
      \Http::redirect('compte.php');
    }
    if ($utilisateur['grade'] < 1 && $utilisateur['grade'] > 9) {
      \Http::redirect('compte.php');
    }
    $this->model->suppressionCompte($utilisateur['id_user']);
    \Http::redirect('../index.php');
  }
}