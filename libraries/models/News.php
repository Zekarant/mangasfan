<?php 

namespace Models;

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
		$search_news = "SELECT id_news, title, description, create_date, image, contenu, slug, username FROM {$this->table} INNER JOIN users ON news.author = users.id_user";
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

	/**
	*
	* Affiche une news spécifique en fonction de l'ID
	* @param $id
	*/
	public function findNews(int $id){
		$query = $this->pdo->prepare("SELECT * FROM {$this->table} INNER JOIN users ON author = id_user WHERE id_news = :id");
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
		$query = $this->pdo->prepare("SELECT * FROM {$this->table} INNER JOIN users ON author = id_user WHERE slug = :id");
		$query->execute(['id' => $slug]);
		$item = $query->fetch();
		return $item;
	}

	public function deleteNews(int $id) : void {
		$query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_news = :id");
		$query->execute(['id' => $id]);
		\Http::redirect('index.php');
	}
	
}