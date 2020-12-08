<?php

namespace models;

class Forum extends Model {


	public function allForums(?int $id = 0){
		$add1 = "";
		$add2 = "";
		if ($id != 0){
			$add1 = ', tv_id, tv_post_id, tv_forum_id, tv_poste'; 
			$add2 = 'LEFT JOIN forum_topic_view 
			ON f_forums.forum_id = forum_topic_view.tv_forum_id AND forum_topic_view.tv_id = :id';
		}
		$req = $this->pdo->prepare('SELECT DISTINCT forum_id, id, name, f_forums.forum_id, forum_name, forum_description, forum_post, forum_topic, permission, forum_locked, f_topics.id_topic, forum_last_post_id, topic_last_post,f_topics.topic_post, id_message, date_created, f_messages.id_user AS id_membre_message, topic_titre, username, users.id_user AS id_utilisateur, grade '.$add1.'
			FROM forum_categories
			INNER JOIN f_forums ON forum_categories.id = f_forums.category_id
			LEFT JOIN f_messages ON f_messages.id_message = f_forums.forum_last_post_id
			LEFT JOIN f_topics ON f_topics.id_topic = f_messages.id_topic
			LEFT JOIN users ON users.id_user = f_messages.id_user
			'.$add2.'
			GROUP BY id, forum_id
			ORDER BY id, forum_id');
		if ($id != 0) {
			$req->execute(['id' => $id]);
		} else {
			$req->execute();
		}
		$categories = $req->fetchAll();
		return $categories;
	}

	public function chercher($id){
		$req = $this->pdo->prepare('SELECT SUM(tv_poste) FROM forum_topic_view WHERE tv_forum_id = :id');
		$req->execute(['id' => $id]);
		$test = $req->fetch();
		return $test;
	}

	public function allSousForums(int $idForum){
		$req = $this->pdo->prepare('SELECT forum_name, forum_topic, forum_locked FROM f_forums WHERE forum_id = :idForum');
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
		$req = $this->pdo->prepare('SELECT f_topics.id_topic, topic_titre, topic_createur, topic_vu, topic_post, topic_posted, topic_last_post, topic_locked,
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
		$req = $this->pdo->prepare('SELECT f_topics.id_topic, topic_titre, topic_createur, topic_vu, topic_post, topic_posted, topic_last_post, topic_locked,
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

		$req = $this->pdo->prepare('SELECT id_topic, topic_titre, topic_post, f_topics.id_forum, topic_last_post, topic_locked, forum_name, forum_locked
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
			(id_topic, id_forum, id_user, contenu, date_created, date_edition)
			VALUES(:idTopic, :idForum, :utilisateur, :contenu, NOW(), NOW())');
		$req->execute(['idTopic' => $idTopic, 'idForum' => $forum, 'utilisateur' => $utilisateur, 'contenu' => $message]);
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

	public function ajouterTopic($title, $type, $idForum, $idUser, $message, $status){
		$req = $this->pdo->prepare('INSERT INTO f_topics
			(id_forum, topic_titre, topic_createur, topic_vu, topic_posted, topic_first_post, topic_last_post, topic_genre, topic_post, topic_locked)
			VALUES(:idForum, :title, :idUser, 1, NOW(), 0, 0, :type, 0, :locked)');
		$req->execute(['idForum' => $idForum, 'title' => $title, 'idUser' => $idUser, 'type' => $type, 'locked' => $status]);
		$nouveautopic = $this->pdo->lastInsertId();

		$req = $this->pdo->prepare('INSERT INTO f_messages
			(id_topic, id_forum, id_user, contenu, date_created, date_edition)
			VALUES (:idTopic, :idForum, :idUser, :message, NOW(), NOW())');
		$req->execute(['idTopic' => $nouveautopic,'idForum' => $idForum, 'idUser' => $idUser, 'message' => $message]);
		$nouveaupost = $this->pdo->lastInsertId();


		$req = $this->pdo->prepare('UPDATE f_topics
			SET topic_first_post = :nouveaupost, topic_last_post = :nouveaupost, topic_post = 1
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

	public function addForum($title, $description, $categorie, $permission, $status){
		$req = $this->pdo->prepare('INSERT INTO f_forums(category_id, forum_name, forum_description, forum_last_post_id, forum_post, forum_topic, permission, forum_locked) VALUES(:categorie, :title, :description, 0, 0, 0, :permission, :status)');
		$req->execute(['categorie' => $categorie, 'title' => $title, 'description' => $description, 'permission' => $permission, 'status' => $status]);
	}

	public function topicVu($idTopic, $idUser){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM forum_topic_view WHERE tv_topic_id = :topic AND tv_id = :id');
		$req->execute(['topic' => $idTopic, 'id' => $idUser]);
		$nbr_vu = $req->fetch();
		return $nbr_vu;
	}

	public function insererVu($idUser, $idTopic, $idForum, $lastPost){
		$req = $this->pdo->prepare('INSERT INTO forum_topic_view (tv_id, tv_topic_id, tv_forum_id, tv_post_id, tv_poste) VALUES (:id, :topic, :forum, :last_post, 1)');
		$req->execute(['id' => $idUser, 'topic' => $idTopic, 'forum' => $idForum, 'last_post' => $lastPost]);
	}

	public function updateVu($idUser, $idTopic, $idForum, $lastPost){
		$req = $this->pdo->prepare('UPDATE forum_topic_view SET tv_poste = 0, tv_post_id = :last_post WHERE tv_forum_id = :forum AND tv_topic_id = :topic AND tv_id = :id');
		$req->execute(['last_post' => $lastPost, 'forum' => $idForum, 'topic' => $idTopic, 'id' => $idUser]);
	}

	public function searchTopic(int $idTopic, int $idMessage){
		$req = $this->pdo->prepare('SELECT * FROM f_topics INNER JOIN f_messages ON f_topics.id_topic = f_messages.id_topic LEFT JOIN f_forums ON f_forums.forum_id = f_topics.id_forum WHERE f_topics.id_topic = :idTopic AND id_message = :idMessage');
		$req->execute(['idTopic' => $idTopic, 'idMessage' => $idMessage]);
		$topic = $req->fetch();
		return $topic;
	}

	public function modifierMessage(string $contenu, int $topic, int $user){
		$req = $this->pdo->prepare('UPDATE f_messages SET contenu = :contenu WHERE id_topic = :topic AND id_user = :user');
		$req->execute(['contenu' => $contenu, 'topic' => $topic, 'user' => $user]);
	}

	public function supprimerTopic(int $idTopic){
		$req = $this->pdo->prepare('SELECT * FROM f_topics LEFT JOIN f_forums ON f_topics.id_forum = f_forums.forum_id
			WHERE f_topics.id_topic = :topic');
		$req->execute(['topic' => $idTopic]);
		$data = $req->fetch();
		$forum = $data['id_forum'];

		$topics = $this->pdo->prepare('SELECT topic_post FROM f_topics WHERE id_topic = :topic');
		$topics->execute(['topic' => $idTopic]);
		$nombreTopics = $topics->fetch();
		$nombrepost = $nombreTopics['topic_post'];

		$suppression = $this->pdo->prepare('DELETE FROM f_topics WHERE id_topic = :topic');
		$suppression->execute(['topic' => $idTopic]); 

		$query = $this->pdo->prepare('SELECT id_user, COUNT(*) AS nombre_mess FROM f_messages WHERE id_topic = :topic GROUP BY id_user');
		$query->execute(['topic' => $idTopic]);
		while($data = $query->fetch()){
			$req2 = $this->pdo->prepare('UPDATE users SET nb_messages = nb_messages - :mess WHERE id_user = :id');
			$req2->execute(['mess' => $data['nombre_mess'], 'id' => $data['id_user']]);
		}

		$suppressionForum = $this->pdo->prepare('DELETE FROM f_messages WHERE id_topic = :topic');
		$suppressionForum->execute(['topic' => $idTopic]);

		$recupererPost = $this->pdo->prepare('SELECT id_message FROM f_messages WHERE id_forum = :forum ORDER BY id_message DESC');
		$recupererPost->execute(['forum' => $forum]);
		$data1 = $recupererPost->fetch();
		if (!empty($data1)) {
			$message = $data1['id_message'];
		} else {
			$message = 0;
		}
		$maj = $this->pdo->prepare('UPDATE f_forums SET forum_topic = forum_topic - 1, forum_post = forum_post - :nbr, forum_last_post_id = :id WHERE forum_id = :forum');
		$maj->execute(['nbr' => $nombrepost, 'id' => $message, 'forum' => $forum]);   
	}

	public function supprimerMessage(int $idTopic, int $idMessage){
		$messageForum = $this->pdo->prepare('SELECT * FROM f_messages LEFT JOIN f_forums ON f_messages.id_forum = f_forums.forum_id WHERE id_message = :idMessage');
		$messageForum->execute(['idMessage' => $idMessage]);
		$data = $messageForum->fetch();
		$topic = $data['id_topic'];
		$forum = $data['id_forum'];
		$poster = $data['id_user'];

		$req = $this->pdo->prepare('SELECT topic_first_post, topic_last_post FROM f_topics
			WHERE id_topic = :idTopic');
		$req->execute(['idTopic' => $idTopic]);
		$data_post = $req->fetch();
		if ($data_post['topic_first_post'] == $idMessage)
		{
			Forum::supprimerTopic($idTopic);          
		}
		elseif ($data_post['topic_last_post'] == $idMessage){

			$suppressionMessage = $this->pdo->prepare('DELETE FROM f_messages WHERE id_message = :idMessage');
			$suppressionMessage->execute(['idMessage' => $idMessage]);

			$recupererId = $this->pdo->prepare('SELECT id_message FROM f_messages WHERE id_topic = :idTopic 
				ORDER BY id_message DESC LIMIT 0,1');
			$recupererId->execute(['idTopic' => $idTopic]);
			$data = $recupererId->fetch();             
			$last_post_topic = $data['id_message'];

			$recupererLastPost = $this->pdo->prepare('SELECT id_message FROM f_messages WHERE id_forum = :forum
				ORDER BY id_message DESC LIMIT 0,1');
			$recupererLastPost->execute(['forum' => $forum]);
			$lastPost = $recupererLastPost->fetch();             
			$last_post_forum = $lastPost['id_message'];
			
			$updateLastPost = $this->pdo->prepare('UPDATE f_topics SET topic_last_post = :last
				WHERE topic_last_post = :post');
			$updateLastPost->execute(['last' => $last_post_topic, 'post' => $idMessage]);

			$updateNumber = $this->pdo->prepare('UPDATE f_forums SET forum_post = forum_post - 1, forum_last_post_id = :last
				WHERE forum_id = :forum');
			$updateNumber->execute(['last' => $last_post_forum, 'forum' => $forum]);

			$enlever = $this->pdo->prepare('UPDATE f_topics SET topic_post = topic_post - 1
				WHERE id_topic = :topic');
			$enlever->execute(['topic' => $idTopic]);

			$enleverMembre = $this->pdo->prepare('UPDATE users SET nb_messages = nb_messages - 1
				WHERE id_user = :id');
			$enleverMembre->execute(['id' => $poster]);
		} else {
			$suppression= $this->pdo->prepare('DELETE FROM f_messages WHERE id_message = :post');
			$suppression->execute(['post' => $idMessage]);

			$enlever = $this->pdo->prepare('UPDATE f_forums SET forum_post = forum_post - 1  WHERE forum_id = :forum');
			$enlever->bindValue(':forum',$forum,PDO::PARAM_INT);
			$enlever->execute(['forum' => $forum]); 

			$enleverTopic = $this->pdo->prepare('UPDATE f_topics SET topic_post = topic_post - 1
				WHERE id_topic = :topic');
			$enleverTopic->execute(['topic' => $idTopic]);

			$enleverMembre = $this->pdo->prepare('UPDATE users SET nb_messages = nb_messages - 1
				WHERE id_user = :id');
			$enleverMembre->execute(['id' => $data['post_createur']]);
		}
		
	}

	public function changerStatus(int $idTopic, int $status){
		$req = $this->pdo->prepare('UPDATE f_topics SET topic_locked = :status WHERE id_topic = :idTopic');
		$req->execute(['status' => $status, 'idTopic' => $idTopic]);
	}
}