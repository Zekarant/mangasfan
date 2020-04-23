<?php 

class Color {
	public static function rang_etat(int $rang){
		$couleurs = ['black', '#2E9AFE', '#632569', 'orange', '#40A497', '#40A497', '#31B404', '#4080BF', 'red', 'red', '#1BB078'];
		$grade = $couleurs[$rang] ?? $couleurs[1];
		return $grade;
	}

	public static function getRang(int $rang, int $sexe, bool $stagiaire = false, bool $chef = false): string {
		$rangs = ['Banni', 'Membre', 'Community Manager', 'Animateur', 'Newseur', 'Rédacteur', 'Modérateur', 'Développeur', 'Administrateur', 'Fondateur', 'Mangas\'Bot'];
		$rangs_femme = ['Bannie', 'Membre', 'Community Manageuse', 'Animatrice', 'Newseuse', 'Rédactrice', 'Modératrice', 'Développeuse', 'Administratrice'];
		if ($sexe == 1 && $chef == 0) {
			$rang_txt = $rangs_femme[$rang] ?? $rangs[1];
		} else {
			$rang_txt = $rangs[$rang] ?? $rangs[1];
		}
		if ($chef && $rang_txt != $rangs[9] && $rang_txt != $rangs[1]  && $rang_txt != $rangs[0] && $rang_txt != $rangs[10]) {
			return "Chef des " . $rang_txt . "s";
		}
		if ($stagiaire) {
			$rang_txt = $rang_txt . " (Stagiaire)";
		}
		return $rang_txt;
	}

}