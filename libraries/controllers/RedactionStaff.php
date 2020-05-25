<?php 

namespace controllers;

class RedactionStaff extends Controller {

	protected $modelName = \models\RedactionStaff::class;

	public function index(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] < 4) {
			\Http::redirect('../../index.php');
		}
		$pageTitle = "Accueil de la rédaction";
		$style = "../../css/staff.css";
		if (!empty($_GET['page']) && is_numeric($_GET['page'])){
			$page = stripslashes($_GET['page']); 
		} else { 
			$page = 1;
		}
		$pagination = 10;
		$limit_start = ($page - 1) * $pagination;
		$nb_total = $this->model->paginationCountJeux();
		$nb_pages = ceil($nb_total / $pagination);
		$allJeux = $this->model->allJeux($limit_start, $pagination);
		$nb_totalMangas = $this->model->paginationCountMangas();
		$nb_pagesMangas = ceil($nb_totalMangas / $pagination);
		$allMangas = $this->model->allMangas($limit_start, $pagination);
		$nb_totalAnimes = $this->model->paginationCountAnimes();
		$nb_pagesAnimes = ceil($nb_totalAnimes / $pagination);
		$allAnimes = $this->model->allAnimes($limit_start, $pagination);
		\Renderer::render('../../templates/staff/redaction/index', '../../templates/staff', compact('pageTitle', 'style', 'utilisateur', 'allJeux', 'nb_pages', 'page', 'allMangas', 'nb_pagesMangas', 'allAnimes', 'nb_pagesAnimes'));
	}

	public function modifier_jeux(){
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
		$jeu = $this->model->donneesJeu($_GET['id']);
		if(strpos($_SERVER['REQUEST_URI'],'/redaction/modifier_jeux.php')){
			\Http::redirect('modification-jeux/' . \Rewritting::sanitize($jeu['slug']));
		}
		$pageTitle = "Modification de " . \Rewritting::sanitize($jeu['name_jeu']);
		$style = "../../../css/staff.css";
		if (isset($_POST['valid_entete'])) {
			RedactionStaff::modifierEntete($_POST['title_game'], $_POST['picture_game'], $_POST['picture_pres'], $utilisateur, $jeu);
		}
		if (isset($_POST['valid_presentation'])) {
			$this->model->modifierDescription($_POST['text_pres'], $jeu['id_jeux']);
			$logs = new \models\Administration();
       		$logs->insertLogs($utilisateur['id_user'], "a modifié la description <strong>" . \Rewritting::sanitize($jeu['name_jeu']) . "</strong>", "Rédaction");

			\Http::redirect($jeu['slug']);
		}
		$recupererOnglets = $this->model->listeOnglets($jeu['id_jeux']);
		$countOnglets = $this->model->countOnglets($jeu['id_jeux']);
		if (isset($_POST['valid_nouvelle_cat'])) {
			$this->model->insererOnglet($jeu['id_jeux'], $_POST['new_cat']);
			$logs = new \models\Administration();
       		$logs->insertLogs($utilisateur['id_user'], "a ajouté la catégorie <strong>" . \Rewritting::sanitize($_POST['new_cat']) . "</strong> dans " . \Rewritting::sanitize($jeu['name_jeu']), "Rédaction");
			\Http::redirect($jeu['slug']);
		}
		$recupererArticles = $this->model->articles($jeu['id_jeux']);
		if (isset($_POST['valid_nouvelle_page'])) {
			RedactionStaff::ajouterArticle($utilisateur, $jeu);
		}
		\Renderer::render('../../templates/staff/redaction/modifierJeu', '../../templates/staff', compact('pageTitle', 'style', 'utilisateur', 'jeu', 'countOnglets', 'recupererOnglets', 'recupererArticles'));
	}

	public function modifierEntete($title, $picture, $cover, $utilisateur, $jeu){
		if (empty($title) || empty($picture) || empty($cover)) {
          	\Http::redirect($jeu['slug']);
		}
		$slug = \Rewritting::stringToURLString($title);
		$this->model->modifierEntete($title, $picture, $cover, $slug, $jeu['id_jeux']);
		$logs = new \models\Administration();
       	$logs->insertLogs($utilisateur['id_user'], "a modifié l'entête de <strong>" . \Rewritting::sanitize($jeu['name_jeu']) . "</strong> (Jeux vidéo)", "Rédaction");
		\Http::redirect(\Rewritting::sanitize($slug));
	}


	public function categories(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$recupererCategorieArticle = $this->model->ongletArticle($_GET['id_jeu']);
		$this->model->updateOnglets($_GET['new_name'], $recupererCategorieArticle[$id_page - 1]['id_category']);
	}

	
	public function ajouterArticle($utilisateur, $jeu){
		$categorie = htmlspecialchars($_POST['liste_categories']);
        if($categorie != "Sélectionner une catégorie" && !empty($_POST['title_page']) && !empty($_POST['text_pres'])){
        	if (strlen($_POST['title_page']) && $_POST['title_page'] == " ") {
        		\Http::redirect(\Rewritting::sanitize($jeu['slug']));
        	}
        	$slug = \Rewritting::stringToURLString($_POST['title_page']);
        	$idOnglet = $this->model->searchIdOnglet($jeu['id_jeux'], $categorie);
        	$this->model->ajouterArticle($jeu['id_jeux'], $idOnglet['id_category'], $_POST['title_page'], $_POST['text_pres'], $utilisateur['id_user'], $_POST['picture_game'], $slug, $_POST['visibilite']);
        	$logs = new \models\Administration();
       		$logs->insertLogs($utilisateur['id_user'], "a ajouté l'article <strong>" . \Rewritting::sanitize($_POST['title_page']) . "</strong> dans " . \Rewritting::sanitize($jeu['name_jeu']), "Rédaction");
       		$url = "https://discordapp.com/api/webhooks/669111297358430228/c98i6GiOrxgCM_lViJFZk5jUSkJN9PYJ7vwWXOWLGpU5MD7lQKpiPmOKxkGFpupqogK8";
			$hookObject = json_encode([
				"tts" => false,
				"embeds" => [
					[
						"title" => "[Jeu vidéo] " . $jeu['name_jeu'] . " - " . htmlspecialchars($_POST['title_page']),
						"type" => "rich",
						"url" => "https://www.mangasfan.fr/jeux-video/". \Rewritting::sanitize($jeu['slug']) . "/" .\Rewritting::sanitize($_POST['title_page']),
						"color" => 12211667,
						"author" => [
							"name" => "Mangas'Fan - Nouvel article - Posté par " . $utilisateur['username'],
							"url" => "https://www.mangasfan.fr",
							"icon_url" => "https://images-ext-1.discordapp.net/external/fPFRMFRClTDREMNdBVT20N4UAbBb8JjeMoiy8Bc3oAY/%3Fwidth%3D473%26height%3D473/https/media.discordapp.net/attachments/417370151424360448/658301476413898792/favicon.png"
						],
						"image" => [
							"url" => htmlspecialchars($_POST['picture_game'])
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
        	\Http::redirect(\Rewritting::sanitize($jeu['slug']));
        }
	}

	public function supprimerPage(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] < 4) {
			\Http::redirect('../../index.php');
		}
		$recupererPage = $this->model->verifierArticle($_GET['id_jeu']);
		$logs = new \models\Administration();
        $logs->insertLogs($utilisateur['id_user'], "a supprimé un article dans les jeux vidéo", "Rédaction");
		$this->model->supprimerPage($_GET['id_jeu'], $recupererPage[$id_page - 1]['id_article']);
	}

	public function deleteCategories(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$recupererCategorieArticle = $this->model->ongletArticle($_GET['id_jeu']);
		$this->model->deleteOnglet($recupererCategorieArticle[$id_page - 1]['id_category'], $_GET['id_jeu']);
	}

	public function modifierArticle(){
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
		$jeu = $this->model->donneesArticle($_GET['jeux'], $_GET['id']);
		if(strpos($_SERVER['REQUEST_URI'],'/redaction/modifierArticleJeu.php')){
			\Http::redirect('modification-jeu/' . \Rewritting::sanitize($jeu['slug']). '/' . \Rewritting::sanitize($jeu['slug_article']));
		}
		$onglet = $this->model->ongletsArticle($jeu['id_jeu']);
		$pageTitle = "Modification de " . \Rewritting::sanitize($jeu['name_article']);
		$style = "../../../../css/staff.css";
		if (isset($_POST['valider_page'])) {
			if (empty($_POST['titre_page']) && strlen($_POST['titre_page']) < 1 AND strlen($_POST['titre_page']) > 50){
				\Http::redirect(\Rewritting::sanitize($jeu['slug_article']));
			}
			if (empty($_POST['image_page'] OR $_POST['liste_onglets']) OR $_POST['modif_visibilite']) {
				\Http::redirect(\Rewritting::sanitize($jeu['slug_article']));
			}
			$slug = \Rewritting::stringToURLString($_POST['titre_page']);
			$this->model->modifyArticle($_POST['titre_page'], $_POST['image_page'], $_POST['liste_onglets'], $_POST['modif_visibilite'], $_POST['en_attente'], $slug, $jeu['id_jeux'], $jeu['id_article']);
			$logs = new \models\Administration();
       		$logs->insertLogs($utilisateur['id_user'], "a modifié l'article <strong>" . \Rewritting::sanitize($_POST['titre_page']) . "</strong> dans " . \Rewritting::sanitize($jeu['name_jeu']), "Rédaction");
			\Http::redirect(\Rewritting::sanitize($slug));
		}
		\Renderer::render('../../templates/staff/redaction/modifierArticle', '../../templates/staff', compact('pageTitle', 'style', 'utilisateur', 'jeu', 'onglet'));

	}

	public function ajouterJeu(){
		if (isset($_POST['ajouter_jeu'])) {
			if (empty($_POST['titre_jeu']) || empty($_POST['banniere_jeu']) || empty($_POST['cover_jeu'])) {
				\Http::redirect('ajouterJeu.php');
			}
			$slug = \Rewritting::stringToURLString($_POST['titre_jeu']);
			$this->model->ajouterJeu($_POST['titre_jeu'], $_POST['cover_jeu'], $_POST['banniere_jeu'], $_POST['presentation_jeu'], $slug);
			\Http::redirect('index.php');
		}
		$pageTitle = "Ajouter un nouveau jeu";
		$style = "../../css/staff.css";
		\Renderer::render('../../templates/staff/redaction/ajouterJeu', '../../templates/staff', compact('pageTitle', 'style'));
	}

	public function supprimerJeu(){
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
		$this->model->supprimerJeu($_GET['id']);
		\Http::redirect('index.php');
	}
}