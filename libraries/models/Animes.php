<?php

namespace models;

class Animes extends Model {

	public function allAnimes(?int $limit = 0, ?int $autre = 24){
		$req = $this->pdo->prepare('SELECT * FROM mangas_animes WHERE type = "anime" ORDER BY id DESC LIMIT ' . $limit . ',' . $autre);
		$req->execute();
		$animes = $req->fetchAll();
		return $animes;
	}

	public function paginationCount(){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM mangas_animes WHERE type = "anime"');
        $req->execute();
        $pagination = $req->fetchColumn();
        return $pagination;
	}

	public function animeDemande(string $name){
		$req = $this->pdo->prepare('SELECT * FROM mangas_animes WHERE titre = :name');
		$req->execute(['name' => $name]);
		$anime = $req->fetch();
		return $anime;
	}

	public function searchAnime($id){
		$req = $this->pdo->prepare('SELECT * FROM mangas_animes WHERE (id = :id OR slug = :id)');
		$req->execute(['id' => $id]);
		$anime = $req->fetch();
		return $anime;
	}

	public function verifierNbrArticles(int $mangas){
		$verif_jeu_exist = $this->pdo->prepare("SELECT * FROM mangas_animes WHERE id = :mangas AND type = 'anime' LIMIT 1");
		$verif_jeu_exist->execute(['mangas' => $mangas]);
		$verif_jeu_existe = $verif_jeu_exist->rowCount();
		return $verif_jeu_exist;
	}

	public function pagesAnimes(int $idManga, int $isAdmin){
		$liste_pages = $this->pdo->prepare("SELECT * FROM mangas_animes_articles 
            INNER JOIN categories_mangas_animes 
                ON id_onglet = id_category 
            WHERE mangas_animes_articles.id_anime_mangas = :idManga AND (:isAdmin || visible = 0)
            ORDER BY id DESC");
		$liste_pages->execute(['idManga' => $idManga, 'isAdmin' => $isAdmin]);
		$donnees_pages = $liste_pages->fetchAll();
		return $donnees_pages;
	}

	public function lastArticle($idManga, $idPage){
		$liste_pages = $this->pdo->prepare("SELECT * FROM mangas_animes_articles INNER JOIN categories_mangas_animes ON mangas_animes_articles.id_anime_mangas = categories_mangas_animes.id_anime_mangas INNER JOIN mangas_animes ON mangas_animes.id = mangas_animes_articles.id_anime_mangas WHERE (mangas_animes_articles.id_anime_mangas = :idManga OR slug_article = :idManga) AND (mangas_animes_articles.id_anime_mangas = :idPage OR slug = :idPage) ORDER BY mangas_animes_articles.id DESC LIMIT 1");
		$liste_pages->execute(['idManga' => $idManga, 'idPage' => $idPage]);
		$donnees_pages = $liste_pages->fetch();
		return $donnees_pages;
	}

	public function category(int $idManga){
		$recup_all_category = $this->pdo->prepare("SELECT DISTINCT O.name_category AS name_onglet FROM mangas_animes_articles P INNER JOIN categories_mangas_animes O ON P.id_onglet = O.id_category WHERE P.id_anime_mangas = :idManga ORDER BY id_onglet");
		$recup_all_category->execute(['idManga' => $idManga]);
		$parcours_category = $recup_all_category->fetchAll();
		return array($recup_all_category, $parcours_category);
	}

	public function oneAnime(int $idManga){
		$liste_pages = $this->pdo->prepare("SELECT * FROM mangas_animes_articles INNER JOIN categories_mangas_animes ON id_onglet = id_category WHERE mangas_animes_articles.id_anime_mangas = :idManga ORDER BY mangas_animes_articles.id_anime_mangas DESC");
		$liste_pages->execute(['idManga' => $idManga]);
		$donnees_pages = $liste_pages->fetch();
		return $donnees_pages;
	}

	public function verifierCategory(string $category, int $idManga){
		$verif_cat = $this->pdo->prepare("SELECT * FROM mangas_animes_articles P LEFT JOIN categories_mangas_animes O ON P.id_onglet = O.id_category WHERE O.name_category = :category AND P.id_anime_mangas = :idManga ORDER BY P.date_post");
		$verif_cat->execute(['category' => $category, 'idManga' => $idManga]);
		return $verif_cat;
	}

	public function categoryExist(string $category, int $idJeu, int $isAdmin){
		$cat_exist = $this->pdo->prepare("SELECT *, O.name_category AS name_onglet FROM mangas_animes_articles P
            INNER JOIN categories_mangas_animes O
                ON P.id_onglet = O.id_category
            INNER JOIN mangas_animes j
                ON j.id = P.id_anime_mangas
            LEFT JOIN users u
                ON u.id_user = P.id_member
            WHERE O.name_category = :category
              AND P.id_anime_mangas = :idJeu
              AND (:isAdmin || visible = 0)
            LIMIT 10");
		$cat_exist->execute(['category' => $category, 'idJeu' => $idJeu, 'isAdmin' => $isAdmin]);
		return $cat_exist;
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
}
