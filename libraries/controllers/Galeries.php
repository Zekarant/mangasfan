<?php 

namespace controllers;

class Galeries extends Controller {

	protected $modelName = \models\Galeries::class;

	public function index(){
		$pageTitle = "Créations des membres";
		$style = "../css/commentaires.css";
		$interval = "";
		$users = new \models\Users();
		$variables = ['pageTitle', 'style', 'interval', 'galeries'];
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
			$date = date_create($user['date_anniversaire']);
			$date_deux = date_create(date('Y-m-d'));
			$interval = date_diff($date, $date_deux);
			$variables = array_merge($variables, ['user']);
		}
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Galeries");
		if ((!isset($_SESSION['auth']) OR $user['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		if (isset($_POST['activer_nsfw'])) {
			Galeries::activerNSFW($user);
		}
		if (isset($_POST['desactiver_nsfw'])) {
			Galeries::desactiverNSFW($user);
		}
		if (isset($_SESSION['auth']) && $user['nsfw'] == 1 || isset($_SESSION['auth']) && $user['grade'] >= 7) {
			$galeries = $this->model->galeries();
		} else {
			$galeries = $this->model->galeries('nsfw_image = 0');
		}
		\Renderer::render('../templates/galeries/index', '../templates/', compact('galeries', $variables));
	}

	public function activerNSFW($user){
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		if ($user['grade'] < 1 && $user['grade'] > 9) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'A cause de votre grade, vous ne pouvez pas activer le NSFW.';
			\Http::redirect('index.php');
		}
		if ($user['date_anniversaire'] == NULL) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné votre date de naissance.";
			\Http::redirect('index.php');
		}
		$date = date_create($user['date_anniversaire']);
		$date_deux = date_create(date('Y-m-d'));
		$interval = date_diff($date, $date_deux);
		if ($interval->format('%y') < 18) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous n'avez pas l'âge requis pour activer le NSFW !";
			\Http::redirect('index.php');
		}
		$this->model->activerNSFW($user['id_user']);
		$logs = new \models\Administration();
		$logs->insertLogs($user['id_user'], "a activé son NSFW", "Activation du NSFW");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Vous avez bien activé votre NSFW !";
		\Http::redirect('index.php');
	}

	public function desactiverNSFW($user){
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		if ($user['grade'] < 1 && $user['grade'] > 9) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = 'A cause de votre grade, vous ne pouvez pas activer le NSFW.';
			\Http::redirect('index.php');
		}
		if ($user['date_anniversaire'] == NULL) {
			$_SESSION['flash-type'] = 'error-flash';
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné votre date de naissance.";
			\Http::redirect('index.php');
		}
		$this->model->desactiverNSFW($user['id_user']);
		$logs = new \models\Administration();
		$logs->insertLogs($user['id_user'], "a désactivé son NSFW", "Désactivation du NSFW");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Vous avez bien désactivé votre NSFW !";
		\Http::redirect('index.php');
	}

	public function voir(){
		$users = new \models\Users();
		$utilisateur = NULL;
		if (isset($_SESSION['auth'])) {
			$utilisateur = $users->user($_SESSION['auth']['id_user']);
		}
		$idGalerie = NULL;
		if (!empty($_GET['id'])) {
			$idGalerie = $_GET['id'];
		}
		if (!$idGalerie) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "On ne peut pas chercher une galerie qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
			\Http::redirect('index.php');
		}
		$galerie = $this->model->findGalerie($idGalerie);
		$commentaires = $this->model->galeriesComments($galerie['id_image']);
		if (!isset($galerie['id_image'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "L'identifiant de cette image n'existe pas";
			\Http::redirect('index.php');
		}
		if(strpos($_SERVER['REQUEST_URI'],'/galeries/voir.php') !== FALSE){
			\Http::redirect($galerie['slug']);
		}
		if ($galerie['nsfw_image'] == 1) {
			if (!isset($_SESSION['auth'])) {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Vous ne pouvez pas accéder à ce type d'images sans être connecté !";
				\Http::redirect('index.php');
			}
			if ($utilisateur['grade'] <= 7 || $utilisateur['nsfw'] = 0) {
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Vous ne pouvez pas accéder à cette image !";
				\Http::redirect('index.php');
			}
		}
		$pageTitle = \Rewritting::sanitize($galerie['title_image']) . " de " . \Rewritting::sanitize($galerie['username']);
		$style = '../css/commentaires.css';
		$variables = ['pageTitle', 'style', 'galerie', 'commentaires', 'utilisateur'];
		if ($galerie['keywords_image'] != "") {
			$keywords = \Rewritting::sanitize($galerie['keywords_image']);
			$variables = array_merge($variables, ['keywords']);
		}
		if (isset($_POST['envoyer_commentaire'])) {
			Galeries::ajouterCommentaire($utilisateur, $galerie);
		}
		if (isset($_POST['valider_rappel'])) {
			Galeries::ajouterRappel($utilisateur, $galerie);
		}
		\Renderer::render('../templates/galeries/voir', '../templates/', compact($variables));
	}

	public function ajouterCommentaire($utilisateur, $galerie){
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../');
		}
		if (!empty($_POST['comme'])) {
			$this->model->ajouterCommentaire($_POST['comme'], $utilisateur['id_user'], $galerie['id_image']);
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Votre commentaire a bien été ajouté !";
			$logs = new \models\Administration();
			$logs->insertLogs($utilisateur['id_user'], "a ajouté un commentaire", "Commentaires de galeries");
			\Http::redirect('voir.php?id=' . $galerie['id_image']);
		} else {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné de commentaire !";
			\Http::redirect('voir.php?id=' . $galerie['id_image']);
		}
	}

	public function edit(){
		$users = new \models\Users();
		if (isset($_GET['id']) && !empty($_GET['id']) & is_numeric($_GET['id'])) {
			if (!isset($_SESSION['auth'])) {
				\Http::redirect('voir.php?id=' . $galerie['id_image']);
			}
			$user = $users->user($_SESSION['auth']['id_user']);
			$galerie = $this->model->findComment($_GET['id']);
			if ($user['id_user'] != $galerie['author_commentary']) {
				\Http::redirect('voir.php?id=' . $galerie['id_image'] . "#commentaires");
			}
			if(isset($_POST['valider'])){
				if (empty($_POST['commentaire'])) {
					$_SESSION['flash-type'] = "error-flash";
					$_SESSION['flash-message'] = "Le commentaire ne peut pas être vide !";
					\Http::redirect('../index.php');
				}
				$this->model->editComment($_POST['commentaire'], $_GET['id']);
				$logs = new \models\Administration();
				$logs->insertLogs($user['id_user'], "a edité un commentaire", "Commentaires de galeries");
				\Http::redirect('voir.php?id=' . $galerie['id_image'] . "#commentaires");
			} else {
				$commentary = $this->model->findComment($_GET['id']);
				if (isset($commentary['id_commentary_galery'])) {
					$pageTitle = "Modifier mon commentaire - " . $commentary['title_image'];
					$style = "../css/commentaires.css";
					\Renderer::render('../templates/galeries/edit', '../templates/', compact('commentary', 'pageTitle', 'style'));
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

	public function delete(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('voir.php?id=' . $_GET['id']);
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		$searchCommentary = $this->model->findComment($_GET['id']);
		if ($user['id_user'] != $searchCommentary['author_commentary'] && $user['grade'] < 6) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas le droit de supprimer ce commentaire !";
			\Http::redirect('voir.php?id=' . $searchCommentary['id_image']);
		}
		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			die("L'id n'a pas été renseigné");
		}
		if ($_GET['id'] != $searchCommentary['id_commentary_galery']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "L'identifiant n'existe pas !";
			\Http::redirect('voir.php?id=' . $searchCommentary['id_image']);
		}
		$id = $_GET['id'];
		$news = $this->model->deleteComment($id);
		$logs = new \models\Administration();
		$logs->insertLogs($user['id_user'], "a supprimé un commentaire", "Commentaires de galeries");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le commentaire a bien été supprimé !";
		\Http::redirect('voir.php?id=' . $searchCommentary['id_image']);
	}

	public function ajouterRappel($utilisateur, $galerie){
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('index.php');
		}
		if ($utilisateur['grade'] <= 6 && $utilisateur['grade'] >= 10) {
			\Http::redirect('index.php');
		}
		$this->model->ajouterRappel($galerie['id_image']);
		$logs = new \models\Administration();
		$logs->insertLogs($utilisateur['id_user'], "a ajouté un rappel sur l'image " . $galerie['title_image'], "Galeries");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le rappel a bien été attribué !";
		\Http::redirect('index.php');
	}

	public function ajouter(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] == 0 || $utilisateur['galerie'] == 1) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas ajouter d'images sur votre galerie car vous possédez une restriction ou car vous êtes actuellement banni du site.";
			\Http::redirect('index.php');
		}
		if (isset($_POST['valider'])) {
			Galeries::ajouterImage($utilisateur);
		}
		$pageTitle = "Ajouter une image à votre galerie";
		$style = '../css/commentaires.css';
		\Renderer::render('../templates/galeries/ajouter', '../templates/', compact(['pageTitle', 'style']));
	}

	public function ajouterImage($utilisateur){
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('index.php');
		}
		if ($utilisateur['grade'] == 0 || $utilisateur['galerie'] == 1) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas ajouter d'images sur votre galerie car vous possédez une restriction ou car vous êtes actuellement banni du site.";
			\Http::redirect('index.php');
		}
		if (empty($_POST['titre']) || (strlen($_POST['titre']) < 3 || strlen($_POST['titre']) > 50)) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné de titre ou alors ce dernier ne contient pas minimum 3 caractères et maximum 50 caractères.";
		}
		if (empty($_FILES['image_galerie']['name'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné d'image.";
		}

		if (empty($_POST['contenu']) || strlen($_POST['contenu']) < 20) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné de contenu ou alors ce dernier fait moins de 20 caractères.";
		}
		if (isset($_POST['nsfw'])) {
			$nsfw = 1;
		} else {
			$nsfw = 0;
		}
		$tailleMax = 2097152;
       	$image = $_FILES['image_galerie']['name'];
       	$extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
       	if($_FILES['image_galerie']['size'] <= $tailleMax) {
        	$extensionUpload = strtolower(substr(strrchr($image, '.'), 1));
        	if(in_array($extensionUpload, $extensionsValides)) {
          		$chemin = "images/".$image;
          		$resultat = move_uploaded_file($_FILES['image_galerie']['tmp_name'], $chemin);
          		$slug = \Rewritting::stringToURLString($_POST['titre']);
          		$this->model->ajouterImage($image, $_POST['titre'], $_POST['titre_image'], $_POST['contenu'], $utilisateur['id_user'], $nsfw, $slug);
          		$logs = new \models\Administration();
				$logs->insertLogs($utilisateur['id_user'], "a ajouté une image sur sa galerie", "Galeries");
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "L'image a bien été ajoutée !";
				\Http::redirect('index.php');
          	}
         }
	}

	public function supprimerImage(){
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('index.php');
		}
		$users = new \models\Users();
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		$galerie = $this->model->findGalerie($_GET['galerie']);
		echo $galerie['auteur_image'];
		if ($utilisateur['id_user'] != $galerie['auteur_image']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas supprimer une image qui ne vous appartient pas !";
			\Http::redirect('administration.php');
		}
		$this->model->supprimerImage($galerie['id_image']);
		$logs = new \models\Administration();
		$logs->insertLogs($utilisateur['id_user'], "a supprimé une image de sa galerie", "Galeries");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "L'image a bien été supprimée !";
		\Http::redirect('administration.php');
	}


	public function administration(){
		if (!isset($_SESSION['auth'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous devez être connecté pour accéder aux galeries.";
			\Http::redirect('index.php');
		}
		$pageTitle = "Administration de ma galerie";
		$style = "../css/commentaires.css";
		$users = new \models\Users();
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Galeries");
		if ((!isset($_SESSION['auth']) OR $utilisateur['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		$countGalerie = $this->model->countGaleries($utilisateur['id_user']);
		$galerie = $this->model->galeries("auteur_image = " . $utilisateur['id_user']);
		\Renderer::render('../templates/galeries/administration', '../templates/', compact('pageTitle', 'style', 'countGalerie', 'galerie'));
	}

	public function modifier(){
		if (!isset($_SESSION['auth'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous devez être connecté pour accéder aux galeries.";
			\Http::redirect('index.php');
		}
		if (!empty($_GET['galerie']) || is_numeric($_GET['galerie'])) {
			$idGalerie = $_GET['galerie'];
		}
		if (!$idGalerie) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "On ne peut pas chercher une galerie qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
			\Http::redirect('index.php');
		}
		$users = new \models\Users();
		$user = $users->user($_SESSION['auth']['id_user']);
		$galerie = $this->model->findGalerie($idGalerie);
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Galeries");
		if ((!isset($_SESSION['auth']) OR $user['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		if (!isset($galerie['id_image'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Cette image n'existe pas.";
			\Http::redirect('index.php');
		}
		if ($user['id_user'] != $galerie['auteur_image']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas modifier une image qui ne vous appartient pas !";
			\Http::redirect('index.php');
		}
		if (isset($_POST['modifier_image'])) {
			Galeries::modifierImage($user, $galerie);
		}
		$pageTitle = 'Modifier l\'image "' . $galerie['title_image'] . '"';
		$style = "../css/commentaires.css";
		\Renderer::render('../templates/galeries/modifier', '../templates/', compact('pageTitle', 'style', 'galerie'));
	}

	public function modifierImage($user, $galerie){
		if ($user['id_user'] != $galerie['auteur_image']) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas modifier une image qui ne vous appartient pas !";
			\Http::redirect('index.php');
		}
		if (empty($_POST['titre']) || (strlen($_POST['titre']) < 3 || strlen($_POST['titre']) > 50)) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné de titre ou alors ce dernier ne contient pas entre 3 et 50 caractères.";
		}
		if (empty($_POST['contenu']) || strlen($_POST['contenu']) < 20) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous n'avez pas renseigné de contenu ou alors ce dernier fait moins de 20 caractères.";
		}
		$slug = \Rewritting::stringToURLString($_POST['titre']);
		$this->model->modifierImage($_POST['titre'], $_POST['keywords'], $_POST['texte'], $slug, $galerie['id_image']);
		$logs = new \models\Administration();
		$logs->insertLogs($user['id_user'], "a modifié une image de sa galerie", "Galeries");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "L'image a bien été modifiée !";
		\Http::redirect('modifier.php?galerie=' . $galerie['id_image']);
	}

	public function voirgalerie(){
		if (!empty($_GET['id']) || is_numeric($_GET['id'])) {
			$idMember = $_GET['id'];
		}
		if (!$idMember) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "On ne peut pas chercher une galerie qui ne contient pas d'identifiants, nous vous avons redirigé :c !";
			\Http::redirect('index.php');
		}
		$users = new \models\Users();
		$user = NULL;
		$galerie = $this->model->memberGalerie($idMember, "AND nsfw_image = 0");
		if (isset($_SESSION['auth'])) {
			$user = $users->user($_SESSION['auth']['id_user']);
			$galerie = $this->model->memberGalerie($idMember);
		}
		$userGalerie = $users->user($idMember);
		$controllerMaintenance = new \models\Administration();
		$maintenance = $controllerMaintenance->verifier("Galeries");
		if ((!isset($_SESSION['auth']) OR $user['grade'] <= 3) && $maintenance['active_maintenance'] == 1) {
			\Http::redirect('/maintenance.php');
			exit();
		}
		$countgaleries = $this->model->countGaleries($idMember);
		if ($countgaleries == 0) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "La galerie de cet utilisateur n'existe pas.";
			\Http::redirect('index.php');
		}
		if(strpos($_SERVER['REQUEST_URI'],'/galeries/voirgalerie.php') !== FALSE){
			\Http::redirect('membres/' . $userGalerie['id_user'] . "-galerie");
		}
		$pageTitle = "Galerie de " . \Rewritting::sanitize($userGalerie['username']);
		$style = "../../css/commentaires.css";
		\Renderer::render('../templates/galeries/voirgalerie', '../templates/', compact('pageTitle', 'style', 'galerie', 'user', 'userGalerie'));
	}
}