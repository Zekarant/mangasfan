<div class="col-lg-2 bg-light border-right border-bottom">
			<div class="avatar_site">
				<img src="/mangasfan/membres/images/avatars/<?= \Rewritting::sanitize($utilisateur['avatar']) ?>"/>
				<center>
					<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat(\Rewritting::sanitize($utilisateur['grade'])) ?>;"><?= Color::getRang(\Rewritting::sanitize($utilisateur['grade']), \Rewritting::sanitize($utilisateur['sexe']), \Rewritting::sanitize($utilisateur['chef'])) ?></span><br/><br/>
					<h3><?= \Rewritting::sanitize($utilisateur['username']) ?></h3>
				</center>
			</div>
			<hr>
			<ul class="nav navbar-inner flex-column">
				Administration :
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#"><s>» Maintenances</s></a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#"><s>» Avertissements</s></a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#"><s>» Bannissements</s></a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#"><s>» Fiches des membres</s></a>
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