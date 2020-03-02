<h1><?= $billet['titre'] ?></h1>
<small>Ecrit le <?= $billet['date_creation'] ?></small>
<p><?= $billet['description'] ?></p>
<hr>
<?= $billet['contenu'] ?>

<?php if (count($commentaires) === 0) : ?>
    <h2>Il n'y a pas encore de commentaires pour cet billet ... SOYEZ LE PREMIER ! :D</h2>
<?php else : ?>
    <h2>Il y a déjà <?= count($commentaires) ?> réactions : </h2>
    <?php foreach ($commentaires as $commentaire) : ?>
        <h3>Commentaire de <?= $commentaire['username'] ?></h3>
        <small>Le <?= $commentaire['date_commentaire'] ?></small>
        <blockquote>
            <em><?= $commentaire['commentaire'] ?></em>
        </blockquote>
        <a href="commentaire.php?controller=comment&task=delete&id=<?= $commentaire['id_commentaire'] ?>" onclick="return window.confirm(`Êtes vous sûr de vouloir supprimer ce commentaire ?!`)">Supprimer</a>
    <?php endforeach ?>
<?php endif ?>