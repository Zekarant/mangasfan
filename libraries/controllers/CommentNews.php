<?php

namespace Controllers;

class CommentNews extends Controller {

	protected $modelName = \Models\CommentNews::class;

	/*
	*
	* Supprimer un commentaire sur l'article sélectionné
	* @return void
	*/
	public function delete() : void {

		if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
			die("Ho ! Fallait préciser le paramètre id en GET !");
		}
		$id = $_GET['id'];

		// Vérification de l'existence du commentaire
		$commentaire = $this->model->find($id);
		if (!$commentaire) {
			die("Aucun commentaire n'a l'identifiant $id !");
		}

		// Récupération de l'id du commentaire et suppression
		$billet_id = $commentaire['id_billet'];
		$this->model->delete($id);
		\Http::redirect("commentaire.php?controller=billet&task=show&id=" . $billet_id);

	}
}