<?php 
    require_once '../inc/base.php';

    if(isset($_POST['valid_entete'])){
    	if(!empty($_POST['title_game']) && !empty($_POST['picture_game']) && !empty($_POST['picture_pres'])){
    		$titre = addslashes(htmlspecialchars($_POST['title_game']));
    		$image_jeu = addslashes(htmlspecialchars($_POST['picture_game']));
    		$picture_pres = addslashes(htmlspecialchars($_POST['picture_pres']));

    		$modif_bdd = $pdo->prepare("UPDATE $type SET titre = ?, vignette = ?, theme = ? WHERE id = ?");
    		$modif_bdd->execute(array(stripslashes($titre),$image_jeu,$picture_pres,$id_news));

    		header('Location: '.traduire_nom(stripslashes($titre)));
    	}
    }

    if(isset($_POST['valid_presentation'])){
    	$presentation = htmlspecialchars($_POST['text_pres']);

    	$d = preg_replace('/\r/', '', $presentation);
	    $clean = preg_replace('/\n{2,}/', '\n\n', preg_replace('/^\s+$/m', '', $d)); 

    	$modif_bdd = $pdo ->prepare("UPDATE $type SET presentation = ? WHERE id = ?");
    	$modif_bdd->execute(array($clean,$id_news));
    	
    	header('Location: '.$save_name_jeu);
    }