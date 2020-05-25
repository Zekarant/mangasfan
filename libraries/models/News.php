<?php 

namespace models;

class News extends Model {

	protected $table = "news";

	/**
	*
	* Affiche toutes les news 
	* @param $order
	* @param $limit
	* @return array
	*/
	public function findAllNews(?string $order = "", ?string $limit = "") : array {
		$search_news = "SELECT id_news, title, news.description, create_date, image, contenu, slug, username FROM {$this->table} INNER JOIN users ON news.author = users.id_user WHERE visibility = 0 AND validation = 0";
		if ($order) {
			$search_news .= " ORDER BY " . $order;
		}
		if ($limit) {
			$search_news .= " LIMIT " . $limit;
		}
		$search_news = $this->pdo->prepare($search_news);
		$search_news->execute();
		$news = $search_news->fetchAll();
		return $news;
	}

	public function archives(){
		$req = $this->pdo->prepare('SELECT *, news.description AS description_news FROM news INNER JOIN users ON id_user = author ORDER BY id_news DESC LIMIT 51');
		$req->execute();
		$archives = $req->fetchAll();
		return $archives;
	}

	/**
	*
	* Affiche une news spécifique en fonction de l'ID
	* @param $id
	*/
	public function findNews(int $id){
		$query = $this->pdo->prepare("SELECT *, news.description AS description_news FROM {$this->table} INNER JOIN users ON author = id_user WHERE id_news = :id");
		$query->execute(['id' => $id]);
		$item = $query->fetch();
		return $item;
	}

	/**
	*
	* Affiche une news spécification en fonction du SLUG
	* @param $slug
	*/
	public function findNewsBySlug(string $slug){
		$query = $this->pdo->prepare("SELECT *, news.description AS description_news FROM {$this->table} INNER JOIN users ON author = id_user WHERE slug = :id");
		$query->execute(['id' => $slug]);
		$item = $query->fetch();
		return $item;
	}

	public function deleteNews(int $id) : void {
		$query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_news = :id");
		$query->execute(['id' => $id]);
	}

	public function newsCategories($categories){
		$req = $this->pdo->prepare('SELECT *, news.description AS description_news FROM news INNER JOIN users ON id_user = author WHERE category = :categories ORDER BY id_news DESC LIMIT 20');
		$req->execute(['categories' => $categories]);
		$news = $req->fetchAll();
		return $news;
	}
	
}