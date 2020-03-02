<?php 

namespace Models;

abstract class Model {

	protected $pdo;
	protected $selection;
	protected $table;

	public function __construct(){
		$this->pdo = \Database::getBdd();
	}

	/*
	*
	* Cherche tous les billets en prenant certains paramètres facultatifs
	* @param $order
	* @param $limit
	* @return array
	*/
	public function FindAll(string $innertable, string $jointable, ?string $order = "", ?string $limit = "") : array {
		$sql = "SELECT {$this->selection} FROM {$this->table} INNER JOIN $innertable ON $jointable";
		if ($order) {
			$sql .= " ORDER BY " . $order;
		}
		if ($limit) {
			$sql .= " LIMIT " . $limit;
		}

		$resultats = $this->pdo->query($sql);
		$item = $resultats->fetchAll();
		return $item;
	}

	/*
	* Recherche d'un seul élément
	* @param $id
	*/
	public function find(int $id) {

		$query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
		$query->execute(['id' => $id]);
		$item = $query->fetch();
		return $item;
	}

	/*
	* Supprime un élément
	* @param $id
	*/
	public function delete(int $id) : void {
		$query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
		$query->execute(['id' => $id]);
	}

}