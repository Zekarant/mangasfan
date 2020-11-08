<?php

namespace models;

class Others extends Model {

	protected $table = "changelog";

	public function changelog(){
		$req = $this->pdo->prepare("SELECT * FROM changelog ORDER BY id_changelog DESC LIMIT 1");
		$req->execute();
		$changelog = $req->fetch();
		return $changelog;
	}

	public function listeAdmins(){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE grade > 7 ORDER BY grade DESC, chef DESC');
		$req->execute();
		$admin = $req->fetchAll();
		return $admin;
	}

	public function listeDevs(){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE grade = 7 ORDER BY grade DESC, chef DESC');
		$req->execute();
		$dev = $req->fetchAll();
		return $dev;
	}

	public function listeModos(){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE grade = 6 ORDER BY grade DESC, chef DESC');
		$req->execute();
		$modos = $req->fetchAll();
		return $modos;
	}

	public function listeRedacs(){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE grade = 5 ORDER BY grade DESC, chef DESC');
		$req->execute();
		$redacs = $req->fetchAll();
		return $redacs;
	}
}