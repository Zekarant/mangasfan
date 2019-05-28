<?php
session_start();
require_once '../inc/base.php';
$user = $pdo->query("SELECT * FROM users WHERE username = '".$_SESSION['auth']->username."'")->fetch();
include('../inc/functions.php');
include('../inc/bbcode.php');
logged_only();

    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['auth'])){
        $_SESSION->flash->danger = "<div style='background-color: #F72424; color: black; border: 1px solid black; padding-left: 10px; padding: 3px;'>Vous n'avez pas le droit d'accéder à cette page</div>";
        header('Location: ../inc/connexion.php');
        exit();
    }
 ?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8" />
  <title>Mangas'Fan - Lecture d'un forum</title>
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
<?php
$forum = (int) $_GET['f'];
//A partir d'ici, on va compter le nombre de messages
//pour n'afficher que les 25 premiers
$query=$pdo->prepare('SELECT forum_name, forum_topic, auth_view, auth_topic FROM forum_forum WHERE forum_id = :forum');
$query->execute(array("forum" => $forum));
$data=$query->fetch();

$totalDesMessages = $data->forum_topic + 1;
$nombreDeMessagesParPage = 25;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);
$page = (isset($_GET['page']))?intval($_GET['page']):1;


$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

//Le titre du forum
echo '<h2>'.$data->forum_name.'</h2>';
$page = (isset($_GET['page']))?intval($_GET['page']):1;
echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> -> <a href="./voirforum.php?f='.$forum.'">'.$data->forum_name.'</a></p>';
//On affiche les pages 1-2-3, etc.
//Initialisation de deux variables
$totaldesmessages = 0;
$categorie = NULL;
$query3=$pdo->prepare('SELECT cat_id, cat_nom,
forum_forum.forum_id, forum_name, forum_desc, forum_post, forum_topic, auth_view, forum_topic.topic_id,  forum_topic.topic_post, post_id, post_time, post_createur, username,
ID, grade
FROM forum_categorie
LEFT JOIN forum_forum ON forum_categorie.cat_id = forum_forum.forum_cat_id
LEFT JOIN forum_post ON forum_post.post_id = forum_forum.forum_last_post_id
LEFT JOIN forum_topic ON forum_topic.topic_id = forum_post.topic_id
LEFT JOIN users ON users.ID = forum_post.post_createur
WHERE cat_nom = "Annonces"
ORDER BY cat_ordre, forum_ordre DESC');
$query3->bindValue(':grade',$grade,PDO::PARAM_INT);
$query3->execute();

if ($forum == 15)
{
}
else
{
?>
<div class="col_trois">
<table width='100%' style="background-color: #868686;">
<?php
//Début de la boucle
while($data3 = $query3->fetch())
{
    //On affiche chaque catégorie
    if( $categorie != stripslashes(nl2br(htmlentities(htmlspecialchars($data3->cat_id)))))
    {
        //Si c'est une nouvelle catégorie on l'affiche

        $categorie = stripslashes(nl2br(htmlentities(htmlspecialchars($data3->cat_id))));
        ?>
        <tr style="background-color: #585858;">
        <th> <div style="background-color: #585858; font-size:18px;">
        <div style="margin-left: 20px;"><strong><font color="#FFFFFF"><?php echo stripslashes(nl2br(htmlentities(htmlspecialchars($data3->cat_nom)))); ?></font></strong></div>
</th>
        <th class="nombremessages"><strong>Sujets</strong></th>
        <th class="nombresujets"><strong>Messages</strong></th>
        <th class="derniermessage" style="width: 20%;"><strong>Dernier message</strong></th></div></tr>
        <?php

    }

    //Ici, on met le contenu de chaque catégorie

	   echo'<tr>
    <td class="titre"><strong>
    <a href="./voirforum.php?f='.stripslashes(nl2br(htmlentities(htmlspecialchars($data3->forum_id)))).'"><br/>
    <div style="font-size: 17px; margin-left: 10px;">'.stripslashes(htmlspecialchars(utf8_decode($data3->forum_name))).'</a></div></strong>
    <i><div style="margin-left: 5px; width: 600px;">'.stripslashes(stripslashes(htmlspecialchars(utf8_decode($data3->forum_desc)))).'</i><br/><br/></div></td>
    <td class="nombresujets"><div style="margin-left: 15px;">'.stripslashes(nl2br(htmlentities(htmlspecialchars($data3->forum_topic)))).'</div></td>
    <td class="nombremessages"><div style="margin-left: 25px;">'.stripslashes(nl2br(htmlentities(htmlspecialchars($data3->forum_post)))).'</div></td>';
	if (stripslashes(nl2br(htmlentities(htmlspecialchars(verif_auth($data3->auth_view))))))
	{
    // Deux cas possibles :
    // Soit il y a un nouveau message, soit le forum est vide
    if (!empty(stripslashes(nl2br(htmlentities(htmlspecialchars($data3->forum_post))))))
    {
         //Selection dernier message
	 $nombreDeMessagesParPage = 15;
         $nbr_post = (stripslashes(nl2br(htmlentities(htmlspecialchars($data3->topic_post)))) +1);
	 $page = ceil($nbr_post / $nombreDeMessagesParPage);

         echo'<td class="derniermessage">
        <div style="font-size: 12px;">Par : <a href="./voirprofil.php?m='.(stripslashes(htmlspecialchars($data3->ID))).'&amp;action=consulter">'.rang_etat($data3->grade,(stripslashes(nl2br(htmlentities(htmlspecialchars($data3->username)))))).'  </a>
         <br/>'.date('d M Y, H\hi',$data3->post_time).'<br />
         <a href="./voirtopic.php?t='.(stripslashes(nl2br(htmlentities(htmlspecialchars($data3->topic_id))))).'&amp;page='.(stripslashes(nl2br(htmlentities(htmlspecialchars($page))))).'#p_'.(stripslashes(nl2br(htmlentities(htmlspecialchars($data3->post_id))))).'">
         Voir</a></td></tr></div>';

     }
     else
     {
         echo'<td class="nombremessages">Aucun message</td></tr>';
     }
	}
     //Cette variable stock le nombre de messages, on la met à jour
     $totaldesmessages += stripslashes(nl2br(htmlentities(htmlspecialchars($data3->forum_post))));

     //On ferme notre boucle et nos balises
echo '</table><br />'; ?></div>

<?php
}
}

echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On ne met pas de lien sur la page actuelle
    {
    echo $i;
    }
    else
    {
    echo '
    <a href="voirforum.php?f='.$forum.'&amp;page='.$i.'">'.$i.'</a>';
    }
}
echo '</p>';

