 <?php

session_start();

require_once('../libraries/autoload.php');

$controller = new \controllers\Forum();
$controller->editionMessage($_GET['topic'], $_GET['message']);