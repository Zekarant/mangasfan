<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-light">
  <a class="navbar-brand" href="https://www.mangasfan.fr"><img src="https://www.mangasfan.fr/images/logo.png" class="logo_site" alt="Logo du site" /></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="https://www.mangasfan.fr/">Accueil</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="https://www.mangasfan.fr/jeux-video">Jeux</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="https://www.mangasfan.fr/mangas">Mangas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="https://www.mangasfan.fr/animes">Animes/Films</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="https://www.mangasfan.fr/galeries">Galeries</a>
      </li>
      <?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 3) { ?>
        <a class="nav-link" href="https://www.mangasfan.fr/staff_index.php">Staff</a>
      <?php } ?>
      <?php
      if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch();
        if ($utilisateur['grade'] < 1 || $utilisateur['grade'] > 10) {
          $modifier_grade = $pdo->prepare('UPDATE users SET grade = 2 WHERE id = ?');
          $modifier_grade->execute(array($utilisateur['id']));
          session_destroy();
          echo '<script>location.href="https://www.mangasfan.fr";</script>';

        }
        if ($utilisateur['confirmation_token'] != NULL) {
          session_destroy();
          echo '<script>location.href="https://www.mangasfan.fr";</script>';
        }
        if (!isset($utilisateur['id'])) {
          session_destroy();
          echo '<script>location.href="https://www.mangasfan.fr";</script>';
        }
        $dsql = $pdo->prepare("SELECT COUNT(*) FROM forum_mp WHERE mp_receveur = ? AND mp_lu = 0");
        $dsql->execute(array($utilisateur['id']));
        $mp = $dsql->fetchColumn(); ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));
            if ($mp >= 1) { ?>
              <img src="https://www.mangasfan.fr/images/mp_nonlu.png" alt="new_mp" class="new_mp" />
            <?php } ?> 
          </a>
          <div class="dropleft" role="group">
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <center>
                Rang : <span class="menu_rang"><?= statut($utilisateur['grade'], $utilisateur['sexe']); ?></span>
                <br/>
                <?php 
                if (!empty($utilisateur['avatar'])){
                  if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
                    <img src="https://www.mangasfan.fr/membres/images/avatars/<?= sanitize($utilisateur['avatar']); ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
                  <?php } } ?>
                </center>
                <a class="dropdown-item" href="https://www.mangasfan.fr/profil/messagesprives.php">Messages Privés <?php if ($mp >= 1) { ?>
                  - <?= $mp; ?> nouveau(x)
                <?php } ?>
              </a>
              <a class="dropdown-item" href="https://www.mangasfan.fr/membres/compte.php">Modifier votre profil</a>
              <a class="dropdown-item" href="https://www.mangasfan.fr/profil/profil-<?= sanitize($utilisateur['id']); ?>">Voir votre profil</a>
              <hr>
              <a class="dropdown-item" href="https://www.mangasfan.fr/galeries/administration_galerie.php">Administrer ma galerie</a>
              <a class="dropdown-item" href="https://www.mangasfan.fr/galeries/members/galerie-<?= sanitize($utilisateur['id']); ?>">Voir ma galerie</a>
              <a class="dropdown-item" href="https://www.mangasfan.fr/membres/deconnexion.php">Déconnexion</a>
            </div>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/membres/inscription.php">Inscription</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/membres/connexion.php">Connexion</a>
          </li>
        <?php } ?>
        <li class="nav-item">
        <a class="nav-link" href="https://www.mangasfan.fr/contact.php">Contact</a>
      </li>
      </ul>
    </div>
  </nav>


