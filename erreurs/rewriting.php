<?php

if (preg_match('#jeux_video/([a-zA-Z0-9_-]+!?[a-zA-Z0-9_-]*)#is', $_SERVER['REDIRECT_URL'], $match)) {
	// Modification du code retour, pour que les moteurs de recherche indexent nos pages !
	header("Status: 200 OK", false, 200);

	require "../jeux_video/index.php";

} else {
	require 'erreur_404.php';
}
