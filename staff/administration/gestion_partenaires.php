<?php

session_start();

require_once('../../libraries/autoload.php');

$controller = new \Controllers\Administration();
$controller->partenaires();