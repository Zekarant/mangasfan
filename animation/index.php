<?php
session_start();
include('../membres/base.php'); 
require_once '../markdown/Michelf/Markdown.inc.php';
require_once '../markdown/Michelf/MarkdownExtra.inc.php';
use Michelf\Markdown;
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{
  $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $user->execute(array($_SESSION['auth']['id']));
  $utilisateur = $user->fetch(); 
}
include('../membres/bbcode.php');
include('../membres/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Mangas'Fan - Animation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="icon" href="../images/favicon.png"/>
  <link rel="stylesheet" type="text/css" href="../style.css" />
</head>
<body>
  <?php 
  if (!isset($_SESSION['auth'])){
    ?>
    <div class='alert alert-danger' role='alert'>
      Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
    </div>
    <?php
  }
  elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] != 3 AND $utilisateur['grade'] < 10) {
    ?>
    <div class='alert alert-danger' role='alert'>
      Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
    </div>
    <?php
  } else { ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
          <nav>
            <center>
              <h5 style="padding-top: 15px;">Bienvenue <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username']));?> !</h5>
              <hr>
              <?php 
              if (!empty($utilisateur['avatar'])){
                if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
                  <img src="https://www.mangasfan.fr/membres/images/avatars/<?php echo $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
                  <?php } } ?><br/><br/>
                  <p>Status : <?php echo statut(sanitize($utilisateur['grade'])); ?></p>
                  <hr>
                  <a href="../staff_index.php" class="btn btn-sm btn-info">Retournez à l'index staff</a>
                </center>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                  <span>Animation</span>
                </h6>
                <ul class="nav flex-column">
                  <li class="nav-item">
                    <a class="nav-link active" href="#points">  
                      » Points
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#stats">
                      » Tableau des membres
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#animation">
                      » Billet d'animation
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="programme.php">
                      » Programme d'animation
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#">
                      <s>» Gestion des badges</s>
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
                    <a class="nav-link" href="https://discord.gg/Cv5qkvV">
                      » Discord du site
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
            <div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
              <?php include ('../elements/nav_anim.php'); ?>
              <section class="marge_page">
              <h3 class="titre_principal_news">
                Bienvenue sur le panneau d'animation de Mangas'Fan
              </h3>
              <div class='alert alert-info' role='alert'>
                <strong>Conseil aux animateurs :</strong> Prenez le temps de vous relire avant de valider quoique ce soit. Toutes vos actions sont visibles sur le site. Merci donc de ne pas publier n'importe quoi.
              </div>
              <!-- Gestion des points -->
              <?php
              if(!empty($_POST['new_points'])){
                if ($_POST['choix_points'] == "attribuer") {
                  if ($_POST['membre_point'] == "all_membres") {
                    $ajoute_points = $pdo->prepare("UPDATE users SET points = points + ?");
                    $ajoute_points->execute(array($_POST['nombre_points']));
                    ?>
                    <div class='alert alert-success' role='alert'>
                      Tous les membres de Mangas'Fan ont reçu <strong><?php echo sanitize($_POST['nombre_points']); ?> points</strong> !
                    </div>
                    <?php
                  }
                  else
                  {
                    $ajoute_points = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
                    $ajoute_points->execute(array($_POST['nombre_points'], $_POST['membre_point']));
                    $membre = $pdo->prepare('SELECT id, username FROM users WHERE id = ?');
                    $membre->execute(array($_POST['membre_point']));
                    $pseudo = $membre->fetch();
                    ?>
                    <div class='alert alert-success' role='alert'>
                      Le membre <strong><?php echo sanitize($pseudo['username']); ?></strong> a reçu <strong><?php echo sanitize($_POST['nombre_points']); ?></strong> points !
                    </div>
                    <?php
                  }
                }
                else
                {
                  if ($_POST['membre_point'] == "all_membres") {
                    $ajoute_points = $pdo->prepare("UPDATE users SET points = points - ?");
                    $ajoute_points->execute(array($_POST['nombre_points']));
                    ?>
                    <div class='alert alert-warning' role='alert'>
                      Tous les membres de Mangas'Fan se sont vu retirer <strong><?php echo sanitize($_POST['nombre_points']); ?> points</strong>...
                    </div>
                    <?php
                  }
                  else
                  {
                    $ajoute_points = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ?");
                    $ajoute_points->execute(array($_POST['nombre_points'], $_POST['membre_point']));
                    $membre = $pdo->prepare('SELECT id, username FROM users WHERE id = ?');
                    $membre->execute(array($_POST['membre_point']));
                    $pseudo = $membre->fetch();
                    ?>
                    <div class='alert alert-warning' role='alert'>
                      Le membre <strong><?php echo sanitize($pseudo['username']); ?></strong> a perdu <strong><?php echo sanitize($_POST['nombre_points']); ?></strong> points !
                    </div>
                    <?php
                  }
                }
              }
              ?>
              <h3 class="titre_secondaire" id="points">
                Points des membres
              </h3>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Membre</th>
                    <th>Attribution</th>
                    <th>Nombre de points à donner</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <form method="POST" action="">
                  <tr>
                    <td>
                      <select class="form-control" name="membre_point">
                        <option value="all_membres" selected="selected">Tous les membres</option>
                        <?php
                        $points_membres = $pdo->prepare('SELECT id, username, points FROM users ORDER BY username ASC');
                        $points_membres->execute();
                        while ($points = $points_membres->fetch()) {
                          ?>
                          <option value="<?php echo sanitize($points['id']); ?>"><?php echo sanitize($points['username']); ?> - <?php echo sanitize($points['points']); ?> point(s)</option>
                        <?php } ?>
                      </select>
                    </td>
                    <td>
                      <select class="form-control" name="choix_points">
                        <option value="attribuer" selected="selected">Attribuer</option>
                        <option value="retrait">Retirer</option>
                      </select>
                    </td>
                    <td><input type="number" name="nombre_points" class="form-control" placeholder="Entrer le nombre de points du membre"></td>
                    <td><input type="submit" name="new_points" class="btn btn-outline-info" value="Valider"></td>
                  </tr>
                </form>
              </table>
              <br/>
              <h3 class="titre_secondaire" id="stats">
               Statistiques des animations
              </h3>
              <div class="container">
        <div class="row">
          <div class="col-md-6">
            <h3 class="titre_petit">
              Membres avec le plus <span class="couleur_mangas">de</span> <span class="couleur_fans">points</span>
            </h3>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Membre</th>
                  <th>Points</th>
                </tr>
              </thead>
              <?php
              $membre_points = $pdo->prepare('SELECT username, points FROM users ORDER BY points DESC LIMIT 5');
              $membre_points->execute();
              while($meilleurs_points = $membre_points->fetch()){
                ?>
                <tr>
                  <td><?php echo sanitize($meilleurs_points['username']); ?></td>
                  <td><?php echo sanitize($meilleurs_points['points']); ?> points</td>
                </tr>
              <?php } ?>
            </table>
          </div>
          <div class="col-md-6">
            <h3 class="titre_petit">
              Membres avec le plus <span class="couleur_mangas">d</span>'<span class="couleur_fans">animations</span>
            </h3>
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Membre</th>
                  <th>Animations</th>
                </tr>
              </thead>
              <?php
              $membre_animation = $pdo->prepare('SELECT username, animation_gagne FROM users ORDER BY animation_gagne DESC LIMIT 5');
              $membre_animation->execute();
              while($meilleures_animation = $membre_animation->fetch()){
                ?>
                <tr>
                  <td><?php echo sanitize($meilleures_animation['username']); ?></td>
                  <td><?php echo sanitize($meilleures_animation['animation_gagne']); ?> animation(s)</td>
                </tr>
              <?php } ?>
            </table>
          </div>
        </div>
      </div>
      <?php
      if (!empty($_POST['new_animation'])) {
        $ajouter_animation = $pdo->prepare('UPDATE animation SET title = ?, contenu = ?, date_ajout = NOW(), visible = ?');
        $ajouter_animation->execute(array($_POST['categorie'], $_POST['contenu_animation'], $_POST['visibilite']));
        $ajouter_archives = $pdo->prepare('INSERT INTO archives_animations(title, contenu, date_ajout) VALUES(?, ?, NOW())');
        $ajouter_archives->execute(array($_POST['categorie'], $_POST['contenu_animation']));
      ?>
        <div class='alert alert-success' role='alert'>
          L'animation a bien été ajoutée sur le site !
        </div>
      <?php } 
      $afficher_animations = $pdo->prepare('SELECT title, contenu FROM animation');
      $afficher_animations->execute();
      $animation = $afficher_animations->fetch();
      ?>
      <br/>
      <h3 class="titre_principal_news" id="animation">
        Billet d'animation
      </h3>
       <div class='alert alert-info' role='alert'>
        <strong>Conseil aux animateurs :</strong> Le billet d'animation vous permets de poster une sorte de news sur la page des comptes. En fonction du thème choisis, le bandeau ne sera pas de la même couleur, prenez le temps de le remplir soigneusement.
      </div>
      <center>
        « <?php 
          $resultat = Markdown::defaultTransform($animation['contenu']);
          echo htmlspecialchars_decode(sanitize($resultat)); ?> »
      </center>
      <form method="POST" action="">
        <label>Visibilité :</label>
          <select class="form-control" name="visibilite">
            <option value="1" selected="selected">Visible</option>
            <option value="0">Caché</option>
          </select>
          <br/>
        <label>Catégorie :</label>
        <select class="form-control" name="categorie">
          <option value="animation" selected="selected">Animation</option>
          <option value="annonce">Annonce importante</option>
          <option value="recrutement">Recrutements</option>
          <option value="maj">Mise à jour</option>
          <option value="alerte">Alerte (Danger)</option>
        </select>
        <br/>
        <label>Message :</label>
        <textarea class="form-control" rows="5" name="contenu_animation" placeholder="Rédigez ici le contenu de l'animation qui apparaîtra sur la page compte du site."><?php echo $animation['contenu']; ?></textarea>
        <input type="submit" name="new_animation" class="btn btn-outline-info" value="Ajouter">
      </form>
    </section>
            </div>
          </div>
        </div>
      <?php } ?>
      <?php include('../elements/footer.php'); ?>
    </body>
    </html>
