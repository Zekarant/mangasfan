<?php 

namespace Controllers;

class Contact extends Controller {

	protected $modelName = \Rewritting::class;
	
	public function contact(){
		$pageTitle = "Nous contacter";
		$style = "css/commentaires.css";
		$variables = ['pageTitle', 'style'];
		if (isset($_POST['envoyer'])) {
			if(isset($_POST['pseudo']) AND isset($_POST['email']) AND isset($_POST['demande']) AND !preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $_POST['email']) AND !empty($_POST['demande'])){
				$header="MIME-Version: 1.0\r\n";
				$header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
				$header.='Content-Type:text/html; charset="utf-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$sujet ='Demande de contact - ' . $this->model->sanitize($_POST['sujet']);
				$demande = 'Content-Type:text/html; charset="utf-8"'."\n";
				$demande = '
				<html>
				<body>
				<div style="border: 1px solid black; font-family: \'Calibri\'">
				<div style="padding: 10px; background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); border-bottom: 3px solid #b4b4b4">
				<p><img src="https://www.mangasfan.fr/images/logo.png" style="width: 40%;  vertical-align: middle;"><a href="#" style="text-decoration: none; float: right; color: #fff; background-color: #17a2b8; border-color: #17a2b8; font-weight: 400; border: 1px solid transparent; padding: .375rem .75rem; font-size: 15px; line-height: 1.5; border-radius: .25rem; margin-top: 15px;">Accéder au site</a></p>
				</div>
				<div style="padding: 10px;">
                <p>Chers administrateurs,</p>
                  <p>Un utilisateur a essayé de vous contacter : <strong>' . $this->model->sanitize($_POST['email']) . ' (' .  $this->model->sanitize($_POST['pseudo']) . ').</strong></p>
                  <p>Le sujet de sa demande concerne : "' . $this->model->sanitize($_POST['sujet']) . '".</p>
                  <p>Voici sa demande de manière plus détaillée :</p>
                  <p>
                  <i>"'.  nl2br($this->model->sanitize($_POST['demande'])) . '".</i>
                  </p>
                  <hr><br/>
				<center><a href="mailto:' .  $this->model->sanitize($_POST['email']) . '" style="text-decoration: none; color: #17a2b8; background-color: transparent; border-color: #17a2b8; font-weight: 400; border: 1px solid #17a2b8; padding: .375rem .75rem; font-size: 13px; line-height: 1.5; border-radius: .25rem; margin-top: 10px;">Recontacter le membre</a></center>
				</div><br/>
				<div style="background-image: url(\'https://zupimages.net/up/20/15/80zr.png\'); padding: 5px; border-top: 3px solid #b4b4b4; text-align: center; color: black">Mangas\'Fan © 2017 - 2020. Développé par Zekarant et Nico. Tous droits réservés.
				</div>
				</div>
				</div>
				</body>
				</html>';
				mail('contact@mangasfan.fr', $sujet, $demande, $header);
				$errors[] = "Mail envoyé, vous aurez une réponse au bout de 24h.";
				$couleur = "success";
			} else {
				$errors[] = "Vous n'avez pas renseigné votre identifiant ou votre adresse email, ou alors le contenu est vide !";
				$couleur = "danger";
			}

			$variables = array_merge($variables, ['errors', 'couleur']);
		}
		\Renderer::render('templates/others/contact', 'templates/', compact($variables));

	}
}