<?php 

namespace controllers;

class Forum extends Controller {

	protected $modelName = \models\Forum::class;

	public function index(){
		$pageTitle = "Index du forum";
		$style = '../css/commentaires.css';
		$categories = $this->model->recupererCategories();
		if (isset($_POST['sectionSubmit'])) {
			Forum::ajouterSection($_POST['sectionName']);
		}
		$recupererCategoriesPrincipales = $this->model->listerSousCategories(0);
		if (isset($_POST['categorieSubmit'])) {
			Forum::ajouterCategorie($_POST['titreCategorie'], $_POST['categoriesAdd']);
		}
		if (isset($_POST['sousCategorieSubmit'])) {
			Forum::ajouterCategorie($_POST['titreSousCategorie'], $_POST['sousCategoriesAdd']);
		}
		$recupererSousCategoriesPrincipales = $this->model->sousCategories();
		\Renderer::render('../templates/forum/index', '../templates', compact('pageTitle', 'style', 'categories', 'recupererCategoriesPrincipales', 'recupererSousCategoriesPrincipales'));
	}

	public function ajouterSection(string $nameSection){
		if (!empty($nameSection)) {
			if (strlen($nameSection) > 5) {
				$slug = \Rewritting::stringToURLString($nameSection);
				$this->model->ajouterSection($nameSection, $slug);
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "La section a bien été créée sur la page d'index du forum !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Le titre de la section est trop court, il doit avoir 6 caractères minimum.";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous ne pouvez pas ajouter une section qui ne possède pas de titre !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

	public function ajouterCategorie(string $nameCategorie, int $parent){
		if (!empty($nameCategorie)) {
			if (strlen($nameCategorie) > 5) {
				$slug = \Rewritting::stringToURLString($nameCategorie);
				$this->model->ajouterCategorie($nameCategorie, $parent, $slug);
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "La catégorie a bien été créée dans la section demandée";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Le titre de la catégorie est trop court, il doit avoir 6 caractères minimum.";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous ne pouvez pas ajouter une catégorie qui ne possède pas de titre !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

	public function ajouterSousCategorie(string $nameCategorie, int $parent){
		if (!empty($nameCategorie)) {
			if (strlen($nameCategorie) > 5) {
				$slug = \Rewritting::stringToURLString($nameCategorie);
				$this->model->ajouterCategorie($nameCategorie, $parent, $slug);
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "La catégorie a bien été créée dans la section demandée";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Le titre de la catégorie est trop court, il doit avoir 6 caractères minimum.";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous ne pouvez pas ajouter une catégorie qui ne possède pas de titre !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

	public function listerTopic(){
		$style = '../css/commentaires.css';
		if(is_numeric($_GET['id_topic'])){
			$categorie = $this->model->recupererCategorie($_GET['id_topic']);
			\Http::redirect($categorie['slug']);
		} else {
			$slugCategory = \Rewritting::stringToURLString($_GET['id_topic']); 
			$categorie = $this->model->recupererCategorieBySlug($slugCategory);
		}
		if ($categorie == NULL) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Cette catégorie n'existe pas !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		if ($categorie['parents'] > 2) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Erreur : Ce n'est pas une catégorie mais une sous catégorie !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = \Rewritting::sanitize($categorie['name']);
		$sousCategories = $this->model->listerSousCategories($categorie['id']);
		$topics = $this->model->listerTopics($categorie['id']);
		
		\Renderer::render('../templates/forum/topic', '../templates', compact('pageTitle', 'style', 'sousCategories', 'topics', 'categorie'));
	}

	public function listerSousTopic(){
		$style = '../../css/commentaires.css';
		if (isset($_GET['id']) AND isset($_GET['id_sous'])) {
			$categorie = $this->model->recupererCategorieBySlug($_GET['id']);
			if ($categorie['slug'] == $_GET['id']) {
				if(is_numeric($_GET['id_sous'])){
					$sousCategorie = $this->model->recupererCategorie($_GET['id_sous']);
					if ($sousCategorie['slug'] = $_GET['id_sous']) {
						if ($sousCategorie['parents'] == $categorie['id']) {
							\Http::redirect($categorie['slug'] . $sousCategorie['id'] . "-" . $sousCategorie['slug']);
						} else {
							$_SESSION['flash-type'] = 'error-flash';
							$_SESSION['flash-message'] = "Erreur : Cette sous-catégorie n'appartient pas à cette catégorie !";
							$_SESSION['flash-color'] = "warning";
							\Http::redirect('index.php');
						}
					} else {
						echo "Erreur : cette sous catégorie n'existe pas";
					}
				}
				else {
					$slugCategory = \Rewritting::stringToURLString($_GET['id_sous']); 
					$sousCategorie = $this->model->recupererCategorieBySlug($slugCategory);
				}
			} else {
				echo "Erreur";
			}
			$pageTitle = \Rewritting::sanitize($sousCategorie['name']);
			$topics = $this->model->listerTopics($sousCategorie['id']);
			\Renderer::render('../templates/forum/sousTopic', '../templates', compact('pageTitle', 'categorie', 'style', 'sousCategorie', 'topics'));
		}
	}

	public function listerMessages(){
		$style = '../../../css/commentaires.css';
		if (isset($_GET['id_category']) || isset($_GET['souscategory']) && isset($_GET['id_message'])) {
			if (is_numeric($_GET['id_category']) && is_numeric($_GET['souscategory']) && is_numeric($_GET['souscategory'])) {
				if ($_GET['id_category'] === $_GET['souscategory']) {
					$categorie = $this->model->recupererCategorie($_GET['id_category']);
					$topic = $this->model->recupererTopicBySlugInt($categorie['slug'], $_GET['id_message']);
					\Http::redirect(\Rewritting::sanitize($categorie['slug']) . "/messages/" . \Rewritting::sanitize($topic['titre']));
				} else {
					$categorie = $this->model->recupererCategorie($_GET['id_category']);
					$sousCategorie = $this->model->recupererCategorie($_GET['souscategory']);
					$topic = $this->model->recupererTopicBySlugInt($sousCategorie['slug'], $_GET['id_message']);
					\Http::redirect(\Rewritting::sanitize($categorie['slug'] . "/" . $sousCategorie['slug']) . "/" . \Rewritting::sanitize($topic['titre']));

				}
			} else {
				$categorie = $this->model->recupererCategorieBySlug($_GET['id_category']);
				if (isset($_GET['souscategory']) && $_GET['souscategory'] != "messages") {
					$topic = $this->model->recupererTopicBySlug($_GET['souscategory'], $_GET['id_message']);
				} else {
					$topic = $this->model->recupererTopicBySlug($_GET['id_category'], $_GET['id_message']);
				}
				
			}
			$pageTitle = \Rewritting::sanitize($topic['titre']);
			$messages = $this->model->allMessages($topic['id_topic']);
			\Renderer::render('../templates/forum/messages', '../templates', compact('style', 'pageTitle', 'topic', 'messages'));

		}

	}

}