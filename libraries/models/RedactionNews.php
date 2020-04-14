<?php 

namespace Models;

class RedactionNews extends Model {
	
	public function recupererNews(){
		$req = $this->pdo->prepare('SELECT * FROM news INNER JOIN users ON id_user = author ORDER BY create_date DESC LIMIT 0, 25');
		$req->execute();
		$news = $req->fetchAll();
		return $news;
	}

	public function verifierNews(int $id_news){
		$req = $this->pdo->prepare('SELECT *, news.description AS description_news from news INNER JOIN users ON id_user = author WHERE id_news = :id_news');
		$req->execute(['id_news' => $id_news]);
		$news = $req->fetch();
		return $news;
	}

	public function modifierNews(string $title, string $description, string $create_date, ?string $keywords, string $image, string $contenu, string $category, ?string $sources, int $visibility, string $slug, int $id_news){
		$modification = $this->pdo->prepare('UPDATE news SET title = :title, description = :description, create_date = :create_date, keywords = :keywords, image = :image, contenu = :contenu, category = :category, sources = :sources, visibility = :visibility, slug = :new_slug WHERE id_news = :id_news');
      	$modification->execute(['title' => $title, 'description' => $description, 'create_date' => $create_date, 'keywords' => $keywords, 'image' => $image, 'contenu' => $contenu, 'category' => $category, 'sources' => $sources, 'visibility' => $visibility, 'new_slug' => $slug, 'id_news' => $id_news]);
	}

}