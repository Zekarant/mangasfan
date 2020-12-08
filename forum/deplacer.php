 <?php

session_start();

require_once('../libraries/autoload.php');

$controller = new \controllers\Forum();
$controller->deplacerTopic($_GET['t'], $_POST['dest'], $_POST['from']);