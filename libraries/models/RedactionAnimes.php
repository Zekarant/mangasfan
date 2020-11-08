<?php

namespace models;

class RedactionAnimes extends Model {

	public function donneesAnimes($idAnime){
		$req = $this->pdo->prepare('SELECT * FROM mangas_animes WHERE (id = :idAnime OR slug = :idAnime)');
		$req->execute(['idAnime' => $idAnime]);
		$manga = $req->fetch();
		return $manga;
	}

	public function modifierEntete(string $title, string $imagePresentation, string $imageCover, string $type, string $slug, int $idAnime, int $avertissement){
		$req = $this->pdo->prepare('UPDATE mangas_animes SET titre = :title, banniere = :imagePresentation, cover = :imageCover, type = :type, slug = :slug, publicAverti = :avertissement WHERE id = :idAnime');
		$req->execute(['title' => $title, 'imagePresentation' => $imagePresentation, 'imageCover' => $imageCover, 'type' => $type, 'slug' => $slug, 'idAnime' => $idAnime, 'avertissement' => $avertissement]);
	}

	public function modifierDescription(string $description, int $idAnime){
		$req = $this->pdo->prepare('UPDATE mangas_animes SET presentation = :description WHERE id = :idAnime');
		$req->execute(['description' => $description, 'idAnime' => $idAnime]);
	}

	public function listeOnglets(int $idAnime){
		$req = $this->pdo->prepare('SELECT * FROM categories_mangas_animes INNER JOIN mangas_animes ON 	id_anime_mangas = id WHERE 	id_anime_mangas = :idAnime ORDER BY id_category');
		$req->execute(['idAnime' => $idAnime]);
		$onglets = $req->fetchAll(); 
		return $onglets;
	}

	public function countOnglets(int $idAnime){
		$req = $this->pdo->prepare('SELECT count(*) FROM categories_mangas_animes WHERE id_anime_mangas = :idAnime ORDER BY id_category');
		$req->execute(['idAnime' => $idAnime]);
		return $req;
	}

	public function insererOnglet(int $idAnime, string $name){
		$req = $this->pdo->prepare('INSERT INTO categories_mangas_animes(id_anime_mangas, name_category) VALUES(:idAnime, :name)');
		$req->execute(['idAnime' => $idAnime, 'name' => $name]);
	}

	public function articles(int $idAnime){
		$req = $this->pdo->prepare("SELECT *, mangas_animes_articles.id AS id_article FROM mangas_animes_articles INNER JOIN mangas_animes ON mangas_animes.id = mangas_animes_articles.id_anime_mangas WHERE mangas_animes_articles.id_anime_mangas = :idAnime");
		$req->execute(['idAnime' => $idAnime]);
		$pages = $req->fetchAll();
		return $pages;
	}

	public function ongletArticle(int $idAnime){
		$liste_cat = $this->pdo->prepare("SELECT * FROM categories_mangas_animes WHERE id_anime_mangas = :idAnime ORDER BY id_category");
		$liste_cat->execute(['idAnime' => $idAnime]);
		$recup_cat = $liste_cat->fetchAll();
		return $recup_cat;
	}

	public function updateOnglets($new_name, $recup_cat){
		$update_cat = $this->pdo->prepare("UPDATE categories_mangas_animes SET name_category = :categories WHERE id_category = :idAnime");
		$update_cat->execute(['categories' => $new_name, 'idAnime' => $recup_cat]); 
	}

	public function deleteOnglet($recup_cat, $idAnime){
		$update_cat = $this->pdo->prepare("DELETE FROM categories_mangas_animes WHERE id_category = :idCategory AND id_anime_mangas = :idAnime");
		$update_cat->execute(['idCategory' => $recup_cat, 'idAnime' => $idAnime]); 
	}

