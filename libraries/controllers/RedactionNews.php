<?php 

namespace Controllers;

class RedactionNews extends Controller {

	protected $modelName = \Models\RedactionNews::class;

	public function index(){
		$pageTitle = "Index de la rédaction";
		$style = '../../css/staff.css';
		$news = $this->model->recupererNews();
		\Renderer::render('../../templates/staff/news/index', '../../templates/staff/news', compact('pageTitle', 'style', 'news'));
	}

	public function verifierNews(){
		if (isset($_GET['id_news']) && is_numeric($_GET['id_news'])) {
			$pageTitle = "Modifier une news";
			$style = '../../css/staff.css';
			$news = $this->model->verifierNews($_GET['id_news']);
			if (!isset($news['id_news'])) {
				\Http::redirect('index.php');
			}
			$variables = ['pageTitle', 'style', 'news'];
			if (isset($_POST['valider_news'])) {
				$modifierNews = RedactionNews::modifierNews();
				$errors = $modifierNews;
				$variables = array_merge($variables, ['news', 'modifierNews', 'errors']);
				if (empty($errors)) {
					\Http::redirect('modifier_news.php?id_news=' . $news['id_news']);
				}
			}
			\Renderer::render('../../templates/staff/news/modifier', '../../templates/staff/news', compact($variables));
		} else {
			\Http::redirect('index.php');
		}
	}

	public function modifierNews(){
		if(strlen($_POST['modif_titre']) < 4 || strlen($_POST['modif_titre']) > 100){
			$errors[] = "Le titre de votre article est trop court. (Entre 5 et 100 caractères)";
		}
		if(strlen($_POST['modif_description']) < 20 || strlen($_POST['modif_description']) > 200){
			$errors[] = "Votre description doit faire entre 20 et 200 caractères. Votre description faisait : " . strlen($_POST['modif_description']) . " caractères.";
		}
		if(empty($_POST['modif_image'])){
			$errors[] = "Vous n'avez pas renseigné d'images.";
		}
		if(isset($_POST['modif_contenu']) AND strlen($_POST['modif_contenu']) < 100){
			$errors[] = "Votre contenu doit posséder minimum 100 caractères. Votre contenu faisait : " . strlen($_POST['contenu_news']) . " caractères.";
		}
		if (empty($errors)) {
			$news = $this->model->verifierNews($_GET['id_news']);
			$slug = \Rewritting::stringToURLString($news['title']);
			$news_slug = \Rewritting::remove_accents($slug);
			$this->model->modifierNews($_POST['modif_titre'], $_POST['modif_description'], $_POST['programmation_news'], $_POST['modif_keywords'], $_POST['modif_image'],$_POST['modif_contenu'], $_POST['modif_categorie'], $_POST['modif_sources'], $_POST['modif_visibilite'], $news_slug, $_GET['id_news']);
		}
		return $errors;
	}
}