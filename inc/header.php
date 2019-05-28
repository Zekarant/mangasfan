<?php session_start(); ?>

    <nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">

          <a class="navbar-brand" href="#">Mangas'Fan</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="inscription.php">S'inscrire</a></li>
            <li><a href="connexion.php">Se connecter</a></li>
            <li><a href="deconnexion.php">Deconnexion</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
      <?php if(isset($_SESSION['flash'])):  ?>
        <?php foreach($_SESSION['flash'] as $type => $message): ?>
          <div class="alerte<?=$type; ?>">
            <?= $message; ?>
          </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
      <?php endif; ?>
    </div>
