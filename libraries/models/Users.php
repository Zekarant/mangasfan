<?php 

namespace models;

class Users extends Model {

	protected $table = "users";

	/**
	* Liste des membres pour le pannel d'administration / modération
	* @param $limit
	* @param $autre
	* @return $membres
	*/
	public function allMembres(?int $limit = 0, ?int $autre = 10){
		$req = $this->pdo->prepare('SELECT * FROM users ORDER BY username LIMIT ' . $limit . ',' . $autre);
		$req->execute();
		$membres = $req->fetchAll();
		return $membres;
	}

	public function gradeMembres(int $idUser){
		$req = $this->pdo->prepare('SELECT * FROM grade_users INNER JOIN users ON users.id_user = grade_users.id_user INNER JOIN grades ON grades.id_grade = grade_users.id_grade WHERE grade_users.id_user = :idUser ORDER BY grades.id_grade DESC');
		$req->execute(['idUser' => $idUser]);
		$membres = $req->fetchAll();
		return $membres;
	}

	/**
	* Liste des membres visible par tous
	* @return $membres
	*/
	public function allMembers(?int $limit = 0, ?int $autre = 10){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE confirmation_token IS NULL ORDER BY username LIMIT ' . $limit . ',' . $autre);
		$req->execute();
		$members = $req->fetchAll();
		return $members;
	}

	/**
	* Fonction de pagination pour les membres
	* @return $pagination
	*/
	public function paginationCount(){
		$req = $this->pdo->prepare('SELECT COUNT(*) FROM users');
        $req->execute();
        $pagination = $req->fetchColumn();
        return $pagination;
	}
	
	/**
	* Liste des membres du staff
	* @return $staff
	*/
	public function recupererStaff(){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE grade > 2 AND id_user != 25 ORDER BY grade DESC, chef DESC');
		$req->execute();
		$staff = $req->fetchAll();
		return $staff;
	}

	/**
	* Inscription du membre
	* @param $username
	* @param $email
	* @param $password
	* @param $confirmation_token
	* @param $description
	* @param $avatar
	*/
	public function inscription(string $username, string $email, string $password, string $confirmation_token, string $description, string $avatar){
		$password_hash = password_hash($password, PASSWORD_BCRYPT);
		$req = $this->pdo->prepare('INSERT INTO users SET username = :username, email = :email, password = :password, confirmation_token = :confirmation_token, description = :description, avatar= :avatar, grade = 1');
		$req->execute(['username' => $username, 'email' => $email, 'password' => $password_hash, 'confirmation_token' => $confirmation_token, 'description' => $description, 'avatar' => $avatar]);
	}

	/**
	* Fonction qui retourne le dernier ID de la table users
	*/
	public function returnId(){
		$user_id = $this->pdo->lastInsertId();
		return $user_id;
	}

	/**
	* Confirmation du compte du membre
	* @param $id
	*/
	public function confirmation(int $id){
		$enregistrement = $this->pdo->prepare('UPDATE users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id_user = :id');
	    $enregistrement->execute(['id' => $id]);
	}

