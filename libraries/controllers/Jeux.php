<?php 

namespace controllers;

class Jeux extends Controller {

	protected $modelName = \models\Jeux::class;

	public function index(){
		$users = new \models\Users();
		$user = NULL;
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
		}
		$pageTitle = "Accueil des jeux vidéo";
		$style = "../css/commentaires.css";
		$description = "Retrouvez l'ensemble de nos jeux vidéo sur mangasfan.fr - Mangas'Fan";
		if (!empty($_GET['page']) && is_numeric($_GET['page'])){
			$page = stripslashes($_GET['page']); 
		} else { 
			$page = 1;
		}
		$pagination = 24;
		$limit_start = ($page - 1) * $pagination;
		$nb_total = $this->model->paginationCount();
		$nb_pages = ceil($nb_total / $pagination);
		$allJeux = $this->model->allJeux($limit_start, $pagination);
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Jeux");
		if ((!isset($_SESSION['auth']) OR $user['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		if(isset($_POST['search_ok'])){
			if (is_numeric($_POST['search'])) {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Vous devez saisir un nom";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
			$jeuDemande = $this->model->jeuDemande($_POST['search']);
			if (isset($jeuDemande['name_jeu'])) {
				\Http::redirect($jeuDemande['slug']);
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Ce jeu n'existe pas !";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/jeux/index', '../templates/', compact('pageTitle', 'style', 'allJeux', 'nb_pages', 'page', 'description'));
	}

	public function jeux(){
		$users = new \models\Users();
		$user = NULL;
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
		}
		if (!isset($_GET['id'])) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Aucun ID a été renseigné, erreur !';
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$jeu = $this->model->searchGame($_GET['id']);
		if (!isset($jeu['id_jeux'])) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Ce jeu n\'existe pas !';
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		if(strpos($_SERVER['REQUEST_URI'],'/jeux-video/jeux.php')){
			\Http::redirect($jeu['slug']);
		}
		$compterArticles = $this->model->verifierNbrArticles($jeu['id_jeux']);
		if ($compterArticles->rowCount() == 0) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Il n\'y a aucun article pour ce jeu.';
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = \Rewritting::sanitize($jeu['name_jeu']);
		$style = "../css/commentaires.css";
		$description = \Rewritting::sanitize($jeu['description_jeu']);
		$image = \Rewritting::sanitize($jeu['cover_jeu']);
		$notes = $this->model->notes('jeux', $jeu['id_jeux']);
		list($moyenne_note, $rst_moy, $vote) = $notes;
		$verifier = "";
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Jeux");
		if ((!isset($_SESSION['auth']) OR $user['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		if(isset($_SESSION['auth'])){ 
			$verifier = $this->model->verifierVote($user['id_user'], "jeux", $jeu['id_jeux']);
			if ($verifier->rowCount() == 0){ 
				if(isset($_POST['etoile1'])){
					$this->model->voter($user['id_user'], 1,  "jeux", $jeu['id_jeux']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-color'] = "success";
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					\Http::redirect($jeu['slug']);
				} elseif (isset($_POST['etoile2'])){
					$this->model->voter($user['id_user'], 2, "jeux", $jeu['id_jeux']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-color'] = "success";
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					\Http::redirect($jeu['slug']);
				} elseif (isset($_POST['etoile3'])){
					$this->model->voter($user['id_user'], 3, "jeux", $jeu['id_jeux']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-color'] = "success";
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					\Http::redirect($jeu['slug']);
				} elseif (isset($_POST['etoile4'])){
					$this->model->voter($user['id_user'], 4, "jeux", $jeu['id_jeux']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-color'] = "success";
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					\Http::redirect($jeu['slug']);
				} elseif (isset($_POST['etoile5'])){
					$this->model->voter($user['id_user'], 5, "jeux", $jeu['id_jeux']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-color'] = "success";
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					\Http::redirect($jeu['slug']);
				}
			}
		}
		$articlesJeux = $this->model->pagesJeux($jeu['id_jeux']);
		$lastArticle = $this->model->lastArticle($jeu['id_jeux'], $_GET['id']);
		$category = $this->model->category($jeu['id_jeux']);
		list($recup_all_category, $parcours_category) = $category;
		$jeux = $this->model->oneGame($jeu['id_jeux']);
		$verifierCategory = $this->model->verifierCategory($jeux['name_category'], $jeu['id_jeux']);
		$catExist = $this->model->categoryExist($jeux['name_category'], $jeu['id_jeux']);
		\Renderer::render('../templates/jeux/voirJeu', '../templates/', compact('pageTitle', 'style', 'jeu', 'notes', 'moyenne_note', 'rst_moy', 'vote', 'verifier', 'articlesJeux', 'compterArticles', 'lastArticle', 'recup_all_category', 'parcours_category', 'verifierCategory', 'catExist', 'jeux', 'description', 'image'));
	}

	public function categories(){
		$category = $this->model->categoryExist($_GET['name_cat'], $_GET['id_elt']);
		$categories = $category->fetchAll();
		return $categories;
	}

	public function article(){
		$users = new \models\Users();
		$user = NULL;
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
		}
		if (!isset($_GET['article'])) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Cet article n\'existe pas !';
			$_SESSION['flash-color'] = "danger";
			\Http::redirect('../');
		}

		$article = $this->model->lastArticle($_GET['article'], $_GET['jeu']);
		if (!isset($article['slug_article'])) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Cet article n\'existe pas !';
			$_SESSION['flash-color'] = "danger";
			\Http::redirect('../');
		}
		$pageTitle = $article['name_article'] . " - " . \Rewritting::sanitize($article['name_jeu']);
		$style = "../../css/commentaires.css";
		$image = $article['cover_image_article'];
		\Renderer::render('../templates/jeux/voirArticle', '../templates/', compact('pageTitle', 'style', 'article', 'user', 'image'));
	}
} 