<?php 

namespace controllers;

class Forum extends Controller {

	protected $modelName = \models\Forum::class;

	public function index(){
		$pageTitle = "Index du forum";
		$style = '../css/commentaires.css';
		$categories = $this->model->recupererCategories();
		\Renderer::render('../templates/forum/index', '../templates', compact('pageTitle', 'style', 'categories'));
    }

    public function ajouterTopic(){
    	if (isset($_POST['tsubmit'])) {
    		if (isset($_POST['tsujet']) && isset($_POST['tcontenu'])) {
    			$sujet = \Rewritting::sanitize($_POST['tsujet']);
    			$contenu = \Rewritting::sanitize($_POST['tcontenu']);
    			if (strlen($sujet > 3 || $sujet < 60)) {
    				$this->model->ajouterTopic($sujet, $contenu);
    				$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = "Votre topic a bien été posté !";
					$_SESSION['flash-color'] = "success";
					\Http::redirect('index.php');
    			}
    			else {
    				echo "pas bon";
    			}
    		} else {
    			echo "toujours pas bon";
    		}
    	}
    }

}