	/**
	* Vérification des noms d'utilisateur et emails
	* @param $pseudo
	* @return $user
	*/
	public function verificationInscription(string $pseudo){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username)');
		$req->execute(['username' => $pseudo]);
		$user = $req->fetch();
		return $user;
	}

	/**
	* Connexion du membre
	* @param $pseudo
	* @return $user
	*/
	public function connexion(string $pseudo){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
		$req->execute(['username' => $pseudo]);
		$user = $req->fetch();
		return $user;
	}

	/**
	* Demande de mot de passe
	* @param $email
	* @return $user
	*/
	public function forget(string $email){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE email = :email AND confirmed_at IS NOT NULL');
      	$req->execute(['email' => $email]);
      	$user = $req->fetch();
      	return $user;
	}

	/**
	* Envoie du reset au membre
	* @param $toke
	* @param $id_user
	*/
	public function sendReset(string $token, int $id_user){
		$retour = $this->pdo->prepare('UPDATE users SET reset_token = :reset, reset_at = NOW() WHERE id_user = :id_user');
        $retour->execute(['reset' => $token, 'id_user' => $id_user]);
	}

	/**
	* Changement de mot de passe
	* @param $id_user
	* @param $reset_toke
	* @return $user
	*/
	public function reset(int $id_user, string $reset_token){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE id_user = :id_user AND reset_token IS NOT NULL AND reset_token = :reset_token AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
  		$req->execute(['id_user' => $id_user, 'reset_token' => $reset_token]);
  		$user = $req->fetch();
  		return $user;
	}

	/**
	* Modifier le mot de passe du membre
	* @param $password
	* @param $id_user
	*/
	public function modifyPassword(string $password, int $id_user){
		 $reset = $this->pdo->prepare("UPDATE users SET password = :password WHERE id_user = :id_user");
         $reset->execute(['password' => $password, 'id_user' => $id_user]);
	}

	/**
	* Modification du mot de passe après un reset
	* @param $password
	* @param $reset_token
	* @param $id_user
	*/
	public function newPasswordReset(string $password, string $reset_token, int $id_user){
		 $reset = $this->pdo->prepare("UPDATE users SET password = :password, reset_at = NULL, reset_token = NULL WHERE reset_token = :reset_token AND id_user = :id_user");
         $reset->execute(['password' => $password, 'reset_token' => $reset_token, 'id_user' => $id_user]);
	}

	/**
	* Récupère les informations de l'utilisateur
	* @param $id
	* @return $user
	*/
	public function user(int $id){
		$user = $this->pdo->prepare("SELECT * FROM users WHERE id_user = :id");
        $user->execute(['id' => $id]);
        $utilisateur = $user->fetch();
        return $utilisateur;
	}

	/**
	* Modification de l'avatar
	* @param $avatar
	* @param $extensionAvatar
	*/
	public function modifierAvatar(string $avatar, string $extensionUpload){
		$updateavatar = $this->pdo->prepare('UPDATE users SET avatar = :avatar WHERE id_user = :id');
        $updateavatar->execute(array(
            'avatar' => $avatar.".".$extensionUpload,
            'id' => $avatar));
	}	

	/**
	* Modification des informations du compte
	* @param $email
	* @param $sexe
	* @param $description
	* @param $role
	* @param $manga
	* @param $anime
	* @param $site
	* @param $id_user
	*/
	public function modifierInfos(string $email, int $sexe, string $description, string $role, string $manga, string $anime, string $site, int $id_user){
		$user = $this->pdo->prepare('UPDATE users SET email = :email, sexe = :sexe, description = :description, role = :role, manga = :manga, anime = :anime, site = :site WHERE id_user = :id_user');
		$user->execute(['email' => $email, 'sexe' => $sexe, 'description' => $description, 'role' => $role, 'manga' => $manga, 'anime' => $anime, 'site' => $site, 'id_user' => $id_user]);
	}

	/**
	* Permet à l'utilisateur de rester connecté
	* @param $recup_cookie_pseudo
	* @param $recup_cookie_id
	* @return $utilisateur
	*/
	public function userCookies(string $recup_cookie_pseudo, int $recup_cookie_id){
		$req = $this->pdo->prepare('SELECT * FROM users WHERE id_user = :id_user AND username = :username AND confirmed_at IS NOT NULL');
        $req->execute(['username' => $recup_cookie_pseudo, 'id_user' => $recup_cookie_id]);
        $utilisateur = $req->fetch();
        return $utilisateur;
	}

	/**
	* Définit la date d'anniversaire
	* @param $anniversaire
	* @param $idUser
	*/
	public function setDateAnniv($anniversaire, int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET date_anniversaire = :anniversaire WHERE id_user = :idUser');
		$req->execute(['anniversaire' => $anniversaire, 'idUser' => $idUser]);
	}

	/**
	* Permet la démission d'un membre
	* @param $idUser
	*/
	public function demission(int $idUser){
		$req = $this->pdo->prepare('UPDATE users SET grade = 1, chef = 0, stagiaire = 0 WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}

	/**
	* Suppression du compte d'un membre
	* @param $idUser
	*/
	public function suppressionCompte(int $idUser){
		$req = $this->pdo->prepare('DELETE FROM users WHERE id_user = :idUser');
		$req->execute(['idUser' => $idUser]);
	}
}