if (verif_auth($data->auth_topic))
{
//On affiche l'image répondre
}
//Et le bouton pour poster
if (verif_auth($data->auth_topic))
{
	if($forum == 15)
	{
		if($user->grade<6)
		{}
		else
		{
//Et le bouton pour poster
echo'<a href="./poster.php?action=nouveautopic&amp;f='.$forum.'">
<img src="images/nouveau.gif" alt="Nouveau topic"
title="Poster un nouveau topic"></a><br><br><br>';
		}
	}
	else
	{
		//Et le bouton pour poster
echo'<a href="./poster.php?action=nouveautopic&amp;f='.$forum.'">
<img src="images/nouveau.gif" alt="Nouveau topic"
title="Poster un nouveau topic"></a><br><br><br>';
	}
}

$query=$pdo->prepare('SELECT forum_topic.topic_id, topic_titre, topic_createur, topic_vu, topic_post, topic_time, topic_last_post,
Mb.username AS membre_pseudo_createur, post_createur, post_time, Ma.username AS membre_pseudo_last_posteur, post_id FROM forum_topic
LEFT JOIN users Mb ON Mb.ID = forum_topic.topic_createur
LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
LEFT JOIN users Ma ON Ma.ID = forum_post.post_createur
WHERE topic_genre = "Annonce" AND forum_topic.forum_id = :forum
ORDER BY topic_last_post DESC');
$query->execute(array("forum" => $forum));

if ($query->rowCount()>0)
{
        ?>
        <table class="table table-striped">
        <tr><div style="background-color:#333; font-size:18px; ">
          <div style="margin-left:10px;"><strong><font color="#FFFFFF">Forum</font></strong></div></div></tr>
        <tr>
        <th></th>
        <th class="titre"><strong>Titre</strong></th>
        <th class="reponse"><strong>Réponses</strong>&nbsp;&nbsp;</th>
        <th class="auteur"><strong>Auteur</strong></th>
        <th class="derniermessage"><strong>Dernier message</strong></th>
        </tr>

        <?php

        //On commence la boucle
        while ($data=$query->fetch())
        {
                //Pour chaque topic :
                //Si le topic est une annonce on l'affiche en haut
                //mega echo de bourrain pour tout remplir
				echo '<tr><td>Message</td>
                <td id="titre">
                <strong><a href="./voirtopic.php?t='.$data->topic_id.'"
                title="Topic commencé à
                '.date('H\hi \l\e d M,y',$data->topic_time).'">
                '.stripslashes(htmlspecialchars($data->topic_titre)).'</a></strong></td>

                <td class="nombremessages">'.stripslashes(htmlspecialchars($data->topic_post)).'</td>



                <td><a href="./voirprofil.php?m='.$data->topic_createur.'
                &amp;action=consulter">
                '.rang_etat($data->grade,(stripslashes(nl2br(htmlentities(htmlspecialchars($data->username)))))).'</a></td>';

               	//Selection dernier message
		$nombreDeMessagesParPage = 15;
		$nbr_post = $data->topic_post +1;
		$page = ceil($nbr_post / $nombreDeMessagesParPage);

                echo '<td class="derniermessage">Par
                <a href="./voirprofil.php?m='.$data->post_createur.'
                &amp;action=consulter">
                '.stripslashes(htmlspecialchars($data->membre_pseudo_last_posteur)).'</a><br />
                A <a href="./voirtopic.php?t='.$data->topic_id.'&amp;page='.$page.'#p_'.$data->post_id.'">'.date('H\hi \l\e d M y',$data->post_time).'</a></td></tr>';
        }
        ?>
        </table>
        <?php
}
$query=$pdo->prepare('SELECT forum_topic.topic_id, topic_titre, topic_createur, topic_vu, topic_post, topic_time, topic_last_post,
Mb.username AS membre_pseudo_createur, post_id, post_createur, post_time, Ma.username AS membre_pseudo_last_posteur FROM forum_topic
LEFT JOIN users Mb ON Mb.ID = forum_topic.topic_createur
LEFT JOIN forum_post ON forum_topic.topic_last_post = forum_post.post_id
LEFT JOIN users Ma ON Ma.ID = forum_post.post_createur
WHERE topic_genre <> "Annonce" AND forum_topic.forum_id = :forum
ORDER BY topic_last_post DESC
LIMIT :premier ,:nombre');
$query->bindValue(':forum',$forum,PDO::PARAM_INT);
$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
$query->execute();
if ($query->rowCount()>0)
{
?>
        <table class="table table-striped">
        <tr><div style="background-color:#585858; font-size:18px; ">
          <div style="margin-left:10px; "><strong><font color="#FFFFFF">Liste des sujets</font></strong></div></tr>
        <tr>
        <th>Type</th>
        <th><strong>Titre</strong></th>
        <th class="reponse"><strong>Réponses</strong>&nbsp;&nbsp;&nbsp;</th>
        <th class="auteur"><strong>Auteur</strong></th>
        <th class="derniermessage"><strong>Dernier message  </strong></th>
    </div>  </tr><br/>
        <?php
        //On lance la boucle

        while ($data = $query->fetch())
        {
                //Ah bah tiens... re vla l'echo de fou
                echo '<tr><td>Message : <br/></td>

                <td>
                <strong><a href="./voirtopic.php?t='.$data->topic_id.'"
                title="Topic commencé à
                '.date('H\hi \l\e d M,y',$data->topic_time).'">
                '.stripslashes(htmlspecialchars($data->topic_titre)).'</a></strong>&nbsp;&nbsp;&nbsp;&nbsp;</td>

                <td class="nombremessages">'.stripslashes(htmlspecialchars($data->topic_post)).'</td>


                <td><a href="./voirprofil.php?m='.$data->topic_createur.'
                &amp;action=consulter">
                '.stripslashes(htmlspecialchars($data->membre_pseudo_createur)).'</a>&nbsp;&nbsp;&nbsp;</td>';

               	//Selection dernier message
		$nombreDeMessagesParPage = 15;
		$nbr_post = $data->topic_post +1;
		$page = ceil($nbr_post / $nombreDeMessagesParPage);

                echo '<td class="derniermessage">Par
                <a href="./voirprofil.php?m='.$data->post_createur.'
                &amp;action=consulter">
                '.stripslashes(htmlspecialchars($data->membre_pseudo_last_posteur)).'</a>
                à <a href="./voirtopic.php?t='.$data->topic_id.'&amp;page='.$page.'#p_'.$data->post_id.'">'.date('H\hi \l\e d M y',$data->post_time).'</a></td></tr>';
        }
        ?>
        </table>
        <?php
}
else //S'il n'y a pas de message
{
        echo'<p>Il n\'y a actuellement aucun sujet dans ce forum !</p>';
}
$query->CloseCursor();

echo '<h3>Informations :</h3>';

$query=$pdo->prepare('SELECT auth_view, auth_modo, auth_post FROM forum_forum WHERE forum_id=:forum');
$query->execute(array("forum" => $forum));
$data=$query->fetch();
$view = (verif_auth($data->auth_view))? 'Vous pouvez <b>voir</b> ce topic':'Vous <i>ne</i> pouvez <i>pas</i> <b>voir</b> ce topic'; - $post = (verif_auth($data->auth_post))? 'Vous pouvez <b>répondre</b> à ce topic':'Vous <i>ne</i> pouvez <i>pas</i> <b>répondre</b> à ce topic'; - $modo = (verif_auth($data->auth_modo))? 'Vous pouvez <b>modérer</b> ce topic':'Vous <i>ne</i> pouvez <i>pas</i> <b>modérer</b> ce topic';
echo '<p>'.$view.'<br />'.$post.'<br />'.$modo.'</p>';
include("../elements/footer.php");
?>
</div>
</body>
</html>
