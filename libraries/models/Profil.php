<?php

namespace models;

class Profil extends Model {

	public function findMember(int $id){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE id_user = :id');
		$req->execute(['id' => $id]);
		$membre = $req->fetch();
		return $membre;
	}

	public function countAvertissements(int $idMember){
		$req = $this->pdo->prepare('SELECT count(*) FROM avertissements WHERE id_membre = :idMember');
		$req->execute(['idMember' => $idMember]);
		$countAvertissement = $req->fetchColumn();
		return $countAvertissement;
	}

	public function searchAvertissements(int $idMember){
		$req = $this->pdo->prepare('SELECT * FROM avertissements WHERE id_membre = :idMember');
		$req->execute(['idMember' => $idMember]);
		$avertissements = $req->fetch();
		return $avertissements;
	}

	public function recupererBannissements(int $idMember){
		$req = $this->pdo->prepare('SELECT id_bannissement, id_membre, us1.username as username_banni, us2.username as username_modo, us1.grade AS grade_banni, us2.grade AS grade_modo, us1.sexe, us1.chef, motif, begin_date, finish_date FROM bannissements INNER JOIN users AS us1 ON bannissements.id_membre = us1.id_user INNER JOIN users AS us2 ON bannissements.id_modo = us2.id_user WHERE id_membre = :idMember ORDER BY username_banni');
		$req->execute(['idMember' => $idMember]);
		if ($req->rowCount() != 0) {
			$bannissement = $req->fetchAll();
			return $bannissement;
		}
	}

	public function modifierGrade(int $grade, int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET grade = :grade, chef = 0, stagiaire = 0 WHERE id_user = :idUser');
		$req->execute(['grade' => $grade, 'idUser' => $idUser]);
	}

	public function modifierGradeStagiaire(int $grade, int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET grade = :grade, stagiaire = 1, chef = 0 WHERE id_user = :idUser');
		$req->execute(['grade' => $grade, 'idUser' => $idUser]);
	}

	public function modifierGradeChef(int $grade, int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET grade = :grade, stagiaire = 0, chef = 1 WHERE id_user = :idUser');
		$req->execute(['grade' => $grade, 'idUser' => $idUser]);
	}
}