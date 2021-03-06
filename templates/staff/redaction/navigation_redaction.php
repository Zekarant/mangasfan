<div class="col-lg-2 bg-light border-right border-bottom">
	<div class="avatar_site">
		<img src="/membres/images/avatars/<?= \Rewritting::sanitize($utilisateur['avatar']) ?>"/>
		<center>
			<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($utilisateur['grade']) ?>;"><?= Color::getRang($utilisateur['grade'], $utilisateur['sexe'], $utilisateur['stagiaire'], $utilisateur['chef']) ?></span><br/><br/>
			<h3><?= \Rewritting::sanitize($utilisateur['username']) ?></h3>
		</center>
	</div>
	<hr>
	<ul class="nav navbar-inner flex-column">
		Navigation :
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="https://imagecompressor.com/fr/" target="_blank">» Compresseur d'images</a>
		</li>
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="/hebergeur/" target="_blank">» Hébergeur d'images</a>
		</li>
		<?php if ($utilisateur['chef'] == 1) { ?>
			<li class="nav-item">
				<a class="nav-link lien_nav_staff" href="/hebergeur/gestion_hebergeur.php">» Accéder à la gestion des images</a>
			</li>
		<?php } ?>
		Rédaction :
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="/staff/redaction/ajouterMangaAnime.php">» Ajouter un anime/manga</a>
		</li>
		<br/>
	</ul>
</div>