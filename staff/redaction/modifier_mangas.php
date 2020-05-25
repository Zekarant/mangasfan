<?php

session_start();

require_once('../../libraries/autoload.php');

$controller = new \controllers\RedactionMangas();
$controller->modifier_mangas();