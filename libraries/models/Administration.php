<?php

namespace Models;

class Administration extends Model {

	public function maintenance(){
		$req = $this->pdo->prepare('SELECT * FROM maintenances');
		$req->execute();
		$maintenance = $req->fetchAll();
		return $maintenance;
	}

	public function verifier(string $area){
		$req = $this->pdo->prepare('SELECT * FROM maintenances WHERE maintenance_area = :area');
		$req->execute(['area' => $area]);
		$maintenance = $req->fetch();
		return $maintenance;
	}

	public function updateAllMaintenance(int $value){
		$req = $this->pdo->prepare('UPDATE maintenances SET active_maintenance = :value');
		$req->execute(['value' => $value]);
	}

	public function updateMaintenance(int $value, string $location){
		$req = $this->pdo->prepare('UPDATE maintenances SET active_maintenance = :value WHERE maintenance_area = :location');
		$req->execute(['value' => $value, 'location' => $location]);
	}

	public function avertissements(){
		$req = $this->pdo->prepare('SELECT id_avertissement, us1.avertissements, us1.username as username_banni, us2.username as username_modo, us1.grade AS grade_banni, us2.grade AS grade_modo, us1.sexe, us1.chef, motif, add_date FROM avertissements INNER JOIN users AS us1 ON avertissements.id_membre = us1.id_user INNER JOIN users AS us2 ON avertissements.id_modo = us2.id_user ORDER BY add_date DESC');
		$req->execute();
		$avertissements = $req->fetchAll();
		return $avertissements;
	}
	
}