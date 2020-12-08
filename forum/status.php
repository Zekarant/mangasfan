 <?php

session_start();

require_once('../libraries/autoload.php');

$controller = new \controllers\Forum();
$controller->changerStatus($_GET['topic'], $_GET['status']);