	public function verifierArticle(int $idAnime){
		$liste_page = $this->pdo->prepare("SELECT *, P.id AS id_article FROM mangas_animes_articles P INNER JOIN categories_mangas_animes O ON O.id_category = P.id_onglet INNER JOIN mangas_animes ON mangas_animes.id = P.id_anime_mangas WHERE P.id_anime_mangas = :idAnime");
		$liste_page->execute(['idAnime' => $idAnime]);
		$recup_pages = $liste_page->fetchAll();
		return $recup_pages;
	}

	public function supprimerPage(int $idAnime, int $idPage){
		$req2 = $this->pdo->prepare('UPDATE mangas_animes SET nb_article = nb_article - 1 WHERE id = :idAnime');
		$req2->execute(['idAnime' => $idAnime]);
		$req = $this->pdo->prepare('DELETE FROM mangas_animes_articles WHERE id_anime_mangas = :idAnime AND id = :idPage');
		$req->execute(['idAnime' => $idAnime, 'idPage' => $idPage]);
	}

	public function searchIdOnglet(int $idAnime, string $categorie){
		$req = $this->pdo->prepare("SELECT id_category FROM categories_mangas_animes WHERE id_anime_mangas = :idAnime AND name_category = :categorie");
        $req->execute(['idAnime' => $idAnime, 'categorie' => $categorie]);
        $idOnglet = $req->fetch();
        return $idOnglet;
	}

	public function ajouterArticle(int $idAnime, int $idOnglet, string $nameArticle, string $contenu, int $idMember, string $image, string $slug, int $visible){
		$req = $this->pdo->prepare('INSERT INTO mangas_animes_articles(id_anime_mangas, id_onglet, name_article, contenu, id_member, cover_image_article, date_post, slug_article, visible) VALUES(:idAnime, :idOnglet, :nameArticle, :contenu, :idMember, :image, NOW(), :slug, :visible)');
		$req->execute(['idAnime' => $idAnime, 'idOnglet' => $idOnglet, 'nameArticle' => $nameArticle, 'contenu' => $contenu, 'idMember' => $idMember, 'image' => $image, 'slug' => $slug, 'visible' => $visible]);
		$reqDeux = $this->pdo->prepare('UPDATE mangas_animes SET nb_article = nb_article + 1 WHERE id = :idAnime');
		$reqDeux->execute(['idAnime' => $idAnime]);
	}

	public function donneesArticle($idJeu, $idArticle){
		$req = $this->pdo->prepare('SELECT *, mangas_animes_articles.id AS id_article FROM mangas_animes_articles INNER JOIN mangas_animes ON mangas_animes.id = mangas_animes_articles.id_anime_mangas WHERE (mangas_animes.id = :idJeu OR mangas_animes.slug = :idJeu) AND (mangas_animes_articles.id = :idArticle OR slug_article = :idArticle)');
		$req->execute(['idJeu' => $idJeu, 'idArticle' => $idArticle]);
		$article = $req->fetch();
		return $article;
	}

	public function ongletsArticle($idArticle){
		$onglet_exist = $this->pdo->prepare("SELECT * FROM categories_mangas_animes WHERE id_anime_mangas = :idArticle");
		$onglet_exist->execute(['idArticle' => $idArticle]);
		$onglet = $onglet_exist->fetchAll();
		return $onglet;
	}

	public function modifyArticle(string $title, string $image, int $category, int $visibility, string $contenu, string $slug, int $idAnime, int $idArticle){
		$req = $this->pdo->prepare('UPDATE mangas_animes_articles SET name_article = :title, cover_image_article = :image, id_onglet = :category, visible = :visibility, contenu = :contenu, slug_article = :slug WHERE id_anime_mangas = :idAnime AND id = :idArticle');
		$req->execute(['title' => $title, 'image' => $image, 'category' => $category, 'visibility' => $visibility, 'contenu' => $contenu, 'slug' => $slug, 'idAnime' => $idAnime, 'idArticle' => $idArticle]);
	}
}