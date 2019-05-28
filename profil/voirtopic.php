<?php
session_start();
require_once '../inc/base.php';
$user = $pdo->query("SELECT * FROM users WHERE username = '".$_SESSION['auth']->username."'")->fetch();
include('../inc/functions.php');

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
  <title>Mangas'Fan - Lecture d'un topic</title>
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
   <?php
include('bbcode.php');
$topic = (int) stripslashes(htmlspecialchars($_GET['t']));
//A partir d'ici, on va compter le nombre de messages pour n'afficher que les 15 premiers
$query=$pdo->prepare('SELECT topic_titre, topic_post, forum_topic.forum_id, topic_last_post,
forum_name, auth_view, auth_topic, auth_post
FROM forum_topic
LEFT JOIN forum_forum ON forum_topic.forum_id = forum_forum.forum_id
WHERE topic_id = :topic');
$query->execute(array("topic" => $topic));
$data=$query->fetch();
$forum=$data->forum_id;
$totalDesMessages = $data->topic_post + 1;
$nombreDeMessagesParPage = 15;
$nombreDePages = ceil($totalDesMessages / $nombreDeMessagesParPage);
echo ('<h2> Titre du Topic: '.stripslashes($data->topic_titre).'</h2>');

$page = (isset($_GET['page']))?intval($_GET['page']):1;

$query=$pdo->prepare('SELECT COUNT(*) FROM forum_topic_view WHERE tv_topic_id = :topic AND tv_id = :id');
$query->execute(array("topic" => $topic, "id" => $ID));
$nbr_vu=$query->fetchColumn();
$query->CloseCursor();
echo'<p><i>Vous êtes ici</i> : <a href="./index.php">Index du forum</a> -> <a href="./voirforum.php?f='.$forum.'">'.$data->forum_name.'</a> ->
'.stripslashes($data->topic_titre).'</p>';
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
<br/><?php
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
        <th> <div style="background-color: #585858; font-size:18px; ">
        <div style="margin-left: 20px;"><strong><font color="#FFFFFF"><?php echo stripslashes(nl2br(htmlentities(htmlspecialchars($data3->cat_nom)))); ?></font></strong></div>
</th>
        <th class="nombremessages"><strong>Sujets</strong></th>
        <th class="nombresujets"><strong>Messages</strong></th>
        <th class="derniermessage"><strong>Dernier message</strong></th></div></tr>
        <?php

    }

    //Ici, on met le contenu de chaque catégorie

	   echo'<tr>
    <td><strong>
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
//On affiche les pages 1-2-3 etc...
echo '<p>Page : ';
for ($i = 1 ; $i <= $nombreDePages ; $i++)
{
    if ($i == $page) //On affiche pas la page actuelle en lien
    {
    echo $i;
    }
    else
    {
    echo '<a href="voirtopic.php?t='.$topic.'&page='.$i.'">
    ' . $i . '</a> ';
    }
}
echo'</p>';

$premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

if (verif_auth($data->auth_post))
{
//On affiche l'image répondre
}

if (verif_auth($data->auth_post))
{
//On affiche l'image répondre
if($forum == 15)
	{
		if($user->grade<6)
		{}
		else
		{
//Et le bouton pour poster
echo'<a href="./poster.php?action=repondre&amp;t='.$topic.'">
<img src="images/repondre.gif" alt="Répondre" title="Répondre à ce topic"></a></a>';
		}
	}
	else
	{
		//Et le bouton pour poster
echo'<a href="./poster.php?action=repondre&amp;t='.$topic.'">
<img src="images/repondre.gif" alt="Répondre" title="Répondre à ce topic"></a></a>';
	}

}

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


