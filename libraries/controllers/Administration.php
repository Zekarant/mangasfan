<?php 

namespace controllers;

class Administration extends Controller {

	protected $modelName = \models\Administration::class;

	public function index(){
		$users = new \models\Users();
		if (!isset($_SESSION['auth'])) {
			\Http::redirect('../../index.php');
		}
		$user = $users->user($_SESSION['auth']['id_user']);
		if ($user['grade'] <= 6) {
			\Http::redirect('../../index.php');
		}
		$pageTitle = "Index de l'administration";
		$style = '../../css/staff.css';
		$maintenance = Administration::Maintenance();
		$membres = Administration::members();
		$avertissements = Administration::avertissements();
		$bannissements = Administration::bannissements();
		list($membres, $nb_pages, $page) = $membres;
		\Renderer::render('../../templates/staff/administration/index', '../../templates/staff', compact('pageTitle', 'style', 'maintenance', 'membres', 'page', 'nb_pages', 'avertissements', 'bannissements'));
	}

	public function Maintenance(){
		$pageTitle = "Index de l'administration";
		$style = '../../css/staff.css';
		$maintenance = $this->model->maintenance();
		if (isset($_POST['maintenance'])) {
			$recuperer = $this->model->verifier($_POST['maintenance']);
			$newValue = !$recuperer['active_maintenance'] ? 1 : 0;
			if ($recuperer['maintenance_area'] === "Site") {
				$this->model->updateAllMaintenance($newValue);
			} else {
				$this->model->updateMaintenance($newValue, $recuperer['maintenance_area']);
			}
			\Http::redirect('index.php');
		}
		return $maintenance;
	}

	public function members(){
		$users = new \models\Users();
        if (!empty($_GET['page']) && is_numeric($_GET['page'])){
            $page = stripslashes($_GET['page']); 
        } else { 
        	$page = 1;
        }
        $pagination = 10;
        $limit_start = ($page - 1) * $pagination;
        $nb_total = $users->paginationCount();
        $nb_pages = ceil($nb_total / $pagination);
        $membres = $users->allMembres($limit_start, $pagination);
        return array($membres, $nb_pages, $page);
    }

    public function avertissements(){
    	$avertissements = $this->model->avertissements();
    	if (isset($_POST['delete_avertissement'])) {
    		Administration::deleteAvertissement();
    	}
    	return $avertissements;
    }

    public function deleteAvertissement(){
    	if (!isset($_SESSION['auth'])) {
         \Http::redirect('../../index.php');
     }
     $users = new \models\Users();
     $user = $users->user($_SESSION['auth']['id_user']);
     if ($user['grade'] <= 6) {
         \Http::redirect('../../index.php');
     }
     $this->model->deleteAvertissement($_POST['delete_avertissement']);
     \Http::redirect('index.php#avertissements');
 }

 public function bannissements(){
   $bannissements = $this->model->bannissements();
   $unban = $this->model->unban();
   return $bannissements;
}

public function modification_cgu(){
   $pageTitle = "Modifier les CGU du site";
   $style = '../../css/staff.css';
   if (isset($_POST['modifier_cgu'])) {
      file_put_contents('../../templates/staff/administration/fichiers-txt/cgu.txt', $_POST['texte-cgu']);
  }
  $ligne = file_get_contents('../../templates/staff/administration/fichiers-txt/cgu.txt', FILE_USE_INCLUDE_PATH);
  \Renderer::render('../../templates/staff/administration/modifierCGU', '../../templates/staff', compact('pageTitle', 'style', 'ligne'));
}

public function partenaires(){
   $pageTitle = "Gestion des partenaires";
   $style = '../../css/staff.css';
   if (isset($_POST['partenaires'])) {
      file_put_contents('../../templates/staff/administration/fichiers-txt/partenaires.txt', $_POST['texte-partenaires']);
  }
  $ligne = file_get_contents('../../templates/staff/administration/fichiers-txt/partenaires.txt', FILE_USE_INCLUDE_PATH);
  \Renderer::render('../../templates/staff/administration/partenaires', '../../templates/staff', compact('pageTitle', 'style', 'ligne'));
}

public function faq(){
   $pageTitle = "Gestion de la FAQ";
   $style = '../../css/staff.css';
   if (isset($_POST['modifier_faq'])) {
      file_put_contents('../../templates/staff/administration/fichiers-txt/faq.txt', $_POST['texte-faq']);
  }
  $ligne = file_get_contents('../../templates/staff/administration/fichiers-txt/faq.txt', FILE_USE_INCLUDE_PATH);
  \Renderer::render('../../templates/staff/administration/faq', '../../templates/staff', compact('pageTitle', 'style', 'ligne'));
}

public function changelog(){
    $pageTitle = "Gestion des mises à jour";
    $style = '../../css/staff.css';
    $changelogs = $this->model->changelog();
    if (isset($_POST['valider-changelog'])) {
        Administration::ajouterChangelog();
    }
    \Renderer::render('../../templates/staff/administration/changelog', '../../templates/staff', compact('pageTitle', 'style', 'changelogs'));
}

public function ajouterChangelog(){
    if (!isset($_SESSION['auth'])) {
        \Http::redirect('../../index.php');
    }
    $users = new \models\Users();
    $user = $users->user($_SESSION['auth']['id_user']);
    if ($user['grade'] <= 6 || $user['stagiaire'] == 1) {
        \Http::redirect('../../index.php');
    }
    $this->model->ajouterChangelog($_POST['titre-changelog'], $_POST['text-changelog']);
    \Http::redirect('gestion_changelog.php');
}

public function modifierChangelog(){
    if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
        die("Aucun ID renseigné");
    }
    $changelog = $this->model->searchChangelog($_GET['id']);
    $pageTitle = "Modifier un changelog";
    $style = "../../css/staff.css";
    if (isset($_POST['modifier-changelog'])) {
        if (!isset($_SESSION['auth'])) {
            \Http::redirect('../../index.php');
        }
        $users = new \models\Users();
        $user = $users->user($_SESSION['auth']['id_user']);
        if ($user['grade'] <= 6 || $user['stagiaire'] == 1) {
            \Http::redirect('../../index.php');
        }
        $this->model->modifierChangelog($_POST['titre-changelog'], $_POST['text-changelog'], $_GET['id']);
        \Http::redirect('modifier_changelog.php?id=' . $_GET['id']);
    }
    \Renderer::render('../../templates/staff/administration/modifierChangelog', '../../templates/staff', compact('pageTitle', 'style', 'changelog'));
}

}