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
	public function find($id) {
		if (is_numeric($id)) {
			$query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
			$query->execute(['id' => $id]);
			$item = $query->fetch();
			if (!empty($item['slug'])) {
				\Http::redirect("commentaire/" . $item['slug']);
			} else {
				\Http::redirect("index.php");
			}
		} elseif ($id == str_replace("-", "_", $id)) {
			$id =  str_replace("_", "-", $id);
			$query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE slug = :id");
			$query->execute(['id' => $id]);
			$item = $query->fetch();
			\Http::redirect($item['slug']);
		} else {
			$query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE slug = :id");
			$query->execute(['id' => $id]);
			$item = $query->fetch();
			if ($id != $item['slug']) {
				\Http::redirect("../index.php");
			}
		}
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