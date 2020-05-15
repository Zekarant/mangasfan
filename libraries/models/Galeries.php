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

	public function galeriesComments(int $idImage){
		$req = $this->pdo->prepare('SELECT * FROM galeries INNER JOIN galeries_commentary ON galeries_commentary.id_image = galeries.id_image INNER JOIN users ON author_commentary = id_user WHERE (galeries.id_image = :idImage OR users.id_user = :idImage) ORDER BY date_commentaire DESC');
		$req->execute(['idImage' => $idImage]);
		$commentaires = $req->fetchAll();
		return $commentaires;
	}

	public function ajouterCommentaire(string $commentaire, int $idUser, int $idImage){
		$req = $this->pdo->prepare('INSERT INTO galeries_commentary(id_image, author_commentary, galery_commentary, date_commentaire) VALUES(:idImage, :idUser, :commentaire, NOW())');
		$req->execute(['idImage' => $idImage, 'idUser' => $idUser, 'commentaire' => $commentaire]);
	}

	public function findComment(int $id){
		$query = $this->pdo->prepare("SELECT * FROM galeries_commentary INNER JOIN galeries ON galeries.id_image = galeries_commentary.id_image WHERE galeries_commentary.id_commentary_galery = :id");
		$query->execute(['id' => $id]);
		$resultat = $query->fetch();
		return $resultat;
	}

	public function editComment(string $commentary, int $idCommentary){
		$query = $this->pdo->prepare("UPDATE galeries_commentary SET galery_commentary = :commentary WHERE id_commentary_galery = :idCommentary");
		$query->execute(['commentary' => $commentary, 'idCommentary' => $idCommentary]);
	}

	public function deleteComment(int $id){
		$query = $this->pdo->prepare("DELETE FROM galeries_commentary WHERE id_commentary_galery = :id");
		$query->execute(['id' => $id]);
	}

	public function ajouterRappel(int $idGalerie){
		$req = $this->pdo->prepare('UPDATE galeries SET rappel_image = 1 WHERE id_image = :idGalerie');
		$req->execute(['idGalerie' => $idGalerie]);
	}

	public function ajouterImage(string $image, string $titleImage, string $keywords, string $contenu, int $auteur, int $nsfw, string $slug){
		$req = $this->pdo->prepare('INSERT INTO galeries(filename, title_image, keywords_image, contenu_image, date_image, auteur_image, rappel_image, nsfw_image, slug) VALUES(:image, :titleImage, :keywords, :contenu, NOW(), :auteur, 0, :nsfw, :slug)');
          $req->execute(['image' => $image, 'titleImage' => $titleImage, 'keywords' => $keywords, 'contenu' => $contenu, 'auteur' => $auteur, 'nsfw' => $nsfw, 'slug' => $slug]);
	}

	public function supprimerImage(int $idImage){
		$req = $this->pdo->prepare('DELETE FROM galeries WHERE id_image = :idImage');
		$req->execute(['idImage' => $idImage]);
	}

	public function countGaleries(int $idMember){
		$req = $this->pdo->prepare('SELECT count(*) FROM galeries WHERE auteur_image = :idMember');
		$req->execute(['idMember' => $idMember]);
		$countGalerie = $req->fetchColumn();
		return $countGalerie;
	}

	public function modifierImage(string $titre, string $keywords, string $contenu, string $slug, int $idImage){
		$req = $this->pdo->prepare('UPDATE galeries SET title_image = :titre, keywords_image = :keywords, contenu_image = :contenu, slug = :slug WHERE id_image = :idImage');
		$req->execute(['titre' => $titre, 'keywords' => $keywords, 'contenu' => $contenu, 'slug' => $slug, 'idImage' => $idImage]);
	}

	public function memberGalerie(int $idUser, ?string $nsfw = ""){
		$req = "SELECT * FROM galeries INNER JOIN users ON id_user = auteur_image WHERE id_user = :idUser AND rappel_image = 0 ";
		if ($nsfw) {
			$req .= $nsfw;
		}
		$req .= " ORDER BY id_image DESC";
		$req = $this->pdo->prepare($req);
		$req->execute(['idUser' => $idUser]);
		$galeriesMembers = $req->fetchAll();
		return $galeriesMembers;
	}
}