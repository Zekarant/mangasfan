<h2 class="titre"><?= \Rewritting::sanitize($galerie['title_image']) ?> par <?= \Rewritting::sanitize($galerie['username']) ?></h2>
<hr>
<?php if(isset($_SESSION['auth']) AND $utilisateur['id_user'] == $galerie['auteur_image']){ ?>
	<div class="d-flex justify-content-center">
		<a href="modifier.php?galerie=<?= \Rewritting::sanitize($galerie['id_image']) ?>" class="btn btn-primary btn-sm">Modifier la description de l'image ou le contenu</a>
		<form method="POST" action="supprimer.php?galerie=<?= \Rewritting::sanitize($galerie['id_image']); ?>">
			<input type="submit" name="supprimer_image" onclick="return window.confirm(`Voulez-vous supprimer cette image ?`)" class="btn btn-outline-danger btn-sm" value="Supprimer l'image">
		</form>
	</div>
	<hr>
<?php } if(isset($_SESSION['auth']) && $utilisateur['grade'] >= 7) { ?>
	<div class="d-flex justify-content-center">
		<form method="POST" action="">
			<input type="submit" name="valider_rappel" onclick="return window.confirm(`Voulez-vous suspendre cette image des galeries en envoyant un rappel ?`)" class="btn btn-sm btn-warning" value="Envoyer un rappel pour cette image">
		</form>
	</div>
	<hr>
<?php } ?>
<img src="../galeries/images/<?= \Rewritting::sanitize($galerie['filename']) ?>" class="mx-auto d-block" style="width: 18rem;" alt="<?= \Rewritting::sanitize($galerie['title_image']); ?> - <?= \Rewritting::sanitize($galerie['username']); ?>">
<hr>
<h3>Concernant cette image :</h3>
<p><?= htmlspecialchars_decode(\Rewritting::sanitize($galerie['contenu_image'])) ?></p>
<hr>
<div class="card text-center">
	<div class="card-header">
		A propos de l'auteur
	</div>
	<div class="card-body">
		<h5 class="card-title">Cette image a été postée par <strong><a href="../profil/profil-<?= \Rewritting::sanitize($galerie['auteur_image']); ?>"><?= \Rewritting::sanitize($galerie['username']); ?></a></strong>.</h5>
		<p class="card-text">Elle appartient à son auteur. Toute reproduction sans son accord peut entrainer des sanctions. © <?= \Rewritting::sanitize($galerie['username']); ?></p>
	</div>
	<div class="card-footer text-muted">
		Image postée le <?= \Users::dateAnniversaire($galerie['date_image']) ?>.
	</div>
</div>
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
	<div class="container" id="commentaires">
		<div class="row">
			<div class="col-lg-3" style="border-right: 1px solid <?= Color::rang_etat(\Rewritting::sanitize($commentaire['grade'])) ?>">
				<div class="avatar-news" style="box-shadow: 0px 0px 2px 2px <?= Color::rang_etat(\Rewritting::sanitize($commentaire['grade'])) ?>; background:url('https://www.mangasfan.fr/membres/images/avatars/<?= \Rewritting::sanitize($commentaire['avatar']) ?>');background-size:100px; background-position: center;"/>
				</div>
				<p class="pseudo">
					<a href="../membres/profil-<?= \Rewritting::sanitize($commentaire['id_user']) ?>" style="color: <?= Color::rang_etat(\Rewritting::sanitize($commentaire['grade'])); ?>"><?= \Rewritting::sanitize($commentaire['username']); ?></a><br/>
					<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($commentaire['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($commentaire['grade']), \Rewritting::sanitize($commentaire['sexe']), \Rewritting::sanitize($commentaire['stagiaire']), \Rewritting::sanitize($commentaire['chef'])) ?></span><br/><br/>
					<?php if (isset($_SESSION['auth'])) { 
						if ($commentaire['author_commentary'] == $utilisateur['id_user']) { ?>
							<a href="../galeries/edit_comment.php?id=<?= \Rewritting::sanitize($commentaire['id_commentary_galery']) ?>"class="btn btn-sm btn-outline-info">Editer</a>
							<a href="../galeries/delete_comment.php?id=<?= \Rewritting::sanitize($commentaire['id_commentary_galery']) ?>" onclick="return window.confirm(`Êtes vous sûr de vouloir supprimer ce commentaire ?!`)" class="btn btn-sm btn-outline-danger">Supprimer</a>
						<?php } elseif ($utilisateur['grade'] >= 6 && $utilisateur['grade'] <= 10) { ?>
							<a href="../galeries/delete_comment.php?id=<?= \Rewritting::sanitize($commentaire['id_commentary_galery']) ?>" onclick="return window.confirm(`Êtes vous sûr de vouloir supprimer ce commentaire ?!`)" class="btn btn-sm btn-outline-danger">Supprimer</a>
						<?php }
					} ?>
				</p>
			</div>
			<div class="col-lg-9">
				<?= nl2br(\Rewritting::sanitize($commentaire['galery_commentary'])) ?>
				<div class="bottom">
					<small>Commentaire posté le <?= \Users::dateAnniversaire(\Rewritting::sanitize($commentaire['date_commentaire'])) ?></small>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<?php endforeach ?>