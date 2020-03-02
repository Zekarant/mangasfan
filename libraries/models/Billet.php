<?php

namespace Models;

class Billet extends Model {

	protected $table = "billets b";
	protected $selection = "b.id AS id_news, b.titre, b.description, b.theme, b.auteur, b.date_creation, u.id, u.username";
	
}