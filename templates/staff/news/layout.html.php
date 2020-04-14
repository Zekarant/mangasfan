<!DOCTYPE html>
<html lang="fr">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title><?= $pageTitle ?> - Mangas'Fan</title>
 <link rel="icon" href="http://localhost/mangasfan/images/favicon.png"/>
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 <script type="text/javascript" src="https://www.mangasfan.fr/redaction/tinymce/js/tinymce/tinymce.min.js"></script>
 <script type="text/javascript" src="https://www.mangasfan.fr/redaction/tinymce/js/tinymce/tinymce.js"></script>
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
<link rel="stylesheet" type="text/css" href="<?= $style ?>">
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="#">Mangas'Fan</a>

        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/mangasfan">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><s>Jeux</s></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><s>Mangas</s></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><s>Animes/Films</s></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><s>Galeries</s></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><s>Mon compte</s></a>
          </li>
        </ul>
      </div>
    </nav>
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
              <li><a href="https://www.mangasfan.fr/">Index</a> - </li>
              <li><a href="https://www.mangasfan.fr/membres/liste_membres.php">Liste des membres</a> - </li>
              <li><a href="https://www.mangasfan.fr/changelog.php">Mises à jour</a> - </li>
              <li><a href="https://www.mangasfan.fr/partenaires.php">Partenaires</a> - </li>
              <li><a href="https://www.mangasfan.fr/foire-aux-questions.php">F.A.Q</a> - </li>
              <li><a href="https://www.mangasfan.fr/recrutements">Recrutements</a> - </li>
              <li><a href="https://www.mangasfan.fr/mentions_legales.php">Mentions Légales</a></li>
            </ul>
          </nav>
        </div>

        <div class="col-md-6">
          <h3>Nos partenaires</h3>
          <a href="https://www.pokelove.fr/" target="_blank">
            <img src="https://1.bp.blogspot.com/-7Ll1bD0j16Y/XWP2tH8flcI/AAAAAAAANO8/y9Rg41CAcC0t_naVCyWNrmug4UYYyPbBwCLcBGAs/s1600/partenaire_nidoranm.png" alt="Logo de Pokélove" width="88" height="31" />
          </a>
          <a href="http://www.nexgate.ch" target="_blank">
            <img style="border:0;" src="https://www.nexgate.ch/images/button8831.png" alt="Hébergement gratuit !" title="Hébergement gratuit - nexgate.ch" />
          </a>
          <a href="https://www.bclover.net/" target="_blank">
            <img style="border:0;" src="https://www.mangasfan.fr/images/bryx.png" alt="Logo de Black Clover" width="88" height="31" />
          </a>
          <a href="http://pokemon-boutique.fr/?afmc=1r&utm_campaign=1r&utm_source=leaddyno&utm_medium=affiliate" target="_blank">
            <img style="border:0;" src="https://www.mangasfan.fr/images/mf-petit.png" alt="Logo pour Pokémon Boutique" width="88" height="31" />
          </a>
          <div class="row">
            <div class="col-md-12">
              <h3>Nos réseaux</h3>
              <a href="https://www.facebook.com/MangasFanOff/" target="_blank">
                <img src="https://www.mangasfan.fr/images/fb.png" alt="Facebook - Mangas'Fan" class="image_reseaux" />
              </a>
              <a href="https://twitter.com/MangasFanOff" target="_blank">
                <img src="https://www.mangasfan.fr/images/tw.png" alt="Twitter - Mangas'Fan"  class="image_reseaux" />
              </a>
              <a href="https://discord.gg/KK43VKd" target="_blank">
                <img src="https://www.mangasfan.fr/images/discord.png" alt="Discord - Mangas'Fan"  class="image_reseaux" />
              </a>
              <a href="https://www.instagram.com/mangasfanoff/" target="_blank">
                <img src="https://www.mangasfan.fr/images/insta.png" alt="Instagram - Mangas'Fan"  class="image_reseaux" />
              </a>
              
              <a href="https://www.twitch.tv/mangasfanofficiel/" target="_blank">
                <img src="https://www.mangasfan.fr/images/twitch.png" alt="Twitch - Mangas'Fan"  class="image_reseaux" />
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
      <p class="pull-left">Version 7.0.0 de Mangas'Fan © 2017 - 2020. Développé par Zekarant et Nico. Design by Asami. Tous droits réservés. Toute atteinte au droit d'auteur n'est pas désirée.<br/> Propulsé par <a href="https://www.nexgate.ch/">https://www.nexgate.ch/.</a></p>        
    </div>    
  </div>
</body>
</html>