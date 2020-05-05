<?php 

namespace controllers;

class Galeries extends Controller {

	protected $modelName = \models\Galeries::class;

	public function index(){
		$pageTitle = "Créations des membres";
		$style = "../css/commentaires.css";
		$interval = "";
		$users = new \models\Users();
		$variables = ['pageTitle', 'style', 'interval', 'galeries'];
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
			$date = date_create($user['date_anniversaire']);
			$date_deux = date_create(date('Y-m-d'));
			$interval = date_diff($date, $date_deux);
			$variables = array_merge($variables, ['user']);
		}
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Galeries");
		if ((!isset($_SESSION['auth']) OR $user['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		if (isset($_POST['activer_nsfw'])) {
			Galeries::activerNSFW();
		}
		if (isset($_POST['desactiver_nsfw'])) {
			Galeries::desactiverNSFW();
		}
		if (isset($_SESSION['auth']) && $user['nsfw'] == 1 || isset($_SESSION['auth']) && $user['grade'] >= 7) {
			$galeries = $this->model->galeries();
		} else {
			$galeries = $this->model->galeries('nsfw_image = 0');
		}
		\Renderer::render('../templates/galeries/index', '../templates/', compact('galeries', $variables));
	}

	public function activerNSFW(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 1 && $user['grade'] > 9) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'A cause de votre grade, vous ne pouvez pas activer le NSFW.';
			\Http::redirect('index.php');
		}
		if ($user['date_anniversaire'] == NULL) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné votre date de naissance.";
			\Http::redirect('index.php');
		}
		$date = date_create($user['date_anniversaire']);
		$date_deux = date_create(date('Y-m-d'));
		$interval = date_diff($date, $date_deux);
		if ($interval->format('%y') < 18) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous n'avez pas l'âge requis pour activer le NSFW !";
			\Http::redirect('index.php');
		}
		$this->model->activerNSFW($user['id_user']);
		$logs = new \models\Administration();
		$logs->insertLogs($user['id_user'], "a activé son NSFW", "Activation du NSFW");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Vous avez bien activé votre NSFW !";
		\Http::redirect('index.php');
	}

	public function desactiverNSFW(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 1 && $user['grade'] > 9) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'A cause de votre grade, vous ne pouvez pas activer le NSFW.';
			\Http::redirect('index.php');
		}
		if ($user['date_anniversaire'] == NULL) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné votre date de naissance.";
			\Http::redirect('index.php');
		}
		$this->model->desactiverNSFW($user['id_user']);
		$logs = new \models\Administration();
		$logs->insertLogs($user['id_user'], "a désactivé son NSFW", "Désactivation du NSFW");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Vous avez bien désactivé votre NSFW !";
		\Http::redirect('index.php');
	}

	public function voir(){
		$idGalerie = NULL;
		if (!empty($_GET['id'])) {
			$idGalerie = $_GET['id'];
		}
		if (!$idGalerie) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "On ne peut pas chercher une galerie qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
			\Http::redirect('index.php');
		}
		$galerie = $this->model->findGalerie($idGalerie);
		if (!isset($galerie['id_image'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "L'identifiant de cette image n'existe pas";
			\Http::redirect('index.php');
		}
		if(strpos($_SERVER['REQUEST_URI'],'/galeries/voir.php')){
  			\Http::redirect($galerie['slug']);
		}
		$pageTitle = \Rewritting::sanitize($galerie['title_image']) . " de " . \Rewritting::sanitize($galerie['username']);
		$style = '../css/commentaires.css';
		$variables = ['pageTitle', 'style', 'galerie'];
		if ($galerie['keywords_image'] != "") {
			$keywords = \Rewritting::sanitize($galerie['keywords_image']);
			$variables = array_merge($variables, ['keywords']);
		}
		\Renderer::render('../templates/galeries/voir', '../templates/', compact($variables));
	}
}