<?php

namespace models;

class RedactionStaff extends Model {

	public function allJeux(?int $limit = 0, ?int $autre = 10){
		$req = $this->pdo->prepare('SELECT * FROM jeux ORDER BY id_jeux DESC LIMIT ' . $limit . ',' . $autre);
		$req->execute();
		$jeux = $req->fetchAll();
		return $jeux;
	}

	public function allMangas(?int $limit = 0, ?int $autre = 10){
		$req = $this->pdo->prepare('SELECT * FROM mangas_animes WHERE type = "manga" ORDER BY id DESC LIMIT ' . $limit . ',' . $autre);
		$req->execute();
		$mangas = $req->fetchAll();
		return $mangas;
	}

	public function allAnimes(?int $limit = 0, ?int $autre = 10){
		$req = $this->pdo->prepare('SELECT * FROM mangas_animes WHERE type = "anime" ORDER BY id DESC LIMIT ' . $limit . ',' . $autre);
		$req->execute();
		$mangas = $req->fetchAll();
		return $mangas;
	}

	public function paginationCountJeux(){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM jeux');
        $req->execute();
        $pagination = $req->fetchColumn();
        return $pagination;
	}

	public function paginationCountMangas(){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM mangas_animes WHERE type = "manga"');
        $req->execute();
        $pagination = $req->fetchColumn();
        return $pagination;
	}

	public function paginationCountAnimes(){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM mangas_animes WHERE type = "anime"');
        $req->execute();
        $pagination = $req->fetchColumn();
        return $pagination;
	}

	public function donneesJeu($idJeu){
		$req = $this->pdo->prepare('SELECT * FROM jeux WHERE (jeux.id_jeux = :idJeu OR jeux.slug = :idJeu)');
		$req->execute(['idJeu' => $idJeu]);
		$jeuConcerne = $req->fetch();
		return $jeuConcerne;
	}

	public function modifierEntete(string $title, string $imagePresentation, string $imageCover, string $slug, int $idJeu){
		$req = $this->pdo->prepare('UPDATE jeux SET name_jeu = :title, banniere_jeu = :imagePresentation, cover_jeu = :imageCover, slug = :slug WHERE id_jeux = :idJeu');
		$req->execute(['title' => $title, 'imagePresentation' => $imagePresentation, 'imageCover' => $imageCover, 'slug' => $slug, 'idJeu' => $idJeu]);
	}

	public function modifierDescription(string $description, int $idJeu){
		$req = $this->pdo->prepare('UPDATE jeux SET description_jeu = :description WHERE id_jeux = :idJeu');
		$req->execute(['description' => $description, 'idJeu' => $idJeu]);
	}

	public function listeOnglets(int $idJeu){
		$req = $this->pdo->prepare('SELECT * FROM categories_jeux INNER JOIN jeux ON id_jeu = id_jeux WHERE id_jeu = :idJeu ORDER BY id_category');
		$req->execute(['idJeu' => $idJeu]);
		$onglets = $req->fetchAll(); 
		return $onglets;
	}

	public function countOnglets(int $idJeu){
		$req = $this->pdo->prepare('SELECT count(*) FROM categories_jeux WHERE id_jeu = :idJeu ORDER BY id_category');
		$req->execute(['idJeu' => $idJeu]);
		return $req;
	}

	public function ongletArticle(int $idJeu){
		$liste_cat = $this->pdo->prepare("SELECT * FROM categories_jeux WHERE id_jeu = :idJeu ORDER BY id_category");
		$liste_cat->execute(['idJeu' => $idJeu]);
		$recup_cat = $liste_cat->fetchAll();
		return $recup_cat;
	}

	public function updateOnglets($new_name, $recup_cat){
		$update_cat = $this->pdo->prepare("UPDATE categories_jeux SET name_category = :categories WHERE id_category = :idJeu");
		$update_cat->execute(['categories' => $new_name, 'idJeu' => $recup_cat]); 
	}

	public function insererOnglet(int $idJeu, string $name){
		$req = $this->pdo->prepare('INSERT INTO categories_jeux(id_jeu, name_category) VALUES(:idJeu, :name)');
		$req->execute(['idJeu' => $idJeu, 'name' => $name]);
	}

	public function articles(int $idJeu){
		$req = $this->pdo->prepare("SELECT * FROM jeux_articles INNER JOIN jeux ON jeux.id_jeux = jeux_articles.id_jeux WHERE jeux_articles.id_jeux = :idJeu");
		$req->execute(['idJeu' => $idJeu]);
		$pages = $req->fetchAll();
		return $pages;
	}

