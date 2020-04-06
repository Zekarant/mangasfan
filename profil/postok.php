<?php
session_start();
require_once '../membres/base.php';
include('../membres/functions.php'); 
 if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['auth'])){
        $_SESSION->flash->danger = "<div class='alert alert-danger' role='alert'>Vous n'avez pas le droit d'accéder à cette page</div>";
        header('Location: ../inc/connexion.php');
        exit();
    }
$id = $_SESSION['auth']['id'];
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';

switch($action)
{
    //Premier cas : nouveau topic
    case "nouveautopic":

    //On passe le message dans une série de fonction
    $message = addslashes(htmlspecialchars($_POST['message']));
    $mess = addslashes(htmlspecialchars($_POST['mess']));

    //Pareil pour le titre
    $titre = addslashes($_POST['titre']);

    //ici seulement, maintenant qu'on est sur qu'elle existe, on récupère la valeur de la variable f
    $forum = (int) $_GET['f'];
    $temps = time();

    if (empty($message) || empty($titre))
    {
        echo'<div class="alert alert-danger" role="alert"><p>Votre message ou votre titre est vide,
        cliquez <a href="./poster.php?action=nouveautopic&amp;f='.$forum.'">ici</a> pour recommencer</div></p>';
    }
    else //Si jamais le message n'est pas vide
    {
        $query=$pdo->prepare('INSERT INTO forum_topic
        (forum_id, topic_titre, topic_createur, topic_vu, topic_time, topic_genre)
        VALUES(:forum, :titre, :id, 1, :temps, :mess)');
        $query->execute(array("forum" => $forum, "titre" => $titre, "id" => $id, "temps" => $temps, "mess"=> $message));


        $nouveautopic = $pdo->lastInsertid(); //Notre fameuse fonction !
        $query->CloseCursor();

        //Puis on entre le message
        $query=$pdo->prepare('INSERT INTO forum_post
        (post_createur, post_texte, post_time, topic_id, post_forum_id, vu)
        VALUES (:id, :mess, :temps, :nouveautopic, :forum, 1)');
        $query->execute(array("id" => $id, "mess" => $message, "temps" => $temps, "nouveautopic" => $nouveautopic, "forum" => $forum));



        $nouveaupost = $pdo->lastInsertid(); //Encore notre fameuse fonction !
        $query->CloseCursor();


        //Ici on update comme prévu la valeur de topic_last_post et de topic_first_post
        $query=$pdo->prepare('UPDATE forum_topic
        SET topic_last_post = :nouveaupost,
        topic_first_post = :nouveaupost
        WHERE topic_id = :nouveautopic');
        $query->execute(array("nouveaupost" => $nouveaupost, "nouveautopic" => $nouveautopic));
        $query->CloseCursor();

        //Enfin on met à jour les tables forum_forum et forum_membres
        $query=$pdo->prepare('UPDATE forum_forum SET forum_post = forum_post + 1 ,forum_topic = forum_topic + 1,
        forum_last_post_id = :nouveaupost
        WHERE forum_id = :forum');
        $query->execute(array("nouveaupost" => $nouveaupost, "forum" => $forum));
        $query->CloseCursor();


        //Et un petit message
        echo'<div class="alert alert-success" role="alert"><p>Votre message a bien été ajouté !</div><br /><br />Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum<br />
        Cliquez <a href="./voirtopic.php?t='.$nouveautopic.'">ici</a> pour le voir</p>';
    }
    break;
    case "delete": //Si on veut supprimer le post
    //On récupère la valeur de p
    $post = (int) $_GET['p'];
    $query=$pdo->prepare('SELECT post_createur, post_texte, forum_id, topic_id, auth_modo
    FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id=:post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    $topic = $data->topic_id;
    $forum = $data->forum_id;
    $poster = $data->post_createur;


    //Ensuite on vérifie que le membre a le droit d'être ici
    //(soit le créateur soit un modo/admin)


        //Ici on vérifie plusieurs choses :
        //est-ce un premier post ? Dernier post ou post classique ?

        $query = $pdo->prepare('SELECT topic_first_post, topic_last_post FROM forum_topic
        WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $data_post=$query->fetch();



        //On distingue maintenant les cas
        if ($data_post->topic_first_post==$post) //Si le message est le premier
        {

            //Les autorisations ont changé !
            //Normal, seul un modo peut décider de supprimer tout un topic
            if (!verif_auth($data->auth_modo))
            {
                erreur('ERR_AUTH_DELETE_TOPIC');
            }

            //Il faut s'assurer que ce n'est pas une erreur

            echo'<p>Vous avez choisi de supprimer un post.
            Cependant ce post est le premier du topic. Voulez-vous supprimer le topic ? <br />
            <a href="./postok.php?action=delete_topic&amp;t='.$topic.'">oui</a> - <a href="./voirtopic.php?t='.$topic.'">non</a>
            </p>';
            $query->CloseCursor();
        }
        elseif ($data_post->topic_last_post==$post)  //Si le message est le dernier
        {

            //On supprime le post
            $query=$pdo->prepare('UPDATE forum_post SET vu=2 WHERE post_id = :post');
            $query->bindValue(':post',$post,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //On modifie la valeur de topic_last_post pour cela on
            //récupère l'id du plus récent message de ce topic
            $query=$pdo->prepare('SELECT post_id FROM forum_post WHERE topic_id = :topic
            ORDER BY post_id DESC LIMIT 0,1');
            $query->bindValue(':topic',$topic,PDO::PARAM_INT);
            $query->execute();
            $data=$query->fetch();
            $last_post_topic=$data->post_id;
            $query->CloseCursor();

            //On fait de même pour forum_last_post_id
            $query=$pdo->prepare('SELECT post_id FROM forum_post WHERE post_forum_id = :forum
            ORDER BY post_id DESC LIMIT 0,1');
            $query->bindValue(':forum',$forum,PDO::PARAM_INT);
            $query->execute();
            $data=$query->fetch();
            $last_post_forum=$data->post_id;
            $query->CloseCursor();

            //On met à jour la valeur de topic_last_post

            $query=$pdo->prepare('UPDATE forum_topic SET topic_last_post = :last
            WHERE topic_last_post = :post');
            $query->bindValue(':last',$last_post_topic,PDO::PARAM_INT);
            $query->bindValue(':post',$post,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //On enlève 1 au nombre de messages du forum et on met à
            //jour forum_last_post
            $query=$pdo->prepare('UPDATE forum_forum SET forum_post = forum_post - 1, forum_last_post_id = :last
            WHERE forum_id = :forum');
            $query->bindValue(':last',$last_post_forum,PDO::PARAM_INT);
            $query->bindValue(':forum',$forum,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //On enlève 1 au nombre de messages du topic
            $query=$pdo->prepare('UPDATE forum_topic SET  topic_post = topic_post - 1
            WHERE topic_id = :topic');
            $query->bindValue(':topic',$topic,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //On enlève 1 au nombre de messages du membre

            //Enfin le message
            echo'<p>Le message a bien été supprimé !<br />
            Cliquez <a href="./voirtopic.php?t='.$topic.'">ici</a> pour retourner au topic<br />
            Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum</p>';

        }
        else // Si c'est un post classique
        {

            //On supprime le post
            $query=$pdo->prepare('UPDATE forum_post SET vu=2 WHERE post_id = :post');
            $query->bindValue(':post',$post,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //On enlève 1 au nombre de messages du forum
            $query=$pdo->prepare('UPDATE forum_forum SET forum_post = forum_post - 1  WHERE forum_id = :forum');
            $query->bindValue(':forum',$forum,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //On enlève 1 au nombre de messages du topic
            $query=$pdo->prepare('UPDATE forum_topic SET  topic_post = topic_post - 1
            WHERE topic_id = :topic');
            $query->bindValue(':topic',$topic,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor();

            //On enlève 1 au nombre de messages du membre

            //Enfin le message
            echo'<p>Le message a bien été supprimé !<br />
            Cliquez <a href="./voirtopic.php?t='.$topic.'">ici</a> pour retourner au topic<br />
            Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum</p>';
        }

     //Fin du else
break;
    case "edit": //Si on veut éditer le post
    //On récupère la valeur de p
    $post = (int) $_GET['p'];

    //On récupère le message
    $message = stripslashes(htmlspecialchars($_POST['message']));

    //Ensuite on vérifie que le membre a le droit d'être ici (soit le créateur soit un modo/admin)
    $query=$pdo->prepare('SELECT post_createur, post_texte, post_time, topic_id, auth_modo
    FROM forum_post
    LEFT JOIN forum_forum ON forum_post.post_forum_id = forum_forum.forum_id
    WHERE post_id=:post');
    $query->bindValue(':post',$post,PDO::PARAM_INT);
    $query->execute();
    $data1 = $query->fetch();
    $topic = $data1->topic_id;

    //On récupère la place du message dans le topic (pour le lien)
    $query = $pdo->prepare('SELECT COUNT(*) AS nbr FROM forum_post
    WHERE topic_id = :topic AND post_time < '.$data1->post_time);
    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
    $query->execute();
    $data2=$query->fetch();

    if (!verif_auth($data1->auth_modo)&& $data1->post_createur != $id)
    {
        // Si cette condition n'est pas remplie ça va barder :o
        erreur(ERR_AUTH_EDIT);
    }
    else //Sinon ça roule et on continue
    {
        $query=$pdo->prepare('UPDATE forum_post SET post_texte =  :message WHERE post_id = :post');
        $query->bindValue(':message',$message,PDO::PARAM_STR);
        $query->bindValue(':post',$post,PDO::PARAM_INT);
        $query->execute();
        $nombreDeMessagesParPage = 15;
        $nbr_post = $data2->nbr+1;
        $page = ceil($nbr_post / $nombreDeMessagesParPage);
        echo'<p>Votre message a bien été édité !<br /><br />
        Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum<br />
        Cliquez <a href="./voirtopic.php?t='.$topic.'&amp;page='.$page.'#p_'.$post.'">ici</a> pour le voir</p>';
        $query->CloseCursor();
    }
break;

    case "repondre":
    $message = addslashes(htmlspecialchars($_POST['message']));

    //ici seulement, maintenant qu'on est sur qu'elle existe, on récupère la valeur de la variable t
    $topic = (int) $_GET['t'];
    $temps = time();

    if (empty($message))
    {
        echo'<p class="alert alert-success" role="alert">Votre message est vide, cliquez <a href="./poster.php?action=repondre&amp;t='.$topic.'">ici</a> pour recommencer</p>';
    }
    else //Sinon, si le message n'est pas vide
    {

        //On récupère l'id du forum
        $query=$pdo->prepare('SELECT forum_id, topic_post FROM forum_topic WHERE topic_id = :topic');
        $query->bindValue(':topic', $topic, PDO::PARAM_INT);
        $query->execute();
        $data=$query->fetch();
        $forum = $data->forum_id;

        //Puis on entre le message
        $query=$pdo->prepare('INSERT INTO forum_post
        (post_createur, post_texte, post_time, topic_id, post_forum_id)
        VALUES(:id,:mess,:temps,:topic,:forum)');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->bindValue(':mess', $message, PDO::PARAM_STR);
        $query->bindValue(':temps', $temps, PDO::PARAM_INT);
        $query->bindValue(':topic', $topic, PDO::PARAM_INT);
        $query->bindValue(':forum', $forum, PDO::PARAM_INT);
        $query->execute();

        $nouveaupost = $pdo->lastInsertid();
        $query->CloseCursor();

        //On change un peu la table forum_topic
        $query=$pdo->prepare('UPDATE forum_topic SET topic_post = topic_post + 1, topic_last_post = :nouveaupost WHERE topic_id =:topic');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);
        $query->bindValue(':topic', (int) $topic, PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

        //Puis même combat sur les 2 autres tables
        $query=$pdo->prepare('UPDATE forum_forum SET forum_post = forum_post + 1 , forum_last_post_id = :nouveaupost WHERE forum_id = :forum');
        $query->bindValue(':nouveaupost', (int) $nouveaupost, PDO::PARAM_INT);
        $query->bindValue(':forum', (int) $forum, PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();


        //Et un petit message
        $nombreDeMessagesParPage = 15;
        $nbr_post = $data->topic_post+1;
        $page = ceil($nbr_post / $nombreDeMessagesParPage);
        echo'<p class="alert alert-success" role="alert">Votre message a bien été ajouté !<br /><br />
        Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum<br />
        Cliquez <a href="./voirtopic.php?t='.$topic.'&amp;page='.$page.'#p_'.$nouveaupost.'">ici</a> pour le voir</p>';
    }//Fin du else
    break;
    case "delete_topic":
    $topic = (int) $_GET['t'];
    $query=$pdo->prepare('SELECT forum_topic.forum_id, auth_modo
    FROM forum_topic
    LEFT JOIN forum_forum ON forum_topic.forum_id = forum_forum.forum_id
    WHERE topic_id=:topic');
    $query->bindValue(':topic',$topic,PDO::PARAM_INT);
    $query->execute();
    $data = $query->fetch();
    $forum = $data->forum_id;

    //Ensuite on vérifie que le membre a le droit d'être ici
    //c'est-à-dire si c'est un modo / admin

    if (!verif_auth($data->auth_modo))
    {
    }
    else //Sinon ça roule et on continue
    {
        $query->CloseCursor();

        //On compte le nombre de post du topic
        $query=$pdo->prepare('SELECT topic_post FROM forum_topic WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetch();
        $nombrepost = $data->topic_post + 1;
        $query->CloseCursor();

        //On supprime le topic
        $query=$pdo->prepare('DELETE FROM forum_topic
        WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();


        //Et on supprime les posts !
        $query=$pdo->prepare('DELETE FROM forum_post WHERE topic_id = :topic');
        $query->bindValue(':topic',$topic,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

        //Dernière chose, on récupère le dernier post du forum
        $query=$pdo->prepare('SELECT post_id FROM forum_post
        WHERE post_forum_id = :forum ORDER BY post_id DESC LIMIT 0,1');
        $query->bindValue(':forum',$forum,PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetch();

        //Ensuite on modifie certaines valeurs :
        $query=$pdo->prepare('UPDATE forum_forum
        SET forum_topic = forum_topic - 1, forum_post = forum_post - :nbr, forum_last_post_id = :id
        WHERE forum_id = :forum');
        $query->bindValue(':nbr',$nombrepost,PDO::PARAM_INT);
        $query->bindValue(':id',$data->post_id,PDO::PARAM_INT);
        $query->bindValue(':forum',$forum,PDO::PARAM_INT);
        $query->execute();
        $query->CloseCursor();

        //Enfin le message
        echo'<div style="background-color: #F72424; color: black; border: 1px solid black; padding-left: 10px; padding: 3px;"><p>Le topic a bien été supprimé !<br />
        Cliquez <a href="./index.php">ici</a> pour revenir à l\'index du forum</div></p>';

    } //Fin du else
break;
case "repondremp": //Si on veut répondre

    //On récupère le titre et le message
    $message = stripslashes(htmlspecialchars(addslashes($_POST['message'])));
    $titre = stripslashes(htmlspecialchars(addslashes($_POST['titre'])));
    $temps = time();

    //On récupère la valeur de l'id du destinataire
    $dest = (int) $_GET['dest'];

    //Enfin on peut envoyer le message

    $query = $pdo->prepare('INSERT INTO forum_mp
    (mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu)
    VALUES(:id, :dest, :titre, :txt, :tps, "0")');
    $query->bindValue(':id',$id,PDO::PARAM_INT);
    $query->bindValue(':dest',$dest,PDO::PARAM_INT);
    $query->bindValue(':titre',$titre,PDO::PARAM_STR);
    $query->bindValue(':txt',$message,PDO::PARAM_STR);
    $query->bindValue(':tps',$temps,PDO::PARAM_INT);
    $query->execute();
    echo'<div class="alert alert-success" role="alert">Votre message a bien été envoyé !</div>';
    echo '<script>location.href="messagesprives.php";</script>';

    break;

    case "nouveaump": //On envoie un nouveau mp

    //On récupère le titre et le message
    $message = addslashes(stripslashes(htmlspecialchars($_POST['message'])));
    $titre = addslashes(stripslashes(htmlspecialchars($_POST['titre'])));
    $temps = time();
    $dest = addslashes(stripslashes(htmlspecialchars($_POST['to'])));

    //On récupère la valeur de l'id du destinataire
    //Il faut déja vérifier le nom

    $query=$pdo->prepare('SELECT id FROM users
    WHERE LOWER(username) = :dest');
    $query->bindValue(':dest',$dest,PDO::PARAM_STR);
    $query->execute();
    if($data = $query->fetch())
    {
        $query=$pdo->prepare('INSERT INTO forum_mp
        (mp_expediteur, mp_receveur, mp_titre, mp_text, mp_time, mp_lu)
        VALUES(:id, :dest, :titre, :txt, :tps, :lu)');
        $query->bindValue(':id',$id,PDO::PARAM_INT);
        $query->bindValue(':dest',(int) $data['id'],PDO::PARAM_INT);
        $query->bindValue(':titre',$titre,PDO::PARAM_STR);
        $query->bindValue(':txt',$message,PDO::PARAM_STR);
        $query->bindValue(':tps',$temps,PDO::PARAM_INT);
        $query->bindValue(':lu','0',PDO::PARAM_STR);
        $query->execute();
        $query->CloseCursor();

       echo'<div class="alert alert-success" role="alert"><p>Votre message a bien été envoyé !</div>
       <br /><br />Cliquez <a href="./index.php">ici</a> pour revenir à l index du
       forum<br />
       <br />Cliquez <a href="./messagesprives.php">ici</a> pour retourner à
       la messagerie</p>';
       echo '<script>location.href="messagesprives.php";</script>';
    }
    //Sinon l'utilisateur n'existe pas !
    else
    {
        echo'<div class="alert alert-danger" role="alert"><p>Désolé ce membre n\'existe pas, veuillez vérifier et
        réessayez à nouveau.</div></p>';
         header('Location: messagesprives.php');
         $_SESSION['flash']['danger'] = "<div class='alert alert-danger' role='alert'>Ce membre n'existe pas. Veuillez recommencer avec un membre valide !</div>";
    }
    break;
    default;
    echo'<div class="alert alert-danger" role="alert"><p>Cette action est imposssible !</div></p>';
} ?>
