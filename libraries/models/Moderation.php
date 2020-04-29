<?php

namespace models;

class Moderation extends Model {

	public function derniersInscrits(){
		$req = $this->pdo->prepare('SELECT id_user, username, email, confirmation_token, DATE_FORMAT(confirmed_at, \'%d %M %Y à %Hh %imin\') AS date_inscription FROM users ORDER BY id_user DESC LIMIT 15');
		$req->execute();
		$users = $req->fetchAll();
		return $users;
	}

	public function derniersCommentaires(){
		$req = $this->pdo->prepare('SELECT news_commentary.id_news, news_commentary.author, posted_date, commentary, username, title FROM news_commentary INNER JOIN users ON author = id_user INNER JOIN news ON news.id_news = news_commentary.id_news ORDER BY id_commentary DESC LIMIT 0, 5');
		$req->execute();
		$commentaires = $req->fetchAll();
		return $commentaires;
	}

	public function paginationCount(){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM users WHERE grade <= 6');
        $req->execute();
        $pagination = $req->fetchColumn();
        return $pagination;
	}

	public function allMembres(?int $limit = 0, ?int $autre = 10){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE grade <= 6 ORDER BY username LIMIT ' . $limit . ',' . $autre);
		$req->execute();
		$membres = $req->fetchAll();
		return $membres;
	}

}