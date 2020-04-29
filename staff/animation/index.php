<?php

session_start();

require_once('../../libraries/autoload.php');

$controller = new \controllers\Animation();
$controller->index();