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
}