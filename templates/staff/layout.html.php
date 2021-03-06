<!DOCTYPE html>
<html lang="fr">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?= $pageTitle ?> - Mangas'Fan</title>
 <link rel="icon" href="/images/favicon.png"/>
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
 <script type="text/javascript" src="https://www.mangasfan.fr/templates/staff/tinymce/js/tinymce/tinymce.min.js"></script>
 <script type="text/javascript" src="https://www.mangasfan.fr/templates/staff/tinymce/js/tinymce/tinymce.js"></script>
 <script>
  tinymce.init({
    selector: 'textarea',
    height: 500,
    language: 'fr_FR',
    force_br_newlines : true,
    force_p_newlines : false,
    entity_encoding : "raw",
    browser_spellcheck: true,
    contextmenu: false,
    plugins: ['autolink visualblocks visualchars image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern autosave'],
    toolbar: 'undo redo |  formatselect | tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | bold italic underline forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | restoredraft',
    image_class_list: [
    {title: 'Image news', value: 'image_tiny'},
    ]
  });
</script>
<link rel="stylesheet" type="text/css" href="<?= \Rewritting::sanitize($style) ?>">
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="/">Mangas'Fan</a>

        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/mangas">Mangas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/animes">Animes/Films</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/galeries">Galeries</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/forum">Forum</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/membres/compte.php">Mon compte</a>
          </li>
          <?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 2) { ?>
            <li class="nav-item">
              <a class="nav-link" href="#" data-toggle="modal" data-target="#exampleModalCenter">Staff</a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </nav>
    <?php if (isset($_SESSION['auth']) && $utilisateur['grade'] >= 2) { ?>
      <div class="modal fade text-dark" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">Bonjour <span style="color: <?= Color::rang_etat($utilisateur['grade']) ?>"><?= \Rewritting::sanitize($utilisateur['username']) ?></span>, heureux de vous revoir !</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Hm... Nous voyons que vous êtes actuellement <span class="badge badge-secondary" style="background-color: <?= Color::rang_etat($utilisateur['grade']) ?>;"><?= Color::getRang($utilisateur['grade'], $utilisateur['sexe'], $utilisateur['stagiaire'], $utilisateur['chef']) ?></span> sur Mangas'Fan !<br/><br/>
              <p>Au vu de votre rôle sur le site, nous pouvons vous proposer les accès suivants :</p>
              <?php if ($utilisateur['grade'] >= 7) { ?>
                <a href="/../staff/administration/index.php" class="btn btn-outline-danger">Administration</a>
              <?php } if($utilisateur['grade'] >= 6){ ?>
                <a href="/../staff/moderation/index.php" class="btn btn-outline-success">Modération</a>
              <?php } if($utilisateur['grade'] >= 4){ ?>
                <a href="/../staff/news/index.php" class="btn btn-outline-info">News</a>
                <a href="/../staff/redaction/index.php" class="btn btn-outline-info">Rédaction</a>
              <?php } if($utilisateur['grade'] == 3 || $utilisateur['grade'] >= 6){ ?>
                <a href="/../staff/animation/index.php" class="btn btn-outline-warning">Animation</a>
              <?php } if($utilisateur['grade'] == 2){ ?>
                Aucun accès pour vous malheureusement...
              <?php } ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer la fenêtre</button>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </header>
  <section>
    <?= $pageContent ?>
  </section>
  <div class="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h3>Liens utiles</h3>
          <nav class="lien_site">
            <ul>
             <li><a href="/">Index</a> - </li>
             <li><a href="/membres/members.php">Liste des membres</a> - </li>
             <li><a href="/changelog.php">Mises à jour</a> - </li>
             <li><a href="/partenaires.php">Partenaires</a> - </li>
             <li><a href="/foire-aux-questions.php">F.A.Q</a> - </li>
             <li><a href="/recrutements">Recrutements</a> - </li>
             <li><a href="/mentions_legales.php">Mentions Légales</a></li>
           </ul>
         </nav>
       </div>

       <div class="col-md-6">
        <h3>Nos partenaires</h3>
        <a href="http://www.nexgate.ch" target="_blank">
          <img style="border:0;" src="https://www.nexgate.ch/images/button8831.png" alt="Hébergement gratuit !" title="Hébergement gratuit - nexgate.ch" />
        </a>
        <div class="row">
          <div class="col-md-12">
            <h3>Nos réseaux</h3>
            <a href="https://twitter.com/MangasFanOff" target="_blank">
              <img src="https://www.mangasfan.fr/images/tw.png" alt="Twitter - Mangas'Fan"  class="image_reseaux" />
            </a>
            <a href="https://discord.gg/KK43VKd" target="_blank">
              <img src="https://www.mangasfan.fr/images/discord.png" alt="Discord - Mangas'Fan"  class="image_reseaux" />
            </a>
            <a href="https://www.instagram.com/mangasfanoff/" target="_blank">
              <img src="https://www.mangasfan.fr/images/insta.png" alt="Instagram - Mangas'Fan"  class="image_reseaux" />
            </a>
            <a href="https://www.youtube.com/channel/UCEKb-Gz4ZyNQo5jHckWimpQ" target="_blank">
              <img src="https://www.mangasfan.fr/images/youtube.png" alt="Youtube - Mangas'Fan"  class="image_reseaux" />
            </a>
          </div>
        </div>  
      </div>
    </div>
  </div>
</div>
<div class="footer-bottom">       
  <div class="container">           
    <p class="pull-left">Version 7.2.0 de Mangas'Fan © 2017 - 2020. Développé par Zekarant et Nico. Design by Asami. Tous droits réservés. Toute atteinte au droit d'auteur n'est pas désirée.<br/> Propulsé par <a href="https://www.nexgate.ch/">https://www.nexgate.ch/.</a></p>        
  </div>    
</div>
</body>
</html>