<?php 

namespace controllers;

class RedactionMangas extends Controller {

	protected $modelName = \models\RedactionMangas::class;

	public function modifier_mangas(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] < 4) {
			\Http::redirect('../../index.php');
		}
		if (!isset($_GET['id'])) {
          	\Http::redirect('../index.php');
		}
		$manga = $this->model->donneesManga($_GET['id']);
		if(strpos($_SERVER['REQUEST_URI'],'/redaction/modifier_mangas.php')){
			\Http::redirect('modification-mangas/' . \Rewritting::sanitize($manga['slug']));
		}
		if ($manga['type'] == "anime" && strpos($_SERVER['REQUEST_URI'],'/redaction/modification-mangas')) {
			\Http::redirect('../modification-animes/' . \Rewritting::sanitize($manga['slug']));
		}
		$pageTitle = "Modification de " . \Rewritting::sanitize($manga['titre']);
		$style = "../../../css/staff.css";
		if (isset($_POST['valid_entete'])) {
			if (isset($_POST['avertissement'])) {
				$avertissement = 1;
			} else {
				$avertissement = 0;
			}
			RedactionMangas::modifierEntete($_POST['title_game'], $_POST['picture_game'], $_POST['picture_pres'], $_POST['inlineRadioOptions'], $utilisateur, $manga, $avertissement);
		}
		if (isset($_POST['valid_presentation'])) {
			$this->model->modifierDescription($_POST['text_pres'], $manga['id']);
			$logs = new \models\Administration();
       		$logs->insertLogs($utilisateur['id_user'], "a modifié la description <strong>" . \Rewritting::sanitize($manga['titre']) . "</strong>", "Rédaction");
			\Http::redirect(\Rewritting::sanitize($manga['slug']));
		}
		$recupererOnglets = $this->model->listeOnglets($manga['id']);
		$countOnglets = $this->model->countOnglets($manga['id']);
		if (isset($_POST['valid_nouvelle_cat'])) {
			$this->model->insererOnglet($manga['id'], $_POST['new_cat']);
			$logs = new \models\Administration();
       		$logs->insertLogs($utilisateur['id_user'], "a ajouté la catégorie <strong>" . \Rewritting::sanitize($_POST['new_cat']) . "</strong> dans " . \Rewritting::sanitize($manga['titre']), "Rédaction");
			\Http::redirect(\Rewritting::sanitize($manga['slug']));
		}
		$recupererArticles = $this->model->articles($manga['id']);
		if (isset($_POST['valid_nouvelle_page'])) {
			RedactionMangas::ajouterArticle($utilisateur, $manga);
		} elseif (isset($_POST['preview'])) {
			RedactionMangas::preview($_POST['title_page'], $_POST['text_pres']);
			die();
		}
		\Renderer::render('../../templates/staff/redaction/modifierMangas', '../../templates/staff', compact('pageTitle', 'style', 'utilisateur', 'manga', 'countOnglets', 'recupererOnglets', 'recupererArticles'));
	}

	public function preview($titre, $contenu){
		$pageTitle = "Preview de l'article";
		$style = '../../../css/commentaires.css';
		$users = new \models\Users();
			if (!isset($_SESSION['auth'])) {
				\Http::redirect('../../index.php');
			}
			$user = $users->user($_SESSION['auth']['id_user']);
			if ($user['grade'] <= 3) {
				\Http::redirect('../../index.php');
			}
			$titre = (empty($titre)) ? 'Aucun titre renseigné' : $titre;
			$contenu = (empty($contenu)) ? 'Aucun contenu renseigné.' : $contenu;
			\Renderer::render('../../templates/staff/redaction/preview', '../../templates/', compact('pageTitle', 'style', 'titre', 'contenu'));
	}

	public function modifierEntete($title, $picture, $cover, $type, $utilisateur, $manga, $avertissement){
		if (empty($title) || empty($picture) || empty($cover)) {
          	\Http::redirect(\Rewritting::sanitize($manga['slug']));
		}
		$slug = \Rewritting::stringToURLString($title);
		$this->model->modifierEntete($title, $picture, $cover, $type, $slug, $manga['id'], $avertissement);
		$logs = new \models\Administration();
       	$logs->insertLogs($utilisateur['id_user'], "a modifié l'entête de <strong>" . \Rewritting::sanitize($manga['titre']) . "</strong> (Mangas)", "Rédaction");
		\Http::redirect(\Rewritting::sanitize($slug));
	}

	public function categoriesMangas(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$recupererCategorieArticle = $this->model->ongletArticle($_GET['id_manga']);
		$this->model->updateOnglets($_GET['new_name'], $recupererCategorieArticle[$id_page - 1]['id_category']);
	}

	public function deleteCategoriesMangas(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$recupererCategorieArticle = $this->model->ongletArticle($_GET['id_manga']);
		$this->model->deleteOnglet($recupererCategorieArticle[$id_page - 1]['id_category'], $_GET['id_manga']);
	}

	public function supprimerPageMangas(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] <= 4) {
			\Http::redirect('../../index.php');
		}
		$recupererPage = $this->model->verifierArticle($_GET['id_manga']);
		$logs = new \models\Administration();
        $logs->insertLogs($utilisateur['id_user'], "a supprimé un article dans les mangas", "Rédaction");
		$this->model->supprimerPage($_GET['id_manga'], $recupererPage[$id_page - 1]['id_article']);
	}

	public function ajouterArticle($utilisateur, $manga){
		$categorie = htmlspecialchars($_POST['liste_categories']);
        if($categorie != "Sélectionner une catégorie" && !empty($_POST['title_page']) && !empty($_POST['text_pres'])){
        	if (strlen($_POST['title_page']) && $_POST['title_page'] == " ") {
        		\Http::redirect(\Rewritting::sanitize($manga['slug']));
        	}
        	$slug = \Rewritting::stringToURLString($_POST['title_page']);
        	$idOnglet = $this->model->searchIdOnglet($manga['id'], $categorie);
        	$this->model->ajouterArticle($manga['id'], $idOnglet['id_category'], $_POST['title_page'], $_POST['text_pres'], $utilisateur['id_user'], $_POST['picture_game'], $slug, $_POST['visibilite']);
        	$url = "https://discordapp.com/api/webhooks/669111297358430228/c98i6GiOrxgCM_lViJFZk5jUSkJN9PYJ7vwWXOWLGpU5MD7lQKpiPmOKxkGFpupqogK8";
			$hookObject = json_encode([
				"embeds" => [
					[
						"title" => "[Manga] " . $manga['titre'] . " - " . $_POST['title_page'],
						"type" => "rich",
						"url" => "https://www.mangasfan.fr/mangas/" . $manga['slug'] . "/" . $slug,
						"color" => 12211667,
						"image" => [
							"url" => $_POST['picture_game']
						],
						"thumbnail" => [
							"url" => $_POST['picture_game']
						],
						"author" => [
							"name" => "Mangas'Fan - Nouvel article - Posté par " . $utilisateur['username'],
							"url" => "https://mangasfan.fr"
						],
					]
				]

			], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

			$ch = curl_init();

			curl_setopt_array( $ch, [
				CURLOPT_URL => $url,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $hookObject,
				CURLOPT_HTTPHEADER => [
					"Length" => strlen( $hookObject ),
					"Content-Type" => "application/json"
				]
			]);
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
			$response = curl_exec( $ch );
			curl_close( $ch );
        	$logs = new \models\Administration();
       		$logs->insertLogs($utilisateur['id_user'], "a ajouté l'article <strong>" . \Rewritting::sanitize($_POST['title_page']) . "</strong> dans " . \Rewritting::sanitize($manga['titre']), "Rédaction");
        	\Http::redirect(\Rewritting::sanitize($manga['slug']));
        }
	}

	public function modifierArticleMangas(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] < 4) {
			\Http::redirect('../../index.php');
		}
		if (!isset($_GET['id'])) {
          	\Http::redirect('../index.php');
		}
		$manga = $this->model->donneesArticle($_GET['manga'], $_GET['id']);
		if(strpos($_SERVER['REQUEST_URI'],'/redaction/modifierArticleMangas.php')){
			\Http::redirect('modification-mangas/' . \Rewritting::sanitize($manga['slug']). '/' . \Rewritting::sanitize($manga['slug_article']));
		}
		$onglet = $this->model->ongletsArticle($manga['id']);
		$pageTitle = "Modification de " . \Rewritting::sanitize($manga['name_article']);
		$style = "../../../../css/staff.css";
		if (isset($_POST['valider_page'])) {
			if (empty($_POST['titre_page']) && strlen($_POST['titre_page']) < 1 AND strlen($_POST['titre_page']) > 50){
				\Http::redirect(\Rewritting::sanitize($manga['slug_article']));
			}
			if (empty($_POST['image_page'] OR $_POST['liste_onglets'])) {
				\Http::redirect(\Rewritting::sanitize($manga['slug_article']));
			}
			$slug = \Rewritting::stringToURLString($_POST['titre_page']);
			$this->model->modifyArticle($_POST['titre_page'], $_POST['image_page'], $_POST['liste_onglets'], $_POST['modif_visibilite'], $_POST['en_attente'], $slug, $manga['id'], $manga['id_article']);
			$logs = new \models\Administration();
       		$logs->insertLogs($utilisateur['id_user'], "a modifié l'article <strong>" . \Rewritting::sanitize($_POST['titre_page']) . "</strong> dans " . \Rewritting::sanitize($manga['titre']), "Rédaction");
			\Http::redirect(\Rewritting::sanitize($slug));
		}
		\Renderer::render('../../templates/staff/redaction/modifierArticleManga', '../../templates/staff', compact('pageTitle', 'style', 'utilisateur', 'manga', 'onglet'));

	}

	public function ajouterManga(){
		if (isset($_POST['ajouter_manga'])) {
			if (empty($_POST['titre_manga']) || empty($_POST['banniere_manga']) || empty($_POST['cover_manga'])) {
				\Http::redirect('ajouterMangaAnime.php');
			}
			$slug = \Rewritting::stringToURLString($_POST['titre_manga']);
			$this->model->ajouterManga($_POST['titre_manga'], $_POST['cover_manga'], $_POST['banniere_manga'], $_POST['presentation_manga'], $_POST['inlineRadioOptions'], $slug);
			\Http::redirect('index.php');
		}
		$pageTitle = "Ajouter un nouveau manga/anime";
		$style = "../../css/staff.css";
		\Renderer::render('../../templates/staff/redaction/ajouterMangaAnime', '../../templates/staff', compact('pageTitle', 'style'));
	}

	public function supprimerManga(){
		if(!isset($_GET['id'])){
			\Http::redirect('index.php');
		}
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] < 4 && $utilisateur['chef'] == 0) {
			\Http::redirect('index.php');
		}
		$this->model->supprimerManga($_GET['id']);
		\Http::redirect('index.php');
	}
}
