<?php 

namespace Models;

class Users extends Model {

	protected $table = "users";

	public function connexion(){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
		$req->execute(['username' => $_POST['username']]);
		$user = $req->fetch();
		return $user;
	}

	public function user(int $id){
		$user = $this->pdo->prepare("SELECT * FROM users WHERE id_user = :id");
        $user->execute(['id' => $id]);
        $utilisateur = $user->fetch();
        return $utilisateur;
	}
}