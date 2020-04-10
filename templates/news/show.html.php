<h1 class="titre"><?= $news['title'] ?></h1>
<hr>
<?= $news['contenu'] ?>
<p class="auteur-news"><small>News rédigée le <?= $news['create_date'] ?> par <a href="#"><?= $news['username'] ?></a></small></p>
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
                        <img src="https://www.mangasfan.fr/membres/images/avatars/<?= $news['avatar']; ?>" alt="Avatar de <?= $news['username'] ?>" class="auteur-avatar" />
                    </div>
                    <div class="col-lg-9 a-propos-auteur">
                        <h5><?= $news['username']; ?></h5>
                        <hr>
                        <small><i>« <?= $news['role']; ?> »</i></small>
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
            <?php if($news['category'] != "Site"){ ?>
                Cette news appartient à la catégorie « <a href="#"><strong><?= $news['category']; ?></strong></a> ».<br/>
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
<?php if (count($commentaires) === 0){ ?>
    <div class="alert alert-primary" role="alert">Cet article n'a pas encore de commentaire ! N'hésitez pas à en poster !</div>
<?php } elseif(count($commentaires) === 1){ ?>
    <div class="alert alert-primary" role="alert">Il y a déjà <?= count($commentaires) ?> commentaire. </div>
<?php } else { ?>
    <div class="alert alert-primary" role="alert">Il y a déjà <?= count($commentaires) ?> commentaires. </div>
<?php } ?>
<?php foreach ($commentaires as $commentaire): ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-3" style="border-right: 1px solid <?= Color::rang_etat($commentaire['grade']) ?>">
                <div class="avatar-news" style="box-shadow: 0px 0px 2px 2px <?= Color::rang_etat($commentaire['grade']) ?>; background:url('https://www.mangasfan.fr/membres/images/avatars/<?= $commentaire['avatar'] ?>');background-size:100px; background-position: center;"/>
                </div>
                <p class="pseudo">
                    <a href="#" style="color: <?= Color::rang_etat($commentaire['grade']); ?>"><?= $commentaire['username']; ?></a><br/>
                    <span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($commentaire['grade']) ?>;"><?= Color::getRang($commentaire['grade'], $commentaire['chef']) ?></span><br/><br/>
                    <?php if (isset($_SESSION['auth'])) { 
                        if ($commentaire['auteur_commentaire'] == $utilisateur['id_user']) {  ?>
                         <a href="../news/edit_comment.php?id=<?= $commentaire['id_commentary'] ?>"class="btn btn-sm btn-outline-info">Editer</a>
                         <a href="../news/delete_comment.php?id=<?= $commentaire['id_commentary'] ?>&news=<?= $news['id_news'] ?>" onclick="return window.confirm(`Êtes vous sûr de vouloir supprimer ce commentaire ?!`)" class="btn btn-sm btn-outline-danger">Supprimer</a>
                     <?php } elseif ($utilisateur['grade'] >= 6 && $utilisateur['grade'] <= 10) { ?>
                      <a href="../news/delete_comment.php?id=<?= $commentaire['id_commentary'] ?>&news=<?= $news['id_news'] ?>" onclick="return window.confirm(`Êtes vous sûr de vouloir supprimer ce commentaire ?!`)" class="btn btn-sm btn-outline-danger">Supprimer</a>
                  <?php }
              } ?>
          </p>
      </div>
      <div class="col-lg-9">
        <?= nl2br($commentaire['commentary']) ?>
        <div class="bottom">
            <small>Commentaire posté le <?= date('d/m/Y à H:i', strtotime($commentaire['posted_date'])) ?></small>
        </div>
    </div>
</div>
</div>
<hr>
<?php endforeach ?>