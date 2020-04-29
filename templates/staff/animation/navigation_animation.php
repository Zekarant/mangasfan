<div class="col-lg-2 bg-light border-right border-bottom">
	<div class="avatar_site">
		<img src="/membres/images/avatars/<?= \Rewritting::sanitize($utilisateur['avatar']) ?>" alt="Avatar staff" />
		<center>
			<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($utilisateur['grade']) ?>;"><?= Color::getRang($utilisateur['grade'], $utilisateur['sexe'], $utilisateur['stagiaire'], $utilisateur['chef']) ?></span><br/><br/>
			<h3><?= \Rewritting::sanitize($utilisateur['username']) ?></h3>
		</center>
	</div>
	<hr>
	<ul class="nav navbar-inner flex-column">
		Animation :
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="index.php#points">» Points</a>
		</li>
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="index.php#stats">» Statistiques des animations</a>
		</li>
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="index.php#billet">» Billet d'animation</a>
		</li>
		Autre :
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="https://discord.gg/Cv5qkvV">» Discord</a>
		</li>
	</ul>
</div>