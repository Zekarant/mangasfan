<?php 

class Renderer {

	public static function render(string $path, string $bothpath, array $variables = []) : void {
		$controller = new \Controllers\Users();
		$utilisateur = $controller->utilisateurConnecte();
		if (isset($_SESSION['auth']) && $utilisateur['username'] != $_SESSION['auth']['username']) {
			\Http::redirect('membres/deconnexion.php');
			exit();
		}
		extract($variables);
		ob_start();
		require($path . '.html.php');
		$pageContent = ob_get_clean();
		require($bothpath . '/layout.html.php');
	}
}