<?php

namespace Controllers;

class News extends Controller {

	protected $modelName = \Models\News::class;

	/**
	*
	* Affiche les news récupérée dans Model/News() sur l'index.
	* @return void
	*/
	public function index() : void {
		$news = $this->model->findAllNews('create_date DESC', '0, 9');
		$pageTitle = "L'actualité des mangas et des animes";
		$style = "css/index.css";
		$description = "Toute l'actualité des animes sur Mangas'Fan ! News, mangas, animes, jeux, tout est à portée de main ! Votre communauté de fans sur Mangas'Fan.";
		$keywords = "Mangas, Fan, Animes, Site Mangas, Produits, Adaptation, Contenu, Site, Communauté, Partenaires, Actualités, Sorties, Débats, Site de discussions mangas, Manga, Fan Manga, Mangas fans, Jeux, Jeux de mangas, Manga Fan, Mangas'Fan";
		$users = new \Models\Users();
		$staff = $users->recupererStaff();
		\Renderer::render('templates/news/index', 'templates/', compact('news', 'pageTitle', 'style', 'description', 'keywords', 'staff'));
	}


	public function delete() {
		$users = new \Models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('commentaire.php?id=' . $_GET['id']);
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] <= 4 && $user['chef'] == 0) {
			\Http::redirect('commentaire.php?id=' . $_GET['id']);
		}
		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			die("L'id n'a pas été renseigné");
		}
		$id = $_GET['id'];
		$news = $this->model->deleteNews($id);
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "La news a bien été supprimée !";
		\Http::redirect('index.php');
	}

	public function showNews(){

		$commentModel = new \Models\NewsComment();

		$news_id = NULL;
		if (!empty($_GET['id'])) {
			$news_id = $_GET['id'];
		}
		if (!$news_id) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "On ne peut pas chercher une news qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
			\Http::redirect('index.php');
		}
		if (is_numeric($news_id)) {
			$news = $this->model->findNews($news_id);
			if (!isset($news['id_news'])) {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "La news que vous avez demandée n'existe pas, pour éviter les problèmes, nous vous avons redirigé !";
				\Http::redirect('index.php');
			} else {
				\Http::redirect('commentaire/' . $news['slug']);
			}
		} elseif (is_string($news_id)) {
			$news = $this->model->findNewsBySlug($news_id);
			if ($news_id == str_replace("-", "_", $news_id)) {
				$news_id =  str_replace("_", "-", $news_id);
				\Http::redirect($news_id);
			} elseif (!isset($news['slug'])) {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "La news que vous avez demandée n'existe pas, pour éviter les problèmes, nous vous avons redirigé !";
				\Http::redirect('../index.php');
			}
		}
		$commentaires = $commentModel->findAllComment($news['id_news']);
		$style = "../css/commentaires.css";
		$pageTitle = $news['title'];
		$description = $news['description_news'];
		if (empty($news['keywords'])) {
			$keywords = "mangas, animes, fans, communauté";
		} else {
			$keywords = $news['keywords'];
		}
		if (isset($_POST['envoyer_commentaire'])) {
			if (!empty($_POST['comme'])) {
				$commentModel->addComment($news['id_news'], $_SESSION['auth']['id_user'], $_POST['comme']);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre commentaire a bien été ajouté !";
				\Http::redirect('../commentaire.php?id=' . $news['id_news']);
			} else {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Vous n'avez pas renseigné de commentaire !";
				\Http::redirect('../commentaire.php?id=' . $news['id_news']);
			}
		}
		\Renderer::render('templates/news/show', 'templates/', compact('news', 'commentaires', 'style', 'pageTitle', 'description', 'keywords'));
	}
}