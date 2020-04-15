<?php

namespace Controllers;


class NewsComment extends Controller {

	protected $modelName = \Models\NewsComment::class;

	public function delete() {
		$users = new \Models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../commentaire.php?id=' . $_GET['news']);
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		$newsSearch = $this->model->findComment($_GET['id']);
		if ($user['id_user'] != $newsSearch['auteur'] && $user['grade'] < 6) {
			\Http::redirect('../commentaire.php?id=' . $_GET['news']);
		}
		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			die("L'id n'a pas été renseigné");
		}
		if ($_GET['id'] != $newsSearch['id_commentary']) {
			\Http::redirect('../commentaire.php?id=' . $_GET['news']);
		}
		$id = $_GET['id'];
		$news = $this->model->deleteComment($id);
		\Http::redirect('../commentaire.php?id=' . $_GET['news']);
	}

	public function edit(){
		$users = new \Models\Users();
		if (isset($_GET['id']) && !empty($_GET['id']) & is_numeric($_GET['id'])) {
			if (!isset($_SESSION['auth'])) {
				\Http::redirect('../commentaire.php?id=' . $_GET['news']);
			}
			$user = $users->user($_SESSION['auth']['id_user']);
			$newsSearch = $this->model->findComment($_GET['id']);
			if ($user['id_user'] != $newsSearch['auteur']) {
				\Http::redirect('../commentaire.php?id=' . $newsSearch['id_news']);
			}
			if(isset($_POST['valider'])){
				$this->model->editComment($_POST['commentaire'], $_GET['id']);
				\Http::redirect('../commentaire.php?id=' . $_GET['news']);
			} else {
				$commentary = $this->model->findComment($_GET['id']);
				if (isset($commentary['id_commentary'])) {
					$pageTitle = "Modifier mon commentaire - " . $commentary['title'];
					$style = "../css/commentaires.css";
					\Renderer::render('../templates/news/edit', '../templates/', compact('commentary', 'pageTitle', 'style'));
				} else {
					$_SESSION['flash-type'] = "error-flash";
					$_SESSION['flash-message'] = "Oups ! Il semblerait que nous ayons rencontré une erreur et que nous ayons dû vous rediriger !";
					\Http::redirect('../index.php');
				}
			}
		} else {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Oups ! Il semblerait que nous ayons rencontré une erreur et que nous ayons dû vous rediriger !";
			\Http::redirect('../index.php');
		}
	}
}