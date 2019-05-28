<?php
session_start();
require_once '../inc/base.php';
$user = $pdo->query("SELECT * FROM users WHERE username = '".$_SESSION['auth']->username."'")->fetch();
include('../inc/functions.php'); ?>
<html>
<head>
<title>Mangas'Fan - Confirmation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="../images/favicon.png"/>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../style.css">
</head>
<body>
  <div id="bloc_principal" style="min-height: 600px; margin-bottom: 25px;">
    <header>
    <?php include ("../elements/navigation.php") ?>

    </header>
   <div id="banniere_image"></div>
   <?php include('bbcode.php'); include("../elements/espace_membre.php"); ?>
<?php include("../elements/messages.php"); ?>
<?php logged_only();

    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['auth'])){
        $_SESSION->flash->danger = "<div style='background-color: #F72424; color: black; border: 1px solid black; padding-left: 10px; padding: 3px;'>Vous n'avez pas le droit d'accéder à cette page</div>";
        header('Location: ../inc/connexion.php');
        exit();
    }
$action = (isset($_GET['action']))?htmlspecialchars(utf8_encode($_GET['action'])):'';

if (isset($_GET['f']))
{
    $forum = (int) $_GET['f'];
    $query= $pdo->prepare('SELECT forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_forum WHERE forum_id =:forum');
    $query->execute(array("forum" => $forum));
    $data=$query->fetch();
}

