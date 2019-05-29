<nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-dark">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
   <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item">
        <a class="nav-link" href="http://localhost/mangasfan6/">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://localhost/mangasfan6/jeux_video">Jeux</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://localhost/mangasfan6/mangas">Mangas/Animes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://localhost/mangasfan6/galeries">Galeries</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://localhost/mangasfan6/contact.php">Contact</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://localhost/mangasfan6/mentions_legales.php">CGU</a>
      </li>
      <?php
      if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
        $dsql = $pdo->prepare("SELECT COUNT(*) FROM forum_mp WHERE mp_receveur = ? AND mp_lu = '0'");
        $dsql->execute(array($utilisateur['id']));
        $mp = $dsql->fetchColumn();?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));
            if ($mp >= 1) { ?>
              <img src="http://localhost/mangasfan6/images/mp.png" alt="new_mp" class="new_mp" />
            <?php } ?> 
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <center>
              Rang : <span class="menu_rang"><?php echo statut(sanitize($utilisateur['grade'])); ?></span>
              <br/>
              <?php 
              if (!empty($utilisateur['avatar'])){
                if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
                  <img src="http://localhost/mangasfan6/membres/images/avatars/<?php echo $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
                <?php } } ?>
            </center>
                <a class="dropdown-item" href="http://localhost/mangasfan6/profil/messagesprives.php">Messages Privés <?php if ($mp >= 1) { ?>
                  - <?php echo sanitize($mp); ?> nouveau(x)
                  <?php } ?>
                </a>
                  <a class="dropdown-item" href="http://localhost/mangasfan6/membres/compte.php">Modifier votre profil</a>
                  <a class="dropdown-item" href="../profil/voirprofil.php?membre=<?php echo $utilisateur['id']; ?>&action=consulter">Voir votre profil</a>
                  <hr>
                  <a class="dropdown-item" href="http://localhost/mangasfan6/galeries">Index des galeries</a>
                  <a class="dropdown-item" href="http://localhost/mangasfan6/galeries/administration_galerie.php">Administrer ma galerie</a>
                  <a class="dropdown-item" href="#">Voir ma galerie</a>
                  <?php if($utilisateur['grade'] >= 3){ ?>
                    <hr>
                    <a class="dropdown-item" href="#">Coin Staff</a>
                  <?php } ?>
                  <a class="dropdown-item" href="http://localhost/mangasfan6/membres/deconnexion.php">Déconnexion</a>
                </div>
              </li>
            <?php } else { ?>
              <li class="nav-item">
                <a class="nav-link" href="#">Inscription</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://projet6.000webhostapp.com/membres/connexion.php">Connexion</a>
              </li>
            <?php } ?>
          </ul>
        </div>
      </nav>


