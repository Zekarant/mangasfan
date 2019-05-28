<nav class="nav_redac navbar navbar-light navbar-expand-lg ">
  <div class="container">
    <span class="navbar-brand">
      Bienvenue sur la rédaction <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));?>
    </span>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/index.php">Index</a>
          </li>
          <?php if($utilisateur['grade'] == 5){ ?>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/redaction/rediger_news.php">Rédiger une news</a>
          </li>
          <?php } elseif ($utilisateur['grade'] == 8) {?>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_jeux.php">Gestion des jeux</a>
          </li>
          <?php } elseif ($utilisateur['grade'] == 6 OR $utilisateur['grade'] == 7) {?>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_mangas.php">Gestion des mangas/animes</a>
          </li>
          <?php } elseif ($utilisateur['grade'] >= 9) { ?>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/redaction/rediger_news.php">Rédiger une news</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://www.mangasfan.fr/redaction/ajouter_jeux.php">Gestion des jeux</a>
          </li>
          <li class="nav-item">
            <a class="nav-link "href="https://www.mangasfan.fr/redaction/ajouter_mangas.php">Gestion des mangas/animes</a>
          </li>
          <?php } ?>
          <li class="nav-item">
            <a class="nav-link "href="https://www.mangasfan.fr/profil/messagesprives.php">Messages Privés</a>
          </li>
        </ul>
      </div>
  </div>
    </nav>