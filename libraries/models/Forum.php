<?php

namespace models;

class Forum extends Model {


	public function allForums(){
		$req = $this->pdo->prepare('SELECT id, name, f_forums.forum_id, forum_name, forum_description, forum_post, forum_topic, permission, f_topics.id_topic, f_topics.topic_post, id_message, date_created, f_messages.id_user AS id_membre_message, topic_titre, username, users.id_user AS id_utilisateur, grade
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

	public function allSujetsAnnonces(int $idForum, ?int $id = 0){
		$add1 = "";
		$add2 = "";
		if ($id != 0){
			$add1 = ", tv_id, tv_post_id, tv_poste"; 
			$add2 = "LEFT JOIN forum_topic_view 
			ON f_topics.id_topic = forum_topic_view.tv_topic_id AND forum_topic_view.tv_id = :id";
		}
		$req = $this->pdo->prepare('SELECT f_topics.id_topic, topic_titre, topic_createur, topic_vu, topic_post, topic_posted, topic_last_post,
			Mb.username AS membre_pseudo_createur, Mb.id_user AS id_utilisateur_posteur, date_created, Ma.username AS membre_pseudo_last_posteur, Ma.id_user AS id_utilisateur_derniere_reponse, id_message '.$add1.' FROM f_topics 
			LEFT JOIN users Mb ON Mb.id_user = f_topics.topic_createur
			LEFT JOIN f_messages ON f_topics.topic_last_post = f_messages.id_message
			LEFT JOIN users Ma ON Ma.id_user = f_messages.id_user
			'.$add2.' 
			WHERE topic_genre = "annonce" AND f_topics.id_forum = :idForum 
			ORDER BY topic_last_post DESC');
		if ($id != 0) {
			$req->execute(['idForum' => $idForum, 'id' => $id]);
		} else {
			$req->execute(['idForum' => $idForum]);
		}
		$sujets = $req->fetchAll();
		return $sujets;
	}

	public function allSujets(int $idForum, ?int $id = 0){
		$add1 = "";
		$add2 = "";
		if ($id != 0){
			$add1 = ", tv_id, tv_post_id, tv_poste"; 
			$add2 = "LEFT JOIN forum_topic_view 
			ON f_topics.id_topic = forum_topic_view.tv_topic_id AND forum_topic_view.tv_id = :id";
		}
		$req = $this->pdo->prepare('SELECT f_topics.id_topic, topic_titre, topic_createur, topic_vu, topic_post, topic_posted, topic_last_post,
			Mb.username AS membre_pseudo_createur, Mb.id_user AS id_utilisateur_posteur, date_created, Ma.username AS membre_pseudo_last_posteur, Ma.id_user AS id_utilisateur_derniere_reponse, id_message ' . $add1 . ' FROM f_topics 
			LEFT JOIN users Mb ON Mb.id_user = f_topics.topic_createur
			LEFT JOIN f_messages ON f_topics.topic_last_post = f_messages.id_message
			LEFT JOIN users Ma ON Ma.id_user = f_messages.id_user
			'.$add2.'
			WHERE topic_genre != "annonce" AND f_topics.id_forum = :idForum 
			ORDER BY topic_last_post DESC');
		if ($id != 0) {
			$req->execute(['idForum' => $idForum, 'id' => $id]);
		} else {
			$req->execute(['idForum' => $idForum]);
		}
		$sujets = $req->fetchAll();
		return $sujets;
	}

	public function topic(int $idTopic){

		$req = $this->pdo->prepare('UPDATE f_topics SET topic_vu = topic_vu + 1 WHERE id_topic = :idTopic');
		$req->execute(['idTopic' => $idTopic]);

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

		$req = $this->pdo->prepare('UPDATE forum_topic_view SET tv_post_id = :post, tv_poste = 1 WHERE tv_id = :id AND tv_topic_id = :topic');
		$req->execute(['post' => $nouveaupost, 'id' => $idUser, 'topic' => $idTopic]);
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

		$req = $this->pdo->prepare('INSERT INTO forum_topic_view (tv_id, tv_topic_id, tv_forum_id, tv_post_id, tv_poste) VALUES(:id, :topic, :forum, :post, 1)');
		$req->execute(['id' => $idUser, 'topic' => $nouveautopic, 'forum' => $idForum, 'post' => $nouveaupost]);
	}

	public function ajouterSection(string $titleSection){
		$req = $this->pdo->prepare('INSERT INTO forum_categories(name, parents) VALUES(:titleSection, 0)');
		$req->execute(['titleSection' => $titleSection]);
	}

	public function allCategories(){
		$req = $this->pdo->prepare('SELECT * FROM forum_categories ORDER BY id');
		$req->execute();
		$categories = $req->fetchAll();
		return $categories;
	}

	public function addForum($title, $description, $categorie, $permission){
		$req = $this->pdo->prepare('INSERT INTO f_forums(category_id, forum_name, forum_description, forum_last_post_id, forum_post, forum_topic, permission) VALUES(:categorie, :title, :description, 0, 0, 0, :permission)');
		$req->execute(['categorie' => $categorie, 'title' => $title, 'description' => $description, 'permission' => $permission]);
	}

	public function topicVu($idTopic, $idUser){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM forum_topic_view WHERE tv_topic_id = :topic AND tv_id = :id');
		$req->execute(['topic' => $idTopic, 'id' => $idUser]);
		$nbr_vu = $req->fetchColumn();
		return $nbr_vu;
	}

	public function insererVu($idUser, $idTopic, $idForum, $lastPost){
		$req = $this->pdo->prepare('INSERT INTO forum_topic_view (tv_id, tv_topic_id, tv_forum_id, tv_post_id) VALUES (:id, :topic, :forum, :last_post)');
		$req->execute(['id' => $idUser, 'topic' => $idTopic, 'forum' => $idForum, 'last_post' => $lastPost]);
	}

	public function updateVu($idUser, $idTopic, $idForum, $lastPost){
		$req = $this->pdo->prepare('UPDATE forum_topic_view SET tv_post_id = :last_post WHERE tv_forum_id = :forum AND tv_topic_id = :topic AND tv_id = :id');
		$req->execute(['last_post' => $lastPost, 'forum' => $idForum, 'topic' => $idTopic, 'id' => $idUser]);
	}
}