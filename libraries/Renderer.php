<?php 

class Renderer {

	public static function render(string $path, string $bothpath, array $variables = []) : void {
		$controller = new \controllers\Users();
		$utilisateur = $controller->utilisateurConnecte();
		if (isset($_SESSION['auth']) && $utilisateur['username'] != $_SESSION['auth']['username']) {
			\Http::redirect('membres/deconnexion.php');
			exit();
		}
		if(isset($_COOKIE['accept_cookie'])) {
			$showcookie = false;
		} else {
			$showcookie = true;
		}
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Site");
		if ((!isset($_SESSION['auth']) OR $utilisateur['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		extract($variables);
		ob_start();
		require($path . '.html.php');
		$pageContent = ob_get_clean();
		require($bothpath . '/layout.html.php');
	}
}