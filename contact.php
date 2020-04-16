<?php

session_start();

require_once('libraries/autoload.php');

$controller = new \Controllers\Contact();
$controller->contact();