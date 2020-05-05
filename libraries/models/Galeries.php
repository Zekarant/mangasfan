<?php

namespace models;

class Galeries extends Model {

	public function galeries(?string $where = ""){
		$req = "SELECT * FROM galeries INNER JOIN users ON id_user = auteur_image";
		if ($where) {
			$req .= " WHERE " . $where;
		}
		$req .= " ORDER BY id_image DESC";
		$req = $this->pdo->prepare($req);
		$req->execute();
		$galeries = $req->fetchAll();
		return $galeries;
	}

	public function activerNSFW(int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET nsfw = 1 WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}

	public function desactiverNSFW(int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET nsfw = 0 WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}

	public function findGalerie($idImage){
		$req = $this->pdo->prepare('SELECT * FROM galeries INNER JOIN users ON auteur_image = id_user WHERE (id_image = :idImage OR slug = :idImage)');
		$req->execute(['idImage' => $idImage]);
		$imageGalerie = $req->fetch();
		return $imageGalerie;
	}
}