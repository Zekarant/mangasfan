<?php

namespace models;

class Forum extends Model {

	/** FONCTION QUI RÉCUPERE LES CATÉGEORIES DU FORUM
	* @return array
	*/
	public function recupererCategories(){
		$req = $this->pdo->prepare('SELECT * FROM forum_categories');
		$req->execute();
		$categories = $req->fetchAll();
		return $categories;
	}

	/* FONCTION QUI PERMET D'AJOUTER UN TOPIC
	* @param string $titre
	* @param string $contenu
	*/
	public function ajouterTopic(string $titre, string $contenu){
		$req = $this->pdo->prepare('INSERT INTO f_topics (id_createur, titre, contenu, date_creation, status) VALUES(1, :titre, :contenu, NOW(), 0)');
		$req->execute(['titre' => $titre, 'contenu' => $contenu]);
	}

	public function recupererCategorie(int $id){
		$req = $this->pdo->prepare('SELECT * FROM forum_categories WHERE id = :id');
		$req->execute(['id' => $id]);
		$categorie = $req->fetch();
		return $categorie;
	}

	public function recupererCategorieBySlug(string $id){
		$req = $this->pdo->prepare('SELECT * FROM forum_categories WHERE slug = :id');
		$req->execute(['id' => $id]);
		$categorie = $req->fetch();
		return $categorie;
	}

	public function listerSousCategories(int $id){
		$req = $this->pdo->prepare('SELECT * FROM forum_categories WHERE parents = :id');
		$req->execute(['id' => $id]);
		$sousCategories = "";
		if ($req->rowCount() > 0) {
			$sousCategories = $req->fetchAll();
		}
		return $sousCategories;
	}

	public function listerTopics(int $id){
		$req = $this->pdo->prepare('SELECT * FROM f_topics INNER JOIN forum_categories ON id = id_category WHERE id = :id');
		$req->execute(['id' => $id]);
		$topics = $req->fetchAll();
		return $topics;
	}

	public function recupererTopic(int $idCategory, int $idTopic){
		$req = $this->pdo->prepare('SELECT * FROM f_topics INNER JOIN users ON users.id_user = f_topics.id_createur WHERE id_category = :idCategory AND id_topic = :idTopic');
		$req->execute(['idCategory' => $idCategory, 'idTopic' => $idTopic]);
		$topic = $req->fetch();
		return $topic;
	}

	public function recupererTopicBySlug(string $idCategory, string $idTopic){
		$req = $this->pdo->prepare('
			SELECT * 
			FROM f_topics 
			INNER JOIN users 
			ON users.id_user = f_topics.id_createur
			INNER JOIN 
			forum_categories
			ON forum_categories.id = f_topics.id_category
			WHERE forum_categories.slug = :idCategory AND f_topics.slug = :idTopic');
		$req->execute(['idCategory' => $idCategory, 'idTopic' => $idTopic]);
		$topic = $req->fetch();
		return $topic;
	}

	public function recupererTopicBySlugInt(string $idCategory, int $idTopic){
		$req = $this->pdo->prepare('
			SELECT *
			FROM f_topics 
			INNER JOIN users 
			ON users.id_user = f_topics.id_createur
			INNER JOIN 
			forum_categories
			ON forum_categories.id = f_topics.id_category
			WHERE forum_categories.slug = :idCategory AND f_topics.id_topic = :idTopic');
		$req->execute(['idCategory' => $idCategory, 'idTopic' => $idTopic]);
		$topic = $req->fetch();
		return $topic;
	}

	public function allMessages(int $idTopic){
		$req = $this->pdo->prepare('
			SELECT *, f_messages.contenu AS contenu_message
			FROM f_messages
			INNER JOIN 
			users 
			ON users.id_user = f_messages.id_user
			INNER JOIN f_topics
			ON f_messages.id_topic = f_topics.id_topic
			WHERE f_topics.id_topic = :idTopic');
		$req->execute(['idTopic' => $idTopic]);
		$messages = $req->fetchAll();
		return $messages;
	}

	public function ajouterSection(string $titleSection, string $slug){
		$req = $this->pdo->prepare('INSERT INTO forum_categories(name, parents, slug) VALUES(:titleSection, 0, :slug)');
		$req->execute(['titleSection' => $titleSection, 'slug' => $slug]);
	}

	public function ajouterCategorie(string $titleCategorie, int $parent, string $slug){
		$req = $this->pdo->prepare('INSERT INTO forum_categories(name, parents, slug) VALUES(:titleCategorie, :parent, :slug)');
		$req->execute(['titleCategorie' => $titleCategorie, 'parent' => $parent, 'slug' => $slug]);
	}
}