//Sinon c'est un nouveau message, on a la variable t et
//On récupère f grâce à une requête
elseif (isset($_GET['t']))
{
    $topic = (int) $_GET['t'];
    $query=$pdo->prepare('SELECT topic_titre, forum_topic.forum_id,
    forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_topic
    LEFT JOIN forum_forum ON forum_forum.forum_id = forum_topic.forum_id
    WHERE topic_id =:topic');
    $query->execute(array("topic" => $topic));
    $data=$query->fetch();
    $forum = $data->forum_id;

}

//Enfin sinon c'est au sujet de la modération(on verra plus tard en détail)
//On ne connait que le post, il faut chercher le reste
elseif (isset ($_GET['p']))
{
    $post = (int) $_GET['p'];
    $query=$pdo->prepare('SELECT post_createur, forum_post.topic_id, topic_titre, forum_topic.forum_id,
    forum_name, auth_view, auth_post, auth_topic, auth_annonce, auth_modo
    FROM forum_post
    LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id
    LEFT JOIN forum_forum ON forum_forum.forum_id = forum_topic.forum_id
    WHERE forum_post.post_id =:post');
    $query->execute(array("post" => $post));
    $data=$query->fetch();

    $topic = $data->topic_id;
    $forum = $data->forum_id;

}
switch($action)
{
case "repondre": //Premier cas : on souhaite répondre
?>
<h1>Poster une réponse</h1>
<?php
 echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> ->
 <a href="./voirforum.php?f='.$forum.'">'.$data->forum_name.'</a>
 -><a href="./voirtopic.php?t='.$topic.'">'.$data->topic_titre.'</a>->Répondre au Topic</p>';
?>
<script type="text/javascript">
function showSpoiler(obj)
{
var inner = obj.parentNode.getElementsByTagName("div")[0];
if (inner.style.display == "none")
inner.style.display = "";
else
inner.style.display = "none";
}
</script>

  <a href="https://mangasfan.000webhostapp.com/inc/bbcode_active.html">Cliquez ici pour voir la liste de BBCode et smileys disponible</a>
<form method="post" action="postok.php?action=repondre&amp;t=<?php echo $topic ?>" name="formulaire">



<fieldset><legend>Message</legend>
<textarea cols="80" rows="8" id="message" style="font-family:Tahoma; font-size:13px;" class="form-control" name="message"></textarea></fieldset>

<input type="submit" name="submit" value="Poster" class="btn btn-sm btn-info"/>
<input type="reset" name = "Effacer" value = "Vider" class="btn btn-sm btn-danger"/>
</p></form>
<?php
break;
case "edit": //Si on veut éditer le post
    //On récupère la valeur de p
    $post = (int) $_GET['p'];
    echo'<h1>Edition</h1>';
 echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> ->
 <a href="./voirforum.php?f='.$forum.'">'.$data->forum_name.'</a>
 -><a href="./voirtopic.php?t='.$topic.'">'.$data->topic_titre.'</a>->Editer le Topic</p>';
    //On lance enfin notre requête

    $query=$pdo->prepare('SELECT post_createur, post_texte, auth_modo FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id=:post');
    $query->execute(array("post" => $post));
    $data=$query->fetch();

    $text_edit = $data->post_texte; //On récupère le message

    //Ensuite on vérifie que le membre a le droit d'être ici (soit le créateur soit un modo/admin)
    if (!verif_auth($data->auth_modo) && $data->post_createur != $ID)
    {
        // Si cette condition n'est pas remplie ça va barder :o
        erreur(ERR_AUTH_EDIT);
    }
    else //Sinon ça roule et on affiche la suite
    {
        //Le formulaire de postage
        ?>
        <form method="post" action="postok.php?action=edit&amp;p=<?php echo $post ?>" name="formulaire">



<script type="text/javascript">
function showSpoiler(obj)
{
var inner = obj.parentNode.getElementsByTagName("div")[0];
if (inner.style.display == "none")
inner.style.display = "";
else
inner.style.display = "none";
}
</script>
 <a href="https://mangasfan.000webhostapp.com/inc/bbcode_active.html">Cliquez ici pour voir la liste de BBCode et smileys disponible</a>
<fieldset><legend>Message</legend><textarea cols="80" rows="8" id="message" style="font-family:Tahoma; font-size:13px;" class="form-control" name="message"><?php echo stripslashes($text_edit); ?></textarea></fieldset>
<input type="submit" name="submit" value="Envoyer le message" class="btn btn-sm btn-info"/>
<input type="reset" name = "Effacer" value = "Vider" class="btn btn-sm btn-danger" />
        </form>
        <?php
    }
break;
case "delete": //Si on veut supprimer le post
    //On récupère la valeur de p
    $post = (int) $_GET['p'];
    //Ensuite on vérifie que le membre a le droit d'être ici
    echo'<h1>Suppression</h1>';
    $query=$pdo->prepare('SELECT post_createur, auth_modo
    FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id= :post');
    $query->execute(array("post" => $post));
    $data = $query->fetch();


        echo'<p>Êtes vous certains de vouloir supprimer ce post ?</p>';
        echo'<p><a href="./postok.php?action=delete&amp;p='.$post.'">Oui</a> ou <a href="./index.php">Non</a></p>';

    $query->CloseCursor();
break;
case "nouveautopic":
?>

<h2>Nouveau topic</h2>
<?php
echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> -> <a href="./voirforum.php?f='.$forum.'">'.$data->forum_name.'</a>->
'.$data->topic_titre.' Nouveau Topic</p>';
?>

<script type="text/javascript">
function showSpoiler(obj)
{
var inner = obj.parentNode.getElementsByTagName("div")[0];
if (inner.style.display == "none")
inner.style.display = "";
else
inner.style.display = "none";
}
</script>
   <a href="https://mangasfan.000webhostapp.com/inc/bbcode_active.html">Cliquez ici pour voir la liste de BBCode et smileys disponible</a><br/><br/>
<form method="post" action="postok.php?action=nouveautopic&amp;f=<?php echo (int) $_GET['f']; ?>" name="formulaire">

<fieldset><legend>Titre</legend>
<input type="text" size="80" id="titre" name="titre" class="form-control" placeholder="Entrez ici le titre du topic"/></fieldset>
<br/>



<fieldset><legend>Message</legend>
<textarea cols="80" rows="8" id="message" style="font-family:Tahoma; font-size:13px;" class="form-control" name="message" placeholder="Entrez votre message, vous poubvez utiliser les balises de BBCode renseignées dans le fichier au dessus"></textarea><br><br>
Type de message :
<?php
if($ID == 1 )
{
?><label><input type="radio" name="mess" value="Annonce" class="form-control" />Annonce</label> -
<?php
}
?>

<?php
if($ID == 51 OR $ID == 50 )
{
?><label><input type="radio" name="mess" value="Annonce" class="form-control" />Annonce</label> -
<?php
}
?>
<label><input type="radio" name="mess" value="Message" checked="checked" />Topic</label><br />
</fieldset>
<p>
  <input type="submit" name="submit" value="Envoyer le message" class="btn btn-sm btn-info"/>
  <input type="reset" name = "Effacer" value = "Vider" class="btn btn-sm btn-danger" /></p>
</form>
<?php
break;

//D'autres cas viendront s'ajouter là plus tard :p

default: //Si jamais c'est aucun de ceux-là, c'est qu'il y a eu un problème :o
echo'<h2>Cette action est impossible</h2>';

}
include("../elements/footer.php");
?>
</div>
</div>
</body>
</html>
