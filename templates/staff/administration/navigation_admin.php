<div class="col-lg-2 bg-light border-right border-bottom">
			<div class="avatar_site">
				<img src="/mangasfan/membres/images/avatars/<?= \Rewritting::sanitize($utilisateur['avatar']) ?>"/>
				<center>
					<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($utilisateur['grade']) ?>;"><?= Color::getRang($utilisateur['grade'], $utilisateur['sexe'], $utilisateur['stagiaire'], $utilisateur['chef']) ?></span><br/><br/>
					<h3><?= \Rewritting::sanitize($utilisateur['username']) ?></h3>
				</center>
			</div>
			<hr>
			<ul class="nav navbar-inner flex-column">
				Administration :
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#maintenances">» Maintenances</a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#membres">» Fiches des membres</a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#avertissements">» Avertissements</a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#bannissements">» Bannissements</a>
				</li>
				<p>Autres outils :</p>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#"><s>» Mises à jour du site</s></a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#"><s>» Liste des membres</s></a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#"><s>» Logs du site</s></a>
				</li>
			</ul>
		</div>