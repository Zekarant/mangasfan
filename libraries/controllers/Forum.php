<?php 

namespace controllers;

class Forum extends Controller {

	protected $modelName = \models\Forum::class;

	public function index(){
		$pageTitle = "Index du forum";
		$style = '../css/commentaires.css';
		$description = "Forum officiel de Mangas'Fan, venez discuter de tout avec d'autres membres de la communauté. Animes, mangas, jeux vidéo, tout est là !";
		$image = "https://www.pixenli.com/image/J6FtHnhW";
		$users = new \models\Users();
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
			$forums = $this->model->allForums($user['id_user']);
		} else {
			$user = 0;
			$forums = $this->model->allForums($user);
		}
		if (isset($_POST['sectionSubmit'])) {
			Forum::ajouterSection($_POST['sectionName'], $user);
		}
		$categories = $this->model->allCategories();
		if (isset($_POST['addForum'])) {
			Forum::addForum($user, $_POST['titreForum'], $_POST['descriptionForum'], $_POST['addCategorie'], $_POST['addPermission'], $_POST['statusForum']);
		}
		\Renderer::render('../templates/forum/index', '../templates', compact('pageTitle', 'style', 'forums', 'user', 'categories', 'description', 'image'));
	}

	public function voirforum(int $idForum){
		$sousForum = $this->model->allSousForums($idForum);
		$pageTitle = $sousForum['forum_name'];
		$description = "Tous les sujets de " . \Rewritting::sanitize($sousForum['forum_name']);
		$image = "https://www.pixenli.com/image/J6FtHnhW";
		$style = "../css/commentaires.css";
		$users = new \models\Users();
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
			$sujets = $this->model->allSujetsAnnonces($idForum, $user['id_user']);
			$sujetsNormaux = $this->model->allSujets($idForum, $user['id_user']);
		} else {
			$user = 0;
			$sujets = $this->model->allSujetsAnnonces($idForum, $user);
			$sujetsNormaux = $this->model->allSujets($idForum, $user);
		}
		if (isset($_POST['topicValider'])) {
			Forum::ajouterTopic($_POST['titleTopic'], $_POST['typeTopic'], $idForum, $user['id_user'], $_POST['messageTopic'], $_POST['status']);
		}
		\Renderer::render('../templates/forum/sousForum', '../templates', compact('pageTitle', 'style', 'sousForum', 'sujets', 'sujetsNormaux', 'user', 'image', 'description'));
	}

	public function listerTopic(int $idTopic){
		$topic = $this->model->topic($idTopic);
		$pageTitle = $topic['topic_titre'];
		$description = "Messages du topic "  . \Rewritting::sanitize($topic['topic_titre']);
		$image = "https://www.pixenli.com/image/J6FtHnhW";
		$style = "../css/commentaires.css";
		$messages = $this->model->allMessages($idTopic);
		$forum = $this->model->recupererForums();
		$users = new \models\Users();
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
			$topicVu = $this->model->topicVu($idTopic, $user['id_user']);
			if ($topicVu['topic_vu'] == 0) {
				$this->model->insererVu($user['id_user'], $idTopic, $topic['id_forum'], $topic['topic_last_post']);
			} else {
				$this->model->updateVu($user['id_user'], $idTopic, $topic['id_forum'], $topic['topic_last_post']);
			}
		} else {
			$user = 0;
			$topicVu = $this->model->topicVu($idTopic, $user);
			if ($topicVu == 0) {
				$this->model->insererVu($user, $idTopic, $topic['id_forum'], $topic['topic_last_post']);
			} else {
				$this->model->updateVu($user, $idTopic, $topic['id_forum'], $topic['topic_last_post']);
			}
		}
		if (isset($_POST['validerMessage'])) {
			Forum::posterMessage($idTopic, $user['id_user'], $_POST['contenuMessage'], $topic['id_forum']);
		}
		\Renderer::render('../templates/forum/topic', '../templates', compact('pageTitle', 'style', 'topic', 'messages', 'user', 'forum', 'description', 'image'));
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

	public function ajouterTopic($title, $type, $idForum, $user, $message, $status){
		if (!empty($message)) {
			if ($user != NULL) {
				$this->model->ajouterTopic($title, $type, $idForum, $user, $message, $status);
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Le topic a bien été ajouté !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('voirforum.php?f=' . $idForum);
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

	public function addForum($user, $title, $description, $categorie, $permission, $status){
		if ($user != NULL && $user['grade'] >= 7) {
			if (!empty($title)) {
				if (!empty($description)) {
					if ($permission >= 0 && $permission <= 8 || is_string($permission)) {
						if ($status != "0" && $status != "1") {
							$_SESSION['flash-type'] = 'error-flash';
							$_SESSION['flash-message'] = "Hop, pas de bonne valeur !";
							$_SESSION['flash-color'] = "warning";
							\Http::redirect('index.php');
						} else {
							$this->model->addForum($title, $description, $categorie, $permission, $status);
							$_SESSION['flash-type'] = 'error-flash';
							$_SESSION['flash-message'] = "Forum ajouté avec succès !";
							$_SESSION['flash-color'] = "success";
							\Http::redirect('index.php');
						}
					} else {
						$_SESSION['flash-type'] = 'error-flash';
						$_SESSION['flash-message'] = "Bien tenté, mais cette catégorie n'existe pas :D";
						$_SESSION['flash-color'] = "warning";
						\Http::redirect('index.php');
					}
				} else {
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = "La description ne peut pas être vide !";
					$_SESSION['flash-color'] = "warning";
					\Http::redirect('index.php');
				}
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Le titre ne peut pas être vide !";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "La section a bien été créée sur la page d'index du forum !";
			$_SESSION['flash-color'] = "success";
			\Http::redirect('index.php');
		}
	}

	public function editionMessage(int $idTopic, int $idMessage){
		if (is_numeric($idTopic) && is_numeric($idMessage)) {
			if (isset($_SESSION['auth'])) {
				$users = new \models\Users();
				$user = $users->user($_SESSION['auth']['id_user']);
				$topic = $this->model->searchTopic($idTopic, $idMessage);
				if(isset($topic['id_topic'])){
					if ($user['id_user'] == $topic['id_user']) {
						$pageTitle = "Modifier mon post de forum";
						$style = "../css/commentaires.css";
						if (isset($_POST['validerNewMessage'])) {
							Forum::validerEdition($_POST['newContenu'], $user, $topic, $idMessage);
						}
						\Renderer::render('../templates/forum/editer', '../templates', compact('pageTitle', 'style', 'topic', 'user'));
					} else {
						$_SESSION['flash-type'] = 'error-flash';
						$_SESSION['flash-message'] = "Ce n'est pas votre message, vous ne pouvez pas le modifier !";
						$_SESSION['flash-color'] = "warning";
						\Http::redirect('index.php');
					}
				}
				else {
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = "On a un problème, on ne trouve rien !";
					$_SESSION['flash-color'] = "warning";
					\Http::redirect('index.php');
				}
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Vous devez être connecté pour pouvoir faire ça !";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		}
	}

	public function suppressionMessage(int $idTopic, int $idMessage){
		if (is_numeric($idTopic) && is_numeric($idMessage)) {
			if (isset($_SESSION['auth'])) {
				$users = new \models\Users();
				$user = $users->user($_SESSION['auth']['id_user']);
				$topic = $this->model->searchTopic($idTopic, $idMessage);
				if(isset($topic['id_topic'])){
					if ($user['id_user'] == $topic['id_user'] || ($user['id_user'] == $topic['id_user'] && $user['grade'] >= 7)) {
						$this->model->supprimerMessage($idTopic, $idMessage);
						$_SESSION['flash-type'] = 'error-flash';
						$_SESSION['flash-message'] = "Votre message a bien été supprimé !";
						$_SESSION['flash-color'] = "success";
						\Http::redirect('voirforum.php?f=' . $topic['id_forum']);
					}
					else {
						$_SESSION['flash-type'] = 'error-flash';
						$_SESSION['flash-message'] = "Vous devez être connecté pour faire ça !";
						$_SESSION['flash-color'] = "warning";
						\Http::redirect('index.php');
					}
				} else {
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = "Le topic n'existe pas !";
					$_SESSION['flash-color'] = "warning";
					\Http::redirect('index.php');
				}
			}
		}
	}

	public function validerEdition($contenu, $user, $topic, $idMessage){
		if (isset($_SESSION['auth'])) {
			if ($user['id_user'] == $topic['id_user']) {
				$this->model->modifierMessage($contenu, $topic['id_topic'], $user['id_user'], $idMessage);
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Votre message a bien été modifié !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('voirtopic.php?t=' . $topic['id_topic'] . "#" . $topic['id_message']);
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Ce n'est pas votre message";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous devez être connecté pour pouvoir faire ça !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

	public function supprimerTopic($topic){
		if (isset($_SESSION['auth'])) {
			$users = new \models\Users();
			$user = $users->user($_SESSION['auth']['id_user']);
			if ($user['grade'] >= 7) {
				$this->model->supprimerTopic($topic);
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Le topic a bien été supprimé !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Vous n'avez pas les permissions de faire ça";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous devez être connecté pour pouvoir faire ça !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

	public function changerStatus($topic, $status){
		if (isset($_SESSION['auth'])) {
			$users = new \models\Users();
			$user = $users->user($_SESSION['auth']['id_user']);
			if ($user['grade'] >= 7) {
				$topicVerifie = $this->model->topic($topic);
				if ($topic == $topicVerifie['id_topic']) {
					if ($status == "1") {
						$this->model->changerStatus($topic, 0);
					} else {
						$this->model->changerStatus($topic, 1);
					}
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = "Le status a bien été changé !";
					$_SESSION['flash-color'] = "success";
					\Http::redirect('voirtopic.php?t=' . $topic);
				} else {
					$_SESSION['flash-type'] = 'error-flash';
					$_SESSION['flash-message'] = "Ce topic n'existe pas !";
					$_SESSION['flash-color'] = "warning";
					\Http::redirect('index.php');
				}
				
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Vous n'avez pas les permissions de faire ça";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous devez être connecté pour pouvoir faire ça !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

	public function deplacerTopic($idTopic, $destination, $from){
		if (isset($_SESSION['auth'])) {
			$users = new \models\Users();
			$user = $users->user($_SESSION['auth']['id_user']);
			if ($user['grade'] >= 7) {
				$this->model->deplacerTopic($idTopic, $destination, $from);
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Le topic a bien été déplacé !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('voirtopic.php?t=' . $destination);
			} else {
				$_SESSION['flash-type'] = 'error-flash';
				$_SESSION['flash-message'] = "Vous n'avez pas les permissions de faire ça";
				$_SESSION['flash-color'] = "warning";
				\Http::redirect('index.php');
			}
		} else {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous devez être connecté pour pouvoir faire ça !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
	}

}