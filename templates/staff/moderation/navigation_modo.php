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
		Modération :
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="index.php#statistiques">» Statistiques</a>
		</li>
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="index.php#membres">» Gestion des membres</a>
		</li>
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="index.php#avertissements">» Avertissements</a>
		</li>
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="index.php#bannissements">» Bannissements</a>
		</li>
		Autre :
		<li class="nav-item">
			<a class="nav-link lien_nav_staff" href="../../membres/members.php">» Liste des membres</a>
		</li>
	</ul>
</div>