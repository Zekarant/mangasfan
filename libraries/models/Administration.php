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
	
}