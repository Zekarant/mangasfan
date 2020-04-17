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
				Outils indispensables :
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="https://imagecompressor.com/fr/">» Compresseur d'images</a>
				</li>
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="#"><s>» Hébergeur d'images</s></a>
				</li>
				Rédaction :
				<li class="nav-item">
					<a class="nav-link lien_nav_staff" href="rediger_news.php">» Rédiger une news</a>
				</li>
				<p>Guides (A télécharger) :</p>
				<li class="nav-item">
					<a class="btn btn-sm btn-outline-info" href="#">Guide du Newseur</a>
				</li>
			</ul>
		</div>