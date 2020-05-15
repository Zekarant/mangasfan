<?php 

namespace controllers;

class Profil extends Controller {

	protected $modelName = \models\Profil::class;

	public function index(){
		$idProfil = NULL;
		if (!empty($_GET['id'])) {
			$idProfil = $_GET['id'];
		}
		if (!$idProfil) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "On ne peut pas chercher un profil qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
			\Http::redirect('index.php');
		}
		$profil = $this->model->findMember($idProfil);
		if (!isset($profil['id_user'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "L'identifiant de ce compte n'existe pas";
			\Http::redirect('../index.php');
		}
		if(strpos($_SERVER['REQUEST_URI'],'/membres/profil.php')){
  			\Http::redirect('profil-' . $profil['id_user']);
		}
		$pageTitle = "Profil de " . \Rewritting::sanitize($profil['username']);
		$style = '../css/commentaires.css';
		$avertissements = $this->model->searchAvertissements($profil['id_user']);
		$avertissement = $this->model->recupererAvertissements($profil['id_user']);
		$countAvertissements = $this->model->countAvertissements($profil['id_user']);
		$recupererBannissement = $this->model->recupererBannissements($profil['id_user']);
		if (isset($_POST['grade'])) {
			$grade = Profil::modifierGrade();
		}
		if (isset($_POST['valider_avertissement'])) {
			Profil::attribuerAvertissement();
		}
		if (isset($_POST['valider_bannissement'])) {
			Profil::attribuerBannissement();
		}
		if (isset($_POST['sanction_galerie'])) {
			Profil::sanctionGalerie();
		}
		if (isset($_POST['non_galerie'])) {
			Profil::autoriserGalerie();
		}
		if (isset($_POST['changement_information'])) {
			Profil::modifierInformation();
		}
		if (isset($_POST['suspension'])) {
			Profil::desactiverCompte();
		}
		if (isset($_POST['reactivation'])) {
			Profil::activerCompte();
		}
		if (isset($_POST['new_avatar'])) {
			Profil::reinitialiserAvatar();
		}
		if (isset($_POST['suppression'])) {
			Profil::suppressionCompte();
		}
		if (isset($_POST['description'])) {
			Profil::modifierDescription();
		}
		if (isset($_POST['role'])) {
			Profil::modifierRole();
		}
		$galeries = new \models\Galeries();
		$countGalerie = $galeries->countGaleries($profil['id_user']);
		\Renderer::render('../templates/membres/profil', '../templates/', compact('pageTitle', 'style', 'profil', 'avertissements', 'countAvertissements', 'recupererBannissement', 'avertissement', 'countGalerie'));
	}

	public function modifierGrade(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 6) {
			\Http::redirect('../../index.php');
		}
		if ($profil['grade'] == 9 && $user['grade'] != 9) {
			\Http::redirect('../../index.php');
		}
		if ($_POST['grades'] < 1 && $_POST['grades'] > 8) {
			\Http::redirect('../../index.php');
		}
		if ($profil['id_user'] == $user['id_user']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas changer votre grade vous-même !";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas changer le grade de quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		if ($_POST['exampleRadios'] == "stagiaire") {
			$this->model->modifierGradeStagiaire($_POST['grades'], $profil['id_user']);
		} elseif ($_POST['exampleRadios'] == "chef") {
			$this->model->modifierGradeChef($_POST['grades'], $profil['id_user']);
		} else {
			$this->model->modifierGrade($_POST['grades'], $profil['id_user']);
		}
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a modifié le grade de " . $profil['username'], "Changement de grade");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le grade du membre a bien été changé !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function attribuerAvertissement(){
		$profil = $this->model->findMember($_GET['id']);
		if (empty($_POST['contenu_sanction'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné de motif pour l'avertissement, veuillez recommencer.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 6) {
			\Http::redirect('../index.php');
		}
		$this->model->attribuerAvertissement($_POST['contenu_sanction'], $profil['id_user'], $_SESSION['auth']['id_user']);
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a attribué un avertissement à " . $profil['username'], "Avertissement");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "L'avertissement a bien été attribué !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function attribuerBannissement(){
		$profil = $this->model->findMember($_GET['id']);
		if (empty($_POST['contenu_bannissement'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné de motif pour le bannissement, veuillez recommencer.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		if (empty($_POST['date_bannissement'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné de date de fin pour le bannissement, veuillez recommencer.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 6) {
			\Http::redirect('../index.php');
		}
		if ($_POST['date_bannissement'] < date('Y-m-d')) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous avez renseigné de fin de bannissement plus petite que la date actuelle.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		if ($profil['id_user'] == $user['id_user']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas vous bannir vous-même !";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas bannir quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$this->model->attribuerBannissement($_POST['contenu_bannissement'], $profil['id_user'], $user['id_user'], $_POST['date_bannissement']);
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a banni " . $profil['username'], "Bannissement");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le bannissement a bien été attribué !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function sanctionGalerie(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 6) {
			\Http::redirect('../index.php');
		}
		if ($profil['id_user'] == $user['id_user']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas vous sanctionner vous-même !";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas sanctionner quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$this->model->nonGalerie($profil['id_user']);
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a empêché " . $profil['username'] . " de poster sur sa galerie", "Sanction sur les galeries");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le membre ne peut plus poster sur sa galerie !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function autoriserGalerie(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 6) {
			\Http::redirect('../index.php');
		}
		if ($profil['id_user'] == $user['id_user']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas vous enlever la sanction à vous-même !";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas enlever la sanction à quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$this->model->autoriserGalerie($profil['id_user']);
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a autorisé " . $profil['username'] . " à poster sur sa galerie", "Sanction sur les galeries");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le membre peut poster sur sa galerie !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function modifierInformation(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 8) {
			\Http::redirect('../index.php');
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas modifier les informations de quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		if (empty($_POST['pseudo']) || empty($_POST['email'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Le pseudo ou l'adresse mail ne peut pas être vide !";
			\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
		}
		if ($_POST['date_anniv'] >= date('Y-m-d')) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez avoir une date de naissance qui dépasse la date d'aujourd'hui...";
			\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
		}
		$this->model->modifierInformations($_POST['pseudo'], $_POST['email'], $_POST['date_anniv'], $_POST['manga'], $_POST['anime'], $_POST['site'], $profil['id_user']);
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a modifié les informations de " . $profil['username'], "[Modération] Modification d'informations");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Les informations du membre ont bien été modifiées !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function desactiverCompte(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 8) {
			\Http::redirect('../index.php');
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas désactiver votre propre compte !";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$token = \Users::str_random(60);
		$this->model->desactiverCompte($token, $profil['id_user']);
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
        <p>Cher ' . $profil['username'] . ',<br/><br/>
        Votre compte sur Mangas\'Fan a été désactivé. Cependant, aucune raison grave n\'est à signaler, vous pouvez donc réactiver votre compte comme lors de votre inscription !<br/><br/>
        Pour procéder à l\'activation de votre compte, veuillez cliquer sur le lien ci-dessous.</p>
        <p>Nous restons à votre disposition en cas d\'interrogations supplémentaires !<br/>
        En espérant que vous vous plairez sur le site !<br/><br/>
        À bientôt sur Mangas\'Fan !
        </p><br/>
        <center><a href="https://btssioslam.nexgate.ch/membres/confirmation.php?id=' . $profil['id_user'] . '&token=' . $token .'" style="text-decoration: none; color: #17a2b8; background-color: transparent; border-color: #17a2b8; font-weight: 400; border: 1px solid #17a2b8; padding: .375rem .75rem; font-size: 13px; line-height: 1.5; border-radius: .25rem; margin-top: 10px;">Confirmer mon compte</a> <a href="mailto:contact@mangasfan.fr" style="text-decoration: none; color: #17a2b8; background-color: transparent; border-color: #17a2b8; font-weight: 400; border: 1px solid #17a2b8; padding: .375rem .75rem; font-size: 13px; line-height: 1.5; border-radius: .25rem; margin-top: 10px;">Contacter l\'équipe du site</a></center>
        </div><br/>
        <div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
        </div>
        </div>
        </div>
        </body>
        </html>';
        mail($profil['email'], 'Votre compte a été désactivé - Mangas\'Fan', $demande, $header);
        $logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a désactivé le compte de " . $profil['username'], "Désactivation de compte");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le compte du membre a bien été désactivé !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function activerCompte(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 8) {
			\Http::redirect('../index.php');
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas activer votre propre compte !";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$this->model->activerCompte($profil['id_user']);
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
        <p>Cher ' . $profil['username'] . ',<br/><br/>
        Nous venons d\'activer votre compte manuellement sur Mangas\'Fan.</p>
        <p>Nous restons à votre disposition en cas d\'interrogations supplémentaires !<br/>
        En espérant que vous vous plairez sur le site !<br/><br/>
        À bientôt sur Mangas\'Fan !
        </p><br/>
        <center><a href="mailto:contact@mangasfan.fr" style="text-decoration: none; color: #17a2b8; background-color: transparent; border-color: #17a2b8; font-weight: 400; border: 1px solid #17a2b8; padding: .375rem .75rem; font-size: 13px; line-height: 1.5; border-radius: .25rem; margin-top: 10px;">Contacter l\'équipe du site</a></center>
        </div><br/>
        <div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
        </div>
        </div>
        </div>
        </body>
        </html>';
        mail($profil['email'], 'Votre compte a été activé - Mangas\'Fan', $demande, $header);
        $logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a activé le compte de " . $profil['username'], "Activation de compte");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le compte du membre a bien été activé !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function reinitialiserAvatar(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 8) {
			\Http::redirect('../index.php');
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas modifier les informations de quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$this->model->reinitialiserAvatar($profil['id_user']);
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a réinitialisé l'avatar de " . $profil['username'], "Réinitialisation de l'avatar");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "L'avatar du membre a bien été réinitialisé !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	}

	public function suppressionCompte(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 8) {
			\Http::redirect('../index.php');
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas supprimer le compte de quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a supprimé le compte de " . $profil['username'], "Suppression de compte");
      	$this->model->suppressionCompte($profil['id_user']);
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le compte du membre a bien été supprimé !";
		\Http::redirect('../index.php');
	}

	public function modifierDescription(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 8) {
			\Http::redirect('../index.php');
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas modifier la description de quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$this->model->modifierDescription($_POST['description_membre'], $profil['id_user']);
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a modifié la description de " . $profil['username'], "Modification de la description");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "La description du membre a bien été modifiée !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	} 

	public function modifierRole(){
		$profil = $this->model->findMember($_GET['id']);
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 8) {
			\Http::redirect('../index.php');
		}
		if ($profil['grade'] > $user['grade']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas modifier le rôle de quelqu'un de plus haut gradé que vous.";
			\Http::redirect('profil-' . $profil['id_user']);
		}
		$this->model->modifierRole($_POST['role_membre'], $profil['id_user']);
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a modifié le rôle de " . $profil['username'], "Modification du rôle");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le rôle du membre a bien été modifiée !";
		\Http::redirect('profil-' . $profil['id_user'] . "#moderation");
	} 
}