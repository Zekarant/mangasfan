<?php 

namespace Controllers;

class Administration extends Controller {

	protected $modelName = \Models\Administration::class;

	public function index(){
		$users = new \Models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] <= 6) {
			\Http::redirect('../../index.php');
		}
		$pageTitle = "Index de l'administration";
		$style = '../../css/staff.css';
		$maintenance = Administration::Maintenance();
		$membres = Administration::members();
		list($membres, $nb_pages, $page) = $membres;
		\Renderer::render('../../templates/staff/administration/index', '../../templates/staff', compact('pageTitle', 'style', 'maintenance', 'membres', 'page', 'nb_pages'));
	}

	public function Maintenance(){
		$pageTitle = "Index de l'administration";
		$style = '../../css/staff.css';
		$maintenance = $this->model->maintenance();
		if (isset($_POST['maintenance'])) {
			$recuperer = $this->model->verifier($_POST['maintenance']);
			$newValue = !$recuperer['active_maintenance'] ? 1 : 0;
			if ($recuperer['maintenance_area'] === "Site") {
				$this->model->updateAllMaintenance($newValue);
			} else {
				$this->model->updateMaintenance($newValue, $recuperer['maintenance_area']);
			}
			\Http::redirect('index.php');
		}
		return $maintenance;
	}

	public function members(){
		$users = new \Models\Users();
        if (!empty($_GET['page']) && is_numeric($_GET['page'])){
            $page = stripslashes($_GET['page']); 
        } else { 
        	$page = 1;
        }
        $pagination = 10;
        $limit_start = ($page - 1) * $pagination;
        $nb_total = $users->paginationCount();
        $nb_pages = ceil($nb_total / $pagination);
        $membres = $users->allMembres($limit_start, $pagination);
        return array($membres, $nb_pages, $page);
    }
	
}