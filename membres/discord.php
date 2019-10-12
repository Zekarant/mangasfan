<?php 
require __DIR__ . '/vendor/autoload.php';
use Xwilarg\Discord\OAuth2;
$idclient = "628029772244582401";
$idsecret = "_lDzL_DzakGCbxA0Xl8sIa1tZf1zFhlI";
$redirection = "https://www.mangasfan.fr/membres/compte.php";
$parametres = new OAuth2($idclient, $idsecret, $redirection);
$parametres->startRedirection(['identify']);
?>

