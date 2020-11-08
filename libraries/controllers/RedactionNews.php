<?php

namespace controllers;


class RedactionNews extends Controller {

	protected $modelName = \models\RedactionNews::class;

	public function index(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] <= 3) {
			\Http::redirect('../../index.php');
		}
		$pageTitle = "Index de la rédaction";
		$style = '../../css/staff.css';
		$news = $this->model->recupererNews();
		if (isset($_POST['suppression_news'])) {
			RedactionNews::supprimerNews();
		}
		if (isset($_POST['valider_news'])) {
			RedactionNews::validerNews();
		}
		\Renderer::render('../../templates/staff/news/index', '../../templates/staff', compact('pageTitle', 'style', 'news'));
	}

	public function ajouterNews(){
		$pageTitle = "Rédiger une news";
		$style = '../../css/staff.css';
		$errors = array();
		$variables = ['pageTitle', 'style'];
		$users = new \models\Users();
		if (isset($_POST['valider'])) {
			if (!isset($_SESSION['auth'])) {
				\Http::redirect('../../index.php');
			}
			$user = $users->user($_SESSION['auth']['id_user']);
			if ($user['grade'] <= 3) {
				\Http::redirect('../../index.php');
			}
			if(strlen($_POST['titre']) < 4 || strlen($_POST['titre']) > 80){
				$errors[] = "Le titre de votre article est trop court ou trop long. Votre titre faisait : " . strlen($_POST['titre']) . " caractères.";
			}
			if(strlen($_POST['description']) < 20 || strlen($_POST['description']) > 200){
				$errors[] = "Votre description doit faire entre 20 et 200 caractères. Votre description faisait : " . strlen($_POST['description']) . " caractères.";
			}
			if(empty($_POST['image'])){
				$errors[] = "Vous n'avez pas renseigné d'images.";
			}
			if(isset($_POST['contenu_news']) AND strlen($_POST['contenu_news']) < 100){
				$errors[] = "Votre contenu doit posséder minimum 100 caractères. Votre contenu faisait : " . strlen($_POST['contenu_news']) . " caractères.";
			}
			$variables = array_merge($variables, ['errors']);
			if(empty($errors)){
				if (empty($_POST['programmation_news'])) {
					$date = date("Y-m-d H:i:s");
				} else {
					$date = \Rewritting::sanitize($_POST['programmation_news']);
				}
				if ($user['stagiaire'] == 1) {
					$attenteValidation = 1;
				} else {
					$attenteValidation = 0;
				}
				$slug = \Rewritting::stringToURLString($_POST['titre']);
				$this->model->ajouterNews($_POST['titre'], $_POST['description'], $date, $_POST['image'], $_POST['contenu_news'], $_POST['categorie'], $_POST['keywords'], $user['id_user'], $_POST['sources'], $slug, $_POST['visible'], $attenteValidation);
				$logs = new \models\Administration();
				$logs->insertLogs($user['id_user'], "a posté une news", "Pannel de rédaction");
				if ($_POST['visible'] == 0) {
					$url2 = "https://discordapp.com/api/webhooks/662479994714456065/RLQZ82-lXO4-QRxq5FVn2VDVHT4AW5Vwr_y_ik5CoXwCJDQp5PClrBfVTMnWtQpgIAd2";
					$hookObject2 = json_encode([
						"tts" => false,
						"embeds" => [
							[
								"title" => htmlspecialchars($_POST['titre']),
								"type" => "rich",
								"description" => htmlspecialchars($_POST['description']),
								"url" => "https://www.mangasfan.fr/commentaire/". $slug,
								"color" => 12211667,
								"author" => [
									"name" => "Mangas'Fan - Nouvelle news !",
									"url" => "https://www.mangasfan.fr",
									"icon_url" => "https://images-ext-1.discordapp.net/external/fPFRMFRClTDREMNdBVT20N4UAbBb8JjeMoiy8Bc3oAY/%3Fwidth%3D473%26height%3D473/https/media.discordapp.net/attachments/417370151424360448/658301476413898792/favicon.png"
								],
								"image" => [
									"url" => htmlspecialchars($_POST['image'])
								],
							]
						]

					], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

					$ch2 = curl_init();

					curl_setopt_array( $ch2, [
						CURLOPT_URL => $url2,
						CURLOPT_POST => true,
						CURLOPT_POSTFIELDS => $hookObject2,
						CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
					]);

					$response = curl_exec( $ch2 );
					curl_close( $ch2 );
				}
				$url = "https://discordapp.com/api/webhooks/714474466461089845/soH9sQmLVQZs64Ry_TzNy_q29i3a1bjPE2AiwIHsrBU52ppFzRFjErCC5IUV2VvkmxsE";
				$hookObject = json_encode([
					"tts" => false,
					"embeds" => [
						[
							"title" => htmlspecialchars($_POST['titre']),
							"type" => "rich",
							"description" => htmlspecialchars($_POST['description']),
							"url" => "https://www.mangasfan.fr/commentaire/". $slug,
							"color" => 12211667,
							"author" => [
								"name" => "Mangas'Fan - Nouvelle news !",
								"url" => "https://www.mangasfan.fr",
								"icon_url" => "https://images-ext-1.discordapp.net/external/fPFRMFRClTDREMNdBVT20N4UAbBb8JjeMoiy8Bc3oAY/%3Fwidth%3D473%26height%3D473/https/media.discordapp.net/attachments/417370151424360448/658301476413898792/favicon.png"
							],
							"image" => [
								"url" => htmlspecialchars($_POST['image'])
							],
						]
					]

				], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

				$ch = curl_init();

				curl_setopt_array( $ch, [
					CURLOPT_URL => $url,
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $hookObject,
					CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
				]);

				$response = curl_exec( $ch );
				curl_close( $ch );
				\Http::redirect('index.php');
			}
		} elseif (isset($_POST['preview'])) {
			RedactionNews::preview($_POST['titre'], $_POST['sources'], $_POST['auteur'], $_POST['contenu_news'], $_POST['categorie']);
			die();
		}
		\Renderer::render('../../templates/staff/news/rediger', '../../templates/staff', compact($variables));
	}

	public function preview($titre, $sources, $auteur, $contenu, $categorie){
		$pageTitle = "Preview de la news";
		$style = '../../css/commentaires.css';
		$users = new \models\Users();
			if (!isset($_SESSION['auth'])) {
				\Http::redirect('../../index.php');
			}
			$user = $users->user($_SESSION['auth']['id_user']);
			if ($user['grade'] <= 3) {
				\Http::redirect('../../index.php');
			}
			$titre = (empty($titre)) ? 'Aucun titre renseigné' : $titre;
			$sources = (empty($sources)) ? 'Aucune source renseignée' : $sources;
			$contenu = (empty($contenu)) ? 'Aucun contenu renseigné.' : $contenu;
			$categorie = (empty($categorie)) ? 'Aucune categorie renseignée.' : $categorie;
			\Renderer::render('../../templates/staff/news/preview', '../../templates/', compact('pageTitle', 'style', 'titre', 'sources', 'contenu', 'categorie'));
	}
	
	public function validerNews(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['chef'] == 0 && ($user['grade'] < 6 || $user['grade'] != 4)) {
			\Http::redirect('../../index.php');
		}
		$this->model->validerNews($_POST['valider_news']);
		$logs = new \models\Administration();
		$logs->insertLogs($user['id_user'], "a validé une news", "Pannel de rédaction");
		\Http::redirect('index.php');
	}

	public function verifierNews(){
		if (!isset($_GET['id_news']) || !is_numeric($_GET['id_news'])) {
			\Http::redirect('index.php');
		}
		$pageTitle = "Modifier une news";
		$style = '../../css/staff.css';
		$news = $this->model->verifierNews($_GET['id_news']);
		$variables = ['pageTitle', 'style', 'news'];
		if (!isset($news['id_news'])) {
			\Http::redirect('index.php');
		}
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('index.php');
		}
		$users = new \models\Users();
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] <= 3) {
			\Http::redirect('../../index.php');
		}
		if ($user['stagiaire'] == 1 && $user['id_user'] != $news['author']) {
			\Http::redirect('index.php');
		}
		if (isset($_POST['valider_news'])){
			$modifierNews = RedactionNews::modifierNews();
			$errors = $modifierNews;
			$variables = array_merge($variables, ['news', 'modifierNews', 'errors']);
			if (empty($errors)) {
				\Http::redirect('modifier_news.php?id_news=' . \Rewritting::sanitize($news['id_news']));
			}
		}
		\Renderer::render('../../templates/staff/news/modifier', '../../templates/staff', compact($variables));
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
			$slug = \Rewritting::stringToURLString($_POST['modif_titre']);
			$this->model->modifierNews($_POST['modif_titre'], $_POST['modif_description'], $_POST['programmation_news'], $_POST['modif_keywords'], $_POST['modif_image'],$_POST['modif_contenu'], $_POST['modif_categorie'], $_POST['modif_sources'], $_POST['modif_visibilite'], $slug, $_GET['id_news']);
			$logs = new \models\Administration();
			$logs->insertLogs($_SESSION['auth']['id_user'], "a modifié une news", "Pannel de rédaction");
			\Http::redirect('modifier_news.php?id_news=' . \Rewritting::sanitize($news['id_news']));
		}
		return $errors;
	}

	public function supprimerNews(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['chef'] == 0 && ($user['grade'] < 6 || $user['grade'] != 4)) {
			\Http::redirect('../../index.php');
		}
		$this->model->supprimerNews($_POST['suppression_news']);
		$logs = new \models\Administration();
		$logs->insertLogs($user['id_user'], "a surpprimé une news", "Pannel de rédaction");
		\Http::redirect('index.php');
	}
}