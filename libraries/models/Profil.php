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

	public function recupererAvertissements(int $idMember){
		$req = $this->pdo->prepare('SELECT id_avertissement, id_membre, us1.username as username_banni, us2.username as username_modo, us1.grade AS grade_banni, us2.grade AS grade_modo, us1.sexe, us1.chef, motif, add_date FROM avertissements INNER JOIN users AS us1 ON avertissements.id_membre = us1.id_user INNER JOIN users AS us2 ON avertissements.id_modo = us2.id_user WHERE id_membre = :idMember ORDER BY id_avertissement DESC');
		$req->execute(['idMember' => $idMember]);
		$avertissements = $req->fetchAll();
		return $avertissements;
	}

	public function recupererBannissements(int $idMember){
		$req = $this->pdo->prepare('SELECT id_bannissement, id_membre, us1.username as username_banni, us2.username as username_modo, us1.grade AS grade_banni, us2.grade AS grade_modo, us1.sexe, us1.chef, motif, begin_date, finish_date FROM bannissements INNER JOIN users AS us1 ON bannissements.id_membre = us1.id_user INNER JOIN users AS us2 ON bannissements.id_modo = us2.id_user WHERE id_membre = :idMember ORDER BY id_bannissement DESC');
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

	public function attribuerAvertissement(string $contenu, int $idUser, int $idModo){
		$req = $this->pdo->prepare('INSERT INTO avertissements(motif, id_membre, id_modo, add_date) VALUES(:contenu, :idUser, :idModo, NOW())');
		$req->execute(['contenu' => $contenu, 'idUser' => $idUser, 'idModo' => $idModo]);
	}

	public function attribuerBannissement(string $contenu, int $idUser, int $idModo, $dateFin){
		$req = $this->pdo->prepare('INSERT INTO bannissements(motif, id_membre, id_modo, begin_date, finish_date) VALUES(:contenu, :idUser, :idModo, NOW(), :dateFin)');
		$req->execute(['contenu' => $contenu, 'idUser' => $idUser, 'idModo' => $idModo, 'dateFin' => $dateFin]);
		$req2 = $this->pdo->prepare('UPDATE users SET grade = 0 WHERE id_user = :idUser');
		$req2->execute(['idUser' => $idUser]);
	}

	public function nonGalerie(int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET galerie = 1 WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}

	public function autoriserGalerie(int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET galerie = 0 WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}

	public function modifierInformations(string $username, string $email, $dateAnniversaire, string $manga, string $anime, string $site, int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET username = :username, email = :email, date_anniversaire = :dateAnniversaire, manga = :manga, anime = :anime, site = :site WHERE id_user = :idUser');
		$req->execute(['username' => $username, 'email' => $email, 'dateAnniversaire' => $dateAnniversaire, 'manga' => $manga, 'anime' => $anime, 'site' => $site, 'idUser' => $idUser]);
	}

	public function desactiverCompte(string $token, int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET confirmation_token = :token, confirmed_at = NULL WHERE id_user = :idUser');
      	$req->execute(['token' => $token, 'idUser' => $idUser]);
	}

	public function activerCompte(int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}

	public function reinitialiserAvatar(int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET avatar = "avatar_defaut.png" WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}

	public function suppressionCompte(int $idUser){
		$req = $this->pdo->prepare('DELETE FROM users WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}
}