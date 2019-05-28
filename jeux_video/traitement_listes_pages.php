<?php
	require_once '../inc/base.php';

	function write_date($date_post,$pseudo_member){
		$liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
		$post = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2}) [0-9]{2}:[0-9]{2}:[0-9]{2}#",function ($key) use ($liste_mois) { return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; },$date_post);

		return "Posté le ". $post ." par ".$pseudo_member;
	}


	if(!empty($_GET['id_jeu']) && !empty($_GET['name_cat'])){
		$id = (int) $_GET['id_jeu'];
		$name_cat = stripslashes(htmlspecialchars($_GET['name_cat']));

		$verif_cat = $pdo->prepare("SELECT * FROM billets_jeux_pages P INNER JOIN billets_jeux_cat O ON P.num_onglet = O.id WHERE O.nom = ? AND P.jeux_id = ? ORDER BY P.date_post");
		$verif_cat->execute(array($name_cat,$id));
	
		if($verif_cat->rowCount() == 0){
			echo 'Une erreur est survenue.';
		} else {
			// PAGINATION DEBUT
			if (!empty($_GET['p'])){
			    $page = stripslashes($_GET['p']);
			} else {
				$page = 1;
			}
				
			$pagination = 5;
			$limit_start = ($page - 1) * $pagination;

			$nb_total = $verif_cat->rowCount();
			// Pagination
			$nb_pages = ceil($nb_total / $pagination);

			$str_page = '<b>Page : </b>';
			// Boucle sur les pages
			for ($i = 1 ; $i <= $nb_pages ; $i++) {
				if ($i == $page ){
					$str_page .= "<span class=\"page_on\">". $i."</span>";
				} else {
					$str_page .= "<span class=\"page_off\">".$i."</span> ";
				}
			}
			// PAGINATION FIN

			$cat_exist = $pdo->prepare("SELECT P.*,O.nom AS name_onglet FROM billets_jeux_pages P INNER JOIN billets_jeux_cat O ON P.num_onglet = O.id WHERE O.nom = ? AND jeux_id = ? ORDER BY date_post DESC LIMIT $limit_start,$pagination");
			$cat_exist->execute(array($name_cat,$id));


			$page = "<span class=\"entete_liste_page\"><b>Pages de :</b> <span class=\"titre_name_cat\">".$name_cat."</span></span>";

			while($all_page = $cat_exist->fetch()){
				$page .= "<div class=\"name_page\">
							<span class=\"titre_post_page\">".$all_page->nom."</span>
							<span class=\"date_time_post_page\">".write_date($all_page->date_post,$all_page->member_post)."</span>
						</div>";
			}

			$page .= $str_page;

			echo $page;
		}
	}
	