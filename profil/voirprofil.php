<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
  $var = (int) $_GET['membre'];
  $se = $pdo->prepare("SELECT * FROM users WHERE id = :id");
  $se->bindValue(':id', $var, PDO::PARAM_INT);
  $se->execute();
  $re = $se->fetch();
?>
<!doctype HTML>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Profil de <?php echo sanitize($re['username']); ?> - Mangas'Fan</title>
    <link rel="icon" href="../images/favicon.png"/>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-129397962-1');
    </script>
    <meta property="og:site_name" content="mangasfan.fr"/>
    <meta property="og:url" content="https://www.mangasfan.fr/profil/voirprofil.php?membre=<?php echo sanitize($re['id']); ?>&action=consulter" />
    <meta property="og:title" content="Mangas'Fan - Profil de <?php echo sanitize($re['username']); ?>" />
    <meta property="og:description" content="Consulter le profil de <?php echo sanitize($re['username']); ?>" />
    <meta property="og:image" content="<?php if (!empty($re['avatar'])){if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $re['avatar'])) { ?>https://www.mangasfan.fr/inc/images/avatars/<?php echo sanitize($re['avatar']); ?><?php } else { ?><?php echo sanitize($re['avatar']); ?><?php } } ?>">
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@Mangas_Fans" />
    <meta name="twitter:creator" content="@Mangas_Fans" />
    <meta name="twitter:title" content="Mangas'Fan - Profil de <?php echo sanitize($re['username']); ?>">
      <meta name="twitter:description" content="Consulter le profil de <?php echo sanitize($re['username']); ?>">
      <meta name="twitter:image" content="<?php if (!empty($re['avatar'])){if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $re['avatar'])) { ?>https://www.mangasfan.fr/inc/images/avatars/<?php echo sanitize($re['avatar']); ?><?php } else { ?><?php echo sanitize($re['avatar']); ?><?php } } ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="../style.css" />
  </head>
  <body>
    <div id="bloc_page">
      <?php include('../elements/header.php'); ?>
      <section class="marge_page">
        <h3><span class="couleur_mangas">Profil</span> <span class="couleur_fans">de</span> <i><?php echo rang_etat(sanitize($re['grade']), sanitize($re['username']));?></i></h3>
        <hr>
        <div id="view_profil">
          <div class="element_profil">
            <?php if (!empty($re['avatar'])){
              if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $re['avatar'])) { ?>
                <center>
                  <img src="../membres/images/avatars/<?php echo sanitize($re['avatar']); ?>" alt="avatar" style="max-height: 285px; max-width: 205px;" title="Avatar de <?php echo sanitize($re['username']); ?>"/>
                </center> <!-- via fichier -->
              <?php } else { ?>
                <center>
                  <img src="<?php echo sanitize($re['avatar']); ?>" alt="avatar" style="max-height: 285px; max-width: 205px;" title="Avatar de <?php echo sanitize($re['username']); ?>"/>
                </center><br/> <!-- via site url -->
              <?php } 
            } ?>
          </div>
          <div class="element_profil">
            Sa description :<br/><br/>
            <i>« <?php 
            echo nl2br(sanitize($re['description'])); ?> » </i><br/><br/>
            Son rang : <?php echo statut(sanitize($re['grade']));?><br/><br/>
            Son manga préféré : <?php  if($re['manga'] == ""){ echo'Non renseigné';} else {echo sanitize($re['manga']);} ?><br/><br/>
            Son anime préféré : <?php if($re['anime'] == ""){ echo'Non renseigné';} else {echo sanitize($re['anime']);} ?><br/><br/>
            Son rôle sur le site : <?php if($re['role'] == ""){ echo'Ce membre n\'est pas du staff !';} else { $role = htmlspecialchars($re['role']);echo sanitize($role);} ?><br/><br/>
            Son site web : <?php if($re['site'] == ""){ echo'Non renseigné';} else {echo '<a href="'.sanitize($re['site']).'" target="_blank">Voir son site web</a>';} ?><br/><br/>
            Nombre de points : <?php echo sanitize($re['points']); ?> points<br/><br/>
            Nombre d'avertissements : <?php echo sanitize($re['avertissements']); ?><br/><br/>
          </div>
        </div>
      </section>
      <?php include('../elements/footer.php'); ?>
    </div>
  </body>
  </html>
