<?php
	require_once '../inc/base.php';
	$tableau_element = "";

	$liste_pages_existantes = $pdo->prepare("
				SELECT J.titre,J.presentation,J.theme,P.id,P.nom,P.contenu,P.image,P.date_post,O.nom AS name_onglet 
				FROM billets_jeux J 
				INNER JOIN billets_jeux_pages P 
				ON J.id = P.jeux_id 
				INNER JOIN billets_jeux_cat O
				ON P.num_onglet = O.id
				WHERE J.id = ?
				ORDER BY P.date_post DESC
				LIMIT 5
				");

	if(!empty($_GET['id_jeu']) && !empty($_GET['action']) && (stripslashes(htmlspecialchars($_GET['action'])) == "right" || stripslashes(htmlspecialchars($_GET['action'])) == "left" || stripslashes(htmlspecialchars($_GET['action'])) == "none")){ 
		$id = (int) $_GET['id_jeu'];
		$id_page = (int) $_GET['id_page'];
		if(stripslashes(htmlspecialchars($_GET['action'])) == "left"){
			$action = -1;
		} else if(stripslashes(htmlspecialchars($_GET['action'])) == "right"){
			$action = 1;
		} else {
			$action = 0;
		}

		$new_aff = $id_page + $action;

		$liste_pages_existantes->execute(array($id));
		$donnees = $liste_pages_existantes->fetchAll();

		if ($liste_pages_existantes->rowCount() == 0){
			echo "Une erreur s'est produite";
		}

		if ($id_page + $action >= $liste_pages_existantes->rowCount()) {
			$new_aff = 0;
		} else if ($id_page + $action < 0) {
			$new_aff = $liste_pages_existantes->rowCount() - 1;
		}

		$tableau_element.= $donnees[$new_aff]->image.";;;";
		$tableau_element.= $donnees[$new_aff]->name_onglet.";;;";
		$tableau_element.= $donnees[$new_aff]->nom.";;;";
		$tableau_element.= $new_aff;

		echo $tableau_element;
	} 
