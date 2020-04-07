<h1 class="titre"><?= $billet['titre'] ?></h1>
<hr>
<?= $billet['contenu'] ?>
<p class="auteur-news"><small>News rédigée le <?= $billet['date_creation'] ?> par <a href="#"><?= $billet['username'] ?></a></small></p>
<hr>
<div class="container">
    <div class="row">
        <div class="col-lg-5">
            <div class="card" style="width: 25rem;">
              <div class="card-header">
                A propos de l'auteur
            </div>
            <div class="bloc-auteur">
                <div class="row">
                    <div class="col-lg-3">
                        <img src="https://www.mangasfan.fr/membres/images/avatars/<?= $billet['avatar']; ?>" alt="Avatar de <?= $billet['username'] ?>" class="auteur-avatar" />
                    </div>
                    <div class="col-lg-9 a-propos-auteur">
                        <h5><?= $billet['username']; ?></h5>
                        <hr>
                        <small><i>« <?= $billet['role']; ?> »</i></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
       <div class="col-lg-4">
        <div class="card" style="width: 18rem;">
          <div class="card-header">
            A propos de la news
        </div>
        <div class="bloc-auteur">
            <?php if($billet['categorie'] != "Site"){ ?>
                Cette news appartient à la catégorie « <a href="#"><strong><?= $billet['categorie']; ?></strong></a> ».<br/>
                Cliquez sur le lien ci-dessus pour accéder à ces derniers.
            <?php } else { ?>
                Cette news appartient à la catégorie « <strong>Site</strong> »
            <?php } ?>
        </div>
    </div>
</div>
</div>
</div>
</div>
<hr>
<h2>Espace commentaires :</h2>
<?php if (count($commentaires) === 0): ?>
    <div class="alert alert-primary" role="alert">Cet article n'a pas encore de commentaire ! N'hésitez pas à en poster !</div>
    <?php else: ?>
        <div class="alert alert-primary" role="alert">Il y a déjà <?= count($commentaires) ?> commentaires. </div>
        <?php foreach ($commentaires as $commentaire): ?>
            <div class="container">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="avatar-news" style="box-shadow: 0px 0px 2px 2px <?= Color::rang_etat($commentaire['grade']) ?>; background:url('https://www.mangasfan.fr/membres/images/avatars/<?= $commentaire['avatar'] ?>');background-size:100px; background-position: center;"/>
                        </div>
                        <span class="pseudo">
                            <span style="color: <?= Color::rang_etat($commentaire['grade']); ?>"><?= $commentaire['username']; ?></span>
                        </span>
                    </div>
                    <div class="col-lg-10">
                        Partie commentaire
                    </div>
                </div>
            </div>






            <h3>Commentaire de <?= $commentaire['username'] ?></h3>
            <small>Le <?= $commentaire['date_commentaire'] ?></small>
            <blockquote>
                <em><?= $commentaire['commentaire'] ?></em>
            </blockquote>
            <a href="commentaire.php?controller=comment&task=delete&id=<?= $commentaire['id_commentaire'] ?>" onclick="return window.confirm(`Êtes vous sûr de vouloir supprimer ce commentaire ?!`)">Supprimer</a>
        <?php endforeach ?>
        <?php endif ?>