<?php 

class Color {
	public static function rang_etat(int $rang){
		$couleurs = ['black', '#2E9AFE', '#632569', 'orange', '#40A497', '#40A497', '#31B404', '#4080BF', 'red', 'red', '#1BB078'];
		$grade = $couleurs[$rang] ?? $couleurs[1];
		return $grade;
	}

	public static function getRang(string $rang, bool $stagiaire = false, bool $chef = false): string {
		if ($chef) {
			return "Chef des " . $rang . "s";
		}
		if ($stagiaire) {
			$rang = $rang . " (Stagiaire)";
		}
		return $rang;
	}
}