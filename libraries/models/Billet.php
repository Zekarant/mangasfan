<?php

namespace Models;

class Billet extends Model {

	protected $table = "billets b";
	protected $selection = "b.id AS id_news, b.titre, b.description AS description_news, b.contenu, b.categorie, b.theme, b.auteur, b.date_creation, b.slug, u.id AS id_user, u.username, u.avatar, u.role";
	
}