<?php 
session_start();
include('../membres/base.php');
include('../membres/functions.php');
include('../membres/bbcode.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
    $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $user->execute(array($_SESSION['auth']['id']));
    $utilisateur = $user->fetch();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mangas'Fan - Messagerie privée</title>
    <link rel="icon" href="../images/favicon.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/jquery.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div id="bloc_page">
        <?php include('../elements/header.php'); ?>
        <section class="marge_page">
            <?php if(!isset($_SESSION['auth'])){ ?>
                <div class='alert alert-danger' role='alert'>
                    Vous n'avez pas les permissions requises pour accéder à cette page.
                </div>
                <script>location.href="../index.php";</script>
            <?php } else { 
               $action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
                switch($action)
                {
                    case "consulter":
                    ?>
                    <span class="texte_messagerie">
                        Vous êtes ici : <a href="../index.php">Index du site</a> -> <a href="./messagesprives.php">Messagerie privée</a> -> Consulter un message
                    </span>
                    <?php
                    $id_mess = (int) $_GET['id']; 
                    ?>
                    <h1 id="titre_messages">
                        Consulter <span class="couleur_mangas">un</span> <span class="couleur_fans">message</span>
                    </h1>
                    <?php
                //La requête nous permet d'obtenir les infos sur ce message :
                    $query = $pdo->prepare('SELECT  mp_expediteur, mp_receveur, mp_titre, mp_time, mp_text, mp_lu, id, username, avatar FROM forum_mp LEFT JOIN users ON id = mp_expediteur WHERE mp_id = :id');
                    $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
                    $query->execute();
                    $data = $query->fetch();
                    ?>
                    <?php if (isset($_SESSION['auth']) AND $utilisateur['grade'] >= 2)
                    { ?>
                       <a href="./messagesprives.php?action=repondre&amp;dest=<?= $data['mp_expediteur']; ?>">
                        <img src="../images/rep.png" class="image_enveloppe_mp" alt="Répondre"
                        title="Répondre à ce message" />
                        <br/><br/>
                    </a>
                    <?php
                }
                $query = $pdo->prepare('SELECT  mp_expediteur, mp_receveur, mp_titre,
                    mp_time, mp_text, mp_lu, id, username, avatar, grade
                    FROM forum_mp
                    LEFT JOIN users ON id = mp_expediteur
                    WHERE mp_id = :id');
                $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
                $query->execute();
                $data = $query->fetch();
                ?>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-2" id="avatar_mp">
                                <?php if (!empty($data['avatar'])){
                                    if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $data['avatar'])) { ?>
                                        <center>
                                            <img src="../membres/images/avatars/<?php echo $data['avatar']; ?>" alt="avatar" class="avatar_mp" title="Avatar de <?php echo $data['username'] ?>"/>
                                        </center> <!-- via fichier -->
                                    <?php } else { ?>
                                        <center>
                                            <img src="<?php echo sanitize($data['avatar']); ?>" alt="avatar" class="avatar_mp" title="Avatar de <?php echo $data['username']; ?>"/>
                                        </center><br/> <!-- via site url -->
                                    <?php }  } ?>   
                                </div>
                                <div class="col-sm-10" id="entete_mp">
                                    <?php echo 'Titre : ' .sanitize($data['mp_titre']).'' ?><br/>
                                    <span class="message_rappel">
                                        Ce message a été envoyé à <?php echo ''.date('H\hi \l\e d M Y',$data['mp_time']).'' ?> par <i><?php echo'<a href="./voirprofil.php?m='.$data['id'].'&amp;action=consulter" title="Accéder au profil du membre">'.stripslashes(htmlspecialchars($data['username'])).'</a>';?>
                                    </i><br/><br/>
                                    Rang : <?php echo statut(sanitize($data['grade'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        echo nl2br(htmlspecialchars_decode(sanitize(bbcode($data['mp_text'])))).'';
                        ?>
                    </div>
                </div>
                <?php
        if ($data['mp_lu'] == 0) //Si le message n'a jamais été lu
        {
            $query->CloseCursor();
            $query = $pdo->prepare('UPDATE forum_mp
                SET mp_lu = :lu
                WHERE mp_id= :id');
            $query->bindValue(':id',$id_mess, PDO::PARAM_INT);
            $query->bindValue(':lu','1', PDO::PARAM_STR);
            $query->execute();
            $query->CloseCursor();
        }
        //Ici on a besoin de la valeur de l'id du mp que l'on veut lire
        break;
        case "nouveau":
        if (isset($_SESSION['auth']) AND $utilisateur['grade'] >=2){ 
            ?>
            <span class="texte_messagerie">
                <p>
                    <i>Vous êtes ici</i> : <a href="../index.php">Index du site</a> -> <a href="./messagesprives.php">Messagerie privée</a> -> Rédiger un message
                </p>
                <h1 class="nouveau_mp">
                    Nouveau <span class="couleur_mangas">message</span> <span class="couleur_fans">privé</span>
                </h1><br/>
            </span>
            <form method="post" action="postok.php?action=nouveaump" name="formulaire">
                <label for="to">Envoyer à : </label>
                <input type="text" size="30" id="to" name="to" class="form-control" placeholder="Entrez le pseudo du membre sans faute" /> <br/>
                <label for="titre">Titre : </label>
                <input type="text" size="80" id="titre" name="titre" class="form-control" value="Sans Titre"/>
                <br/>   
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
                <a href="../membres/bbcode_active.html" class="bbcode">
                    Cliquez ici pour voir la liste de BBCode et smileys disponible
                </a><br/><br/>
                <fieldset>
                    <legend class="rediger_mp">
                        Rédiger un <span class="couleur_mangas">message</span> <span class="couleur_fans">privé</span>
                    </legend>
                    <textarea cols="80" rows="8" id="message" style="font-family:Tahoma; font-size:13px;" class="form-control" name="message" placeholder="Entrez ici le message que vous souhaitez envoyer à votre destinataire !"></textarea>
                </fieldset>
                <input type="submit" name="submit" value="Envoyer le message" class="btn btn-sm btn-info"/>
                <input type="reset" name = "Effacer" value = "Vider" class="btn btn-sm btn-danger" />
            </p>
        </form>
    <?php }
    else
    {
        echo "<div class='alert alert-danger' role='alert'>Vous ne pouvez pas envoyer de MP étant donné que vous êtes banni.</div>";
    }
        //Ici on a besoin de la valeur d'aucune variable :p
    break;
    case "repondre":
    ?>
    <span class="texte_messagerie">
        <p>
            <i>Vous êtes ici</i> : <a href="../index.php">Index du site</a> -> <a href="./messagesprives.php">Messagerie privée</a> -> Répondre à un message
        </p>
    </span>
    <h1 class="envoyer_mp">
        Répondre à un <span class="couleur_mangas">message</span> <span class="couleur_fans">privé</span>
    </h1><br/><br/>
    <?php
    $dest = $_GET['dest'];
    ?>
    <form method="post" action="postok.php?action=repondremp&amp;dest=<?php echo $dest; ?>" name="formulaire">
        <p>
            <label for="titre">Titre : </label>
            <input type="text" size="80" id="titre" name="titre" value="Sans Titre" class="form-control"/>
            <br/><br/>
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
            <a href="bbcode.php" class="lien_bbcode">
                Cliquez ici pour retrouver la liste des BBCode disponibles
            </a>
            <fieldset>
                <legend class="repondre_mp">
                    Répondre à un message privé
                </legend>
                <textarea cols="80" rows="8" id="message" style="font-family:Tahoma; font-size:13px;" class="form-control" name="message" placeholder="Mettez ici le message que vous souhaitez envoyer comme réponse !"></textarea>
            </fieldset>
            <input type="submit" name="submit" value="Envoyer le message" class="btn btn-sm btn-info"/>
            <input type="reset" name = "Effacer" value = "Vider" class="btn btn-sm btn-danger" />
        </p>
    </form>
    <?php
    break;
        case "supprimer": //4eme cas : on veut supprimer un mp reçu
        //On récupère la valeur de l'id
        $id_mess = (int) $_GET['id'];
        //Il faut vérifier que le membre est bien celui qui a reçu le message
        $query = $pdo->prepare('SELECT mp_receveur
            FROM forum_mp WHERE mp_id = :id');
        $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetch();
            //Sinon la sanction est terrible :p
        if ($id_mess != $data['mp_receveur']) {};
        $query->CloseCursor();
        //2 cas pour cette partie : on est sûr de supprimer ou alors on ne l'est pas
        $sur = (int) $_GET['sur'];
        //Pas encore certain
        if ($sur == 0)
        {
            ?>
            <div class="alert alert-danger" role="alert">
                Voulez-vous supprimer ce message de manière définitive ? Il ne sera pas récupérable après !
            </div>
            <a href="./messagesprives.php?action=supprimer&amp;id=<?php echo $id_mess; ?>&amp;sur=1" class="btn btn-sm btn-info">Oui</a> - <a href="./messagesprives.php" class="btn btn-sm btn-danger">Non</a>
            <?php
        }
            //Certain
        else
        {
            $query=$pdo->prepare('DELETE from forum_mp WHERE mp_id = :id');
            $query->bindValue(':id',$id_mess,PDO::PARAM_INT);
            $query->execute();
            $query->CloseCursor(); 
            ?>
            <p>
                <div class="alert alert-success" role="alert">Votre message a bien été supprimé de manière définitive !
                </div><br />
                Cliquez <a href="./messagesprives.php">ici</a> pour revenir à la boite de messagerie
            </p>
            <?php   
        }
                //Ici on a besoin de la valeur de l'id du mp à supprimer
        break;
        default;
        ?>
        <span class="texte_messagerie">
            <p><i>Vous êtes ici</i> : <a href="../index.php">Index du site</a> -> <a href="./messagesprives.php">Messagerie privée</a>
                <?php
                $query = $pdo->prepare('SELECT mp_lu, mp_id, mp_expediteur, mp_titre, mp_time, id, username
                    FROM forum_mp
                    LEFT JOIN users ON forum_mp.mp_expediteur = users.ID
                    WHERE mp_receveur = :id ORDER BY mp_id DESC');
                $query->bindValue(':id',$_SESSION['auth']['id'],PDO::PARAM_INT);
                $query->execute();
                if (isset($_SESSION['auth']) AND $utilisateur['grade'] >= 2){?>
                    <p>
                        <a href="./messagesprives.php?action=nouveau">
                            <img src="../images/nouv.png" class="image_enveloppe_mp" alt="Nouveau" title="Nouveau message" />
                        </a></p></span>
                        <?php
                    }
                    if ($query->rowCount()>=0)
                    {
                        ?>
                        <table class="table table-striped">
                          <thead>
                            <tr>
                                <th></th>
                                <th class="mp_titre"><strong>Titre</strong></th>
                                <th class="mp_expediteur"><strong>Expéditeur</strong></th>
                                <th class="mp_time"><strong>Date de réception</strong></th>
                                <th><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <?php
        //On boucle et on remplit le tableau
                        while ($data2 = $query->fetch())
                        {
                            ?><tr><?php
            //Mp jamais lu, on affiche l'icone en question
                            if($data2['mp_lu'] == 0)
                            {
                                ?><td><img src="../images/message_nonlu.png" class="image_enveloppe_mp_accueil" alt="Non lu" /></td><?php
                            }
            else //sinon une autre icone
            {
               ?><td><img src="../images/mp_lu.png" class="image_enveloppe_mp_accueil" alt="Déja lu" /></td><?php
           }
           echo'
           <td id="mp_titre">
           <a href="./messagesprives.php?action=consulter&amp;id='.$data2['mp_id'].'">
           '.stripslashes($data2['mp_titre']).'
           </a>
           </td>
           <td id="mp_expediteur">
           <a href="./voirprofil.php?action=consulter&amp;m='.$data2['mp_expediteur'].'">
           '.stripslashes($data2['username']).'
           </a>
           </td>
           <td id="mp_time">'.date('H\hi \l\e d M Y',$data2['mp_time']).'</td>
           <td>
           <a href="./messagesprives.php?action=supprimer&amp;id='.$data2['mp_id'].'&amp;sur=0">Supprimer</a></td></tr>';
        } //Fin de la boucle
        $query->CloseCursor();
        echo '</table>';

    } //Fin du if
    else
    { ?>
        <p>Vous n'avez aucun message privé pour l'instant, cliquez
        <a href="./index.php">ici</a> pour revenir à la page d index</p>
    <?php } } } ?>
        </section>
        <?php include('../elements/footer.php'); ?>
    </div>
</body>
</html>