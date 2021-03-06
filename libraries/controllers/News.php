<?php

namespace controllers;

class News extends Controller {

	protected $modelName = \models\News::class;

	/**
	*
	* Affiche les news récupérées dans Model/News() sur l'index.
	* @return void
	*/
	public function index() : void {
		$new = $this->model->findLastNew();
		$news = $this->model->findAllNews('create_date DESC', '1, 9');
		$pageTitle = "L'actualité des mangas et des animes";
		$style = "css/index.css";
		$description = "Toute l'actualité des animes sur Mangas'Fan ! News, mangas, animes, jeux, tout est à portée de main ! Votre communauté de fans sur Mangas'Fan.";
		$keywords = "Mangas, Fan, Animes, Site Mangas, Produits, Adaptation, Contenu, Site, Communauté, Partenaires, Actualités, Sorties, Débats, Site de discussions mangas, Manga, Fan Manga, Mangas fans, Jeux, Jeux de mangas, Manga Fan, Mangas'Fan";
		$image = "https://www.pixenli.com/image/J6FtHnhW";
		$animations = new \models\Animation();
		$animation = $animations->animation();
		$mangas = $this->model->recentsMangas();
		\Renderer::render('templates/news/index', 'templates/', compact('news', 'pageTitle', 'style', 'description', 'keywords', 'animation', 'image', 'mangas', 'new'));
	}

	public function newsArchives(){
		$pageTitle = "Archives des news";
		$style = "css/index.css";
		$archives = $this->model->archives();
		\Renderer::render('templates/news/archives', 'templates/', compact('archives', 'pageTitle', 'style'));
	}

	public function delete() {
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('commentaire.php?id=' . $_GET['id']);
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] <= 4 && $user['chef'] == 0) {
			\Http::redirect('commentaire.php?id=' . \Rewritting::sanitize($_GET['id']));
		}
		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			die("L'id n'a pas été renseigné");
		}
		$id = $_GET['id'];
		$news = $this->model->deleteNews($id);
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "La news a bien été supprimée !";
		$_SESSION['flash-color'] = "success";
		$logs = new \models\Administration();
      	$logs->insertLogs($user['id_user'], "a supprimé une news", "Suppression de news");
		\Http::redirect('index.php');
	}

	public function showNews(){

		$commentModel = new \models\NewsComment();
		$gradeMembre = new \models\Users();
		

		$news_id = NULL;
		if (!empty($_GET['id'])) {
			$news_id = $_GET['id'];
		}
		if (!$news_id) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "On ne peut pas chercher une news qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		if (is_numeric($news_id)) {
			$news = $this->model->findNews($news_id);
			if (!isset($news['id_news'])) {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "La news que vous avez demandée n'existe pas, pour éviter les problèmes, nous vous avons redirigé !";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			} else {
				\Http::redirect('commentaire/' . \Rewritting::sanitize($news['slug']));
			}
		} elseif (is_string($news_id)) {
			$news = $this->model->findNewsBySlug($news_id);
			if ($news_id == str_replace("-", "_", $news_id)) {
				$news_id =  str_replace("_", "-", $news_id);
				\Http::redirect($news_id);
			} elseif (!isset($news['slug'])) {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "La news que vous avez demandée n'existe pas, pour éviter les problèmes, nous vous avons redirigé !";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('../index.php');
			}
		}
		$commentaires = $commentModel->findAllComment($news['id_news']);
		$style = "../css/commentaires.css";
		$pageTitle = $news['title'];
		$description = $news['description_news'];
		$image = $news['image'];
		if (empty($news['keywords'])) {
			$keywords = "mangas, animes, fans, communauté";
		} else {
			$keywords = $news['keywords'];
		}
		$users = new \models\Users();
		$user = NULL;
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
		}
		if (isset($_POST['envoyer_commentaire'])) {
			if (!empty($_POST['comme'])) {
				$commentModel->addComment($news['id_news'], $_SESSION['auth']['id_user'], $_POST['comme']);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre commentaire a bien été ajouté !";
				$logs = new \models\Administration();
      			$logs->insertLogs($user['id_user'], "a ajouté un commentaire", "Commentaires de news");
				\Http::redirect('../commentaire.php?id=' . $news['id_news']);
			} else {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Vous n'avez pas renseigné de commentaire !";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('../commentaire.php?id=' . \Rewritting::sanitize($news['id_news']));
			}
		}
		\Renderer::render('templates/news/show', 'templates/', compact('news', 'commentaires', 'style', 'pageTitle', 'description', 'keywords', 'image'));
	}

	public function categoriesNews(){
		if (!isset($_GET['id'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné d'ID !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('/index.php');
		}
		if ($_GET['id'] != "mangas" && $_GET['id'] != "animes" && $_GET['id'] != "jv" && $_GET['id'] != "site") {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "On a rien en rapport avec cet ID, désolés !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('/index.php');
		}
		if(strpos($_SERVER['REQUEST_URI'],'/categories.php') !== FALSE){
			\Http::redirect('categories/' . $_GET['id']);
		}
		if ($_GET['id'] == "mangas") {
			$pageTitle = "News sur les mangas";
			$search = "Mangas";
		} elseif ($_GET['id'] == "animes") {
			$pageTitle = "News sur les animes";
			$search = "Anime";
		} elseif ($_GET['id'] == "jv") {
			$pageTitle = "News sur les jeux vidéo";
			$search = "Jeux Vidéo";
		} else {
			$pageTitle = "News sur le site";
			$search = "Site";
		}
		$style = "../css/commentaires.css";
		$categories = $this->model->newsCategories($search);
		\Renderer::render('templates/news/categories', 'templates/', compact('style', 'pageTitle', 'categories'));
	}
}