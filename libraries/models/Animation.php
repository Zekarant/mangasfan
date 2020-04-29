<?php

namespace models;

class Animation extends Model {

	public function membersAnimation(){
		$req = $this->pdo->prepare('SELECT id_user, username, points FROM users ORDER BY username ASC');
        $req->execute();
        $membres = $req->fetchAll();
        return $membres;
	}

	public function addAllMembers(int $points){
		$req = $this->pdo->prepare("UPDATE users SET points = points + :points");
        $req->execute(['points' => $points]);
	}

	public function lessAllMembers(int $points){
		$req = $this->pdo->prepare("UPDATE users SET points = points - :points");
        $req->execute(['points' => $points]);
	}

	public function addMembers(int $points, int $user){
		$req = $this->pdo->prepare("UPDATE users SET points = points + :points WHERE id_user = :user");
        $req->execute(['points' => $points, 'user' => $user]);
	}

	public function lessMembers(int $points, int $user){
		$req = $this->pdo->prepare("UPDATE users SET points = points - :points WHERE id_user = :user");
        $req->execute(['points' => $points, 'user' => $user]);
	}
	
	public function rankedPoints(){
		$req = $this->pdo->prepare('SELECT id_user, username, points FROM users ORDER BY points DESC LIMIT 0, 10');
		$req->execute();
		$classements = $req->fetchAll();
		return $classements;
	}	

	public function animation(){
		$req = $this->pdo->prepare('SELECT * FROM animations ORDER BY id_animation DESC LIMIT 0, 1');
		$req->execute();
		$animation = $req->fetch();
		return $animation;
	}

	public function updateAnimation($contenu, int $visibility){
		$req = $this->pdo->prepare('UPDATE animations SET contenu = :contenu, visibility = :visibility, posted_date = NOW()');
		$req->execute(['contenu' => $contenu, 'visibility' => $visibility]);
	}
}