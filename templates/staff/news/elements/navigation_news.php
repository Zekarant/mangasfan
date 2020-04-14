<div class="col-lg-2 bg-light border-right border-bottom">
			<div class="avatar_site">
				<img src="/mangasfan/membres/images/avatars/<?= $utilisateur['avatar'] ?>"/>
				<center>
					<span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($utilisateur['grade']) ?>;"><?= Color::getRang($utilisateur['grade'], $utilisateur['sexe'], $utilisateur['chef']) ?></span><br/><br/>
					<h3><?= $utilisateur['username'] ?></h3>
				</center>
			</div>
			<hr>
			<ul class="nav navbar-inner flex-column">
				Outils indispensables :
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="https://imagecompressor.com/fr/">» Compresseur d'images</a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#">» Hébergeur d'images</a>
				</li>
				Rédaction :
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#">» Rédiger une news</a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#">» Toutes les news postées</a>
				</li>
				<p>Guides (A télécharger) :</p>
				<li class="nav-item">
					<a class="btn btn-sm btn-outline-info" href="#">Guide du Newseur</a>
				</li>
			</ul>
		</div>