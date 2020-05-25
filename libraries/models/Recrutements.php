<?php

namespace models;

class Recrutements extends Model {

	public function recrutements(){
		$req = $this->pdo->prepare('SELECT * FROM recrutements WHERE recrutement = 1');
		$req->execute();
		$recrutements = $req->fetchAll();
		return $recrutements;
	}

	public function recupererRecrutement(string $link){
		$req = $this->pdo->prepare('SELECT * FROM recrutements WHERE link = :link');
		$req->execute(['link' => $link]);
		$recrutements = $req->fetch();
		return $recrutements;
	}

	public function gestionrecrutements(){
		$req = $this->pdo->prepare('SELECT * FROM recrutements');
		$req->execute();
		$recrutements = $req->fetchAll();
		return $recrutements;
	}

	public function modifierRecrutement(int $value, string $link){
		$req = $this->pdo->prepare('UPDATE recrutements SET recrutement = :recrutements WHERE link = :link');
		$req->execute(['recrutements' => $value, 'link' => $link]);
	}
}