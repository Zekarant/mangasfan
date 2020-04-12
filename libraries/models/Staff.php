<?php 

namespace Models;

class Staff extends Model {
	
	public function tousUtilisateurs(?string $staff){
		$sql = "SELECT * FROM users";
		if ($staff) {
			$sql .= " WHERE grade >= 2";
		}
		$requete = $this->pdo->prepare($sql);
		$requete->execute();
	}
}