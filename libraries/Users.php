<?php 

class Users {

	public static function str_random($length){
		$alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
		return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
	}

	public static function sexe($id_sexe){
		$sexe = ['Homme', 'Femme', 'Autre'];
		$choix = $sexe[$id_sexe] ?? $sexe[0];
		return $choix;
	}

	public static function dateAnniversaire($date){
		$liste_mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $date_anniversaire = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2})#",function ($key) use ($liste_mois){
              return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; 
            }, $date);
        return $date_anniversaire; 

	}

}