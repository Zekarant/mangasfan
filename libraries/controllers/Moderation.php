<?php 

namespace controllers;

class Moderation extends Controller {

	protected $modelName = \models\Moderation::class;

	public function index(){
		$users = new \models\Users();
		$administration = new \models\Administration();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] < 6) {
			\Http::redirect('../../index.php');
		}
		$pageTitle = "Index de la modération";
		$style = '../../css/staff.css';
		$users = $this->model->derniersInscrits();
		$commentaires = $this->model->derniersCommentaires();
		$galeries = $this->model->derniersCommentairesGaleries();
		$membres = Moderation::members();
		$avertissements = $administration->avertissements();
		$bannissements = $administration->bannissements();
		list($membres, $nb_pages, $page) = $membres;
		\Renderer::render('../../templates/staff/moderation/index', '../../templates/staff', compact('pageTitle', 'style', 'users', 'commentaires', 'membres', 'nb_pages', 'page', 'avertissements', 'bannissements', 'galeries'));
	}

	public function members(){
		if (!empty($_GET['page']) && is_numeric($_GET['page'])){
			$page = stripslashes($_GET['page']); 
		} else { 
			$page = 1;
		}
		$pagination = 10;
		$limit_start = ($page - 1) * $pagination;
		$nb_total = $this->model->paginationCount();
		$nb_pages = ceil($nb_total / $pagination);
		$membres = $this->model->allMembres($limit_start, $pagination);
		return array($membres, $nb_pages, $page);
	}
}