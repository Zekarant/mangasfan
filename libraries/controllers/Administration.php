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
		\Renderer::render('../../templates/staff/administration/index', '../../templates/staff', compact('pageTitle', 'style', 'maintenance'));
	}

	public function Maintenance(){
		$pageTitle = "Index de l'administration";
		$style = '../../css/staff.css';
		$maintenance = $this->model->maintenance();
		if (isset($_POST['maintenance'])) {
			$recuperer = $this->model->verifier($_POST['maintenance']);
			if ($recuperer['active_maintenance'] == 0) {
				$newValue = 1;
			} else {
				$newValue = 0;
			}
			if ($recuperer['maintenance_area'] === "Site") {
				$this->model->updateAllMaintenance($newValue);
			} else {
				$this->model->updateMaintenance($newValue, $recuperer['maintenance_area']);
			}
			\Http::redirect('index.php');
		}
		\Renderer::render('../../templates/staff/administration/index', '../../templates/staff', compact('pageTitle', 'style', 'maintenance'));
	}
	
}