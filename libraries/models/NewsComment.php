<?php 

namespace Models;

class NewsComment extends Model {

	protected $table = "news_commentary";

	public function findAllComment(int $id) {
		$query = $this->pdo->prepare("SELECT * FROM {$this->table} INNER JOIN news ON news.id_news = news_commentary.id_news INNER JOIN users ON users.id_user = news_commentary.author WHERE news.id_news = :id ORDER BY posted_date DESC");
		$query->execute(['id' => $id]);
		$resultat = $query->fetchAll();
		return $resultat;
	}
}