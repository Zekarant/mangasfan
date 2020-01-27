<?php 
	$can_commentary = false;
	$str_page = "";
	if (!empty($_GET['p']) && is_numeric($_GET['p'])){
    	$page = stripslashes($_GET['p']);
    } else {
		$page = 1;
	}
	$pagination = 7;
	// Numéro du 1er enregistrement à lire
	$limit_start = ($page - 1) * $pagination;
	$nb_total = $pdo->query("SELECT COUNT(*) AS nb_total FROM commentary_page WHERE name_elt = '$type_elt' AND id_cat = '$id_jeu' AND id_page = '$id_page'");
	$nb_total->execute();
	$nb_total = $nb_total->fetchColumn();
	// Pagination
	$nb_pages = ceil($nb_total / $pagination);

	$str_page = $str_page.'<table style="width:50%;margin-left:5px;"><th style="width:33%"><span class="pagination_mobile_membres">[ Page :';
	// Boucle sur les pages
	for ($i = 1 ; $i <= $nb_pages ; $i++) {
		if ($i == $page ){
			$str_page = $str_page."<span style=\"color:red\"> $i</span>";
		} else {
			$str_page = $str_page." <a href=\"?jeux=$id_jeu&page=$id_page&p=$i\" style=\"color:black !important;\">$i</a> ";
		}
	}
	$str_page = $str_page.' ]</span></th></table>';
	

	//list of commentary
	$all_commentary = $pdo->prepare("SELECT * FROM commentary_page WHERE id_cat = ? AND id_page = ? AND name_elt = ? ORDER BY time_post DESC LIMIT $limit_start, $pagination");
	$all_commentary->execute(array($id_jeu,$id_page,$type_elt));
	$nbr_commentary = $all_commentary->rowCount();

	$description = isset($_POST['description']) ? $_POST['description'] : '';

	// send new commentary
	if (isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){
		$can_commentary = true;
		$not_last_com = true;
		
	   	$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
	    $user->execute(array($_SESSION['auth']['id']));
		$utilisateur = $user->fetch(); 


		if($all_commentary->rowCount() != 0){
			$last_commentary = $pdo->query("SELECT * FROM commentary_page WHERE id = (SELECT MAX(id) FROM commentary_page WHERE name_elt = '$type_elt' AND id_cat = $id_jeu AND id_page = $id_page)")->fetch();

			if($last_commentary['id_member'] == $utilisateur['id']){
				$not_last_com = false;
			} else {
				$not_last_com = true;
			}
		}

		if($type_elt == 'jeux'){
			$type = "jeux_video";
		} elseif($type_elt == 'mangas'){
			$type = 'mangas';
		} else {
			$type = 'anime';
		}

		if(isset($_POST['valid_send'])){
			$description = htmlspecialchars($_POST['description']);	

			$d = preg_replace('/\r/', '', $description);
	    	$clean = preg_replace('/\n{2,}/', '\n\n', preg_replace('/^\s+$/m', '', $d));
			if (!empty($description)){
				$commentary_ok = $pdo->prepare("INSERT INTO commentary_page(id_member,id_cat,id_page,name_elt,commentary,time_post) VALUES(?,?,?,?,?,NOW())");
				$commentary_ok->execute(array($utilisateur['id'],$id_jeu,$id_page,$type_elt,htmlspecialchars($clean)));

				header("Location: $save_name_page");
			}
		}
		

	} 
?>