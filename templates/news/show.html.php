<h1 class="titre"><?= $news['title'] ?></h1>
<hr>
<?= $news['contenu'] ?>
<p class="auteur-news"><small>News rédigée le <?= $news['create_date'] ?> par <a href="#"><?= $news['username'] ?></a></small></p>
<hr>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
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
    <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            A propos de la news
        </div>
        <div class="bloc-auteur">
            <?php if($news['category'] != "Site"){ ?>
                Cette news appartient à la catégorie « <a href="#"><strong><?= $news['category']; ?></strong></a> ».<br/><br/>
                Par ailleurs, nous avons une page concernée aux animes sur le site, consultez-là <a href="#">ici</a>.
            <?php } else { ?>
                Cette news appartient à la catégorie « <strong>Site</strong> »
            <?php } ?>
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
<?php }
if (isset($_SESSION['auth']) && $utilisateur['grade'] == 0) { ?>
    <div class="alert alert-danger" role="alert">
        Vous avez été banni des services de Mangas'Fan, vous ne pouvez donc pas poster de nouveaux commentaires.
    </div>
<?php } elseif (isset($_SESSION['auth']) && $utilisateur['grade'] != 0) { ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h4>Ajouter un commentaire :</h4>
                <hr>
                <h5>Règles de l'espace commentaires</h5>
                <small>
                    <ul>
                        <li>Restez courtois lorsque vous postez un commentaire.</li>
                        <li>Merci de respecter les avis des autres membres.</li>
                        <li>Lorsque vous postez un commentaire, merci de respecter le sujet de la news.</li>
                        <li>Tout abus de l'espace commentaires sera sanctionné.</li>
                        <li>Respecter le travail de l'auteur.</li>
                    </ul>
                </small>
            </div>
            <div class="col-lg-8">
                <form method="POST" action="">
                    <textarea name="comme" class="form-control" rows="10" placeholder="Écrivez-ici votre commentaire."></textarea>
                    <div class="text-center">
                        <input type="submit" name="envoyer_commentaire" class="btn btn-info btn-sm" value="Poster mon commentaire">
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="alert alert-danger" role="alert">
        Vous devez être connecté pour pouvoir poster une commentaire ! <a href="#">Me connecter</a> ou <a href="#">m'inscrire</a>.
    </div>
<?php }
foreach ($commentaires as $commentaire): ?>
    <hr>
    <div class="container">
        <div class="row">
            <div class="col-lg-3" style="border-right: 1px solid <?= Color::rang_etat($commentaire['grade']) ?>">
                <div class="avatar-news" style="box-shadow: 0px 0px 2px 2px <?= Color::rang_etat($commentaire['grade']) ?>; background:url('https://www.mangasfan.fr/membres/images/avatars/<?= $commentaire['avatar'] ?>');background-size:100px; background-position: center;"/>
                </div>
                <p class="pseudo">
                    <a href="#" style="color: <?= Color::rang_etat($commentaire['grade']); ?>"><?= $commentaire['username']; ?></a><br/>
                    <span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($commentaire['grade']) ?>;"><?= Color::getRang($commentaire['grade'], $commentaire['sexe'], $commentaire['chef']) ?></span><br/><br/>
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