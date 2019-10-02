<?php 
include('../membres/base.php');

    if(isset($_POST['valid_nouvelle_page'])){
        $categorie = addslashes(htmlspecialchars($_POST['liste_categories']));
        if($categorie != "Sélectionner une catégorie" && $categorie != NULL && !empty($_POST['title_page']) && !empty($_POST['text_pres'])){
            $title_page = addslashes(htmlspecialchars($_POST['title_page']));

            $text_page = htmlspecialchars($_POST['text_pres']);
            $d = preg_replace('/\r/', '', $text_page);
            $clean = preg_replace('/\n{2,}/', '\n\n', preg_replace('/^\s+$/m', '', $d));
            $text_page = $clean;

            $image = htmlspecialchars($_POST['picture_game']);

            $type_message = (isset($_POST['type_message']) && $_POST['type_message'] == "post-it") ? $_POST['type_message'] : null;

            $verif_deja_exist = $pdo->prepare("SELECT * FROM $type3 B INNER JOIN $type2 O ON B.num_onglet = O.id WHERE B.nom = ? AND B.$type4 = ? AND O.nom = ? LIMIT 1");
            $verif_deja_exist->execute(array($title_page,$id_news,$categorie));

            $num_id_onglet = $pdo->prepare("SELECT id FROM $type2 WHERE nom = ? AND billets_id = ?");
            $num_id_onglet->execute(array($categorie,$id_news));
            $num_id_onglet = $num_id_onglet->fetch();

            if($verif_deja_exist->rowCount() == 0){
                if($verif_type == "jeux"){
                    $ajoute_page = $pdo->prepare('
                        INSERT INTO billets_jeux_pages(jeux_id,num_onglet,nom,type_art,contenu,member_post,date_post,image,position) 
                        VALUES(?,?,?,?,?,?,NOW(),?,?)');
                } else {
                    $ajoute_page = $pdo->prepare('
                        INSERT INTO billets_mangas_pages(mangas_id,num_onglet,nom,type_art,contenu,member_post,date_post,image,position) 
                        VALUES(?,?,?,?,?,?,NOW(),?,?)');
                }
                $ajoute_page->execute(array($id_news,$num_id_onglet['id'],$title_page,$type_message,$text_page,$utilisateur['username'],$image,0));

                header('Location: ../modif_'.$verif_type.'/'.$save_name_jeu);
            }       
        } 
    } 

    if(isset($_POST['valid_nouvelle_cat']) && !empty($_POST['valid_nouvelle_cat'])){
        $categorie = addslashes(htmlspecialchars($_POST['new_cat']));
        $verif_deja_exist = $pdo->prepare("SELECT * FROM $type2 WHERE nom = ? AND billets_id = ? LIMIT 1");
        $verif_deja_exist->execute(array($categorie,$id_news));

        if($verif_deja_exist->rowCount() == 0){
            $derniere_categorie = $pdo->query("SELECT position FROM $type2 WHERE position = (SELECT MAX(position) FROM $type2 WHERE billets_id = $id_news)");

            if($derniere_categorie->rowCount() > 0) {
                $derniere_categorie = $derniere_categorie->fetch();
                $last_cat = $derniere_categorie['position'];
            } else {
                $last_cat = 0;
            }

            if($verif_type == "mangas"){
                $ajoute_cat = $pdo->prepare('INSERT INTO billets_mangas_cat(billets_id,nom,position) VALUES(?,?,?)');
            } else {
                $ajoute_cat = $pdo->prepare('INSERT INTO billets_jeux_cat(billets_id,nom,position) VALUES(?,?,?)');
            }

            $ajoute_cat->execute(array($id_news,$categorie,$last_cat+1));

            header('Location: ../modif_'.$verif_type.'/'.$save_name_jeu.'');
        }
    }
