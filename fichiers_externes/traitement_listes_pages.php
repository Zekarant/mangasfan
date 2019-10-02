<?php
	require_once '../membres/base.php';
	include('../membres/functions.php');

	$type = ($_GET['type'] == "jeux") ? "billets_jeux" : "billets_mangas";
	$type2 = ($_GET['type'] == "jeux") ? "billets_jeux_cat" : "billets_mangas_cat";
	$type3 = ($_GET['type'] == "jeux") ? "billets_jeux_pages" : "billets_mangas_pages";

	$type4 = ($_GET['type'] == "jeux") ? "jeux_id" : "mangas_id";
	$type5 = ($_GET['type'] == "jeux") ? "jeux" : "mangas";

	if(!empty($_GET['id_elt']) && !empty($_GET['name_cat'])){
		$id = (int) $_GET['id_elt'];
		$name_cat = stripslashes(htmlspecialchars($_GET['name_cat']));

		$verif_cat = $pdo->prepare("SELECT * FROM $type3 P INNER JOIN $type2 O ON P.num_onglet = O.id WHERE O.nom = ? AND P.$type4 = ? ORDER BY P.date_post");
		$verif_cat->execute(array($name_cat,$id));
		
		$recup_nom_jeu = $pdo->prepare("SELECT titre FROM $type WHERE id = ? LIMIT 1");
		$recup_nom_jeu->execute(array($id));
		$nom_jeu = $recup_nom_jeu->fetch();

		if($verif_cat->rowCount() == 0){
			echo 'Une erreur est survenue.';
		} else {
			$cat_exist = $pdo->prepare("SELECT P.*,O.nom AS name_onglet FROM $type3 P INNER JOIN $type2 O ON P.num_onglet = O.id WHERE O.nom = ? AND $type4 = ? ORDER BY type_art DESC,date_post DESC LIMIT 10");
			$cat_exist->execute(array($name_cat,$id));


			$page = "<span class=\"entete_liste_page\"><b>Pages de :</b> <span class=\"titre_name_cat\">".$name_cat."</span></span>";

			while($all_page = $cat_exist->fetch()){
				$type_page = ($all_page['type_art'] == "post-it") ? "pit_mess" : "name_page";
				$titre_css = ($all_page['type_art'] == "post-it") ? "titre_pit_mess" : "titre_post_page";
				$message = ($all_page['type_art'] == "post-it") ? "[Post-It] " : "";

				$page .= "<div class=\"".$type_page." bloc_page\">

						<a href=\"".traduire_nom($nom_jeu['titre'])."/".traduire_nom($all_page['nom'])."\"><span class=\"".$titre_css."\">".$message.$all_page['nom']."</span></a>
							<span class=\"date_time_post_page\">".write_date($all_page['date_post'],$all_page['member_post'])."</span>
						</div>";
			}
			$page .= "<p><i>Limitation à 10 articles</i></p>";

			echo $page;
		}
	}
	