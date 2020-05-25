<?php

namespace models;

class Jeux extends Model {

	public function allJeux(?int $limit = 0, ?int $autre = 24){
		$req = $this->pdo->prepare('SELECT * FROM jeux WHERE nb_article != 0 ORDER BY id_jeux DESC LIMIT ' . $limit . ',' . $autre);
		$req->execute();
		$jeux = $req->fetchAll();
		return $jeux;
	}

	public function paginationCount(){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM jeux');
        $req->execute();
        $pagination = $req->fetchColumn();
        return $pagination;
	}

	public function jeuDemande(string $name){
		$req = $this->pdo->prepare('SELECT * FROM jeux WHERE name_jeu = :name');
		$req->execute(['name' => $name]);
		$jeu = $req->fetch();
		return $jeu;
	}

	public function searchGame($id){
		$req = $this->pdo->prepare('SELECT * FROM jeux WHERE (id_jeux = :id OR slug = :id)');
		$req->execute(['id' => $id]);
		$jeu = $req->fetch();
		return $jeu;
	}

	public function notes(string $type, int $id){
		$moyenne_note = $this->pdo->prepare("SELECT * FROM note_members WHERE type = :type AND id_elt = :id");
		$moyenne_note->execute(['type' => $type, 'id' => $id]);
		if($moyenne_note->rowCount() != 0){
			$somme_note = $this->pdo->prepare("SELECT SUM(val_note) AS sum_notes FROM note_members WHERE type = :type AND id_elt = :id");
			$somme_note->execute(['type' => $type, 'id' => $id]);
			$somme_notes = $somme_note->fetch();
			$vote = ($moyenne_note->rowCount() > 1) ? " votes" : " vote";
			$rst_moy = round($somme_notes['sum_notes'] / $moyenne_note->rowCount(), 2);
			return array($moyenne_note, $rst_moy, $vote);
		}
	}

	public function verifierVote(int $member, string $type, int $id){
		$req = $this->pdo->prepare("SELECT * FROM note_members WHERE id_member = :member AND type = :type AND id_elt = :id");
		$req->execute(['member' => $member, 'type' => $type, 'id' => $id]);
		return $req;
	}

	public function voter(int $member, int $note, string $type, int $id){
		$req = $this->pdo->prepare("INSERT INTO note_members(id_member, val_note, type, id_elt) VALUES(:member, :note, :type, :id)");
		$req->execute(['member'=> $member, 'note' => $note, 'type' => $type, 'id' => $id]);
	}


	public function verifierNbrArticles(int $jeux){
		$verif_jeu_exist = $this->pdo->prepare("SELECT * FROM jeux_articles WHERE id_jeux = :jeux LIMIT 1");
		$verif_jeu_exist->execute(['jeux' => $jeux]);
		$verif_jeu_existe = $verif_jeu_exist->rowCount();
		return $verif_jeu_exist;
	}

	public function pagesJeux(int $idJeu){
		$liste_pages = $this->pdo->prepare("SELECT * FROM jeux_articles INNER JOIN categories_jeux ON id_onglet = id_category WHERE id_jeux = :idJeu ORDER BY id_article DESC");
		$liste_pages->execute(['idJeu' => $idJeu]);
		$donnees_pages = $liste_pages->fetchAll();
		return $donnees_pages;
	}

	public function lastArticle($idJeu, $idPage){
		$liste_pages = $this->pdo->prepare("SELECT * FROM jeux_articles INNER JOIN categories_jeux ON id_jeux = id_jeu INNER JOIN jeux ON jeux.id_jeux = jeux_articles.id_jeux WHERE (jeux_articles.id_jeux = :idJeu OR slug_article = :idJeu) AND (jeux.id_jeux = :idPage OR jeux.slug = :idPage) ORDER BY id_article DESC LIMIT 1");
		$liste_pages->execute(['idJeu' => $idJeu, 'idPage' => $idPage]);
		$donnees_pages = $liste_pages->fetch();
		return $donnees_pages;
	}

	public function category(int $idJeu){
		$recup_all_category = $this->pdo->prepare("SELECT DISTINCT O.name_category AS name_onglet FROM jeux_articles P INNER JOIN categories_jeux O ON P.id_onglet = O.id_category WHERE id_jeux = :idJeu ORDER BY id_onglet");
		$recup_all_category->execute(['idJeu' => $idJeu]);
		$parcours_category = $recup_all_category->fetchAll();
		return array($recup_all_category, $parcours_category);
	}

	public function verifierCategory(string $category, int $idJeu){
		$verif_cat = $this->pdo->prepare("SELECT * FROM jeux_articles P INNER JOIN categories_jeux O ON P.id_onglet = O.id_category WHERE O.name_category = :category AND P.id_jeux = :idJeu ORDER BY P.date_post");
		$verif_cat->execute(['category' => $category, 'idJeu' => $idJeu]);
		return $verif_cat;
	}

	public function oneGame(int $idJeu){
		$liste_pages = $this->pdo->prepare("SELECT * FROM jeux_articles INNER JOIN categories_jeux ON id_onglet = id_category WHERE jeux_articles.id_jeux = :idJeu ORDER BY id_article DESC");
		$liste_pages->execute(['idJeu' => $idJeu]);
		$donnees_pages = $liste_pages->fetch();
		return $donnees_pages;
	}

	public function categoryExist(string $category, int $idJeu){
		$cat_exist = $this->pdo->prepare("SELECT *, O.name_category AS name_onglet FROM jeux_articles P INNER JOIN categories_jeux O ON P.id_onglet = O.id_category INNER JOIN jeux j ON j.id_jeux = P.id_jeux LEFT JOIN users u ON u.id_user = P.id_member WHERE O.name_category = :category AND P.id_jeux = :idJeu AND visible = 0 LIMIT 10");
		$cat_exist->execute(['category' => $category, 'idJeu' => $idJeu]);
		return $cat_exist;
	}
}