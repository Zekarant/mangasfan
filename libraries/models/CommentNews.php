<?php 

namespace Models;

class CommentNews extends Model {

	protected $table = "commentaires c";

	/* 
	*
	* Récupération de tous les commentaires allant avec la news concernée
	* @param $billet_id
	* @return array
	*/
	public function findAllWithNews($billet_id) : array {
			$query = $this->pdo->prepare("SELECT *, c.id AS id_commentaire FROM commentaires c INNER JOIN users u ON c.id_membre = u.id WHERE id_billet = :billet_id");
			$query->execute(['billet_id' => $billet_id]);
			$commentaires = $query->fetchAll();
		return $commentaires;
	}
}