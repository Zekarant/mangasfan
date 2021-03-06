<?php 

namespace controllers;

class RedactionAnimes extends Controller {

	protected $modelName = \models\RedactionAnimes::class;

	public function modifier_animes(){
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
		$anime = $this->model->donneesAnimes($_GET['id']);
		if(strpos($_SERVER['REQUEST_URI'],'/redaction/modifier_animes.php')){
			\Http::redirect('modification-mangas/' . \Rewritting::sanitize($anime['slug']));
		}
		if ($anime['type'] == "manga" && strpos($_SERVER['REQUEST_URI'],'/redaction/modification-animes')) {
			\Http::redirect('../modification-mangas/' . \Rewritting::sanitize($anime['slug']));
		}
		$pageTitle = "Modification de " . \Rewritting::sanitize($anime['titre']);
		$style = "../../../css/staff.css";
		if (isset($_POST['valid_entete'])) {
			if (isset($_POST['avertissement'])) {
				$avertissement = 1;
			} else {
				$avertissement = 0;
			}
			RedactionAnimes::modifierEntete($_POST['title_game'], $_POST['picture_game'], $_POST['picture_pres'], $_POST['inlineRadioOptions'], $utilisateur, $anime, $avertissement);
		}
		if (isset($_POST['valid_presentation'])) {
			$this->model->modifierDescription($_POST['text_pres'], $_POST['text_synop'], $anime['id']);
			$logs = new \models\Administration();
			$logs->insertLogs($utilisateur['id_user'], "a modifié la description <strong>" . \Rewritting::sanitize($anime['titre']) . "</strong>", "Rédaction");
			\Http::redirect(\Rewritting::sanitize($anime['slug']));
		}
		if (isset($_POST['valid_synopsis'])) {
			$this->model->modifierSynopsis($_POST['text_synop'], $anime['id']);
			$logs = new \models\Administration();
			$logs->insertLogs($utilisateur['id_user'], "a modifié le synopsis <strong>" . \Rewritting::sanitize($anime['titre']) . "</strong>", "Rédaction");
			\Http::redirect(\Rewritting::sanitize($anime['slug']));
		}
		$recupererOnglets = $this->model->listeOnglets($anime['id']);
		$countOnglets = $this->model->countOnglets($anime['id']);
		if (isset($_POST['valid_nouvelle_cat'])) {
			$this->model->insererOnglet($anime['id'], $_POST['new_cat']);
			$logs = new \models\Administration();
			$logs->insertLogs($utilisateur['id_user'], "a ajouté la catégorie <strong>" . \Rewritting::sanitize($_POST['new_cat']) . "</strong> dans " . \Rewritting::sanitize($anime['titre']), "Rédaction");
			\Http::redirect(\Rewritting::sanitize($anime['slug']));
		}
		$recupererArticles = $this->model->articles($anime['id']);
		if (isset($_POST['valid_nouvelle_page'])) {
			RedactionAnimes::ajouterArticle($utilisateur, $anime);
		} elseif (isset($_POST['preview'])) {
			RedactionAnimes::preview($_POST['title_page'], $_POST['text_pres']);
			die();
		}
		\Renderer::render('../../templates/staff/redaction/modifierAnimes', '../../templates/staff', compact('pageTitle', 'style', 'utilisateur', 'anime', 'countOnglets', 'recupererOnglets', 'recupererArticles'));
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

	public function modifierEntete($title, $picture, $cover, $type, $utilisateur, $anime, $avertissement){
		if (empty($title) || empty($picture) || empty($cover)) {
			\Http::redirect($anime['slug']);
		}
		$slug = \Rewritting::stringToURLString($title);
		$this->model->modifierEntete($title, $picture, $cover, $type, $slug, $anime['id'], $avertissement);
		$logs = new \models\Administration();
		$logs->insertLogs($utilisateur['id_user'], "a modifié l'entête de <strong>" . \Rewritting::sanitize($anime['titre']) . "</strong> (Animes)", "Rédaction");
		\Http::redirect(\Rewritting::sanitize($slug));
	}

	public function categoriesAnimes(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$recupererCategorieArticle = $this->model->ongletArticle($_GET['id_anime']);
		$this->model->updateOnglets($_GET['new_name'], $recupererCategorieArticle[$id_page - 1]['id_category']);
	}

	public function deleteCategoriesAnimes(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$recupererCategorieArticle = $this->model->ongletArticle($_GET['id_anime']);
		$this->model->deleteOnglet($recupererCategorieArticle[$id_page - 1]['id_category'], $_GET['id_anime']);
	}

	public function supprimerPageAnimes(){
		$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ;
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] < 4) {
			\Http::redirect('../../index.php');
		}
		$recupererPage = $this->model->verifierArticle($_GET['id_anime']);
		$logs = new \models\Administration();
		$logs->insertLogs($utilisateur['id_user'], "a supprimé un article dans les animes", "Rédaction");
		$this->model->supprimerPage($_GET['id_anime'], $recupererPage[$id_page - 1]['id_article']);
	}

	public function ajouterArticle($utilisateur, $anime){
		$categorie = htmlspecialchars($_POST['liste_categories']);
		if($categorie != "Sélectionner une catégorie" && !empty($_POST['title_page']) && !empty($_POST['text_pres'])){
			if (strlen($_POST['title_page']) && $_POST['title_page'] == " ") {
				\Http::redirect(\Rewritting::sanitize($anime['slug']));
			}
			$slug = \Rewritting::stringToURLString($_POST['title_page']);
			$idOnglet = $this->model->searchIdOnglet($anime['id'], $categorie);
			$this->model->ajouterArticle($anime['id'], $idOnglet['id_category'], $_POST['title_page'], $_POST['text_pres'], $utilisateur['id_user'], $_POST['picture_game'], $slug, $_POST['visibilite']);
			$url = "https://discord.com/api/webhooks/802923096067276860/GPomQEypBp10EXoKny75mtQ1z13el28yHWdpedJvgNtOcZWcrSZrUVllOz9y6aiB0zlV";
			$hookObject = json_encode([
				"embeds" => [
					[
						"title" => "[Anime] " . $anime['titre'] . " - " . $_POST['title_page'],
						"type" => "rich",
						"url" => "https://www.mangasfan.fr/animes/" . $anime['slug'] . "/" . $slug,
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
			$logs->insertLogs($utilisateur['id_user'], "a ajouté l'article <strong>" . \Rewritting::sanitize($_POST['title_page']) . "</strong> dans " . \Rewritting::sanitize($anime['titre']), "Rédaction");
			\Http::redirect(\Rewritting::sanitize($anime['slug']));
		}
	}

	public function modifierArticleAnimes(){
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
		$anime = $this->model->donneesArticle($_GET['anime'], $_GET['id']);
		if(strpos($_SERVER['REQUEST_URI'],'/redaction/modifierArticleAnimes.php')){
			\Http::redirect('modification-animes/' . \Rewritting::sanitize($anime['slug']). '/' . \Rewritting::sanitize($anime['slug_article']));
		}
		$onglet = $this->model->ongletsArticle($anime['id']);
		$pageTitle = "Modification de " . \Rewritting::sanitize($anime['name_article']);
		$style = "../../../../css/staff.css";
		if (isset($_POST['valider_page'])) {
			if (empty($_POST['titre_page']) && strlen($_POST['titre_page']) < 1 AND strlen($_POST['titre_page']) > 50){
				\Http::redirect($anime['slug_article']);
			}
			if (empty($_POST['image_page'] OR $_POST['liste_onglets'])) {
				\Http::redirect($anime['slug_article']);
			}
			$slug = \Rewritting::stringToURLString($_POST['titre_page']);
			$this->model->modifyArticle($_POST['titre_page'], $_POST['image_page'], $_POST['liste_onglets'], $_POST['modif_visibilite'], $_POST['en_attente'], $slug, $anime['id'], $anime['id_article']);
			$logs = new \models\Administration();
			$logs->insertLogs($utilisateur['id_user'], "a modifié l'article <strong>" . \Rewritting::sanitize($_POST['titre_page']) . "</strong> dans " . \Rewritting::sanitize($anime['titre']), "Rédaction");
			\Http::redirect(\Rewritting::sanitize($slug));
		}
		\Renderer::render('../../templates/staff/redaction/modifierArticleAnime', '../../templates/staff', compact('pageTitle', 'style', 'utilisateur', 'anime', 'onglet'));

	}
}
