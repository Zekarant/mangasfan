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
		\Renderer::render('templates/news/index', 'templates/', compact('news', 'pageTitle', 'style'));
	}


	public function delete() {
		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			die("L'id n'a pas été renseigné");
		}
		$id = $_GET['id'];
		$news = $this->model->deleteNews($id);
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
			$_SESSION['flash_message'] = "On ne peut pas chercher une news qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
			\Http::redirect('index.php');
		}
		if (is_numeric($news_id)) {
			$news = $this->model->findNews($news_id);
			if (!isset($news['id_news'])) {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash_message'] = "La news que vous avez demandée n'existe pas, pour éviter les problèmes, nous vous avons redirigé !";
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
				$_SESSION['flash_message'] = "La news que vous avez demandée n'existe pas, pour éviter les problèmes, nous vous avons redirigé !";
				\Http::redirect('../index.php');
			}
		}
		$commentaires = $commentModel->findAllComment($news['id_news']);
		$style = "../css/commentaires.css";
		$pageTitle = $news['title'];
		\Renderer::render('templates/news/show', 'templates/', compact('news', 'commentaires', 'style', 'pageTitle'));
	}
}