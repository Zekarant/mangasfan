<nav>
  <center>
    <h5 style="padding-top: 15px;">Bienvenue <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));?> !</h5>
    <hr>
    <?php 
    if (!empty($utilisateur['avatar'])){
      if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
        <img src="https://www.mangasfan.fr/membres/images/avatars/<?php echo $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
        <?php } } ?><br/><br/>
        <p>Status : <td><?php if($utilisateur['chef'] != 0){ echo chef(sanitize($utilisateur['chef'])); } else { echo statut($utilisateur['grade'], $utilisateur['sexe']); } ?></td></p>
        <hr>
        <a href="../staff_index.php" class="btn btn-sm btn-info">Retournez à l'index staff</a>
      </center>
      <ul class="nav flex-column">
        <?php if($utilisateur['grade'] >= 5){ ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= $url_rewriting; ?>rediger_news.php">» Rédiger une news</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $url_rewriting; ?>ajouter_jeux.php"> » Gestion des jeux</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $url_rewriting; ?>ajouter_mangas.php">» Gestion des mangas/animes</a>
          </li>
        <?php } elseif ($utilisateur['grade'] >= 7) {?>
          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Administration</span>
          </h6>
          <li class="nav-item">
            <a class="nav-link" href="<?= $url_rewriting; ?>rediger_news.php">» Rédiger une news</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $url_rewriting; ?>ajouter_jeux.php"> » Gestion des jeux</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= $url_rewriting; ?>ajouter_mangas.php">» Gestion des mangas/animes</a>
          </li>
        <?php } ?>  
      </ul>
    </nav>