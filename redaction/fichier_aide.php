<?php
    session_start();
    require_once '../inc/base.php';
    if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
?>
<?php
 $allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
 $allowedTags.='<li><ol><ul><span><div><br><ins><del>';  
 $elm1 = isset( $_POST['elm1'] ) ? $_POST['elm1'] : '';
// Should use some proper HTML filtering here.
  if($elm1!='') {
    $sHeader = '<h1>Ah, content is king.</h1>';
    $sContent = strip_tags(stripslashes($_POST['elm1']),$allowedTags);
} else {
    $sHeader = '<h1>Nothing submitted yet</h1>';
    $sContent = '<p>Start typing...</p>';
    $sContent.= '<p><img width="107" height="108" border="0" src="/mediawiki/images/badge.png"';
    $sContent.= 'alt="TinyMCE button"/>This rover has crossed over</p>';
  }
include('../theme_temporaire.php');
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <title>Mangas'Fan - Fichier aide Rédacteurs/Newseurs</title>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="icon" href="../images/favicon.png"/>
    <script src='http://use.edgefonts.net/nosifer.js'></script>
    <script src='http://use.edgefonts.net/emilys-candy.js'></script>
    <script src='http://use.edgefonts.net/butcherman.js'></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
  </head>
  <body>
    <div id="bloc_page">
      <?php
      include('../inc/functions.php');
      if($utilisateur['grade'] <= 4){
      echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
      exit;} 
      ?>
      <?php include('../elements/nav_redac.php') ?>
      <section class="marge_page">
        <h2 class="titre_commentaire_news">
          Fichier d'aide concernant <span class="couleur_mangas">la</span> <span class="couleur_fans">rédaction</span>
        </h2><br/>
        <h4 id="titre_aide_redac">
          INFORMATIONS CONCERNANT <span class="couleur_mangas">LE</span> <span class="couleur_fans">PANNEL</span>
        </h4>
        <p>Toutes les personnes ayant obtenu l'accès à ce pannel sont priées de le garder secret. Seules les personnes ayant le grade <b>« Rédacteurs Animes, Rédacteurs Jeux Vidéos, Rédacteurs Mangas ou Newseurs »</b> ont accès à cette partie du site. Les membres ne peuvent pas y accéder. Veuillez donc à ne pas communiquer votre mot de passe afin qu'aucun tiers ne puisse accéder à cette partie réservée. </p>
        <p><b>En cas de partage de la plateforme de rédaction de Mangas'Fan, vous vous exposez à une poursuite en justice par une action en responsabilité contractuelle.</b></p><br/>

        <?php if($utilisateur['grade'] == 5 || $utilisateur['grade'] >= 9){ ?>
          <h4 id="titre_aide_redac">NEWS - COMMENT REMPLIR <span class="couleur_mangas">LE</span> <span class="couleur_fans">FORMULAIRE</span></h4>

          <center><img src="https://www.pixenli.com/image/r3DP1asf" id="image_fichier_aide" title="Image du pannel de Mangas'Fan" /></center><br/>
          <p>Le formulaire de news de Mangas'Fan est composé de plusieurs champs à remplir afin de publier une new agréable aux yeux des visiteurs. Vous pourrez donc y retrouver les éléments suivants :</p>
          <p><b>Titre</b> - Le titre de la new est le plus important. Il doit donner aux visiteurs envie de cliquer dessus. Pour ce faire, nous vous conseillons donc d'utiliser un titre assez court qui va droit au but.</p>
          <p><b>Description</b> - Brève description de la new qui résume dans les grandes lignes le contenu de la new elle-même. Elle doit être assez courte.</p>
          <p><b>Contenu</b> - Il s'agit de la partie la plus importante de la rédaction de new. Vous devez mettre de la mise en style, c'est à dire, du gras, des couleurs, des images, et même des vidéos si nécessaire ! Évidemment, il ne faut pas en abuser, mais les mettre aux bons endroits.</p>
          <p><b>Catégorie</b> - Il s'agit de là où sera archivée la new. (Lorsque le système aura été développé correctement !)</p>
          <p><b>Image de la new</b> - C'est l'image qui apparaît sur la page d'accueil, elle se redimensionne automatiquement. Cependant, elle doit vous appartenir de droits. Une image prise sur Internet ne sera pas tolérée.</p><br/>
            <?php } elseif($utilisateur['grade'] == 6){?>
          <h4 id="titre_aide_redac">RÉDACTION ANIMES - FORMULAIRE <span class="couleur_mangas">DES</span> <span class="couleur_fans">ANIMES</span></h4>
          <p>Il n'y a actuellement aucun pannel pour les rédaction d'anime.</p><br/>
            <?php } ?> <?php if($utilisateur['grade'] == 7 || $utilisateur['grade'] == 8 || $utilisateur['grade'] >= 9){?>
          <h4 id="titre_aide_redac">RÉDACTION MANGAS/JEUX VIDÉOS - COMMENT S'OCCUPER <span class="couleur_mangas">DES</span> <span class="couleur_fans">MANGAS/JEUX</span></h4>
          <center><img src="https://www.pixenli.com/image/oas42LRA" id="image_fichier_aide" title="Éléments principaux" /></center><br/>
           <p><b>Modifier le titre du mangas/jeux</b> - Il s'agit du titre qui apparait sur la page des mangas/jeux en dessous de l'image, il n'y a logiquement pas besoin d'y toucher, sauf si cela comporte une faute d'orthographe.</p>
           <p><b>Modifier l'image du mangas/jeux</b> - C'est l'image qui apparaît sur la page des mangas/jeux, il n'y a pas besoin d'y toucher normalement, elles sont crées par Zekarant lors de la création de la page.</p>
           <p><b>Genre</b> - Quel est le type du manga/jeux ? Est-ce un manga/jeux de combat ? Un hentai ? UN RPG ?</p>
           <p><b>Date de sortie</b> - A quel moment est sorti ce manga/jeux ? Précisez, comme sur l'exemple, le pays !</p>
           <p><b>Langue</b> - Ce manga/jeux est-il en Français, Anglais, Japonais, Espagnol ?</p>
           <p><b>Navigation & Catégories</b> - Normalement, tout sera déjà crée pour vous, si vous êtes cependant assez expérimenté, vous pourrez vous servir du système seul.</p><br/>
           <center><img src="https://www.pixenli.com/image/ovH2lS8Z" id="image_fichier_aide" title="Création de page" /></center><br/>
           <p><b>Sélectionnez une catégorie</b> - C'est l'endroit où l'article sera placé. Si votre article parle d'une personnage, vous devrez donc classer votre article dans la catégorie « Personnages » du manga.</p>
           <p><b>Titre</b> - Le titre de l'article. Il doit être court tout en étant explicite.</p>
           <p><b>Texte</b> - C'est là que vous écrivez votre article. L'équipe de développeur de Mangas'Fan vous a préparé un éditeur de texte complet pour travailler dans les meilleures conditions : TinyMCE. Il est complet, vous permet de mettre toute la mise en forme que vous souhaitez et prévisualiser avant d'envoyer. Bref, tous vos besoins sont normalement dans cet éditeur.</p>
           <br/>
    <?php } ?>

      <h4 id="titre_aide_redac">ASTUCE <span class="couleur_mangas">POUR</span><span class="couleur_fans"> RÉUSSIR</span></h4>
        <p>- Vous devez maîtriser le fichier de <a href="../inc/bbcode_active.html">BBCodes</a>.</p>
        <p>- Votre orthographe et votre grammaire doit être irréprochable.</p>
        <p>- Si vous utilisez l'image d'un autre site, citez vos sources.</p>
        <p>- Notez que plus vous serez rapide à sortir une new, plus ça donnera une image réactive de Mangas'Fan.</p>
        <p>- En cas de questions, vous pouvez les poser aux administrateurs et aux développeurs.</p>
        <p>- Prenez du plaisir à rédiger.</p>
    </section>
      <?php include('../elements/footer.php'); ?>
    </div>
  </body>
</html>