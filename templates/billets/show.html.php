<h1 class="titre"><?= $billet['titre'] ?></h1>
<hr>
<?= $billet['contenu'] ?>
<p class="auteur-news"><small>News rédigée le <?= $billet['date_creation'] ?> par <a href="#"><?= $billet['username'] ?></a></small></p>
<hr>
<h2>Espace commentaires :</h2>
<?php if (count($commentaires) === 0): ?>
    <div class="alert alert-primary" role="alert">Cet article n'a pas encore de commentaire ! N'hésitez pas à en poster !</div>
    <?php else: ?>
        <div class="alert alert-primary" role="alert">Il y a déjà <?= count($commentaires) ?> commentaires. </div>
        <?php foreach ($commentaires as $commentaire): ?>
            <h3>Commentaire de <?= $commentaire['username'] ?></h3>
            <small>Le <?= $commentaire['date_commentaire'] ?></small>
            <blockquote>
                <em><?= $commentaire['commentaire'] ?></em>
            </blockquote>
            <a href="commentaire.php?controller=comment&task=delete&id=<?= $commentaire['id_commentaire'] ?>" onclick="return window.confirm(`Êtes vous sûr de vouloir supprimer ce commentaire ?!`)">Supprimer</a>
        <?php endforeach ?>
        <?php endif ?>