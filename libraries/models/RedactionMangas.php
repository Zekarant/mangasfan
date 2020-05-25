<?php

namespace models;

class RedactionMangas extends Model {

	public function donneesManga($idManga){
		$req = $this->pdo->prepare('SELECT * FROM mangas_animes WHERE (id = :idManga OR slug = :idManga)');
		$req->execute(['idManga' => $idManga]);
		$manga = $req->fetch();
		return $manga;
	}

	public function modifierEntete(string $title, string $imagePresentation, string $imageCover, string $type, string $slug, int $idAnime){
		$req = $this->pdo->prepare('UPDATE mangas_animes SET titre = :title, banniere = :imagePresentation, cover = :imageCover, type = :type, slug = :slug WHERE id = :idAnime');
		$req->execute(['title' => $title, 'imagePresentation' => $imagePresentation, 'imageCover' => $imageCover, 'type' => $type, 'slug' => $slug, 'idAnime' => $idAnime]);
	}

	public function modifierDescription(string $description, int $idManga){
		$req = $this->pdo->prepare('UPDATE mangas_animes SET presentation = :description WHERE id = :idManga');
		$req->execute(['description' => $description, 'idManga' => $idManga]);
	}

	public function listeOnglets(int $idManga){
		$req = $this->pdo->prepare('SELECT * FROM categories_mangas_animes INNER JOIN mangas_animes ON 	id_anime_mangas = id WHERE 	id_anime_mangas = :idManga ORDER BY id_category');
		$req->execute(['idManga' => $idManga]);
		$onglets = $req->fetchAll(); 
		return $onglets;
	}

	public function countOnglets(int $idManga){
		$req = $this->pdo->prepare('SELECT count(*) FROM categories_mangas_animes WHERE id_anime_mangas = :idManga ORDER BY id_category');
		$req->execute(['idManga' => $idManga]);
		return $req;
	}

	public function insererOnglet(int $idManga, string $name){
		$req = $this->pdo->prepare('INSERT INTO categories_mangas_animes(id_anime_mangas, name_category) VALUES(:idManga, :name)');
		$req->execute(['idManga' => $idManga, 'name' => $name]);
	}

	public function articles(int $idManga){
		$req = $this->pdo->prepare("SELECT *, mangas_animes_articles.id AS id_article FROM mangas_animes_articles INNER JOIN mangas_animes ON mangas_animes.id = mangas_animes_articles.id_anime_mangas WHERE mangas_animes_articles.id_anime_mangas = :idManga");
		$req->execute(['idManga' => $idManga]);
		$pages = $req->fetchAll();
		return $pages;
	}

	public function ongletArticle(int $idManga){
		$liste_cat = $this->pdo->prepare("SELECT * FROM categories_mangas_animes WHERE id_anime_mangas = :idManga ORDER BY id_category");
		$liste_cat->execute(['idManga' => $idManga]);
		$recup_cat = $liste_cat->fetchAll();
		return $recup_cat;
	}

	public function updateOnglets($new_name, $recup_cat){
		$update_cat = $this->pdo->prepare("UPDATE categories_mangas_animes SET name_category = :categories WHERE id_category = :idManga");
		$update_cat->execute(['categories' => $new_name, 'idManga' => $recup_cat]); 
	}

	public function deleteOnglet($recup_cat, $idManga){
		$update_cat = $this->pdo->prepare("DELETE FROM categories_mangas_animes WHERE id_category = :idCategory AND id_anime_mangas = :idManga");
		$update_cat->execute(['idCategory' => $recup_cat, 'idManga' => $idManga]); 
	}

	public function verifierArticle(int $idManga){
		$liste_page = $this->pdo->prepare("SELECT *, P.id AS id_article FROM mangas_animes_articles P INNER JOIN categories_mangas_animes O ON O.id_category = P.id_onglet INNER JOIN mangas_animes ON mangas_animes.id = P.id_anime_mangas WHERE P.id_anime_mangas = :idManga");
		$liste_page->execute(['idManga' => $idManga]);
		$recup_pages = $liste_page->fetchAll();
		return $recup_pages;
	}

	public function supprimerPage(int $idManga, int $idPage){
		$req2 = $this->pdo->prepare('UPDATE mangas_animes SET nb_article = nb_article - 1 WHERE id = :idManga');
		$req2->execute(['idManga' => $idManga]);
		$req = $this->pdo->prepare('DELETE FROM mangas_animes_articles WHERE id_anime_mangas = :idManga AND id = :idPage');
		$req->execute(['idManga' => $idManga, 'idPage' => $idPage]);
	}

	public function searchIdOnglet(int $idManga, string $categorie){
		$req = $this->pdo->prepare("SELECT id_category FROM categories_mangas_animes WHERE id_anime_mangas = :idManga AND name_category = :categorie");
        $req->execute(['idManga' => $idManga, 'categorie' => $categorie]);
        $idOnglet = $req->fetch();
        return $idOnglet;
	}

	public function ajouterArticle(int $idManga, int $idOnglet, string $nameArticle, string $contenu, int $idMember, string $image, string $slug, int $visible){
		$req = $this->pdo->prepare('INSERT INTO mangas_animes_articles(id_anime_mangas, id_onglet, name_article, contenu, id_member, cover_image_article, date_post, slug_article, visible) VALUES(:idManga, :idOnglet, :nameArticle, :contenu, :idMember, :image, NOW(), :slug, :visible)');
		$req->execute(['idManga' => $idManga, 'idOnglet' => $idOnglet, 'nameArticle' => $nameArticle, 'contenu' => $contenu, 'idMember' => $idMember, 'image' => $image, 'slug' => $slug, 'visible' => $visible]);
		$reqDeux = $this->pdo->prepare('UPDATE mangas_animes SET nb_article = nb_article + 1 WHERE id = :idManga');
		$reqDeux->execute(['idManga' => $idManga]);
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

	public function modifyArticle(string $title, string $image, int $category, int $visibility, string $contenu, string $slug, int $idManga, int $idArticle){
		$req = $this->pdo->prepare('UPDATE mangas_animes_articles SET name_article = :title, cover_image_article = :image, id_onglet = :category, visible = :visibility, contenu = :contenu, slug_article = :slug WHERE id_anime_mangas = :idManga AND id = :idArticle');
		$req->execute(['title' => $title, 'image' => $image, 'category' => $category, 'visibility' => $visibility, 'contenu' => $contenu, 'slug' => $slug, 'idManga' => $idManga, 'idArticle' => $idArticle]);
	}

	public function ajouterManga(string $titre, string $cover, string $banniere, string $presentation, string $type, string $slug){
		$req = $this->pdo->prepare('INSERT INTO mangas_animes(titre, cover, banniere, presentation, type, date_creation, slug, nb_article) VALUES(:titre, :cover, :banniere, :presentation, :type, NOW(), :slug, 0)');
		$req->execute(['titre' => $titre, 'cover' => $cover, 'banniere' => $banniere, 'presentation' => $presentation, 'type' => $type, 'slug' => $slug]);
	}

	public function supprimerManga(int $idManga){
		$req = $this->pdo->prepare('DELETE FROM mangas_animes WHERE id = :idManga');
		$req->execute(['idManga' => $idManga]);
	}
}