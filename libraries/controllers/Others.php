<?php

namespace controllers;

class Others extends Controller {

	protected $modelName = \models\Others::class;

	public function cgu(){
		$pageTitle = "Conditions Générales d'Utilisation";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-txt/cgu.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/cgu', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}

	public function partenaires(){
		$pageTitle = "Partenaires du site";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-txt/partenaires.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/partenaires', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}

	public function faq(){
		$pageTitle = "Foire aux questions";
		$style = 'css/commentaires.css';
		$ligne = file_get_contents('templates/staff/administration/fichiers-txt/faq.txt', FILE_USE_INCLUDE_PATH);
		\Renderer::render('templates/others/faq', 'templates/', compact('pageTitle', 'style', 'ligne'));
	}

	public function changelog(){
		$pageTitle = "Mises à jour du site";
		$style = "css/commentaires.css";
		$changelog = $this->model->changelog();
		\Renderer::render('templates/others/changelog', 'templates/', compact('pageTitle', 'style', 'changelog'));
	}

	public function hebergeur(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas avoir accès à cette page !";
			$_SESSION['flash-color'] = "danger";
			\Http::redirect('../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] <= 4) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas avoir accès à cette page !";
			$_SESSION['flash-color'] = "danger";
			\Http::redirect('../index.php');
		}
		$pageTitle = "Hébergeur du site";
		$style = "../css/commentaires.css";
		$poids_max = 512000;
		$repertoire = 'uploads/'; 
		$erreur = "";
		$nom_fichier = "";
		$url = "";
		if (isset($_FILES['fichier'])) {
			echo $_FILES['fichier']['type'];
			if ($_FILES['fichier']['type'] != 'image/png' && $_FILES['fichier']['type'] != 'image/jpeg' && $_FILES['fichier']['type'] != 'image/jpg' && $_FILES['fichier']['type'] != 'image/gif' && $_FILES['fichier']['type'] != 'image/webp'){ 
				$erreur = "Le format du fichier est valide, votre fichier doit être au format PNG, JPG, JPEG ou GIF. Veuillez recommencer." . $_FILES['fichier']['type'];
			} elseif ($_FILES['fichier']['size'] > $poids_max){
				$erreur = "La taille du fichier est envoyée est trop volumineuse, veuillez prendre une image plus légère.";
			} elseif (!file_exists($repertoire)){ 
				$erreur = "Le répertoire qui stocke vos belles images n'existe pas, merci de contacter un administrateur pour ce problème."; 
			} else {
				if ($_FILES['fichier']['type'] == 'image/jpeg'){ 
					$extention = '.jpeg'; 
				} if ($_FILES['fichier']['type'] == 'image/jpg'){ 
					$extention = '.jpg'; 
				} if ($_FILES['fichier']['type'] == 'image/png'){ 
					$extention = '.png'; 
				} if ($_FILES['fichier']['type'] == 'image/gif'){ 
					$extention = '.gif'; 
				}
				$nom_fichier = time().$extention; 
				if (move_uploaded_file($_FILES['fichier']['tmp_name'], $repertoire.$nom_fichier)){ 
					$url = 'https://www.mangasfan.fr/hebergeur/'.$repertoire.''.$nom_fichier.'';
					$erreur = "Ok";
					$logs = new \models\Administration();
    				$logs->insertLogs($utilisateur['id_user'], "a hébergé une image", "Hébergeur");
    				
				} 
			}
		} 
		\Renderer::render('../templates/others/hebergeur', '../templates/', compact('pageTitle', 'style', 'utilisateur', 'erreur', 'repertoire', 'poids_max', 'nom_fichier', 'url'));
	}

	public function gestionImages(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas avoir accès à cette page !";
			$_SESSION['flash-color'] = "danger";
			\Http::redirect('../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] <= 4 AND $utilisateur['chef'] != 1) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Vous ne pouvez pas avoir accès à cette page !";
			$_SESSION['flash-color'] = "danger";
			\Http::redirect('../index.php');
		}
		$pageTitle = "Gérer les images du site";
		$style = "../css/commentaires.css";
		if (isset($_POST['delete'])) {
			unlink('uploads/' . $_POST['supprimer_image']);
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "L'image a bien été supprimée !";
			$_SESSION['flash-color'] = "success";
			\Http::redirect('gestion_hebergeur.php');
		}
		\Renderer::render('../templates/others/gererImages', '../templates/', compact('pageTitle', 'style', 'utilisateur'));
	}

	public function equipeSite(){
		$pageTitle = "L'équipe du site";
		$style = "css/commentaires.css";
		$administrateurs = $this->model->listeAdmins();
		$developpeurs = $this->model->listeDevs();
		$moderateurs = $this->model->listeModos();
		$redacteurs = $this->model->listeRedacs();
		\Renderer::render('templates/others/equipeSite', 'templates/', compact('pageTitle', 'style', 'administrateurs', 'developpeurs', 'moderateurs', 'redacteurs'));
	}
}