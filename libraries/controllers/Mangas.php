<?php 

namespace controllers;

class Mangas extends Controller {

	protected $modelName = \models\Mangas::class;
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
		$pageTitle = "Accueil des mangas";
		$style = "../css/commentaires.css";
		$description = "Retrouvez l'ensemble de nos mangas sur mangasfan.fr - Mangas'Fan";
		if (!empty($_GET['page']) && is_numeric($_GET['page'])){
			$page = stripslashes($_GET['page']); 
		} else { 
			$page = 1;
		}
		$pagination = 24;
		$limit_start = ($page - 1) * $pagination;
		$nb_total = $this->model->paginationCount();
		$nb_pages = ceil($nb_total / $pagination);
		$allMangas = $this->model->allMangas($limit_start, $pagination);
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Mangas");
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
			$mangaDemande = $this->model->mangaDemande($_POST['search']);
			if (isset($mangaDemande['titre'])) {
				\Http::redirect($mangaDemande['slug']);
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Ce manga n'existe pas !";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/mangas/index', '../templates/', compact('pageTitle', 'style', 'allMangas', 'nb_pages', 'page', 'description'));
	}

	public function mangas(){
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
		$manga = $this->model->searchManga($_GET['id']);
		if (!isset($manga['id'])) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Ce manga n\'existe pas !';
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		if(strpos($_SERVER['REQUEST_URI'],'/mangas/mangas.php')){
			\Http::redirect($manga['slug']);
		}
		$compterArticles = $this->model->verifierNbrArticles($manga['id']);
		if ($compterArticles->rowCount() == 0) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Il n\'y a aucun article pour ce manga.';
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = \Rewritting::sanitize($manga['titre']);
		$style = "../css/commentaires.css";
		$description = \Rewritting::sanitize($manga['presentation']);
		$image = \Rewritting::sanitize($manga['cover']);
		$notes = $this->model->notes('mangas', $manga['id']);
		list($moyenne_note, $rst_moy, $vote) = $notes;
		$verifier = "";
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Mangas");
		if ((!isset($_SESSION['auth']) OR $user['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		if(isset($_SESSION['auth'])){ 
			$verifier = $this->model->verifierVote($user['id_user'], "mangas", $manga['id']);
			if ($verifier->rowCount() == 0){ 
				if(isset($_POST['etoile1'])){
					$this->model->voter($user['id_user'], 1,  "mangas", $manga['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($manga['slug']);
				} elseif (isset($_POST['etoile2'])){
					$this->model->voter($user['id_user'], 2, "mangas", $manga['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($manga['slug']);
				} elseif (isset($_POST['etoile3'])){
					$this->model->voter($user['id_user'], 3, "mangas", $manga['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($manga['slug']);
				} elseif (isset($_POST['etoile4'])){
					$this->model->voter($user['id_user'], 4, "mangas", $manga['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($manga['slug']);
				} elseif (isset($_POST['etoile5'])){
					$this->model->voter($user['id_user'], 5, "mangas", $manga['id']);
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = 'Merci d\'avoir voté !';
					$_SESSION['flash-color'] = "success";
					\Http::redirect($manga['slug']);
				}
			}
		}
		$articlesMangas = $this->model->pagesMangas($manga['id'], $this->isAdmin);
		$lastArticle = $this->model->lastArticle($manga['id'], $_GET['id']);
		$category = $this->model->category($manga['id']);
		list($recup_all_category, $parcours_category) = $category;
		$mangas = $this->model->oneManga($manga['id']);
		$verifierCategory = $this->model->verifierCategory($mangas['name_category'], $manga['id']);
		$catExist = $this->model->categoryExist($mangas['name_category'], $manga['id'], $this->isAdmin);
		\Renderer::render('../templates/mangas/voirManga', '../templates/', compact('pageTitle', 'style', 'manga', 'notes', 'moyenne_note', 'rst_moy', 'vote', 'verifier', 'articlesMangas', 'compterArticles', 'lastArticle', 'recup_all_category', 'parcours_category', 'verifierCategory', 'catExist', 'mangas', 'description', 'image'));
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

		$article = $this->model->lastArticle($_GET['article'], $_GET['manga']);
		if (!isset($article['slug_article']) || ($article['visible'] && $_SESSION['auth']['grade'] < 4)) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'Cet article n\'existe pas !';
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('../');
		}

		$pageTitle = $article['name_article'] . " - " . \Rewritting::sanitize($article['titre']);
		$style = "../../css/commentaires.css";
		$image = $article['cover_image_article'];
		\Renderer::render('../templates/mangas/voirArticle', '../templates/', compact('pageTitle', 'style', 'article', 'user', 'image'));
	}
}
