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
$admin = $pdo->prepare('SELECT id, username FROM users WHERE username = "Équipe du site"');
$admin->execute();
$id_admin = $admin->fetch();
if (isset($_POST['valider'])){
  if(strlen($_POST['titre']) < 4 || strlen($_POST['titre']) > 80){
    $errors[] = "Le titre de votre article est trop court.";
  }
  if(strlen($_POST['description']) < 20 || strlen($_POST['description']) > 200){
    $errors[] = "Votre description doit faire entre 20 et 200 caractères. Votre description faisait : " . strlen($_POST['description']) . " caractères.";
  }
  if(empty($_POST['image'])){
    $errors[] = "Vous n'avez pas renseigné d'images.";
  }
  if(isset($_POST['contenu_news']) AND strlen($_POST['contenu_news']) < 100){
    $errors[] = "Votre contenu doit posséder minimum 100 caractères. Votre contenu faisait : " . strlen($_POST['contenu_news']) . " caractères.";
  }
  if(empty($errors) AND isset($_POST['valider'])){
    $url = "https://discordapp.com/api/webhooks/662479994714456065/RLQZ82-lXO4-QRxq5FVn2VDVHT4AW5Vwr_y_ik5CoXwCJDQp5PClrBfVTMnWtQpgIAd2";
    if (isset($_POST['programmation_news']) AND !empty($_POST['programmation_news']) AND $_POST['auteur'] == 0) {
      $ajouter_news = $pdo->prepare('INSERT INTO billets(titre, description, keywords, theme, contenu, date_creation, auteur, categorie, sources, visible) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
      $ajouter_news->execute(array($_POST['titre'], $_POST['description'], $_POST['keywords'], $_POST['image'], $_POST['contenu_news'], $_POST['programmation_news'], $utilisateur['id'], $_POST['categorie'], $_POST['sources'], $_POST['visible']));
    } elseif (isset($_POST['programmation_news']) AND !empty($_POST['programmation_news']) AND $_POST['auteur'] == 1) {
      $ajouter_news = $pdo->prepare('INSERT INTO billets(titre, description, keywords, theme, contenu, date_creation, auteur, categorie, sources, visible) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
      $ajouter_news->execute(array($_POST['titre'], $_POST['description'], $_POST['keywords'], $_POST['image'], $_POST['contenu_news'], $_POST['programmation_news'], $id_admin['id'], $_POST['categorie'], $_POST['sources'], $_POST['visible']));
    }  elseif (empty($_POST['programmation_news']) AND $_POST['auteur'] == 1) {
      $ajouter_news = $pdo->prepare('INSERT INTO billets(titre, description, keywords, theme, contenu, date_creation, auteur, categorie, sources, visible) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)');
      $ajouter_news->execute(array($_POST['titre'], $_POST['description'], $_POST['keywords'], $_POST['image'], $_POST['contenu_news'], $id_admin['id'], $_POST['categorie'], $_POST['sources'], $_POST['visible']));
      $hookObject = json_encode([
        "tts" => false,
        "content" => "Une nouvelle news a été postée sur le site ! (Cliquez sur le titre pour accéder à la news)",
        "embeds" => [
          [
            "title" => htmlspecialchars($_POST['titre']),
            "type" => "rich",
            "description" => htmlspecialchars($_POST['description']),
            "url" => "https://www.mangasfan.fr/commentaire/".traduire_nom($_POST['titre']),
            "color" => 12211667,
            "author" => [
              "name" => "Mangas'Fan - Nouvelle news !",
              "url" => "https://www.mangasfan.fr",
              "icon_url" => "https://images-ext-1.discordapp.net/external/fPFRMFRClTDREMNdBVT20N4UAbBb8JjeMoiy8Bc3oAY/%3Fwidth%3D473%26height%3D473/https/media.discordapp.net/attachments/417370151424360448/658301476413898792/favicon.png"
            ],
            "footer" => [
              "text" => "N'hésitez pas à réagir avec les réactions Discord !"
            ],
            "image" => [
              "url" => htmlspecialchars($_POST['image'])
            ],
          ]
        ]

      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

      $ch = curl_init();

      curl_setopt_array( $ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $hookObject,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
      ]);

      $response = curl_exec( $ch );
      curl_close( $ch );
    } else {
      $ajouter_news = $pdo->prepare('INSERT INTO billets(titre, description, keywords, theme, contenu, date_creation, auteur, categorie, sources, visible) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)');
      $ajouter_news->execute(array($_POST['titre'], $_POST['description'], $_POST['keywords'], $_POST['image'], $_POST['contenu_news'], $utilisateur['id'], $_POST['categorie'], $_POST['sources'], $_POST['visible']));
      $hookObject = json_encode([
        "tts" => false,
        "content" => "Une nouvelle news a été postée sur le site ! (Cliquez sur le titre pour accéder à la news)",
        "embeds" => [
          [
            "title" => htmlspecialchars($_POST['titre']),
            "type" => "rich",
            "description" => htmlspecialchars($_POST['description']),
            "url" => "https://www.mangasfan.fr/commentaire/".traduire_nom($_POST['titre']),
            "color" => 12211667,
            "author" => [
              "name" => "Mangas'Fan - Nouvelle news !",
              "url" => "https://www.mangasfan.fr",
              "icon_url" => "https://images-ext-1.discordapp.net/external/fPFRMFRClTDREMNdBVT20N4UAbBb8JjeMoiy8Bc3oAY/%3Fwidth%3D473%26height%3D473/https/media.discordapp.net/attachments/417370151424360448/658301476413898792/favicon.png"
            ],
            "footer" => [
              "text" => "N'hésitez pas à réagir avec les réactions Discord !"
            ],
            "image" => [
              "url" => htmlspecialchars($_POST['image'])
            ],
          ]
        ]

      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

      $ch = curl_init();

      curl_setopt_array( $ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $hookObject,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
      ]);

      $response = curl_exec( $ch );
      curl_close( $ch );
    }
    $texte = "La news a bien été postée.";
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Rédiger une news - Mangas'Fan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="../images/favicon.png" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
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
      plugins: ['autolink visualblocks visualchars image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern autosave'],
      toolbar: 'undo redo |  formatselect | tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | bold italic underline forecolor | alignleft aligncenter alignright alignjustify | bullist numlist | removeformat | restoredraft',
       browser_spellcheck: true,
  contextmenu: false,
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
        <h1 class="titre_principal_news">Rédiger une nouvelle news</h1>
        <hr>
        <section>
          <a href="index.php" class="btn btn-primary btn-sm">Retourner à l'index de la rédaction</a>
          <a href="../hebergeur/index.php" class="btn btn-primary btn-sm" target="_blank">Accéder à l'hébergeur d'images</a>
        </section>
        <?php if(empty($errors) AND isset($_POST['valider'])){ ?>
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
                <label>Titre de la news : </label>
                <input type="text" name="titre" class="form-control" placeholder="Entrez le titre de la news : il doit être explicite." value="<?php if(isset($_POST['titre'])){ echo sanitize($_POST['titre']); } ?>" />
                <br/>
                <label>Description de la new :</label>
                <input type="text" name="description" class="form-control" placeholder="Entrez une courte description de la news pour la résumer !" value="<?php if(isset($_POST['description'])){ echo sanitize($_POST['description']); } ?>" />
                <br/>
                <label>Image de la news (200*200) : </label>
                <input type="url" name="image" class="form-control" placeholder="Lien de l'image" value="<?php if(isset($_POST['image'])){ echo sanitize($_POST['image']); } ?>"/>
                <br/>
                <label>Catégorie de la news : </label>
                <select name="categorie" class="form-control">
                  <?php if(isset($_POST['categorie'])) { ?>
                    <option value="<?= sanitize($_POST['categorie']); ?>"><?= sanitize($_POST['categorie']); ?></option>
                  <?php } ?>
                  <option value="Site">Site</option>
                  <option value="Jeux vidéo">Jeux vidéo</option>
                  <option value="Mangas">Mangas</option>
                  <option value="Anime">Anime</option>
                  <option value="Autres">Autres</option>
                </select>
                <br/>
                <label>Mots-clés : </label>
                <input type="text" name="keywords" class="form-control" placeholder="Vos mots-clés, séparés par une virgule" value="<?php if(isset($_POST['keywords'])){ echo sanitize($_POST['keywords']); } ?>"/>
              </div>
              <div class="col-md-6">
                <label>Sources : </label>
                <input type="text" name="sources" class="form-control" placeholder="Sources" value="<?php if(isset($_POST['sources'])){ echo sanitize($_POST['sources']); } ?>"/>
                <br/>
                <label>Visibilité de la news :</label> 
                <select class="form-control" name="visible" placeholder="Voulez-vous rendre la news visible aux gens ?">
                  <option value="0">Visible</option>
                  <option value="1">Cachée</option>
                </select>
                <br/>
                <label>Auteur de la news :</label> 
                <select class="form-control" name="auteur">
                  <option value="0"><?= sanitize($utilisateur['username']); ?></option>
                  <option value="1"><?= sanitize($id_admin['username']); ?></option>
                </select>
                <br/>
                <label>Programmer la news :</label> 
                <input type="datetime-local" class="form-control" name="programmation_news" value="<?php if(isset($_POST['programmation_news'])){ echo sanitize($_POST['programmation_news']); } ?>"/>
              </div>
            </div>
            <br/>
            <label>Contenu de la new :</label>
            <textarea name="contenu_news" id="contenu_redac"><?php if(isset($_POST['contenu_news'])){ echo sanitize($_POST['contenu_news']); } ?></textarea>
            <input type="submit" class="btn btn-sm btn-info" name="valider"/>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<?php include('../elements/footer.php'); ?>
</body>
</html>