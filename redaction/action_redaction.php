<?php 
	require_once '../membres/base.php';
	$type = ($_GET['type'] == "jeux") ? "billets_jeux" : "billets_mangas";
	$type2 = ($_GET['type'] == "jeux") ? "billets_jeux_cat" : "billets_mangas_cat";
	$type3 = ($_GET['type'] == "jeux") ? "billets_jeux_pages" : "billets_mangas_pages";

	$type4 = ($_GET['type'] == "jeux") ? "jeux_id" : "mangas_id";


	$action = (!empty($_GET['action'])) ? stripslashes(htmlspecialchars($_GET['action'])) : ""; 
	
	$id_page = (!empty($_GET['page_id'])) ? $_GET['page_id'] : -1 ; // 2 
	$jeu_id = (!empty($_GET['id_jeu'])) ? (int) $_GET['id_jeu'] : -1 ; // 3
	$new_name = (!empty($_GET['new_name'])) ? stripslashes(htmlspecialchars($_GET['new_name'])) : ""; 
	
	if($action == "suppr" && $id_page >= 0){
		$liste_page = $pdo->prepare("SELECT P.id FROM $type3 P INNER JOIN $type2 O ON O.id = P.num_onglet WHERE P.$type4 = ? ORDER BY O.position,P.type_art DESC, P.date_post DESC ");
		$liste_page->execute(array($jeu_id));
		$recup_pages = $liste_page->fetchAll();
		$delete_page_jeu = $pdo->prepare("DELETE FROM $type3 WHERE id = ?");
		$delete_page_jeu->execute(array($recup_pages[$id_page - 1]['id'])); 
		
	} elseif($action == "suppr_cat" && $id_page >= 0) {
		$liste_page = $pdo->prepare("SELECT * FROM $type2 WHERE billets_id = ? ORDER BY position");
		$liste_page->execute(array($jeu_id));
		$recup_pages = $liste_page->fetchAll();

		$delete_all_page = $pdo->prepare("DELETE FROM $type3 WHERE num_onglet = ?");
		$delete_all_page->execute(array($recup_pages[$id_page - 1]['id']));

		$delete_cat_jeu = $pdo->prepare("DELETE FROM $type2 WHERE id = ?");
		$delete_cat_jeu->execute(array($recup_pages[$id_page - 1]['id'])); 
	} elseif($action == "modif_cat" && !empty($new_name)){
		$liste_cat = $pdo->prepare("SELECT * FROM $type2 WHERE billets_id = ? ORDER BY position");
		$liste_cat->execute(array($jeu_id));
		$recup_cat = $liste_cat->fetchAll();

		$update_cat = $pdo->prepare("UPDATE $type2 SET nom = ? WHERE id = ?");
		$update_cat->execute(array($new_name,$recup_cat[$id_page - 1]['id'])); 
	}
