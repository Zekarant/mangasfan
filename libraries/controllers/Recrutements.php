<?php 

namespace controllers;

class Recrutements extends Controller {

	protected $modelName = \models\Recrutements::class;

	public function recrutements(){
		$pageTitle = "Index des recrutements";
		$style = "../css/commentaires.css";
		$recrutements = $this->model->recrutements();
		\Renderer::render('../templates/recrutements/index', '../templates/', compact('pageTitle', 'style', 'recrutements'));
	}

	public function gestion(){
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$users = new \models\Users();
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] < 7) {
			\Http::redirect('../index.php');
		}
		$pageTitle = "Index des recrutements";
		$style = "../css/commentaires.css";
		$recrutement = $this->model->gestionRecrutements();
		if (isset($_POST['recrutement'])) {
			Recrutements::modifierStatus($_POST['recrutement']);
		}
		\Renderer::render('../templates/recrutements/gestion', '../templates/', compact('pageTitle', 'style', 'recrutement'));
	}

	public function modifierStatus(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../index.php');
		}
		$utilisateur = $users->user($_SESSION['auth']['id_user']);
		if ($utilisateur['grade'] < 7) {
			\Http::redirect('../index.php');
		}
		$recuperer = $this->model->recupererRecrutement($_POST['recrutement']);
		$newValue = !$recuperer['recrutement'] ? 1 : 0;
		$this->model->modifierRecrutement($newValue, $_POST['recrutement']);
		$logs = new \models\Administration();
		$logs->insertLogs($utilisateur['id_user'], "a modifié un recrutement", "Recrutements");
		$_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Vous avez bien modifié le recrutement !";
		$_SESSION['flash-color'] = "success";
		\Http::redirect('gestion-recrutements.php');
	}

	public function administrateurs(){
		$recuperation = $this->model->recupererRecrutement('administrateurs');
		if ($recuperation['recrutement'] == 0) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Ces recrutements ne sont pas ouverts !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = "Recrutements - " . \Rewritting::sanitize($recuperation['name_animation']);
		$style = '../css/commentaires.css';
		if (isset($_POST['envoyer']) && !empty($_POST['envoyer'])) {
			if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['motivations']) AND isset($_POST['experiences']) AND isset($_POST['idees']) AND isset($_POST['disponibilites']) AND isset($_POST['prenom']) AND isset($_POST['nom']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){

				$header="MIME-Version: 1.0\r\n";
				$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
				$demande = '
				<html>
				<body>
				<div style="border: 1px solid black; font-family: \'Calibri\'">
				<div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
				<p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
				</div>
				<div style="padding: 10px;">
				<p>Chers administrateurs, ' . \Rewritting::sanitize($_POST['prenom']) . ' vous a envoyé sa candidature :<br/><br/>
				Nom : <strong>' . \Rewritting::sanitize($_POST['nom']) . '</strong><br/>
				Email : ' . \Rewritting::sanitize($_POST['email']) . '<br/>
				Idées : <strong>' . \Rewritting::sanitize($_POST['idees']) . '</strong><br/>
				Expériences : ' . \Rewritting::sanitize($_POST['experiences']) . '<br/>
				Disponibilités : ' . \Rewritting::sanitize($_POST['disponibilites']) . '<br/>
				Motivations : ' . \Rewritting::sanitize($_POST['motivations']) . '<br/>
				Autre : ' . \Rewritting::sanitize($_POST['autre']) . '<br/>
				</p><br/>
				<div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
				</div>
				</div>
				</div>
				</body>
				</html>';
				mail("contact@mangasfan.fr", "Recrutements Administrateurs - " . \Rewritting::sanitize($_POST['pseudo']) . "", $demande, $header);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre candidature a bien été envoyée !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/recrutements/recrutementAdmin', '../templates/', compact('pageTitle', 'style', 'recuperation'));
	}

	public function moderateurs(){
		$recuperation = $this->model->recupererRecrutement('moderateurs');
		if ($recuperation['recrutement'] == 0) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Ces recrutements ne sont pas ouverts !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = "Recrutements - " . \Rewritting::sanitize($recuperation['name_animation']);
		$style = '../css/commentaires.css';
		if (isset($_POST['envoyer']) && !empty($_POST['envoyer'])) {
			if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['motivations']) AND isset($_POST['experiences']) AND isset($_POST['idees']) AND isset($_POST['disponibilites']) AND isset($_POST['pseudo']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){

				$header="MIME-Version: 1.0\r\n";
				$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
				$demande = '
				<html>
				<body>
				<div style="border: 1px solid black; font-family: \'Calibri\'">
				<div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
				<p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
				</div>
				<div style="padding: 10px;">
				<p>Chers administrateurs, ' . \Rewritting::sanitize($_POST['pseudo']) . ' vous a envoyé sa candidature :<br/><br/>
				Email : ' . \Rewritting::sanitize($_POST['email']) . '<br/>
				Idées : <strong>' . \Rewritting::sanitize($_POST['idees']) . '</strong><br/>
				Expériences : ' . \Rewritting::sanitize($_POST['experiences']) . '<br/>
				Disponibilités : ' . \Rewritting::sanitize($_POST['disponibilites']) . '<br/>
				Motivations : ' . \Rewritting::sanitize($_POST['motivations']) . '<br/>
				Autre : ' . \Rewritting::sanitize($_POST['autre']) . '<br/>
				</p><br/>
				<div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
				</div>
				</div>
				</div>
				</body>
				</html>';
				mail("contact@mangasfan.fr", "Recrutements Modérateurs - " . \Rewritting::sanitize($_POST['pseudo']) . "", $demande, $header);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre candidature a bien été envoyée !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/recrutements/recrutementModo', '../templates/', compact('pageTitle', 'style', 'recuperation'));
	}

	public function animateurs(){
		$recuperation = $this->model->recupererRecrutement('animateurs');
		if ($recuperation['recrutement'] == 0) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Ces recrutements ne sont pas ouverts !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = "Recrutements - " . \Rewritting::sanitize($recuperation['name_animation']);
		$style = '../css/commentaires.css';
		if (isset($_POST['envoyer']) && !empty($_POST['envoyer'])) {
			if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['motivations']) AND isset($_POST['experiences']) AND isset($_POST['idees']) AND isset($_POST['disponibilites']) AND isset($_POST['pseudo']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){

				$header="MIME-Version: 1.0\r\n";
				$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
				$demande = '
				<html>
				<body>
				<div style="border: 1px solid black; font-family: \'Calibri\'">
				<div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
				<p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
				</div>
				<div style="padding: 10px;">
				<p>Chers administrateurs, ' . \Rewritting::sanitize($_POST['pseudo']) . ' vous a envoyé sa candidature :<br/><br/>
				Email : ' . \Rewritting::sanitize($_POST['email']) . '<br/>
				Idées : <strong>' . \Rewritting::sanitize($_POST['idees']) . '</strong><br/>
				Expériences : ' . \Rewritting::sanitize($_POST['experiences']) . '<br/>
				Disponibilités : ' . \Rewritting::sanitize($_POST['disponibilites']) . '<br/>
				Motivations : ' . \Rewritting::sanitize($_POST['motivations']) . '<br/>
				Autre : ' . \Rewritting::sanitize($_POST['autre']) . '<br/>
				</p><br/>
				<div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
				</div>
				</div>
				</div>
				</body>
				</html>';
				mail("contact@mangasfan.fr", "Recrutements Animateurs - " . \Rewritting::sanitize($_POST['pseudo']) . "", $demande, $header);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre candidature a bien été envoyée !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/recrutements/recrutementAnim', '../templates/', compact('pageTitle', 'style', 'recuperation'));
	}

	public function developpeurs(){
		$recuperation = $this->model->recupererRecrutement('developpeurs');
		if ($recuperation['recrutement'] == 0) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Ces recrutements ne sont pas ouverts !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = "Recrutements - " . \Rewritting::sanitize($recuperation['name_animation']);
		$style = '../css/commentaires.css';
		if (isset($_POST['envoyer']) && !empty($_POST['envoyer'])) {
			if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['motivations']) AND isset($_POST['experiences']) AND isset($_POST['langages']) AND isset($_POST['disponibilites']) AND isset($_POST['prenom']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){

				$header="MIME-Version: 1.0\r\n";
				$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
				$demande = '
				<html>
				<body>
				<div style="border: 1px solid black; font-family: \'Calibri\'">
				<div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
				<p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
				</div>
				<div style="padding: 10px;">
				<p>Chers administrateurs, ' . \Rewritting::sanitize($_POST['pseudo']) . ' vous a envoyé sa candidature :<br/><br/>
				Email : ' . \Rewritting::sanitize($_POST['email']) . '<br/>
				Langages maitrisés : <strong>' . \Rewritting::sanitize($_POST['langages']) . '</strong><br/>
				Expériences : ' . \Rewritting::sanitize($_POST['experiences']) . '<br/>
				Disponibilités : ' . \Rewritting::sanitize($_POST['disponibilites']) . '<br/>
				Motivations : ' . \Rewritting::sanitize($_POST['motivations']) . '<br/>
				Autre : ' . \Rewritting::sanitize($_POST['autre']) . '<br/>
				</p><br/>
				<div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
				</div>
				</div>
				</div>
				</body>
				</html>';
				mail("contact@mangasfan.fr", "Recrutements Développeur - " . \Rewritting::sanitize($_POST['pseudo']) . "", $demande, $header);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre candidature a bien été envoyée !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/recrutements/recrutementDev', '../templates/', compact('pageTitle', 'style', 'recuperation'));
	}

	public function community(){
		$recuperation = $this->model->recupererRecrutement('community-manager');
		if ($recuperation['recrutement'] == 0) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Ces recrutements ne sont pas ouverts !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = "Recrutements - " . \Rewritting::sanitize($recuperation['name_animation']);
		$style = '../css/commentaires.css';
		if (isset($_POST['envoyer']) && !empty($_POST['envoyer'])) {
			if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['motivations']) AND isset($_POST['experiences']) AND isset($_POST['disponibilites']) AND isset($_POST['pseudo']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){
				$header="MIME-Version: 1.0\r\n";
				$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
				$demande = '
				<html>
				<body>
				<div style="border: 1px solid black; font-family: \'Calibri\'">
				<div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
				<p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
				</div>
				<div style="padding: 10px;">
				<p>Chers administrateurs, ' . \Rewritting::sanitize($_POST['pseudo']) . ' vous a envoyé sa candidature :<br/><br/>
				Email : ' . \Rewritting::sanitize($_POST['email']) . '<br/>
				Poste demandé : <strong>' . \Rewritting::sanitize($_POST['poste']) . '</strong><br/>
				Expériences : ' . \Rewritting::sanitize($_POST['experiences']) . '<br/>
				Disponibilités : ' . \Rewritting::sanitize($_POST['disponibilites']) . '<br/>
				Motivations : ' . \Rewritting::sanitize($_POST['motivations']) . '<br/>
				Autre : ' . \Rewritting::sanitize($_POST['autre']) . '<br/>
				</p><br/>
				<div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
				</div>
				</div>
				</div>
				</body>
				</html>';
				mail("contact@mangasfan.fr", "Recrutements Community Manager - " . \Rewritting::sanitize($_POST['pseudo']) . "", $demande, $header);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre candidature a bien été envoyée !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/recrutements/recrutementCM', '../templates/', compact('pageTitle', 'style', 'recuperation'));
	}

	public function newseurs(){
		$recuperation = $this->model->recupererRecrutement('newseurs');
		if ($recuperation['recrutement'] == 0) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Ces recrutements ne sont pas ouverts !";
			$_SESSION['flash-color'] = "warning";
			\Http::redirect('index.php');
		}
		$pageTitle = "Recrutements - " . \Rewritting::sanitize($recuperation['name_animation']);
		$style = '../css/commentaires.css';
		if (isset($_POST['envoyer']) && !empty($_POST['envoyer'])) {
			if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['motivations']) AND isset($_POST['experiences']) AND isset($_POST['disponibilites']) AND isset($_POST['pseudo']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){

				$header="MIME-Version: 1.0\r\n";
				$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
				$demande = '
				<html>
				<body>
				<div style="border: 1px solid black; font-family: \'Calibri\'">
				<div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
				<p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
				</div>
				<div style="padding: 10px;">
				<p>Chers administrateurs, ' . \Rewritting::sanitize($_POST['pseudo']) . ' vous a envoyé sa candidature :<br/><br/>
				Email : ' . \Rewritting::sanitize($_POST['email']) . '<br/>
				Poste demandé : <strong>' . \Rewritting::sanitize($_POST['poste']) . '</strong><br/>
				Expériences : ' . \Rewritting::sanitize($_POST['experiences']) . '<br/>
				Disponibilités : ' . \Rewritting::sanitize($_POST['disponibilites']) . '<br/>
				Motivations : ' . \Rewritting::sanitize($_POST['motivations']) . '<br/>
				Autre : ' . \Rewritting::sanitize($_POST['autre']) . '<br/>
				</p><br/>
				<div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
				</div>
				</div>
				</div>
				</body>
				</html>';
				mail("contact@mangasfan.fr", "Recrutements Newseurs - " . \Rewritting::sanitize($_POST['pseudo']) . "", $demande, $header);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre candidature a bien été envoyée !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/recrutements/recrutementNewseurs', '../templates/', compact('pageTitle', 'style', 'recuperation'));
	}

	public function redacteurs(){
		$recuperation = $this->model->recupererRecrutement('redacteurs');
		if ($recuperation['recrutement'] == 0) {
			$_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-color'] = "warning";
			$_SESSION['flash-message'] = "Ces recrutements ne sont pas ouverts !";
			\Http::redirect('index.php');
		}
		$pageTitle = "Recrutements - " . \Rewritting::sanitize($recuperation['name_animation']);
		$style = '../css/commentaires.css';
		if (isset($_POST['envoyer']) && !empty($_POST['envoyer'])) {
			if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['motivations']) AND isset($_POST['experiences']) AND isset($_POST['disponibilites']) AND isset($_POST['pseudo']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email'])){

				$header="MIME-Version: 1.0\r\n";
				$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
				$demande = '
				<html>
				<body>
				<div style="border: 1px solid black; font-family: \'Calibri\'">
				<div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
				<p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
				</div>
				<div style="padding: 10px;">
				<p>Chers administrateurs, ' . \Rewritting::sanitize($_POST['pseudo']) . ' vous a envoyé sa candidature :<br/><br/>
				Email : ' . \Rewritting::sanitize($_POST['email']) . '<br/>
				Poste demandé : <strong>' . \Rewritting::sanitize($_POST['poste']) . '</strong><br/>
				Expériences : ' . \Rewritting::sanitize($_POST['experiences']) . '<br/>
				Disponibilités : ' . \Rewritting::sanitize($_POST['disponibilites']) . '<br/>
				Motivations : ' . \Rewritting::sanitize($_POST['motivations']) . '<br/>
				Autre : ' . \Rewritting::sanitize($_POST['autre']) . '<br/>
				</p><br/>
				<div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
				</div>
				</div>
				</div>
				</body>
				</html>';
				mail("contact@mangasfan.fr", "Recrutements Rédacteurs - " . \Rewritting::sanitize($_POST['pseudo']) . "", $demande, $header);
				$_SESSION['flash-type'] = "error-flash";
				$_SESSION['flash-message'] = "Votre candidature a bien été envoyée !";
				$_SESSION['flash-color'] = "success";
				\Http::redirect('index.php');
			}
		}
		\Renderer::render('../templates/recrutements/recrutementRedacteurs', '../templates/', compact('pageTitle', 'style', 'recuperation'));
	}


}