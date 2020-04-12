<?php

namespace Controllers;

class Staff extends Controller {
	
	protected $modelName = \Models\Staff::class;

	public function indexStaff(){

		$userControllers = new \Models\Users();

		if (isset($_SESSION['auth'])) {
			$user = $userControllers->user($_SESSION['auth']['id_user']);
			if ($user['grade'] >= 2) {
				$pageTitle = "Index du staff";
				$style = "../css/commentaires.css";
				\Renderer::render('../templates/staff/staff', '../templates/', compact('pageTitle', 'style', 'user'));
			}
		}
		else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Vous n\'avez pas le droit d\'accéder à cette page !';
			\Http::redirect('../index.php');
		}
	}
}