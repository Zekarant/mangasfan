<?php
session_start(); 
include('../inc/base.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
  if ($utilisateur['grade'] == 1) 
    {
      echo '<script>location.href="bannis.php";</script>';
    }
}
  include('../inc/functions.php'); 
  include('../theme_temporaire.php');
  ?>
  <!doctype html>
  <html lang="fr">
  <head>
    <meta charset="utf-8" />
    <title>Mangas'Fan - Pannel bêta</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <link rel="icon" href="../images/favicon.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>">
    <link rel="stylesheet" type="text/css" href="../overlay.css" />
  </head>
  <body>
    <div id="bloc_page">
      <header>
        <div id="banniere_image">
          <div id="titre_site">
            <span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN
          </div>
          <div class="slogan_site">Votre référence Mangas</div>
          <?php include("../elements/navigation.php") ?>
          <h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
          <h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
          <div class="bouton_fofo"><a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a></div>
          <?php include('../elements/header.php'); ?>
        </div>
      </header>
      <section>
        <?php
        if (($utilisateur['grade'] >= 2 AND $utilisateur['testeurs'] == 1 OR $utilisateur['testeurs']) OR ($utilisateur['grade'] >= 2 AND $utilisateur['testeurs'] == 1 AND $utilisateur['testeurs_deux'] == 3) OR ($utilisateur['grade'] >= 2 AND $utilisateur['testeurs'] AND $utilisateur['testeurs_deux'] == 4) OR ($utilisateur['grade'] >= 2 AND $utilisateur['testeurs'] == 3 AND $utilisateur['testeurs_deux'] == 1) OR ($utilisateur['grade'] >= 2 AND $utilisateur['testeurs'] == 3 AND $utilisateur['testeurs_deux'] == 2) OR ($utilisateur['grade'] >= 2 AND $utilisateur['testeurs'] == 2 AND $utilisateur['testeurs_deux'] == 3) OR ($utilisateur['grade'] >= 2 AND $utilisateur['testeurs'] == 4 AND $utilisateur['testeurs_deux'] == 1) OR ($utilisateur['grade'] >= 2 AND $utilisateur['testeurs'] == 4 AND $utilisateur['testeurs_deux'] == 2)){ ?>

          <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">Bienvenue bêta testeur <?php echo $utilisateur['username']; ?> !
            </h4>
            <p>Bonjour,<br/>
              Bienvenue sur le panel de bêta test de Mangas'Fan. Si vous êtes ici, c'est que vous avez minimum le grade Membres et le titre « Bêta Testeurs ». Il faut savoir que <b>toutes les personnes ayant accès à cette page s'engagent à ne pas dévoiler d'informations sur ce qu'il se passera ici.</b> Je compte sur tous les membres présents ici pour jouer le jeu de la confidentialité.</p> 
              <p>Vous retrouverez donc prochainement ici le programme de la bêta ainsi que les avancements du site avec les dernières mises à jour citées ! Nous essaierons de faire ça de manière ludique !</p>
              <p><b>Note 26/10 :</b> Les fonctionnalités du site étant avant tout testés par les administrateurs et les développeurs du site, les bêtas-testeurs ne sont pas solicités pour le moment !</p>
              <p>Merci à tous ! </p>
            </div>

            <div id="titre_news">
              <center>
                <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" /> Programme<span class="couleur_mangas"> Bêta</span><span class="couleur_fans">-Test</span> <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
              </center>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>Nom</th>
                  <th>État</th>
                  <th>Lien</th>
                  <th>Avancement</th>
                </tr>
              </thead>
              <tbody>
                <tr class="success">
                  <td>Système de thème temporaire</td>
                  <td>Terminé</td>
                  <td>Visible le 31/10, 25/12 pour le moment.</td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">Complet à 100%</div></div></td>
                </tr>    
                <tr class="warning">
                  <td>Version mobile</td>
                  <td>En cours, pratiquement terminée</td>
                  <td>Aucun lien. Voir sur mobile.</td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">Complet à 90%</div></div></td>
                </tr>     
                <tr class="warning">
                  <td>Blogs</td>
                  <td>En cours</td>
                  <td><a href="https://www.mangasfan.fr/blogs">Blogs</a></td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">Complet à 90%</div></div></td>
                </tr> 
                <tr class="warning">
                  <td>Système de points/trophées</td>
                  <td>En cours.</td>
                  <td>Visible sur votre page compte.</td>
                  <td> <div class="progress"><div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">Complet à 40%</div></div></td>
                </tr>
                <tr class="warning">
                  <td>Grades et titres.</td>
                  <td>En cours.</td>
                  <td>Aucun lien.</td>
                  <td> <div class="progress"><div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">Complet à 70%</div></div></td>
                </tr>
                <tr class="danger">
                  <td>Galeries</td>
                  <td>Non commencées.</td>
                  <td>Aucun lien.</td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Complet à 0%</div></div></td>
                </tr>
                <tr class="danger">
                  <td>Système d'encyclopédie</td>
                  <td>Non commencées.</td>
                  <td>Aucun lien.</td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Complet à 0%</div></div></td>
                </tr>
                <tr class="danger">
                  <td>Système de jukebox pour les OST</td>
                  <td>Non commencées.</td>
                  <td>Aucun lien.</td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Complet à 0%</div></div></td>
                </tr>
                <tr class="danger">
                  <td>Système de mini-jeux, tombola avec les points</td>
                  <td>Non commencées.</td>
                  <td>Aucun lien.</td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Complet à 0%</div></div></td>
                </tr>
                <tr class="danger">
                  <td>Système d'animation inividuelle</td>
                  <td>Non commencées.</td>
                  <td>Aucun lien.</td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Complet à 0%</div></div></td>
                </tr>
                <tr class="info">
                  <td>Forum maison</td>
                  <td>Sujet en cours de réfléxion</td>
                  <td>Aucun lien.</td>
                  <td><div class="progress"><div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Complet à 0%</div></div></td>
                </tr>
              </tbody>
            </table>
          <?php }
          else
          {
            echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
          }
          ?>
        </section>
        <?php include('../elements/footer.php') ?>
      </div>
    </body>
    </html>