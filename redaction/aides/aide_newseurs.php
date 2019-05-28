<?php 
    session_start();
    require_once '../../inc/base.php';
    if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
      $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
      $user->execute(array($_SESSION['auth']['id']));
      $utilisateur = $user->fetch(); 
    }
    include('../../inc/functions.php');
    include('../../theme_temporaire.php');
?>
<!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <title>Aide aux Newseurs - Mangas'Fan</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="../../images/favicon.png" />
      <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
      <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
      <script type="text/javascript" src="tinymce/js/tinymce/tinymce.js"></script>
      <script>
        tinymce.init({
          selector: 'textarea',
          height: 250,
          theme: 'modern',
          language: 'fr_FR',
          plugins: ['print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help'],
          toolbar: 'insert | undo redo |  formatselect | bold italic underline backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
          content_css: [
          '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
          '//www.tinymce.com/css/codepen.min.css']
        });
      </script>
      <link rel="stylesheet" href="<?php echo $lienCss; ?>" />
    </head>
    <body>
      <div id="bloc_page">
        <?php 
        if (!isset($_SESSION['auth'])){
          ?>
          <div class='alert alert-danger' role='alert'>
            Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
          </div>
          <?php
        }
        elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] < 5) {
          ?>
          <div class='alert alert-danger' role='alert'>
            Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
          </div>
          <?php
        }
        else {
         include('../../elements/nav_redac.php');
         ?>
        <h3 class="titre_pannel">
         Aide pour <span class="couleur_mangas">les</span> <span class="couleur_fans">Newseurs</span>
        </h3>
        <section class="marge_page">
          <a href="../redac.php" class="btn btn-primary btn-sm">
            Retourner à l'index de la rédaction
          </a>
          <br/><br/>
          <center>
            <img src="https://www.mangasfan.fr/hebergeur/uploads/1556910798.png" style="border: 1px solid black;"/><br/>
            <i>Aperçu du pannel de rédaction</i>
          </center>
          <br/>
          <h3>Les différentes choses à savoir</h3>
            <div class='alert alert-info' role='alert'>
              Tous les bons Newseurs doivent passer par là pour pouvoir rédiger des bonnes news de qualité. Les administrateurs vous répertorient ici toutes les choses à savoir concernant le pannel réservé aux Newseurs.<br/><br/>
              <strong>Note importante :</strong> En cas de mauvaises rédaction, le système vous indiquera une erreur au moment de la validation. Si c'est le cas, vous devrez tout retaper sauf si vous faites un retour arrière.
            </div>
            <h5><u>Titre de la news :</u></h5> 
            <p>Le titre de la news doit être le plus attachant : quand les gens le lisent, ils doivent avoir envie de cliquer sur la news pour en connaître les détails.</p>
            <p>Sur Mangas'Fan, les titres doivent posséder entre <strong>4 et 50 caractères</strong> pour être validés. Dans le cas où cette condition ne serait pas remplie, vous devrez tout recommencer.</p>

            <h5><u>Description de la news :</u></h5> 
            <p>La description de news est quelque chose de complémentaire au titre, elle doit rajouter une envie de cliquer pour connaître davantage de détails, plus vous donnerez envie, mieux ce sera pour vous.</p>
            <p>Pour que votre news soit valide et ne rencontre pas une erreur, il vous faut entrer une description comprise entre <strong>50 et 200 caractères</strong>. Si vous faites plus de 200 ou moins de 50, vous rencontrerez une erreur au moment de la validation.</p>

            <h5><u>Image de la news :</u></h5> 
            <p>Il s'agit là d'un élément extrêmement important sur notre système de news. En effet, l'image visuel est une chose importante sur laquelle nous sommes assez strictes.</p>
            <p>Pour réaliser les images, pas besoin de talent, il vous faut simplement respecter les dimensions qui sont <strong>310px par 100px</strong> et de rajouter une bordure autour. Si jamais vous avez Photoshop, contactez Zekarant dans le but d'avoir le lien du PSD.</p>
            <p>Si jamais il n'y a pas d'image au moment de la validation, une erreur sera renvoyée et vous devrez recommencer.</p>

            <h5><u>Sources de la news :</u></h5> 
            <p>Il faut déjà savoir que cette section est facultative, car quand nous rédigeons des news qui concernent le site, nous n'avons pas besoin de citer nos sources. Cependant, pour les articles d'actualités ect...cette partie reste <strong>obligatoire</strong> pour vous, utilisateurs du pannel.</p>
            <p>Pour citer vos sources, vous avez simplement à les noter dans le cadre en les séparant d'une virgule et elles apparaitront en fin d'article.</p>
            <p><strong>Note importante :</strong> Ne pas remplir cette case n'entraînera aucune erreur, mais il est, comme dit, extrêmement conseillé de la remplir.</p>

            <h5><u>Visibilité de la news :</u></h5>
            <p>Tout est dans le nom. Cette option se présente de manière différente. En effet, vous avez le choix de mettre la news en « Visible » ou en « Cachée ».</p>
            <p><strong>Note : </strong> Lorsque vous postez une news, l'option sera automatiquement placée sur « Visible ». Si jamais vous estimez que vous devez finir votre news plus tard, vous n'avez qu'à la mettre en « Cachée » dans un première temps puis en « Visible » par la suite.</p>

            <h5><u>Contenu de la news :</u></h5>
            <p>Il doit certainement s'agir de l'option la plus importante sur cette partie. En effet, le contenu de la news est la chose que les gens vont lire, il faut donc veiller à être complet pour que les gens se disent qu'ils ont eu toutes les informations en lisant votre article.</p>
            <p>Notez que vous avez à votre disposition un éditeur de texte complet pour pouvoir mettre votre texte en forme. Les prévisualisations se font en directes, vous pouvez donc jouer avec l'éditeur.</p>
            <p><strong>Note importante :</strong> Le contenu doit faire au moins <strong>100 caractères</strong> pour que la news se poste, dans le cas contraire, une erreur surviendra lorsque vous validerez votre news. Soyez vigilants, faire 100 caractères n'est pas dur sur une news.</p>

            <h3>Notre hébergeur d'images</h3>
            <div class='alert alert-info' role='alert'>
              L'hébergeur d'image est propre à Mangas'Fan. Il suffit de prendre une image dans vos fichiers pour l'héberger chez nous. Notez que l'utilisation abusive pour des images hors news est sanctionnée chez nous.
            </div>
            <p>Lorsque vous faites des images de news, il est important de les importer de votre PC, l'hébergeur est là pour ça, vous pouvez importer vos images et notre système vous créera un lien que vous aurez juste à coller. De plus, nous réhébergeons les images officielles. Si vous prenez une affiche officielle sur un autre site, vous devez la réehéberger et pas faire un copié collé du lien de départ.</p>
        </section>
        <?php }  ?>
  <?php include('../../elements/footer.php'); ?>
</div>
</body>
</html>