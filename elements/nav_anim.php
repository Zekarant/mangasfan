<nav class="nav_redac navbar navbar-light navbar-expand-lg ">
  <div class="container">
    <span class="navbar-brand">
      Bienvenue sur l'animation <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));?>
    </span>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/index.php">Index</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/inc/compte.php">Mon compte</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/profil/messagesprives.php">Messages Privés</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://discord.gg/Cv5qkvV">Discord</a>
          </li>
        </ul>
      </div>
  </div>
    </nav>