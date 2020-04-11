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

	public function userCookies(string $recup_cookie_pseudo, int $recup_cookie_password){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE id_user = :id_user AND username = :username AND confirmed_at IS NOT NULL');
        $req->execute(['username' => $recup_cookie_pseudo, 'id_user' => $recup_cookie_password]);
        $utilisateur = $req->fetch();
        return $utilisateur;
	}
}