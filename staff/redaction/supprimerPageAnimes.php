<?php

session_start();

require_once('../../libraries/autoload.php');

$controller = new \controllers\RedactionAnimes();
$controller->supprimerPageAnimes();