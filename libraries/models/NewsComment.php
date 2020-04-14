<?php 

namespace Models;

class NewsComment extends Model {

	protected $table = "news_commentary";

	public function findAllComment(int $id) {
		$query = $this->pdo->prepare("SELECT *, news_commentary.author AS auteur_commentaire FROM {$this->table} INNER JOIN news ON news.id_news = news_commentary.id_news INNER JOIN users ON users.id_user = news_commentary.author WHERE news.id_news = :id ORDER BY posted_date DESC");
		$query->execute(['id' => $id]);
		$resultat = $query->fetchAll();
		return $resultat;
	}

	public function findComment(int $id){
		$query = $this->pdo->prepare("SELECT *, news_commentary.author AS auteur FROM {$this->table} INNER JOIN news ON news.id_news = news_commentary.id_news WHERE news_commentary.id_commentary = :id");
		$query->execute(['id' => $id]);
		$resultat = $query->fetch();
		return $resultat;
	}

	public function addComment(int $id_news, int $author, string $commentary){
		$req = $this->pdo->prepare('INSERT INTO news_commentary(id_news, author, posted_date, commentary) VALUES(:id_news, :author, NOW(), :commentary)');
		$req->execute(['id_news' => $id_news, 'author' => $author, 'commentary' => $commentary]);
	}
	public function editComment(string $commentary, int $idCommentary){
		$query = $this->pdo->prepare("UPDATE {$this->table} SET commentary = ? WHERE id_commentary = ?");
		$query->execute(array($commentary, $idCommentary));
	}

	public function deleteComment(int $id){
		$query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id_commentary = :id");
		$query->execute(['id' => $id]);
	}
}