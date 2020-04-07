<?php 

class Color {
	public static function rang_etat(int $rang){
		$couleurs = ['purple', 'black', '#2E9AFE', 'orange', '#632569', '#401497', '#401497', '#31B404', '#4080BF', 'darkblue', 'red', 'red'];
		$grade = $couleurs[$rang] ?? $couleurs[0];
		return $grade;
	}

}