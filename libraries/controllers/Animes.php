<?php 

namespace controllers;

class Animes extends Controller {

	protected $modelName = \models\Animes::class;
    private $isAdmin;

    public function __construct() {
        parent::__construct();
        $this->isAdmin = isset($_SESSION['auth']) ? $_SESSION['auth']['grade'] >= 4 : false;
    }

	public function index(){
		$users = new \models\Users();
		$user = NULL;
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
		}
		$pageTitle = "Accueil des animes";
		$style = "../css/commentaires.css";
		$description = "Retrouvez l'ensemble de nos animes sur mangasfan.fr - Mangas'Fan";
		if (!empty($_GET['page']) && is_numeric($_GET['page'])){
			$page = stripslashes($_GET['page']); 
		} else { 
			$page = 1;
		}
		$pagination = 24;
		$limit_start = ($page - 1) * $pagination;
		$nb_total = $this->model->paginationCount();
		$nb_pages = ceil($nb_total / $pagination);
		$allAnimes = $this->model->allAnimes($limit_start, $pagination);
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Animes");
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
			$animeDemande = $this->model->animeDemande($_POST['search']);
			if (isset($animeDemande['titre'])) {
				\Http::redirect($animeDemande['slug']);
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Cet anime n'existe pas !";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/animes/index', '../templates/', compact('pageTitle', 'style', 'allAnimes', 'nb_pages', 'page', 'description'));
	}

	public function animes(){
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
		$anime = $this->model->searchAnime($_GET['id']);
		if (!isset($anime['id'])) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Cet anime n\'existe pas !';
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		if(strpos($_SERVER['REQUEST_URI'],'/animes/animes.php')){
			\Http::redirect($anime['slug']);
		}
		$compterArticles = $this->model->verifierNbrArticles($anime['id']);
		if ($compterArticles->rowCount() == 0) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Il n\'y a aucun article pour cet anime.';
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = \Rewritting::sanitize($anime['titre']);
		$style = "../css/commentaires.css";
		$description = \Rewritting::sanitize($anime['presentation']);
		$image = \Rewritting::sanitize($anime['cover']);
		$notes = $this->model->notes('animes', $anime['id']);
		list($moyenne_note, $rst_moy, $vote) = $notes;
		$verifier = "";
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Animes");
		if ((!isset($_SESSION['auth']) OR $user['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		if(isset($_SESSION['auth'])){ 
			$verifier = $this->model->verifierVote($user['id_user'], "animes", $anime['id']);
			if ($verifier->rowCount() == 0){ 
				if(isset($_POST['etoile1'])){
					$this->model->voter($user['id_user'], 1,  "animes", $anime['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($anime['slug']);
				} elseif (isset($_POST['etoile2'])){
					$this->model->voter($user['id_user'], 2, "animes", $anime['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($anime['slug']);
				} elseif (isset($_POST['etoile3'])){
					$this->model->voter($user['id_user'], 3, "animes", $anime['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($anime['slug']);
				} elseif (isset($_POST['etoile4'])){
					$this->model->voter($user['id_user'], 4, "animes", $anime['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($anime['slug']);
				} elseif (isset($_POST['etoile5'])){
					$this->model->voter($user['id_user'], 5, "animes", $anime['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($anime['slug']);
				}
			}
		}
		$articlesAnimes = $this->model->pagesAnimes($anime['id'], $this->isAdmin);
		$lastArticle = $this->model->lastArticle($anime['id'], $_GET['id']);
		$category = $this->model->category($anime['id']);
		list($recup_all_category, $parcours_category) = $category;
		$animes = $this->model->oneAnime($anime['id']);
		$verifierCategory = $this->model->verifierCategory($animes['name_category'], $anime['id']);
		$catExist = $this->model->categoryExist($animes['name_category'], $anime['id'], $this->isAdmin);
		\Renderer::render('../templates/animes/voirAnime', '../templates/', compact('pageTitle', 'style', 'anime', 'notes', 'moyenne_note', 'rst_moy', 'vote', 'verifier', 'articlesAnimes', 'compterArticles', 'lastArticle', 'recup_all_category', 'parcours_category', 'verifierCategory', 'catExist', 'animes', 'description', 'image'));
	}

	public function categories(){
		$category = $this->model->categoryExist($_GET['name_cat'], $_GET['id_elt'], $this->isAdmin);
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
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('../');
		}

		$article = $this->model->lastArticle($_GET['article'], $_GET['anime']);
        if (!isset($article['slug_article']) || ($article['visible'] && $_SESSION['auth']['grade'] < 4)) {
            $_SESSION['flash-type'] = 'error-flash';
            $_SESSION['flash-message'] = 'Cet article n\'existe pas !';
            $_SESSION['flash-color'] = "warning";
            \Http::redirect('../');
        }

		$pageTitle = $article['name_article'] . " - " . $article['titre'];
		$style = "../../css/commentaires.css";
		$image = $article['cover_image_article'];
		\Renderer::render('../templates/animes/voirArticle', '../templates/', compact('pageTitle', 'style', 'article', 'user', 'image'));
	}
}
