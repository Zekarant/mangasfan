<?php 

namespace Controllers;

class Users extends Controller {

	protected $modelName = \Models\Users::class;

	public function indexConnexion() {
    $error = '';
    $pageTitle = 'Se connecter - Mangas\'Fan';
    $style = '../css/commentaires.css';

    if (isset($_SESSION['auth'])) {
        // ici, essaie de garder une cohérence, t'as un index avec un tiret, l'autre avec un underscore
        $_SESSION['flash-type'] = 'error-flash';
        $_SESSION['flash_message'] = 'Vous êtes déjà connecté machin';
        \Http::redirect('../index.php');
    }

    $variables = ['pageTitle', 'style'];

    if (!empty($_POST)) {
        $users = $this->model->connexion();

        if (!$users) {
            $_SESSION['flash-type'] = "error-flash";
			$_SESSION['flash-message'] = "Il semble que le pseudo que vous avez renseigné soit incorrect !";
			\Http::redirect('connexion.php');
        }

        // maintenant que t'as géré le cas où les données sont pas présentes (avec redirect), tu dois plus tomber ici si les données ne sont pas renseignées par ton utlisateur

        if (password_verify($_POST['password'], $users['password'])) {
            if ($users['grade'] === 1) {
                echo "Banni";
            }

            $_SESSION['auth'] = $users;
            \Http::redirect('../index.php');
        } 
        
        // t'as un redirect dans ton if d'au dessus, donc pas besoin de else ici. si tu passes pas dans ton if, tu passeras forcément ici. et il faudrait potentiellement faire la même chose que plus haut avec la logique de redirection + erreur
        $_SESSION['flash-type'] = "error-flash";
		$_SESSION['flash-message'] = "Le mot de passe de passe renseigné est incorrect !";
		\Http::redirect('connexion.php');

        // par contre je maintiens, ici ta variable $utilisateur n'existe pas, le seul moment où tu la définis, tu rediriges l'utlisateur ailleurs
        $variables = array_merge($variables, ['error', 'users']);
    }

    // compact peut prendre un tableau de variables en paramètres, du coup tu peux faire un seul appel à ton render en mettant les bonnes variables dans un tableau en fonction du cas où tu te trouves
    \Renderer::render('../templates/membres/connexion', '../templates/', compact($variables));
}

	public function utilisateurConnecte(){
		if (isset($_SESSION['auth'])) {
			$utilisateur = $this->model->user($_SESSION['auth']['id_user']);
			return $utilisateur;
		}
	}


}