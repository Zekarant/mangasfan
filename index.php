  <?php 
  session_start();
  include('inc/functions.php');
  include('inc/base.php');
  include('theme_temporaire.php');
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8" />
    <title>Mangas'Fan - L'actualité des mangas et animes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.png" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-129397962-1');
    </script>
    <meta name=”twitter:card” content="summary_large_image" />
    <meta name="twitter:site" content="@Mangas_Fans" />
    <meta name="twitter:creator" content="@Mangas_Fans" />
    <meta property="og:site_name" content="mangasfan.fr"/>
    <meta property="og:url" content="https://www.mangasfan.fr" />
    <meta property="og:title" content="Mangas'Fan - L'actualité des mangas et animes" />
    <meta property="og:description" content="Site concernant les mangas et les animes anciens et récents. Retrouvez une communauté de fans pour discuter et partagez !" />
    <meta property="og:image" content="https://www.pixenli.com/image/J6FtHnhW" />
    <meta name="twitter:title" content="Mangas'Fan - L'actualité des mangas et animes">
    <meta name="twitter:description" content="Site concernant les mangas et les animes anciens et récents. Retrouvez une communauté de fans pour discuter et partagez !">
    <meta name="twitter:image" content="https://www.pixenli.com/image/J6FtHnhW">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
    <script src="bootstrap/js/jquery.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
    <link rel="stylesheet" type="text/css" href="overlay.css" />
    <meta name="description" content="Site concernant les mangas et les animes anciens et récents. Retrouvez une communauté de fans pour discuter et partagez !"/>
    <meta name="keywords" content="Mangas, Fans, Animes, Site Mangas, Produits, Adaptation, Contenu, Site, Communauté, Partenaires, Actualités, Sorties, Débats, Site de discussions mangas"/>
  </head>
  <body>
    <div id="bloc_page">
      <header>
        <div id="banniere_image">
          <div id="titre_site">
            <span class="couleur_mangas"><?php echo $titre_1; ?></span><?php echo $titre_2; ?><span class="couleur_fans">F</span>AN
          </div>
          <div class="slogan_site"><?php echo $slogan; ?></div>
          <?php include("elements/navigation.php") ?>
          <h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
          <h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
          <div class="bouton_fofo">
            <a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter
            </a>
          </div>
          <?php include('inc/bbcode.php'); 
          include('elements/header.php'); ?>
        </div>
      </header>
      <section class="marge_page">
        <h3 id="titre_news">
          <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
          NEWS DE <span class="couleur_mangas"><?php echo $titre_1 . '' . $titre_2; ?></span><span class="couleur_fans">FAN</span>
          <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
        </h3>
        <div class="sous_news">
          Bienvenue sur le site de Mangas'Fan ! Ci-dessous, vous retrouvez toutes les dernières news postées sur le site et qui concernent l'actualité des mangas, des animes, des goodies...
        </div>
        <?php
        if (!empty($_GET['page']) && is_numeric($_GET['page']))
          $page = stripslashes($_GET['page']);
        else
        $page = 1;
        $pagination = 3;
                    // Numéro du 1er enregistrement à lire
        $limit_start = ($page - 1) * $pagination;
        $nb_total = $pdo->prepare('SELECT COUNT(*) AS nb_total FROM billets');
        $nb_total->execute();
        $nb_total = $nb_total->fetchColumn();
                    // Pagination
        $nb_pages = ceil($nb_total / $pagination);

        ?>
        <div id="conteneur">
        <?php 
        $news = $pdo->prepare("SELECT id, titre, auteur, theme, description, DATE_FORMAT(date_creation, '%d %M %Y à %Hh %imin') AS date_creation, visible FROM billets WHERE visible = 0 ORDER BY id DESC LIMIT $limit_start, $pagination");
        $news->execute();
        while($news_affiche = $news->fetch()){
          ?>
            <div class="element">
              <div class="effet">
                <span class="effet_activé">
                  <?php
                  $nom_commentaire = $pdo->prepare('SELECT C.auteur, U.grade FROM commentaires C INNER JOIN users U ON U.username = C.auteur WHERE C.date_commentaire = (SELECT MAX(date_commentaire) FROM commentaires, users WHERE id_billet = ?)'); 
                  $nom_commentaire->execute(array($news_affiche['id']));
                  $nom_commentaire_ligne = $nom_commentaire->fetch();
                  $commentaire = $pdo->prepare("SELECT * FROM commentaires WHERE id_billet = ?");
                  $commentaire->execute(array($news_affiche['id']));
                  $commentaire_ligne = $commentaire->fetch();
                  $nbr_com = $commentaire->rowCount();
                  $pluriel = ($nbr_com > 1) ? "commentaires."  : "commentaire.";
                  $message = ($nbr_com > 0) ? "<span class='glyphicon glyphicon-comment' aria-hidden='true' title='Nombre de commentaires'></span> <b>".$nbr_com."</b> ".$pluriel." " : "Il n'y a aucun commentaire sur cette news.";
                  $message .= ($nbr_com > 0) ? "<span class='glyphicon glyphicon-user' aria-hidden='true' title='Personne qui a posté le dernier commentaire'></span> Par " .rang_etat(sanitize($nom_commentaire_ligne['grade']), sanitize($nom_commentaire_ligne['auteur'])) : "";
                  echo $message;
                  ?>
                </span>
                  <img src="<?php echo sanitize($news_affiche['theme']); ?>" />
              </div>
              <div class="auteur_date">
                <span class="auteur"><?php echo sanitize($news_affiche['auteur']); ?></span>
                <span class="date_news"><?php echo sanitize($news_affiche['date_creation']); ?></span>
              </div>
              <div class="titre_principal_news">
               <a href="commentaire/<?= sanitize(traduire_nom($news_affiche['titre'])); ?>">
                <?php echo sanitize($news_affiche['titre']); ?>
              </a>
            </div>
            <div class="description_news">
             <?php echo sanitize($news_affiche['description']); ?>
           </div>
         </div>
       <?php }?>
    </div>
    </section>
      <nav>
        <ul class="pagination justify-content-center">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">Pages :</a>
          </li>
          <?php
            // Boucle sur les pages
              for ($i = 1; $i <= $nb_pages; $i++) {
                if ($i == $page){
          ?>
          <li class="page-item">
            <a class="page-link" href="#">
              <?php echo $i; ?>
            </a>
          <?php } else { ?>
          <li class="page-item">
            <a class="page-link" href="<?php echo "?page=" . $i; ?>">
              <?php echo $i;?>
            </a>
          </li>
        <?php } } ?>
        </ul>
      </nav>
       
      <div id="banniere_reseaux">
        <?php include('elements/twitter.php'); ?>
        <?php include('elements/facebook.php'); ?>
        <?php include('elements/discord.php'); ?>
      </div>
      <div id="staff">
        <?php include('staff.php'); ?>
      </div>
      <div id="i_contact">
        <?php include('contact.php'); ?>
      </div>
      <div id="i_contact">
        <?php include('elements/qeel.php') ?>
      </div>
    <?php include('elements/footer.php'); ?>
  </div>
  </body>
  </html>