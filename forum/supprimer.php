 <?php

session_start();

require_once('../libraries/autoload.php');

$controller = new \controllers\Forum();
$controller->suppressionMessage($_GET['topic'], $_GET['message']);