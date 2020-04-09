<?php

namespace Controllers;


class NewsComment extends Controller {

	protected $modelName = \Models\NewsComment::class;

	public function delete() {
		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			die("L'id n'a pas été renseigné");
		}
		$id = $_GET['id'];
		$news = $this->model->deleteComment($id);
		\Http::redirect('../commentaire.php?id=' . $_GET['news']);
	}

	public function edit(){
		if (isset($_GET['id']) && !empty($_GET['id']) & is_numeric($_GET['id'])) {
			if(isset($_POST['valider'])){
				$this->model->editComment($_POST['commentaire'], $_GET['id']);
				\Http::redirect('../commentaire.php?id=' . $_GET['news']);
			} else {
				$commentary = $this->model->findComment($_GET['id']);
				if (isset($commentary['id_commentary'])) {
					$pageTitle = "Modifier mon commentaire - " . $commentary['title'];
					$style = "../css/index.css";
					\Renderer::render('../templates/news/edit', '../templates/', compact('commentary', 'pageTitle', 'style'));
				} else {
					\Http::redirect('../index.php');
				}
			}
		} else {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash_message'] = "Oups ! Il semblerait que nous ayons rencontré une erreur et que nous ayons du vous rediriger !";
			\Http::redirect('../index.php');
		}
	}
}