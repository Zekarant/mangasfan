<?php 

class Renderer {

	public static function render(string $path, string $bothpath, array $variables = []) : void {
		$controller = new \Controllers\Users();
		$utilisateur = $controller->utilisateurConnecte();
		if (isset($_SESSION['auth']) && $utilisateur['username'] != $_SESSION['auth']['username']) {
			\Http::redirect('membres/deconnexion.php');
			exit();
		}
		$controllerMaintenance = new \Models\Administration();
		$maintenance = $controllerMaintenance->verifier("Site");
		if ($utilisateur['grade'] <= 3 && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/mangasfan/maintenance.php');
			exit();
		}
		extract($variables);
		ob_start();
		require($path . '.html.php');
		$pageContent = ob_get_clean();
		require($bothpath . '/layout.html.php');
	}
}