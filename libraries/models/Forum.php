<?php

namespace models;

class Forum extends Model {

	/** FONCTION QUI RÉCUPERE LES CATÉGEORIES DU FORUM
	* @return array
	*/
	public function recupererCategories(){
		$req = $this->pdo->prepare('SELECT * FROM forum_categories');
		$req->execute();
		$categories = $req->fetchAll();
		return $categories;
	}

	/* FONCTION QUI PERMET D'AJOUTER UN TOPIC
	* @param string $titre
	* @param string $contenu
	*/
	public function ajouterTopic(string $titre, string $contenu){
		$req = $this->pdo->prepare('INSERT INTO f_topics (id_createur, titre, contenu, date_creation, status) VALUES(1, :titre, :contenu, NOW(), 0)');
		$req->execute(['titre' => $titre, 'contenu' => $contenu]);
	}
}