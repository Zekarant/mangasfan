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
}