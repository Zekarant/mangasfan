<?php 

namespace controllers;

class Profil extends Controller {

	protected $modelName = \models\Profil::class;

	public function index(){
		$message = "";
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
		\Renderer::render('../templates/membres/profil', '../templates/', compact('pageTitle', 'style', 'profil', 'message', 'avertissements', 'countAvertissements', 'recupererBannissement'));
	}
}