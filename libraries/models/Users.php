<?php 

namespace Models;

class Users extends Model {

	protected $table = "users";

	public function inscription(string $username, string $email, string $password, string $confirmation_token, string $description, string $avatar){
		$password_hash = password_hash($password, PASSWORD_BCRYPT);
		$req = $this->pdo->prepare('INSERT INTO users SET username = :username, email = :email, password = :password, confirmation_token = :confirmation_token, description = :description, avatar= :avatar, grade = 1');
		$req->execute(['username' => $username, 'email' => $email, 'password' => $password_hash, 'confirmation_token' => $confirmation_token, 'description' => $description, 'avatar' => $avatar]);
		return $req;
	}

	public function returnId(){
		$user_id = $this->pdo->lastInsertId();
		return $user_id;
	}

	public function confirmation(int $id){
		$enregistrement = $this->pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id_user = :id');
	    $enregistrement->execute(['id' => $id]);
		return $enregistrement;
	}

	public function connexion(string $pseudo){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
		$req->execute(['username' => $pseudo]);
		$user = $req->fetch();
		return $user;
	}

	public function forget(string $email){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE email = :email AND confirmed_at IS NOT NULL');
      	$req->execute(['email' => $email]);
      	$user = $req->fetch();
      	return $user;
	}

	public function sendReset(string $token, int $id_user){
		$retour = $this->pdo->prepare('UPDATE users SET reset_token = :reset, reset_at = NOW() WHERE id_user = :id_user');
        $retour->execute(['reset' => $token, 'id_user' => $id_user]);
	}

	public function reset(int $id_user, string $reset_token){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE id_user = :id_user AND reset_token IS NOT NULL AND reset_token = :reset_token AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
  		$req->execute(['id_user' => $id_user, 'reset_token' => $reset_token]);
  		$user = $req->fetch();
  		return $user;
	}

	public function modifyPassword(string $password, int $id_user){
		 $reset = $this->pdo->prepare("UPDATE users SET password = :password WHERE id_user = :id_user");
         $reset->execute(['password' => $password, 'id_user' => $id_user]);
	}

	public function newPasswordReset(string $password, string $reset_token, int $id_user){
		 $reset = $this->pdo->prepare("UPDATE users SET password = :password, reset_at = NULL, reset_token = NULL WHERE reset_token = :reset_token AND id_user = :id_user");
         $reset->execute(['password' => $password, 'reset_token' => $reset_token, 'id_user' => $id_user]);
	}

	public function user(int $id){
		$user = $this->pdo->prepare("SELECT * FROM users WHERE id_user = :id");
        $user->execute(['id' => $id]);
        $utilisateur = $user->fetch();
        return $utilisateur;
	}

	public function modifierAvatar(string $avatar, string $extensionUpload){
		$updateavatar = $this->pdo->prepare('UPDATE users SET avatar = :avatar WHERE id_user = :id');
        $updateavatar->execute(array(
            'avatar' => $avatar.".".$extensionUpload,
            'id' => $avatar));

	}	

	public function modifierInfos(string $email, int $sexe, string $description, string $role, string $manga, string $anime, string $site, int $id_user){
		$user = $this->pdo->prepare('UPDATE users SET email = :email, sexe = :sexe, description = :description, role = :role, manga = :manga, anime = :anime, site = :site WHERE id_user = :id_user');
		$user->execute(['email' => $email, 'sexe' => $sexe, 'description' => $description, 'role' => $role, 'manga' => $manga, 'anime' => $anime, 'site' => $site, 'id_user' => $id_user]);
	}

	public function userCookies(string $recup_cookie_pseudo, int $recup_cookie_password){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE id_user = :id_user AND username = :username AND confirmed_at IS NOT NULL');
        $req->execute(['username' => $recup_cookie_pseudo, 'id_user' => $recup_cookie_password]);
        $utilisateur = $req->fetch();
        return $utilisateur;
	}
}