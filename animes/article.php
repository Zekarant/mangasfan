<?php

session_start();

require_once('../libraries/autoload.php');

$controller = new \controllers\Animes();
$controller->article();