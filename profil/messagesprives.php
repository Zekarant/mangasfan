<?php 
session_start();
include('../membres/base.php');
include('../membres/functions.php');
if (!isset($_SESSION['auth'])) {
    header('Location: ..');
    exit();
}
// Modifier
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):'';
switch ($action) {
    case 'consulter':
    $id_message = (int) $_GET['id'];
    $consulter_mp = $pdo->prepare('SELECT m.mp_id, m.mp_expediteur, m.mp_receveur, m.mp_titre, m.mp_time, m.mp_text, m.mp_lu, u.id, u.username, u.avatar, u.grade, u.sexe FROM forum_mp m LEFT JOIN users u ON u.id = m.mp_expediteur WHERE m.mp_id = :id');
    $consulter_mp->bindValue(':id',$id_message,PDO::PARAM_INT);
    $consulter_mp->execute();
    $consulter = $consulter_mp->fetch();
    if (isset($_SESSION['auth'])) {
        if ($consulter['mp_receveur'] != $utilisateur['id']) {
        header('Location: messagesprives.php');
        exit();
        }
    }
    if ($consulter['mp_lu'] == 0){
        $query = $pdo->prepare('UPDATE forum_mp SET mp_lu = :lu WHERE mp_id= :id');
        $query->bindValue(':id',$id_message, PDO::PARAM_INT);
        $query->bindValue(':lu','1', PDO::PARAM_STR);
        $query->execute();
        $query->CloseCursor();
    }
    break;
    case 'nouveau':
    if (isset($_SESSION['auth']) && $utilisateur['grade'] <= 1) {
        header('Location: messagesprives.php');
        exit();
    }
    break;
    case 'repondre':
    if (isset($_SESSION['auth']) && $utilisateur['grade'] <= 1) {
        header('Location: messagesprives.php');
        exit();
    }
    
    $recuperation_mp = $pdo->prepare('SELECT m.mp_id, m.mp_titre, u.id, u.username FROM forum_mp m INNER JOIN users u ON m.mp_expediteur = u.id WHERE mp_receveur = ? ORDER BY mp_time DESC');
    $recuperation_mp->execute(array($_GET['dest']));
    $consulter = $recuperation_mp->fetch();
    break;
    case 'supprimer':
    $id_message = (int) $_GET['id'];
    $query = $pdo->prepare('SELECT mp_receveur FROM forum_mp WHERE mp_id = :id');
    $query->bindValue(':id', $id_message, PDO::PARAM_INT);
    $query->execute();
    $supprimer = $query->fetch();
    if ($utilisateur['id'] != $supprimer['mp_receveur']){;
        header('Location: messagesprives.php');
        exit();
    }
    $sur = (int) $_GET['sur'];
    if ($sur == 0){ 
        $couleur = "danger";
        $texte = "Voulez-vous supprimer ce message de manière définitive ? Toute suppression est définitive.";
    } else { 
        $query = $pdo->prepare('DELETE from forum_mp WHERE mp_id = :id');
        $query->bindValue(':id', $id_message, PDO::PARAM_INT);
        $query->execute();
        header('Location: messagesprives.php');
        exit();
    }
    break;
    default:
    $recuperation_lecture = $pdo->prepare('SELECT m.mp_lu, m.mp_id, m.mp_expediteur, m.mp_titre, m.mp_time, u.id, u.username FROM forum_mp m INNER JOIN users u ON m.mp_expediteur = u.id WHERE m.mp_receveur = ? ORDER BY mp_time DESC');
    $recuperation_lecture->execute(array($utilisateur['id']));
}
$recuperation_mp = $pdo->prepare('SELECT m.mp_id, u.id, u.username FROM forum_mp m INNER JOIN users u ON m.mp_expediteur = u.id WHERE mp_receveur = ? ORDER BY mp_time DESC');
$recuperation_mp->execute(array($utilisateur['id']));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mes messages privés - Mangas'Fan</title>
    <link rel="icon" href="../images/favicon.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
    <?php include('../elements/header.php');
    include('../membres/bbcode.php'); ?>
    <section>
        <?php include('../elements/messages.php'); ?>
        <?php switch ($action) {
            case 'consulter': ?>
            <h1 class="titre_principal_news">Consulter un message</h1>
            <hr>
            <a href="messagesprives.php" class="btn btn-outline-primary">Retourner sur l'index de ma messagerie</a>
            <?php if(isset($_SESSION['auth']) && $utilisateur['grade'] >= 2){ ?>
                <a href="messagesprives.php?action=repondre&amp;dest=<?= sanitize($consulter['mp_expediteur']); ?>" class="btn btn-outline-primary">Répondre à ce MP</a>
            <?php } ?>
            <a href="messagesprives.php?action=supprimer&id=<?= sanitize($consulter['mp_id']); ?>&sur=0" class="btn btn-outline-danger">Supprimer ce MP</a>
            <hr>
            <div class="card">
                <div class="card-header">
                    <div class="media">
                        <?php if (!empty($consulter['avatar'])) {
                            if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $consulter['avatar'])) { ?>
                                <img src="../membres/images/avatars/<?= sanitize($consulter['avatar']); ?>" alt="avatar" class="align-self-center mr-3" title="Avatar de <?= sanitize($consulter['username']); ?>" style="max-height: 200px; max-width: 150px;" />
                            <?php } else { ?>
                                <img src="<?= sanitize($consulter['avatar']); ?>" alt="avatar" class="align-self-center mr-3" title="Avatar de <?= sanitize($consulter['username']); ?>" style="max-height: 200px; max-width: 150px;"/>
                            <?php }
                        } ?>
                        <div class="media-body">
                            <h5 class="mt-0"><?= sanitize($consulter['mp_titre']); ?></h5>
                            <hr>
                            <p>Vous avez reçu ce message le <?= date('d M Y à H\hi', sanitize($consulter['mp_time'])); ?>.</p>
                            <p>Ce message vous a été envoyé par <a href="profil-<?= sanitize($consulter['id']); ?>"><?= sanitize($consulter['username']); ?></a>.</p>
                            <p>Sur le site, cette personne est <?= statut($consulter['grade'], $consulter['sexe']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars_decode(sanitize(bbcode($consulter['mp_text'])))); ?>
                </div>
            </div>
            <?php break;
            case 'supprimer':
            if ($sur == 0) { ?>
                <div class='alert alert-<?= sanitize($couleur); ?>' role='alert'>
                    <?= sanitize($texte); ?>
                </div>
                <a href="messagesprives.php?action=supprimer&id=<?= sanitize($id_message); ?>&sur=1" class="btn btn-sm btn-info">Oui</a> - <a href="./messagesprives.php" class="btn btn-sm btn-danger">Non</a>
            <?php }
            break;
            case 'nouveau': ?>
            <h1 class="titre_principal_news">Envoyer un message privé - Mangas'Fan</h1>
            <hr>
            <a href="messagesprives.php" class="btn btn-outline-primary">Retourner sur l'index de ma messagerie</a>
            <hr>
            <div class="container">
                <form method="post" action="postok.php?action=nouveaump">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Destinataire :</label>
                            <input type="text" name="to" class="form-control" placeholder="Saisir le pseudo du membre" />
                            <br/>
                            <label>Titre du message :</label>
                            <input type="text" name="titre" class="form-control" value="Sans titre"/>
                            <input type="submit" name="submit" value="Envoyer le message" class="btn btn-sm btn-info"/>
                        </div>
                        <div class="col-md-6">
                            <label>Contenu du message :</label><br/>
                            <a href="../membres/bbcode_active.html" class="bbcode">Cliquez ici pour voir la liste de BBCode et smileys disponible</a>
                            <textarea cols="80" rows="8" class="form-control" name="message" placeholder="Entrez ici le message que vous souhaitez envoyer à votre destinataire !"></textarea>
                            <input type="reset" name="Effacer" value="Vider" class="btn btn-sm btn-danger" />
                        </div>
                    </div>
                </form>
            </div>
            <?php break;
            case 'repondre': ?>
            <h1 class="titre_principal_news">Répondre à un message privé - Mangas'Fan</h1>
            <hr>
            <a href="messagesprives.php" class="btn btn-outline-primary">Retourner sur l'index de ma messagerie</a>
            <hr>
            <div class="container">
                <form method="post" action="postok.php?action=repondremp&dest=<?= sanitize($_GET['dest']); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Titre du MP :</label>
                            <input type="text" name="titre" value="[Re] <?= $consulter['mp_titre']; ?>" class="form-control"/>
                            <input type="submit" name="submit" value="Envoyer le message" class="btn btn-sm btn-info"/>
                        </div>
                        <div class="col-md-6">
                            <label>Réponse au message :</label><br/>
                            <a href="../membres/bbcode_active.php" class="lien_bbcode">Cliquez ici pour retrouver la liste des BBCode disponibles</a>
                            <textarea cols="80" rows="8" class="form-control" name="message" placeholder="Mettez ici le message que vous souhaitez envoyer comme réponse !"></textarea>
                            <input type="reset" name = "Effacer" value = "Vider" class="btn btn-sm btn-danger" />
                        </div>
                    </div>
                </form>
            </div>
            <?php break;
            default: ?>
            <h1 class="titre_principal_news">Mes messages privés - Mangas'Fan</h1>
            <hr>
            <?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 2){ ?>
                <a href="messagesprives.php?action=nouveau" class="btn btn-outline-primary">Envoyer un MP</a>
                <br/><br/>
            <?php } if($recuperation_mp->rowCount() == 0){ ?>
                <div class="alert alert-info" role="alert">
                    Vous n'avez aucun message privé ! Vous ne pouvez donc rien consulter...
                </div>
            <?php } else { ?>
                <table class="table">
                    <thead>
                        <th>Status</th>
                        <th>Titre</th>
                        <th>Expéditeur</th>
                        <th>Date de réception</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php while($messages = $recuperation_lecture->fetch()){ ?>
                            <tr>
                                <td>
                                    <?php if ($messages['mp_lu'] == 0) { ?>
                                        <img src="../images/mp_nonlu.png" class="image_enveloppe_mp_accueil" alt="Non lu" />
                                    <?php } else { ?>
                                        <img src="../images/mp_lu.png" class="image_enveloppe_mp_accueil" alt="Déja lu" />
                                    <?php } ?>
                                </td>
                                <td><a href="messagesprives.php?action=consulter&id=<?= sanitize($messages['mp_id']); ?>"><?= sanitize($messages['mp_titre']); ?></a></td>
                                <td><a href="profil-<?= sanitize($messages['id']); ?>"><?= sanitize($messages['username']); ?></a></td>
                                <td><?= date('\L\e d M Y à H\hi', sanitize($messages['mp_time'])); ?></td>
                                <td><a href="messagesprives.php?action=supprimer&id=<?= sanitize($messages['mp_id']); ?>&sur=0" class="btn btn-outline-danger">Supprimer</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php }
            break;
        } ?>
    </section>
    <?php include('../elements/footer.php'); ?>
</body>
</html>