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

}