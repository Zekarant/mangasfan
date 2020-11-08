<?php 

namespace controllers;

class Animation extends Controller {

	protected $modelName = \models\Animation::class;

	public function index(){
		$error = "";
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 3 || $user['grade'] == 5 || $user['grade'] == 4) {
			\Http::redirect('../../index.php');
		}
		$pageTitle = "Index de l'animation";
		$style = '../../css/staff.css';
		$membres = $this->model->membersAnimation();
		if (isset($_POST['new_points'])) {
			Animation::gestionPoints();
		}
		$classements = $this->model->rankedPoints();
		$animation = $this->model->animation();
		if (isset($_POST['new_animation'])) {
			$error = Animation::gestionAnimation();
		}
		\Renderer::render('../../templates/staff/animation/index', '../../templates/staff', compact('pageTitle', 'style', 'membres', 'classements', 'animation', 'error'));
	}

	public function gestionPoints(){
		if ($_POST['choix_points'] == "attribuer") {
			if ($_POST['membre_point'] == "all_membres") {
				$this->model->addAllMembers($_POST['points']);
			} else {
				$this->model->addMembers($_POST['points'], $_POST['membre_point']);
			}
		} else {
			if ($_POST['membre_point'] == "all_membres") {
				$this->model->lessAllMembers($_POST['points']);
			} else {
				$this->model->lessMembers($_POST['points'], $_POST['membre_point']);
			}
		}
		\Http::redirect('index.php');
	}

	public function gestionAnimation(){
		if (!empty($_POST['contenu_animation'])) {
			$error = "";
			$this->model->updateAnimation($_POST['contenu_animation'], $_POST['visibilite']);
			\Http::redirect('index.php#billet');
		} else {
			$error = "Aucun contenu renseigné";
		}
		return $error;
	}
}
