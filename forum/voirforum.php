 <?php

session_start();

require_once('../libraries/autoload.php');

$controller = new \controllers\Forum();
$controller->voirforum($_GET['f']);