	public function searchIdOnglet(int $idJeu, string $categorie){
		$req = $this->pdo->prepare("SELECT id_category FROM categories_jeux WHERE id_jeu = :idJeu AND name_category = :categorie");
        $req->execute(['idJeu' => $idJeu, 'categorie' => $categorie]);
        $idOnglet = $req->fetch();
        return $idOnglet;
	}

	public function ajouterArticle(int $idJeu, int $idOnglet, string $nameArticle, string $contenu, int $idMember, string $image, string $slug, int $visible){
		$req = $this->pdo->prepare('INSERT INTO jeux_articles(id_jeux, id_onglet, name_article, contenu_article, id_member, cover_image_article, date_post, slug_article, visible) VALUES(:idJeu, :idOnglet, :nameArticle, :contenu, :idMember, :image, NOW(), :slug, :visible)');
		$req->execute(['idJeu' => $idJeu, 'idOnglet' => $idOnglet, 'nameArticle' => $nameArticle, 'contenu' => $contenu, 'idMember' => $idMember, 'image' => $image, 'slug' => $slug, 'visible' => $visible]);
		$reqDeux = $this->pdo->prepare('UPDATE jeux SET nb_article = nb_article + 1 WHERE id_jeux = :idJeu');
		$reqDeux->execute(['idJeu' => $idJeu]);
	}

	public function verifierArticle(int $idJeu){
		$liste_page = $this->pdo->prepare("SELECT * FROM jeux_articles P INNER JOIN categories_jeux O ON O.id_category = P.id_onglet INNER JOIN jeux ON jeux.id_jeux = P.id_jeux WHERE P.id_jeux = :idJeu");
		$liste_page->execute(['idJeu' => $idJeu]);
		$recup_pages = $liste_page->fetchAll();
		return $recup_pages;
	}

	public function supprimerPage(int $idJeu, int $idPage){
		$req2 = $this->pdo->prepare('UPDATE jeux SET nb_article = nb_article - 1 WHERE id_jeux = :idJeu');
		$req2->execute(['idJeu' => $idJeu]);
		$req = $this->pdo->prepare('DELETE FROM jeux_articles WHERE id_jeux = :idJeu AND id_article = :idPage');
		$req->execute(['idJeu' => $idJeu, 'idPage' => $idPage]);
	}

	public function deleteOnglet($recup_cat, $idJeu){
		$update_cat = $this->pdo->prepare("DELETE FROM categories_jeux WHERE id_category = :idCategory AND id_jeu = :idJeu");
		$update_cat->execute(['idCategory' => $recup_cat, 'idJeu' => $idJeu]); 
	}

	public function donneesArticle($idJeu, $idArticle){
		$req = $this->pdo->prepare('SELECT *, jeux.id_jeux AS id_jeu FROM jeux_articles INNER JOIN jeux ON jeux.id_jeux = jeux_articles.id_jeux WHERE (jeux.id_jeux = :idJeu OR jeux.slug = :idJeu) AND (id_article = :idArticle OR slug_article = :idArticle)');
		$req->execute(['idJeu' => $idJeu, 'idArticle' => $idArticle]);
		$article = $req->fetch();
		return $article;
	}

	public function ongletsArticle($idArticle){
		$onglet_exist = $this->pdo->prepare("SELECT * FROM categories_jeux WHERE id_jeu = :idArticle");
		$onglet_exist->execute(['idArticle' => $idArticle]);
		$onglet = $onglet_exist->fetchAll();
		return $onglet;
	}

	public function modifyArticle(string $title, string $image, int $category, int $visibility, string $contenu, string $slug, int $idJeu, int $idArticle){
		$req = $this->pdo->prepare('UPDATE jeux_articles SET name_article = :title, cover_image_article = :image, id_onglet = :category, visible = :visibility, contenu_article = :contenu, slug_article = :slug WHERE id_jeux = :idJeu AND id_article = :idArticle');
		$req->execute(['title' => $title, 'image' => $image, 'category' => $category, 'visibility' => $visibility, 'contenu' => $contenu, 'slug' => $slug, 'idJeu' => $idJeu, 'idArticle' => $idArticle]);
	}

	public function ajouterJeu(string $titre, string $cover, string $banniere, string $presentation, string $slug){
		$req = $this->pdo->prepare('INSERT INTO jeux(name_jeu, cover_jeu, banniere_jeu, description_jeu, date_ajout, slug, nb_article) VALUES(:titre, :cover, :banniere, :presentation, NOW(), :slug, 0)');
		$req->execute(['titre' => $titre, 'cover' => $cover, 'banniere' => $banniere, 'presentation' => $presentation, 'slug' => $slug]);
	}

	public function supprimerJeu(int $idJeu){
		$req = $this->pdo->prepare('DELETE FROM jeux WHERE id_jeux = :idJeu');
		$req->execute(['idJeu' => $idJeu]);
	}
}