$query=$pdo->prepare('SELECT post_id , vu,  post_createur , post_texte , post_time ,
ID, username, avatar, description, grade
FROM forum_post
LEFT JOIN users ON users.ID = forum_post.post_createur
WHERE topic_id =:topic AND vu <= 1
ORDER BY post_id
LIMIT :premier, :nombre');
$query->bindValue(':topic',$topic,PDO::PARAM_INT);
$query->bindValue(':premier',(int) $premierMessageAafficher,PDO::PARAM_INT);
$query->bindValue(':nombre',(int) $nombreDeMessagesParPage,PDO::PARAM_INT);
$query->execute();

//On vérifie que la requête a bien retourné des messages
if ($query->rowCount()<1)
{
        echo'<p>Il n y a aucun post sur ce topic, vérifiez l\'url et réessayez</p>';
}
else
{
        //Si tout roule on affiche notre tableau puis on remplit avec une boucle
        ?><br/><table style="text-align: left; margin-left: 20px;">
        <?php
        while ($data = $query->fetch())
        {
		         echo'<tr style="margin-top: 25px;"><th><strong><font color="#FFFFFF"><strong>
         <div style="font-size: 19px; margin-left: 25px;"><a href="./voirprofil.php?m='.$data->ID.'&amp;action=consulter"></a>
         '.rang_etat($data->grade,$data->username).'</a></strong></font></strong></th>';

         /* Si on est l'auteur du message, on affiche des liens pour
         Modérer celui-ci.
         Les modérateurs pourront aussi le faire, il faudra donc revenir sur
         ce code un peu plus tard ! */
   $select_rang = $pdo->query("SELECT * FROM users WHERE ID='".$_SESSION['auth']->id."'")->fetch();
   $ID = $select_rang->id;
         if (($ID == $data->post_createur) || ($select_rang->grade >=3))
         {
         echo'<font color="#FFFFFF"><th id=p_'.$data->post_id.' style="background-color: #7A7A7A; border: 1px solid black; color: #FFFFFF; margin-left: 25px; padding: 5px;">Posté à '.date('H\hi \l\e d M y',$data->post_time).'';
		 if($forum == 15)
		 {
			 if($user->grade<6)
			 {
			 }
			 else
			 {
        echo '</font> <a href="./poster.php?p='.$data->post_id.'&amp;action=delete">
         Supprimer</a>
         <a href="./poster.php?p='.$data->post_id.'&amp;action=edit">
         Editer</a></th></tr>';
			 }
         }
		 else
		 {
			 echo '</font> <a href="./poster.php?p='.$data->post_id.'&amp;action=delete">
         Supprimer</a>
         <a href="./poster.php?p='.$data->post_id.'&amp;action=edit">
         Editer</a></th></tr>';
		 }
		 }
         else
         {
         echo'<th style="background-color: #7A7A7A; border: 1px solid black; color: #FFFFFF; margin-left: 25px; padding: 5px;">&nbsp;&nbsp;
         Posté à '.date('H\hi \l\e d M y',$data->post_time).'
         </th></tr>';
         }
       ?>

        <tr><td><div style="margin-top: 10px; margin-right: 15px; margin-bottom: 10px;"><br/>
		<img src="<?php echo stripslashes(htmlspecialchars($data->avatar));?>" alt="avatar" style="max-height: 215px; max-width: 155px; margin-top: -45px;" title="Avatar de <?php echo stripslashes(htmlspecialchars($data->username));?>"/>
</div>

		 </td>
         <?php
         //Message

		 $texte = stripslashes($data->post_texte);
         echo'<td style="margin-left: 25px; padding-top: -200px; margin-bottom: 10px!important; background-color: #A4A4A4; border: 1px solid #585858; border-top: 1px solid transparent; min-height: 300px; min-width: 1000px; padding: 10px;"><div style="margin--top: -150px;">'.bbcode(nl2br($texte)).'</div>
		 '.($data->signature).'
		 </th></tr>';
         } //Fin de la boucle ! \o/
         $query->CloseCursor();

         ?>
</table><br/><br/>
<?php
        echo '<p>Page : ';
        for ($i = 1 ; $i <= $nombreDePages ; $i++)
        {
                if ($i == $page) //On affiche pas la page actuelle en lien
                {
                echo $i;
                }
                else
                {
                echo '<a href="voirtopic.php?t='.$topic.'&amp;page='.$i.'">
                ' . $i . '</a> ';
                }
        }
        echo'</p>';

        //On ajoute 1 au nombre de visites de ce topic
        $query=$pdo->prepare('UPDATE forum_topic
        SET topic_vu = topic_vu + 1 WHERE topic_id = :topic');
        $query->execute(array("topic" => $topic));
        $query->CloseCursor();

} //Fin du if qui vérifiait si le topic contenait au moins un message
?>
<br/><?php
if (verif_auth($data->auth_post))
{
//On affiche l'image répondre
if($forum == 15)
	{
		if($user->grade<6)
		{}
		else
		{
//Et le bouton pour poster
echo'<a href="./poster.php?action=repondre&amp;t='.$topic.'">
<img src="images/repondre.gif" alt="Répondre" title="Répondre à ce topic"></a></a>';
		}
	}
	else
	{
		//Et le bouton pour poster
echo'<a href="./poster.php?action=repondre&amp;t='.$topic.'">
<img src="images/repondre.gif" alt="Répondre" title="Répondre à ce topic"></a></a>';
	}

}

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
$query->CloseCursor();
include("../elements/footer.php");
?><br/>
</div>
</div>
</body>
</html>
