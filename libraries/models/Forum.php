<?php

namespace models;

class Forum extends Model {


	public function allForums(){
		$req = $this->pdo->prepare('SELECT id, name,
			f_forums.forum_id, forum_name, forum_description, forum_post, forum_topic, f_topics.id_topic, f_topics.topic_post, id_message, date_created, f_messages.id_user AS id_membre_message, topic_titre, username, users.id_user AS id_utilisateur, grade
			FROM forum_categories
			INNER JOIN f_forums ON forum_categories.id = f_forums.category_id
			LEFT JOIN f_messages ON f_messages.id_message = f_forums.forum_last_post_id
			LEFT JOIN f_topics ON f_topics.id_topic = f_messages.id_topic
			LEFT JOIN users ON users.id_user = f_messages.id_user

			ORDER BY id, forum_id');
		$req->execute();
		$categories = $req->fetchAll();
		return $categories;
	}

	public function allSousForums(int $idForum){
		$req = $this->pdo->prepare('SELECT forum_name, forum_topic FROM f_forums WHERE forum_id = :idForum');
		$req->execute(['idForum' => $idForum]);
		$sousForums = $req->fetch();
		return $sousForums;
	}

	public function allSujetsAnnonces(int $idForum){
		$req = $this->pdo->prepare('SELECT f_topics.id_topic, topic_titre, topic_createur, topic_vu, topic_post, topic_posted, topic_last_post,
		Mb.username AS membre_pseudo_createur, Mb.id_user AS id_utilisateur_posteur, date_created, Ma.username AS membre_pseudo_last_posteur, Ma.id_user AS id_utilisateur_derniere_reponse, id_message FROM f_topics 
		LEFT JOIN users Mb ON Mb.id_user = f_topics.topic_createur
		LEFT JOIN f_messages ON f_topics.topic_last_post = f_messages.id_message
		LEFT JOIN users Ma ON Ma.id_user = f_messages.id_user    
		WHERE topic_genre = "annonce" AND f_topics.id_forum = :idForum 
		ORDER BY topic_last_post DESC');
		$req->execute(['idForum' => $idForum]);
		$sujets = $req->fetchAll();
		return $sujets;
	}

	public function allSujets(int $idForum){
		$req = $this->pdo->prepare('SELECT f_topics.id_topic, topic_titre, topic_createur, topic_vu, topic_post, topic_posted, topic_last_post,
		Mb.username AS membre_pseudo_createur, Mb.id_user AS id_utilisateur_posteur, date_created, Ma.username AS membre_pseudo_last_posteur, Ma.id_user AS id_utilisateur_derniere_reponse, id_message FROM f_topics 
		LEFT JOIN users Mb ON Mb.id_user = f_topics.topic_createur
		LEFT JOIN f_messages ON f_topics.topic_last_post = f_messages.id_message
		LEFT JOIN users Ma ON Ma.id_user = f_messages.id_user    
		WHERE topic_genre != "annonce" AND f_topics.id_forum = :idForum 
		ORDER BY topic_last_post DESC');
		$req->execute(['idForum' => $idForum]);
		$sujets = $req->fetchAll();
		return $sujets;
	}

	public function topic(int $idTopic){
		$req = $this->pdo->prepare('SELECT topic_titre, topic_post, f_topics.id_forum, topic_last_post, forum_name
		FROM f_topics 
		LEFT JOIN f_forums ON f_topics.id_forum = f_forums.forum_id 
		WHERE id_topic = :idTopic');
		$req->execute(['idTopic' => $idTopic]);
		$topic = $req->fetch();
		return $topic;
	}

	public function allMessages($idTopic){
		$req = $this->pdo->prepare('SELECT id_message, f_messages.id_user AS id_utilisateur_message, contenu, date_created,
		users.id_user AS id_utilisateur, username, grade, sexe, stagiaire, chef, manga, anime, confirmed_at, avatar, points, nb_messages
		FROM f_messages
		LEFT JOIN users ON users.id_user = f_messages.id_user
		WHERE id_topic = :idTopic
		ORDER BY id_message');
		$req->execute(['idTopic' => $idTopic]);
		$messages = $req->fetchAll();
		return $messages;
	}

	public function ajouterMessage($idTopic, $utilisateur, $message, $forum){
		//On récupère l'id du forum
        $req = $this->pdo->prepare('SELECT id_forum, topic_post FROM f_topics WHERE id_topic = :idTopic');
        $req->execute(['idTopic' => $idTopic]);
        $data = $req->fetch();
        $forum = $data['id_forum'];

        //Puis on entre le message
        $req = $this->pdo->prepare('INSERT INTO f_messages
        (id_topic, id_user, contenu, date_created, date_edition)
        VALUES(:idTopic, :utilisateur, :contenu, NOW(), NOW())');
        $req->execute(['idTopic' => $idTopic, 'utilisateur' => $utilisateur, 'contenu' => $message]);
        $nouveaupost = $this->pdo->lastInsertId();

        //On change un peu la table forum_topic
        $req = $this->pdo->prepare('UPDATE f_topics SET topic_post = topic_post + 1, topic_last_post = :nouveaupost WHERE id_topic = :idTopic');
        $req->execute(['nouveaupost' => $nouveaupost, 'idTopic' => $idTopic]);

        //Puis même combat sur les 2 autres tables
        $req = $this->pdo->prepare('UPDATE f_forums SET forum_post = forum_post + 1 , forum_last_post_id = :nouveaupost WHERE forum_id = :forum');
        $req->execute(['nouveaupost' => $nouveaupost, 'forum' => $forum]);

        $req = $this->pdo->prepare('UPDATE users SET nb_messages = nb_messages + 1 WHERE id_user = :utilisateur'); 
        $req->execute(['utilisateur' => $utilisateur]);

	}

	public function ajouterTopic($title, $type, $idForum, $idUser, $message){
		 $req = $this->pdo->prepare('INSERT INTO f_topics
        (id_forum, topic_titre, topic_createur, topic_vu, topic_posted, topic_last_post, topic_genre, topic_post)
        VALUES(:idForum, :title, :idUser, 1, NOW(), 0, :type, 0)');
        $req->execute(['idForum' => $idForum, 'title' => $title, 'idUser' => $idUser, 'type' => $type]);
        $nouveautopic = $this->pdo->lastInsertId();

        $req = $this->pdo->prepare('INSERT INTO f_messages
        (id_topic, id_user, contenu, date_created, date_edition)
        VALUES (:idTopic, :idUser, :message, NOW(), NOW())');
        $req->execute(['idTopic' => $nouveautopic, 'idUser' => $idUser, 'message' => $message]);
        $nouveaupost = $this->pdo->lastInsertId();


        $req = $this->pdo->prepare('UPDATE f_topics
        SET topic_last_post = :nouveaupost, topic_post = 1
        WHERE id_topic = :nouveautopic');
        $req->execute(['nouveaupost' => $nouveaupost, 'nouveautopic' => $nouveautopic]);

        //Enfin on met à jour les tables forum_forum et forum_membres
        $req = $this->pdo->prepare('UPDATE f_forums SET forum_post = forum_post + 1, forum_topic = forum_topic + 1, 
        forum_last_post_id = :nouveaupost
        WHERE forum_id = :forum');
        $req->execute(['nouveaupost' => $nouveaupost, 'forum' => $idForum]);
    
        $req = $this->pdo->prepare('UPDATE users SET nb_messages = nb_messages + 1 WHERE id_user = :id');    
        $req->execute(['id' => $idUser]);
	}

	public function ajouterSection(string $titleSection){
		$req = $this->pdo->prepare('INSERT INTO forum_categories(name, parents) VALUES(:titleSection, 0)');
		$req->execute(['titleSection' => $titleSection]);
	}


// 	/* FONCTION QUI PERMET D'AJOUTER UN TOPIC
// 	* @param string $titre
// 	* @param string $contenu
// 	*/
// 	public function ajouterTopic(string $titre, string $contenu){
// 		$req = $this->pdo->prepare('INSERT INTO f_topics (id_createur, titre, contenu, date_creation, status) VALUES(1, :titre, :contenu, NOW(), 0)');
// 		$req->execute(['titre' => $titre, 'contenu' => $contenu]);
// 	}

// 	public function recupererCategorie(int $id){
// 		$req = $this->pdo->prepare('SELECT * FROM forum_categories WHERE id = :id');
// 		$req->execute(['id' => $id]);
// 		$categorie = $req->fetch();
// 		return $categorie;
// 	}

// 	public function recupererCategorieBySlug(string $id){
// 		$req = $this->pdo->prepare('SELECT * FROM forum_categories WHERE slug = :id');
// 		$req->execute(['id' => $id]);
// 		$categorie = $req->fetch();
// 		return $categorie;
// 	}

// 	public function listerSousCategories(int $id){
// 		$req = $this->pdo->prepare('SELECT * FROM forum_categories WHERE parents = :id');
// 		$req->execute(['id' => $id]);
// 		$sousCategories = "";
// 		if ($req->rowCount() > 0) {
// 			$sousCategories = $req->fetchAll();
// 		}
// 		return $sousCategories;
// 	}

// 	public function compterMessages(int $categorie){
// 		$req = $this->pdo->prepare('SELECT * FROM f_messages INNER JOIN f_topics ON f_topics.id_topic = f_messages.id_topic WHERE f_topics.id_category = :categorie');
// 		$req->execute(['categorie' => $categorie]);
// 		return $req->rowCount();
// 	}

// 	public function compterTopicsAccueil(int $idCategory) {
// 		$nbr = $this->pdo->prepare('SELECT * FROM forum_categories INNER JOIN f_topics ON f_topics.id_category = forum_categories.id WHERE forum_categories.id = :idCategory OR forum_categories.parents = :idCategory');
// 		$nbr->execute(['idCategory' => $idCategory]);
// 		return $nbr->rowCount();
// 	}

// 	public function chercherDernierMember(int $idCategory) {
// 		$req = $this->pdo->prepare('SELECT *, CONCAT(date_created, date_creation) AS dates 
// 			FROM f_messages, f_topics, forum_categories, users
// 			WHERE f_messages.id_user = users.id_user
// 			AND f_topics.id_category = :idCategory
// 			GROUP BY id_message
// 			ORDER BY dates DESC');
// 		$req->execute(['idCategory' => $idCategory]);
// 		$dernierMembre = $req->fetch();
// 		return $dernierMembre;
// 	}



// 	public function compterMessagesAccueil(int $categorie){
// 		$req = $this->pdo->prepare('SELECT * FROM f_messages INNER JOIN f_topics ON f_topics.id_topic = f_messages.id_topic WHERE f_topics.id_category = :categorie AND forum_categories.parents = :categorie');
// 		$req->execute(['categorie' => $categorie]);
// 		return $req->rowCount();
// 	}

// 	public function nombreTopics(int $idCategory){
// 		$nbr = $this->pdo->prepare('SELECT * FROM f_topics INNER JOIN forum_categories ON forum_categories.id = f_topics.id_category WHERE forum_categories.id = :idCategory');
// 		$nbr->execute(['idCategory' => $idCategory]);
// 		return $nbr->rowCount();
// 	}

// 	public function dernierMessage(int $categorie){
// 		$req = $this->pdo->prepare('SELECT * FROM f_messages INNER JOIN f_topics ON f_topics.id_topic = f_messages.id_topic INNER JOIN users ON users.id_user = f_messages.id_user WHERE f_topics.id_category = :categorie ORDER BY id_message DESC');
// 		$req->execute(['categorie' => $categorie]);
// 		$user = $req->fetch();
// 		return $user;
// 	}

// 	public function listerTopics(int $id){
// 		$req = $this->pdo->prepare('SELECT * FROM f_topics INNER JOIN forum_categories ON id = id_category INNER JOIN users ON id_user = id_createur  WHERE id = :id');
// 		$req->execute(['id' => $id]);
// 		$topics = $req->fetchAll();
// 		return $topics;
// 	}

// 	public function recupererTopic(int $idCategory, int $idTopic){
// 		$req = $this->pdo->prepare('SELECT * FROM f_topics INNER JOIN users ON users.id_user = f_topics.id_createur WHERE id_category = :idCategory AND id_topic = :idTopic');
// 		$req->execute(['idCategory' => $idCategory, 'idTopic' => $idTopic]);
// 		$topic = $req->fetch();
// 		return $topic;
// 	}

// 	public function recupererTopicBySlug(string $idCategory, string $idTopic){
// 		$req = $this->pdo->prepare('
// 			SELECT *
// 			FROM f_topics 
// 			INNER JOIN users 
// 			ON users.id_user = f_topics.id_createur
// 			INNER JOIN 
// 			forum_categories
// 			ON forum_categories.id = f_topics.id_category
// 			WHERE forum_categories.slug = :idCategory AND f_topics.slug_topic = :idTopic');
// 		$req->execute(['idCategory' => $idCategory, 'idTopic' => $idTopic]);
// 		$topic = $req->fetch();
// 		return $topic;
// 	}

// 	public function recupererTopicBySlugInt(string $idCategory, int $idTopic){
// 		$req = $this->pdo->prepare('
// 			SELECT *
// 			FROM f_topics 
// 			INNER JOIN users 
// 			ON users.id_user = f_topics.id_createur
// 			INNER JOIN 
// 			forum_categories
// 			ON forum_categories.id = f_topics.id_category
// 			WHERE forum_categories.slug = :idCategory AND f_topics.id_topic = :idTopic');
// 		$req->execute(['idCategory' => $idCategory, 'idTopic' => $idTopic]);
// 		$topic = $req->fetch();
// 		return $topic;
// 	}

// 	public function allMessages(int $idTopic){
// 		$req = $this->pdo->prepare('
// 			SELECT *, f_messages.contenu AS contenu_message
// 			FROM f_messages
// 			INNER JOIN 
// 			users 
// 			ON users.id_user = f_messages.id_user
// 			INNER JOIN f_topics
// 			ON f_messages.id_topic = f_topics.id_topic
// 			WHERE f_topics.id_topic = :idTopic');
// 		$req->execute(['idTopic' => $idTopic]);
// 		$messages = $req->fetchAll();
// 		return $messages;
// 	}

// 	public function ajouterCategorie(string $titleCategorie, int $parent, string $slug){
// 		$req = $this->pdo->prepare('INSERT INTO forum_categories(name, parents, slug) VALUES(:titleCategorie, :parent, :slug)');
// 		$req->execute(['titleCategorie' => $titleCategorie, 'parent' => $parent, 'slug' => $slug]);
// 	}
}