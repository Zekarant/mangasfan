<nav>
	<center>
		<h5 style="padding-top: 15px;">Bienvenue <?= rang_etat($utilisateur['grade'], $utilisateur['username']);?> !</h5>
		<hr>
		<?php if (!empty($utilisateur['avatar'])){
			if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
				<img src="https://www.mangasfan.fr/membres/images/avatars/<?= $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
			<?php }
		} ?><br/><br/>
		<p>Status : <?php if($utilisateur['chef'] != 0){ 
			echo chef(sanitize($utilisateur['chef'])); 
		} else { 
			echo statut($utilisateur['grade'], $utilisateur['sexe']); 
		} ?></p>
		<hr>
		<a href="../staff_index.php" class="btn btn-sm btn-info">Retournez à l'index staff</a>
	</center>
	<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">Administration</h6>
	<ul class="nav flex-column">
		<li class="nav-item">
			<a class="nav-link active" href="#maintenances">  
				» Maintenances
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#avertis">
				» Membres avertis
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#bannis">
				» Membres bannis
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#fiches">
				» Fiches des membres
			</a>
		</li>
	</ul>
	<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">Autres</h6>
	<ul class="nav flex-column mb-2">
		<li class="nav-item">
			<a class="nav-link" href="../membres/liste_membres.php">
				» Liste des membres
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="admin_changelog.php">
				» Changelogs du site
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="envoi_newsletter.php">
				» Newsletter
			</a>
		</li>
	</ul>
</nav>