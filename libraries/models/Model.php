<?php

namespace models;

abstract class Model {

	protected $pdo;
	protected $selection;
	protected $table;

	public function __construct(){
		$this->pdo = \Database::getBdd();
	}

	/*
	* Supprime un élément
	* @param $id
	*/
	public function delete(int $id) : void {
		$query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
		$query->execute(['id' => $id]);
		\Http::redirect('index.php');
	}

}