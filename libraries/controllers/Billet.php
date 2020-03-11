<?php

namespace Controllers;

class Billet extends Controller {

	protected $modelName = \Models\Billet::class;

	/*
	*
	* Sélectionne les tables à afficher, définit le titre de la page, le chemin du fichier CSS, ainsi que les variables à prendre en compte dans l'affichage
	* @return void
	*/
	public function index() : void {

		$billets = $this->model->findAll("users u", "u.id = b.auteur", "date_creation DESC", "0, 9");
		$pageTitle = "L'actualité des mangas et des animes";
		$style = "css/index.css";
		\Renderer::render('billets/index', compact('billets', 'pageTitle', 'style'));
	}

	/*
	* Affiche une news spécifique et les commentaires associés
	* @return void
	*/
	public function show() {

		$commentModel = new \Models\CommentNews();
		// On part du principe qu'on ne possède pas de param "id"
		$billet_id = null;

		// Mais si il y'en a un et que c'est un nombre entier, alors c'est cool
		if (!empty($_GET['id'])) {
			$billet_id = $_GET['id'];
		}
		// Si on a pas de id renseigné, on redirige sur la page d'accueil
		if (!$billet_id) {
			echo "Error";
		}
		$billet = $this->model->find($billet_id);
		$commentaires = $commentModel->findAllWithNews($billet_id);
		$style = "../css/commentaires.css";
		$pageTitle = $billet['titre'];
		\Renderer::render('billets/show', compact('pageTitle', 'style', 'billet', 'commentaires', 'billet_id'));

	}

	/*
	* Supprime une news
	* @return void
	*/
	public function delete() : void {

	if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
		die("Ho ?! Tu n'as pas précisé l'id de l'article !");
	}

	$id = $_GET['id'];
	$billet = $this->model->find($id);
	if (!$billet) {
		\Http::redirect('index.php');
	}
	$this->model->delete($id);
	\Http::redirect("index.php");
	}
}