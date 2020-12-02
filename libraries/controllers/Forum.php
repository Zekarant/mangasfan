<?php 

namespace controllers;

class Forum extends Controller {

	protected $modelName = \models\Forum::class;

	public function index(){
		$pageTitle = "Index du forum";
		$style = '../css/commentaires.css';
		$categories = $this->model->allForums();
		$users = new \models\Users();
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
		} else {
			$user = NULL;
		}
		if (isset($_POST['sectionSubmit'])) {
			Forum::ajouterSection($_POST['sectionName'], $user);
		}
		\Renderer::render('../templates/forum/index', '../templates', compact('pageTitle', 'style', 'categories'));
	}

	public function voirforum(int $idForum){
		$sousForum = $this->model->allSousForums($idForum);
		$pageTitle = $sousForum['forum_name'];
		$style = "../css/commentaires.css";
		$sujets = $this->model->allSujetsAnnonces($idForum);
		$sujetsNormaux = $this->model->allSujets($idForum);
		$users = new \models\Users();
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
		} else {
			$user = NULL;
		}
		if (isset($_POST['topicValider'])) {
			Forum::ajouterTopic($_POST['titleTopic'], $_POST['typeTopic'], $idForum, $user['id_user'], $_POST['messageTopic']);
		}
		\Renderer::render('../templates/forum/sousForum', '../templates', compact('pageTitle', 'style', 'sousForum', 'sujets', 'sujetsNormaux'));
	}

	public function listerTopic(int $idTopic){
		$topic = $this->model->topic($idTopic);
		$pageTitle = $topic['topic_titre'];
		$style = "../css/commentaires.css";
		$messages = $this->model->allMessages($idTopic);
		$users = new \models\Users();
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
		} else {
			$user = NULL;
		}
		if (isset($_POST['validerMessage'])) {
			Forum::posterMessage($idTopic, $user['id_user'], $_POST['contenuMessage'], $topic['id_forum']);
		}
		\Renderer::render('../templates/forum/topic', '../templates', compact('pageTitle', 'style', 'topic', 'messages', 'user'));
	}

	public function posterMessage($idTopic, $utilisateur, $message, $forum){
		if (!empty($message)) {
			if (isset($utilisateur)) {
				$this->model->ajouterMessage($idTopic, $utilisateur, $message, $forum);
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Votre message a bien été posté !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect($_SERVER['HTTP_REFERER']);
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Vous devez être connecté pour pouvoir poster un message.";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous ne pouvez pas poster un message vide !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

	public function ajouterTopic($title, $type, $idForum, $user, $message){
		if (!empty($message)) {
			if ($user != NULL) {
				$this->model->ajouterTopic($title, $type, $idForum, $user, $message);
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Vous devez être connecté pour pouvoir poster un message.";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous ne pouvez pas poster un message vide !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

	public function ajouterSection(string $nameSection, $idUser){
		if ($idUser['grade'] > 6) {
			if (!empty($nameSection)) {
				if (strlen($nameSection) > 5) {
					$this->model->ajouterSection($nameSection);
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
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous n'avez pas les droits d'ajouter une section !";
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

	// public function listerTopic(){
	// 	$style = '../css/commentaires.css';
	// 	if(is_numeric($_GET['id_topic'])){
	// 		$categorie = $this->model->recupererCategorie($_GET['id_topic']);
	// 		\Http::redirect($categorie['slug']);
	// 	} else {
	// 		$slugCategory = \Rewritting::stringToURLString($_GET['id_topic']); 
	// 		$categorie = $this->model->recupererCategorieBySlug($slugCategory);
	// 	}
	// 	if ($categorie == NULL) {
	// 		$_SESSION['flash-type'] = 'error-flash';
	// 		$_SESSION['flash-message'] = "Cette catégorie n'existe pas !";
	// 		$_SESSION['flash-color'] = "warning";
	// 		\Http::redirect('index.php');
	// 	}
	// 	if ($categorie['parents'] > 2) {
	// 		$_SESSION['flash-type'] = 'error-flash';
	// 		$_SESSION['flash-message'] = "Erreur : Ce n'est pas une catégorie mais une sous catégorie !";
	// 		$_SESSION['flash-color'] = "warning";
	// 		\Http::redirect('index.php');
	// 	}
	// 	$pageTitle = \Rewritting::sanitize($categorie['name']);
	// 	$sousCategories = $this->model->listerSousCategories($categorie['id']);
	// 	$topics = $this->model->listerTopics($categorie['id']);
	// 	$compter = $this->model->compterMessages($categorie['id']);
	// 	$dernierMembre = $this->model->dernierMessage($categorie['id']);
	// 	\Renderer::render('../templates/forum/topic', '../templates', compact('pageTitle', 'style', 'sousCategories', 'topics', 'categorie', 'compter', 'dernierMembre'));
	// }

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
					\Http::redirect(\Rewritting::sanitize($categorie['slug']) . "/messages/" . \Rewritting::sanitize($topic['slug_topic']));
				} else {
					$categorie = $this->model->recupererCategorie($_GET['id_category']);
					$sousCategorie = $this->model->recupererCategorie($_GET['souscategory']);
					$topic = $this->model->recupererTopicBySlugInt($sousCategorie['slug'], $_GET['id_message']);
					\Http::redirect(\Rewritting::sanitize($categorie['slug'] . "/" . $sousCategorie['slug']) . "/" . \Rewritting::sanitize($topic['slug_topic']));

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