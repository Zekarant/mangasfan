<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
if (!isset($_SESSION['auth'])) {
  header('Location: ../erreurs/erreur_403.php');
  exit();
}
if (isset($_SESSION['auth']) && $utilisateur['grade'] < 5) {
  header('Location: ../erreurs/erreur_403.php');
  exit();
}
if (isset($_POST['valider_news'])){
  if(strlen($_POST['modif_titre']) < 4 || strlen($_POST['modif_titre']) > 80){
    $errors[] = "Le titre de votre article est trop court.";
  }
  if(strlen($_POST['modif_description']) < 20 || strlen($_POST['modif_description']) > 200){
    $errors[] = "Votre description doit faire entre 20 et 200 caractères. Votre description faisait : " . strlen($_POST['modif_description']) . " caractères.";
  }
  if(empty($_POST['modif_image'])){
    $errors[] = "Vous n'avez pas renseigné d'images.";
  }
  if(isset($_POST['modif_contenu']) AND strlen($_POST['modif_contenu']) < 100){
    $errors[] = "Votre contenu doit posséder minimum 100 caractères. Votre contenu faisait : " . strlen($_POST['contenu_news']) . " caractères.";
  }
  if(empty($errors) AND isset($_POST['valider_news'])){
    if(isset($_POST['programmation_news']) AND !empty($_POST['programmation_news'])){
      $modification = $pdo->prepare('UPDATE billets SET titre = ?, description = ?, date_creation = ?, keywords = ?, theme = ?, contenu = ?, categorie = ?, sources = ?, visible = ? WHERE id = ?');
      $modification->execute(array($_POST['modif_titre'], $_POST['modif_description'], $_POST['programmation_news'], $_POST['modif_keywords'], $_POST['modif_image'], $_POST['modif_contenu'], $_POST['modif_categorie'], $_POST['modif_sources'], $_POST['modif_visibilité'], $_GET['id_news']));
    } else {
      $modification = $pdo->prepare('UPDATE billets SET titre = ?, description = ?, keywords = ?, theme = ?, contenu = ?, categorie = ?, sources = ?, visible = ? WHERE id = ?');
      $modification->execute(array($_POST['modif_titre'], $_POST['modif_description'], $_POST['modif_keywords'], $_POST['modif_image'], $_POST['modif_contenu'], $_POST['modif_categorie'], $_POST['modif_sources'], $_POST['modif_visibilité'], $_GET['id_news']));
    }
    $texte = "La news a bien été modifiée !";
  }
}
$donnees_news = $pdo->prepare('SELECT b.id, b.titre, b.date_creation, b.contenu, b.description, b.keywords, b.theme, b.auteur, b.categorie, b.sources, b.visible, u.id, u.username FROM billets b LEFT JOIN users u ON auteur = u.id WHERE b.id = ?');
$donnees_news->execute(array($_GET['id_news']));
$news = $donnees_news->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<title>Modifier une news - Mangas'Fan</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../images/favicon.png" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"/>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="tinymce/js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript" src="tinymce/js/tinymce/tinymce.js"></script>
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
<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div class="container-fluid">
<div class="row">
<div class="col-md-2 d-none d-md-block bg-light sidebar" style="padding: 0px!important">
<?php include('../elements/navredac_v.php'); ?>
</div>
<div class="col-sm-10" style="background-color: white; border-left: 2px solid grey; padding: 0px!important;">
  <?php include ('../elements/nav_redac.php'); ?>
  <h1 class="titre_principal_news">Modifier la news « <?= sanitize($news['titre']); ?> »</h1>
  <hr>
  <section>
    <a href="index.php" class="btn btn-primary btn-sm">Retourner à l'index de la rédaction</a>
    <a href="../hebergeur/index.php" class="btn btn-primary btn-sm" target="_blank">Accéder à l'hébergeur d'images</a>
  </section>
  <?php if(empty($errors) AND isset($_POST['valider_news'])){ ?>
    <div class='alert alert-success' role='alert'>
      <?= sanitize($texte); ?>
    </div>
  <?php } ?>
  <?php if(!empty($errors)): ?>
    <div class='alert alert-warning' role='alert'>
      <h4>Oups ! On a un problème chef...</h4>
      <hr>
      <p>On a un petit problème chef, il semblerait que vous ayez oublié les détails suivants :</p>
      <ul><?php foreach($errors as $error): ?>
      <li><?= $error; ?></li>
      <?php endforeach; ?></ul>
    </div>
  <?php endif; ?>
  <div class="container">
    <form method="POST" action="">
      <div class="row">
        <div class="col-md-6">
          <label>Modifier le titre de la news :</label>
          <input type="text" name="modif_titre" class="form-control" value="<?= sanitize($news['titre']); ?>">
          <br/>
          <label>Modifier la description de la news :</label>
          <input type="text" name="modif_description" class="form-control" value="<?= sanitize($news['description']); ?>">
          <br/>
          <label>Modifier l'image de la news :</label>
          <input type="text" name="modif_image" class="form-control" value="<?= sanitize($news['theme']); ?>">
          <a href="<?= sanitize($news['theme']); ?>" target="_blank">Voir l'image de news utilisée</a>
          <br/>
          <label>Catégorie de la news : </label>
          <select name="modif_categorie" class="form-control">
            <option value="Site" <?= (($news['categorie'] == "Site") ? "selected" : "" ) ?>>Site</option>
            <option value="Jeux Vidéo" <?= (($news['categorie'] == "Jeux Vidéo") ? "selected" : "" ) ?>>Jeux Vidéo</option>
            <option value="Mangas" <?= (($news['categorie'] == "Mangas") ? "selected" : "" ) ?>>Mangas</option>
            <option value="Anime" <?= (($news['categorie'] == "Anime") ? "selected" : "" ) ?>>Anime</option>
            <option value="Autres" <?= (($news['categorie'] == "Autres") ? "selected" : "" ) ?>>Autres</option>
          </select>
          <br/>
          <label>Modifier les mots-clés de la news :</label>
          <input type="text" name="modif_keywords" class="form-control" value="<?= sanitize($news['keywords']); ?>">
        </div>
        <div class="col-md-6">
          <label>Modifier les sources de la news :</label>
          <input type="text" name="modif_sources" class="form-control" placeholder="<?php if(empty($news['sources'])){ echo "Aucune source pour cette article."; } ?>" value="<?= sanitize($news['sources']); ?>">
          <br/>
          <label>Modifier la visibilité de la news :</label> <i><?php if($news['visible'] == 0) { echo "News visible"; } else { echo "News cachée."; } ?></i>
          <select name="modif_visibilité" class="form-control">
            <?php if($news['visible'] == 0){ ?>
              <option value="0" selected="selected">Visible</option>
              <option value="1">Cachée</option>
            <?php } else { ?>
              <option value="0">Visible</option>
              <option value="1" selected="selected">Cachée</option>
            <?php } ?>
          </select>
          <br/>
          <label>Auteur original de la news : (Non modifiable)</label>
          <input type="text" name="auteur" class="form-control" value="<?= sanitize($news['username']); ?>" readonly>
          <br/>
          <label>Programmer la news : <?php if (date('Y-m-d H:i:s') <= $news['date_creation']){ echo sanitize($news['date_creation']); } ?></label> 
          <input type="datetime-local" class="form-control" value="<?= sanitize($news['date_creation']); ?>" name="programmation_news"/>
        </div>
      </div>
      <br/>
      <label>Modifier le contenu de la news :</label>
      <textarea name="modif_contenu" class="form-control" id="contenu_redac">
        <?= sanitize($news['contenu']); ?>
      </textarea>
      <input type="submit" class="btn btn-sm btn-info" name="valider_news" value="Modifier la news" />
    </form>
  </div>
</div>
</div>
</div>
<?php include('../elements/footer.php'); ?>
</body>
</html>