<?php

class Database {
	/**
* Connexion à la base de données
* @return PDO
*/
public static function getBdd() : PDO {
	$pdo = new PDO('mysql:host=localhost;dbname=mangasfans;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	return $pdo;
}
}

