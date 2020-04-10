<?php 

class Renderer {

	public static function render(string $path, string $bothpath, array $variables = []) : void {
		$controller = new \Controllers\Users();
		$utilisateur = $controller->utilisateurConnecte();
		extract($variables);
		ob_start();
		require($path . '.html.php');
		$pageContent = ob_get_clean();
		require($bothpath . '/layout.html.php');
	}
}