<nav>
  <center>
    <h5 style="padding-top: 15px;">Bienvenue <?= rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));?> !</h5>
    <hr>
    <?php 
    if (!empty($utilisateur['avatar'])){
      if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
        <img src="https://www.mangasfan.fr/membres/images/avatars/<?= $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
      <?php } 
    } ?><br/><br/>
    <p>Status : <?= statut($utilisateur['grade'], $utilisateur['sexe']); ?></p>
    <hr>
    <a href="../staff_index.php" class="btn btn-sm btn-info">Retournez à l'index staff</a>
  </center>
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Modération</span>
  </h6>
  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link active" href="#stats">  
        » Statistiques
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
  </ul>
  <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
    <span>Autres liens du pannel</span>
    <a class="d-flex align-items-center text-muted" href="#">
    </a>
  </h6>
  <ul class="nav flex-column mb-2">
    <li class="nav-item">
      <a class="nav-link" href="#">
        » Liste des membres
      </a>
    </li>
  </ul>
</nav>