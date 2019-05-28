<?php 
session_start();
include('../inc/functions.php');
include('../inc/base.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
include('../theme_temporaire.php');
?>
<!DOCTYPE HTML>
  <html>
    <head>
        <meta charset="utf-8" />
        <title>Mangas'Fan - Modifier une page</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
        <link rel="stylesheet" href="https://www.mangasfan.fr/bootstrap/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="icon" href="../images/favicon.png"/>
        <link rel="stylesheet" href="../style.css" />
        <script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
        <script type="text/javascript" src="tinymce/js/tinymce/tinymce.js"></script>
        <script>
     tinymce.init({
      selector: 'textarea',
      height: 250,
      language: 'fr_FR',
      theme: 'modern',
      plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
      toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
      image_advtab: true,
      entity_encoding : "raw",
      encoding: 'xml',
      extended_valid_elements : 'img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]',
      auto_focus: 'element1'
     });
    </script>

    </head>
  <body>
    <div id="bloc_page">
    <?php
      $message = '';
     if($utilisateur['grade'] >= 4){
        $crit = stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($_GET['id_page'])))));
        $select_all_news = $pdo->query("SELECT * FROM billets_mangas_pages WHERE id ='".$crit."'")->fetch();
        $select_all = $pdo->query("SELECT * FROM billets_mangas_onglet WHERE id = '$select_all_news['onglet_id']'");
        $new = $select_all->fetch();
    ?>
    <?php include('../elements/nav_redac.php') ?>
    <section class="marge_page">
      <center>
        <h2 class="titre_commentaire_news">Modifier <span class="couleur_mangas">la</span> <span class="couleur_fans">page</span></h2>
      </center>
    <a href="redac.php">Retournez à l'index de la rédaction</a> - <a href="modif_news_mangas.php?id_news=<?php echo $new['billets_id'];?>">Retournez à l'administration du manga</a>


    <h4 class="titre_commentaire_news">Modification <span class="couleur_mangas">d'une</span> <span class="couleur_fans">page</span> </h4>
    <?php 
      if (isset($_POST['valider_page'])){
          $categorie = addslashes(htmlspecialchars($_POST['liste_onglets']));
          $titre_page = addslashes(htmlspecialchars($_POST['titre_page']));
          $contenu = addslashes(htmlspecialchars($_POST['en_attente']));
          if ($categorie != '---'){
            if (!empty($_POST['titre_page'])){
              if (strlen($titre_page) > 4 AND strlen($titre_page) < 36){
                if (!empty($_POST['en_attente'])){
                  $modif_page = $pdo->prepare("UPDATE billets_mangas_pages SET onglet_id = ?, nom = ?,contenu = ? WHERE id = '$crit'");

                  $onglet_id = $pdo->query("SELECT id FROM billets_mangas_onglet WHERE nom = '$categorie' AND billets_id = '$crit'")->fetch();

                  $modif_page->execute(array($onglet_id['id'],$titre_page,$contenu));

                  $message = "<div class='alert alert-success' role='alert'>Votre page a bien été modifiée !</div>";

                } else {
                  $message = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Veuillez remplir le champ \"Texte\".</div>";
                }
              } else {
                $message = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Vous devez sélectionner un titre possédant entre 5 et 35 caractères.</div>";
              }
            } else {
              $message = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Veuillez remplir le champ \"Titre\".</div>";
            }
          } else {
            $message = "<div class='alert alert-warning' role='alert'><b>Erreur : </b>Vous devez sélectionner un onglet proposé. S'il vous en manque l'onglet correspondant, veuillez l'ajouter.</div>";
          }
        } ?>
          <center>
            <?php echo $message;?>
            <form method="POST" action="">
            <label> Sélectionnez un onglet : </label>
            <select class="form-control" id="exampleSelect1" name="liste_onglets">
                <option>---</option>
                <?php $onglet_exist = $pdo->query("SELECT * FROM billets_mangas_onglet WHERE billets_id = '$crit'");
                while($parcours_onglet = $onglet_exist->fetch()) { ?>
                <option <?php if($parcours_onglet['id'] == $select_all_news['onglet_id']){ ?> selected="selected" <?php } ?> ><?php echo $parcours_onglet['nom'] ;?></option>
                <?php } ?>
            </select><br /><br />
            <label> Titre : </label>
            <input type="text" class="form-control" name="titre_page" value="<?php echo stripslashes($select_all_news['nom']); ?>"><br /><br />
            <label> Texte : </label>
            <textarea name="en_attente"><?php echo htmlspecialchars_decode(htmlspecialchars_decode($select_all_news['contenu']));?></textarea>
            <input type="submit" class="btn btn-sm btn-info" name="valider_page" value="Valider la page">
          </form></center>
        <?php } else {
          echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
        } ?>
      </section>
      <?php include('../elements/footer.php'); ?>
    </div>
   </body>
</html>

  <style>
    .form-control[name="new_onglet"],.form-control[name="titre_page"]{
      padding:none !important;
      width:30%;
      display:inline-block;
      margin-left:15px;
      margin-bottom:5px;
  }

  .form-control[name="liste_onglets"], option {
      width:40%;
      display:inline-block;
  }
</style>