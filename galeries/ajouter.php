<?php
session_start(); 
include('../membres/base.php');
include('../membres/functions.php'); 
include('../membres/data/maintenance_galeries.php');
if (!isset($_SESSION['auth'])) {
  header("Location: ../");
  exit();
}
if ($utilisateur['grade'] == 1){
  header("Location: ../bannis.php");
  exit();
}
if (isset($_POST['valider'])) {
  if ($utilisateur['grade'] > 1 && $utilisateur['grade'] <= 11) {
    if(isset($_POST['titre']) && strlen($_POST['titre']) < 3 || strlen($_POST['titre']) > 50){
      $errors[] = "Votre titre doit posséder entre 3 et 50 caractères. Votre titre faisait : " . strlen($_POST['titre']) . " caractères.";
      $couleur = "danger";
    }
    if(isset($_POST['contenu']) && strlen($_POST['contenu']) < 20){
      $errors[] = "Votre contenu doit faire au moins 20 caractères. Votre contenu faisait : " . strlen($_POST['contenu']) . " caractères.";
      $couleur = "danger";
    }
    if(empty($_FILES['image_galerie']['name'])){
      $errors[] = "Vous n'avez pas selectionné d'images.";
      $couleur = "danger";
    }
    if (empty($errors)){
      if (isset($_POST['nsfw'])) {
       $tailleMax = 2097152;
       $image = $_FILES['image_galerie']['name'];
       $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
       if($_FILES['image_galerie']['size'] <= $tailleMax) {
        $extensionUpload = strtolower(substr(strrchr($image, '.'), 1));
        if(in_array($extensionUpload, $extensionsValides)) {
          $chemin = "images/".$image;
          $resultat = move_uploaded_file($_FILES['image_galerie']['tmp_name'], $chemin);
          $updateavatar = $pdo->prepare('INSERT INTO galerie(filename, titre, titre_image, texte, date_image, auteur, nsfw) VALUES(?, ?, ?, ?, NOW(), ?, 1)')
          ;
          $updateavatar->execute(array($image, $_POST['titre'], $_POST['titre_image'], $_POST['contenu'], $utilisateur['id']));
          $errors[] = "Votre image a bien été herbergée sur le site avec le NSFW.";
          $couleur = "success";
        }
      }
    } else {
      $tailleMax = 2097152;
        $image = $_FILES['image_galerie']['name'];
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
        if($_FILES['image_galerie']['size'] <= $tailleMax) {
          $extensionUpload = strtolower(substr(strrchr($image, '.'), 1));
          if(in_array($extensionUpload, $extensionsValides)) {
            $chemin = "images/".$image;
            $resultat = move_uploaded_file($_FILES['image_galerie']['tmp_name'], $chemin);
            $updateavatar = $pdo->prepare('INSERT INTO galerie(filename, titre, titre_image, texte, date_image, auteur, nsfw) VALUES(?, ?, ?, ?, NOW(), ?, 0)')
            ;
            $updateavatar->execute(array($image, $_POST['titre'], $_POST['titre_image'], $_POST['contenu'], $utilisateur['id']));
            $errors[] = "Votre image a bien été herbergée sur le site sans le NSFW.";
            $couleur = "success";
          }
        }
    }
  }
}
}
?>
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <title>Ajouter une nouvelle image - Mangas'Fan</title>
 <link rel="icon" href="../images/favicon.png"/>
 <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
 <link rel="icon" href="images/favicon.png"/>
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include('../elements/header.php'); ?>
  <section>
    <h1 class="titre_principal_news">Ajouter une nouvelle image sur ma galerie</h1>
    <hr>
    <a href="administration_galerie.php" class="btn btn-primary">Retourner à l'administration de ma galerie</a><br/><br/>
    <div class='alert alert-warning' role='alert'>
      <b>Information importante :</b> Vous avez très certainement envie d'ajouter une image tout de suite, mais noter que pour que votre image soit ajoutée, il faut qu'elle respecte quelques contraintes pour permettre une bonne lecture :<br/><br/>
      - Votre titre doit comporter entre <b>3 et 50 caractères.</b><br/>
      - Les <b>mots clés</b> sont facultatifs. Si vous en mettez, ils doivent être séparés par une virgule !<br/>
      - Votre contenu doit faire au minimum <b>20 caractères</b>.<br/><br/>
      <b>Note :</b> Dans le cas où un de ces critères ne serait pas respecté, lorsque vous validerez votre formulaire, une erreur apparaitra vous indiquant ce que vous devez corriger.
    </div>
    <?php if(!empty($errors)): ?>
      <div class='alert alert-<?= sanitize($couleur); ?>' role='alert'>
        <?php foreach($errors as $error): ?>
          - <?= $error; ?><br/>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="container">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6">
            <label>Titre :</label> 
            <input type="text" name="titre" class="form-control" placeholder="Votre titre">
            <br/>
            <label>Votre image :</label><br/>
            <input type="file" name="image_galerie" class="file btn btn-info"/>
            <br/><br/>
            <label>Mots clés de l'image (Merci de les séparer par une virgule) :</label>
            <input type="text" name="titre_image" class="form-control" placeholder="Insérez les mots clés de votre image, ils serviront au référencement. Facultatif.">
            <br/>
            <div class="custom-control custom-checkbox mr-sm-2">
              <input type="checkbox" class="custom-control-input" name="nsfw" id="customControlAutosizing">
              <label class="custom-control-label" for="customControlAutosizing">Ajouter cette image au NSFW</label>
            </div>
          </div>
          <div class="col-md-6">
            <label>Contenu :</label>
            <textarea type="texterea" name="contenu" rows="10" class="form-control" placeholder="Votre contenu"></textarea>
            <input type="submit" name="valider" class="btn btn-sm btn-info" value="Publier mon image" />
          </div>
        </div>
      </form>
    </div>
  </section>
  <?php include('../elements/footer.php'); ?>
</body>
</html>
