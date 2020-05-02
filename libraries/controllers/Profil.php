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
			$_SESSION['flash-message'] = "On ne peut pas chercher une news qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
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
		\Renderer::render('../templates/membres/profil', '../templates/', compact('pageTitle', 'style', 'profil', 'avertissements', 'countAvertissements', 'recupererBannissement'));
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
		\Http::redirect('profil-' . $profil['id_user']);
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
		\Http::redirect('profil-' . $profil['id_user']);
	}
}