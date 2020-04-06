<?php
session_start();
include('base.php');
include('functions.php');
logged_only();
require_once '../markdown/Michelf/Markdown.inc.php';
require_once '../markdown/Michelf/MarkdownExtra.inc.php';
use Michelf\Markdown;
require __DIR__ . '/vendor/autoload.php';
use Xwilarg\Discord\OAuth2;
$recuperer_compte = $pdo->prepare('SELECT * FROM users WHERE id = ?'); 
$recuperer_compte->execute(array($utilisateur['id']));
$informations = $recuperer_compte->fetch();
$recuperer_badges = $pdo->prepare('SELECT ba.id, ba.badges_name, ba.badges_description, ba.badges_image, b.id_user, b.attribued_at, b.id_badge FROM badges_dons b INNER JOIN badges ba ON ba.id = b.id_badge WHERE id_user = ?');
$recuperer_badges->execute(array($utilisateur['id']));
$total_badges = $pdo->prepare('SELECT id FROM badges');
$total_badges->execute();
if(!empty($_POST['changer_mdp'])){
  if (isset($_SESSION['auth'])) {
    if (!empty($_POST['oldpassword'])) {
      if (!empty($_POST['password'])) {
        if (!empty($_POST['password_confirm'])) {
          if (password_verify($_POST['oldpassword'], $utilisateur['password'])) {
            if(!empty($_POST['password']) && $_POST['password'] != $_POST['password_confirm']){
              $errors[] = "Les deux mots de passe ne correpondent pas !";
              $couleur = "danger";
            }
            if ($_POST['password'] == $_POST['password_confirm'] AND strlen($_POST['password']) < 8) {
              $errors[] = "Le mot de passe est trop court ! (Minimum 8 caractères)";
              $couleur = "danger";
            }
            if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])#', $_POST['password'])) {
              $errors[] = "Le mot de passe doit contenir une majuscule et un chiffre !";
              $couleur = "danger";
            } if(empty($errors)){
              $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
              $mdp = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
              $mdp->execute([$password, $utilisateur['id']]);
            }
          } else {
            $errors[] = "Votre mot de passe actuel renseigné n'est pas le bon.";
            $couleur = "danger";
          }
        } else {
          $errors[] = "Vous n'avez pas renseigné confirmation de mot de passe.";
          $couleur = "danger";
        }
      } else {
        $errors[] = "Vous n'avez pas renseigné nouveau mot de passe.";
        $couleur = "danger";
      }
    } else {
      $errors[] = "Vous n'avez pas renseigné votre ancien mot de passe.";
      $couleur = "danger";
    }
  }
}
if (!empty($_POST['valider_avatar'])){
  if (isset($_SESSION['auth'])) {
    if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name'])) {
      $tailleMax = 2097152;
      $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
      if($_FILES['avatar']['size'] <= $tailleMax) {
        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        if(in_array($extensionUpload, $extensionsValides)) {
          $chemin = "images/avatars/".$utilisateur['id'].".".$extensionUpload;
          $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
          if($resultat) {
            $updateavatar = $pdo->prepare('UPDATE users SET avatar = :avatar WHERE id = :id');
            $updateavatar->execute(array(
              'avatar' => $utilisateur['id'].".".$extensionUpload,
              'id' => $utilisateur['id']));
            $couleur = "success";
            $texte = "Votre avatar a bien été upload sur le serveur.";
          } else {
            $couleur = "danger";
            $texte = "Erreur durant l'importation de votre photo de profil.";
          }
        } else {
          $couleur= "warning";
          $texte = "Votre photo de profil doit être au format jpg, jpeg, gif ou png.";
        }
      } else {
        $couleur = "danger";
        $texte = "Votre photo de profil ne doit pas dépasser 2Mo.";
      }
    } else {
      $couleur = "danger";
      $texte = "Vous n'avez pas sélectionné d'image pour votre avatar. Veuillez recommencer.";
    }
  }
}
if (!empty($_POST['valider_anniv'])) {
  if (isset($_SESSION['auth'])) {
    $modifier_information = $pdo->prepare('UPDATE users SET date_anniv = ? WHERE id = ?');
    $modifier_information->execute(array($_POST['date_anniv'], $utilisateur['id']));
    $couleur = "success";
    $texte = "Vous avez bien rentré votre date de naissance sur le site.";
  }
}
if (!empty($_POST['valider_informations'])) {
  if (isset($_SESSION['auth'])) {
    $modifier_information = $pdo->prepare('UPDATE users SET email = ?, description = ?, sexe = ?, role = ?, manga = ?, anime = ?, site = ? WHERE id = ?');
    $modifier_information->execute(array($_POST['email'], $_POST['description'], $_POST['sexe'], $_POST['role'], $_POST['manga'], $_POST['anime'], $_POST['site'], $utilisateur['id']));
    $couleur = "success";
    $texte = "Toutes les informations que vous avez voulu modifier sont désormais mises à jour !";
  }
}
$recuperation_discord = $pdo->prepare('SELECT id, username, pseudo_discord, id_discord FROM users WHERE id = ?');
$recuperation_discord->execute(array($utilisateur['id']));
$discord = $recuperation_discord->fetch();
$idclient = "628029772244582401";
$idsecret = "_lDzL_DzakGCbxA0Xl8sIa1tZf1zFhlI";
$redirection = "https://www.mangasfan.fr/membres/compte.php";
$parametres = new OAuth2($idclient, $idsecret, $redirection);
if ($parametres->isRedirected() === false){
  if (isset($_POST['discord'])){
    header('Location: discord.php');
    die();
  }
} else {
  $ok = $parametres->loadToken();
  if ($ok !== true){
    if (isset($_POST['discord'])){
      header('Location: discord.php');
      die();
    }
  } else {
    $answer = $parametres->getUserInformation();
    if (array_key_exists("code", $answer)){
      exit("Erreur : " . $answer["message"]);
    } else {
      $ajouter_discord = $pdo->prepare('UPDATE users SET pseudo_discord = ?, id_discord = ? WHERE username = ?');
      $ajouter_discord->execute(array($answer['username'], $answer['discriminator'], $utilisateur['username']));
      $_SESSION['flash']['success'] = "<div class='alert alert-success' role='alert'>Vous avez bien relié votre compte Discord à votre compte Mangas'Fan !</div>";
      header('Location: compte.php');
      die();
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Modifier mon compte - Mangas'Fan</title>
  <link rel="icon" href="../images/favicon.png"/>
  <meta http-equiv="pragma" content="no-cache" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <?php include('../elements/header.php'); ?>
  <h1 class="titre_principal_news">Profil de <?= sanitize($utilisateur['username']); ?></h1>
  <hr>
  <section>
    <?php include("../elements/messages.php");
    include('bbcode.php');
    if ($utilisateur['grade'] == 1) { ?>
      <div class="alert alert-danger" role="alert">
        <h5 class="alert-heading">Vous êtes actuellement bannis du site !</h5>
        <hr>
        Cher <?= sanitize($utilisateur['username']); ?>,<br/>
        Si vous voyez ce message, c'est que votre compte est actuellement bannis du site.<br/><br/>
        Que-ce que cela veut dire ?<br/><br/>
        Etre banni du site signifique que les accès suivants vous sont supprimés : <br/>
        <ul>
          <li>Impossibilité d'envoyer/répondre à des MP.</li>
          <li>Impossibilité de commenter les news/articles du site.</li>
          <li>Accès restreint aux galeries : vous ne pouvez plus poster d'images.</li>
          <li>Vous apparaissez désormais en noir sur le site montrant que vous êtes bannis.</li>
        </ul>
        Concrètement, vous êtes un membre avec les droits d'un visiteur. Nous sommes désolés que cela vous arrive.
      </div>
    <?php } if (isset($_POST['valider_anniv']) || isset($_POST['valider_avatar']) || isset($_POST['valider_informations'])) { ?>
      <div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
        <?= sanitize($texte); ?>
      </div>
    <?php } ?>
    <?php if(!empty($errors)): ?>
      <div class='alert alert-<?= sanitize($couleur); ?>' role='alert'>
        <p>Oups ! Nous n'avons pas pu modifier votre mot de passe pour les raisons suivantes :</p>
        <ul><?php foreach($errors as $error): ?>
        <li><?= $error; ?></li>
        <?php endforeach; ?></ul>
      </div>
    <?php endif; ?>
    <?php if(empty($errors) AND isset($_POST['password'])): ?>
    <div class='alert alert-success' role='alert'>
      Votre mot de passe a bien été modifié !
    </div>
  <?php endif; ?>
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            Modifier les informations de mon compte - Mangas'Fan
          </div>
          <div class="card-body">
            <form method="POST" action="">
              <div class="row">
                <label class="col-md-4">Modifier mon mot de passe :<br/><p class="attention_compte">- 1 majuscule et un chiffre obligatoire<br/>- 8 caractères minimum</p></label>
                <div class="col-md-8">
                  <input type="password" name="oldpassword" class="form-control" placeholder="Saisissez votre ancien mot de passe" />
                  <input type="password" name="password" class="form-control" placeholder="Saisissez votre nouveau mot de passe" />
                  <input type="password" name="password_confirm" class="form-control" placeholder="Saisissez à nouveau votre mot de passe pour confirmer" />
                  <input type="submit" class="btn btn-sm btn-info" name="changer_mdp" value="Changer mon mot de passe">
                </div>
              </div>
            </form>
            <hr>
            <form method="POST" action="" enctype="multipart/form-data">
              <div class="row">
                <label class="col-md-4">Sélectionner un avatar :<br/>
                  <p class="attention_compte">Pensez à vider le cache après le changement (Ctrl + F5)</p></label>
                  <div class="col-md-8">
                    <input type="file" name="avatar" class="file btn btn-info"/><br/>
                    <input type="submit" name="valider_avatar" class="btn btn-sm btn-info" value="Choisir ce fichier comme avatar" />
                  </div>
                </div>
              </form>
              <hr>
              <?php if ($utilisateur['date_anniv'] == NULL){ ?>
                <form method="POST" action="">
                  <div class="row">
                    <label class="col-md-4">Ma date d'anniversaire :</label>
                    <div class="col-md-8">
                      <em>Attention : une fois validée, vous ne pourrez plus la changer.</em>
                      <input type="date" name="date_anniv" class="form-control" placeholder="Changer ma date d'anniversaire" />
                      <input type="submit" name="valider_anniv" class="btn btn-info" value="Renseigner ma date d'anniversaire" />
                    </div>
                  </div>
                </form>
                <hr>
              <?php } ?>
              <form method="POST" action="">
               <div class="row">
                <label class="col-md-4">Modifier mon adresse email :</label>
                <div class="col-md-8">
                  <input type="email" name="email" class="form-control" placeholder="Modifier mon adresse email" value="<?= sanitize($informations['email']); ?>" />
                </div>
              </div>
              <br/>
              <div class="row">
                <label class="col-md-4">Modifier mon sexe :</label>
                <div class="col-md-8">
                  <select name="sexe" id="sexe" class="form-control">
                   <option value="Homme">Homme</option>
                   <option value="Femme">Femme</option>
                   <option value="Autre">Autre</option>
                 </select>
               </div>
             </div>
             <br/>
             <div class="row">
              <label class="col-md-4">Modifier ma description :<br/><a href="bbcode_active.html" class="attention_compte" target="blank">Voici la liste des bbcodes possibles</a><button class="btn btn-sm btn-danger" type="reset">Réinitialiser</button></label>
              <div class="col-md-8">
                <textarea name="description" class="form-control" rows="10" cols="70" placeholder="Entrez ou modifiez votre description sur vous ou mettez quelque chose que vous avez envie de dire ! Cette partie est à vous."><?php if($informations['description'] != NULL){ echo sanitize($informations['description']); } ?></textarea>
              </div>
            </div>
            <br/>
            <?php if($utilisateur['grade'] >= 3){ ?>
              <div class="row">
                <label class="col-md-4">Modifier mon rôle :<br/><button class="btn btn-sm btn-danger" type="reset">Réinitialiser</button></label>
                <div class="col-md-8">
                  <textarea name="role" class="form-control" rows="10" cols="30"  placeholder="Entrez ou modifiez votre rôle sur le site, cette partie n'est visible que par les membres du staff et servira à montrer sur votre page de profil le rôle que vous avez !" ><?php if($informations['role'] != NULL){ echo sanitize($informations['role']); } ?></textarea>
                </div>
              </div>
              <br/>
            <?php } ?>
            <div class="row">
              <label class="col-md-4">Manga favori :</label>
              <div class="col-md-8">
                <input type="text" name="manga" class="form-control" placeholder="Renseigner mon manga préféré" value="<?php if($informations['manga'] != NULL){ echo sanitize($informations['manga']); } ?>" />
              </div>
            </div>
            <br/>
            <div class="row">
              <label class="col-md-4">Anime favori :</label>
              <div class="col-md-8">
                <input type="text" name="anime" class="form-control" placeholder="Renseigner mon anime préféré" value="<?php if($informations['anime'] != NULL){ echo sanitize($informations['anime']); } ?>" />
              </div>
            </div>
            <br/>
            <div class="row">
              <label class="col-md-4">Modifier mon site internet :</label>
              <div class="col-md-8">
                <input type="url" name="site" class="form-control" placeholder="Modifier le lien de votre site internet" value="<?php if($informations['site'] != NULL){ echo sanitize($informations['site']); } ?>" />
              </div>
            </div>
            <br/>
            <div class="row justify-content-center">
              <div class="col-md-6">
                <input type="submit" name="valider_informations" class="btn btn-sm btn-info" value="Valider toutes les informations entrées ci-dessus">
              </div>
            </div>
          </form>
          <br/>
          <hr>
          <div class="row">
            <label class="col-md-4">Lier mon compte Discord :</label>
            <div class="col-md-8">
              <?php if ($discord['pseudo_discord'] == NULL) { ?>
                <form method="POST">
                  <input type="submit" name="discord" class="btn btn-info btn-sm" value="Relier mon compte Discord" />
                </form>
              <?php } else {
                echo $discord["pseudo_discord"] . "#" . $discord["id_discord"];
                ?>
                <br/>
                <form method="POST">
                  <input type="submit" name="discord" class="btn btn-info btn-sm" value="Mettre à jour mon compte Discord" />
                </form>
              <?php } ?>
            </div>
          </div>
          <br/>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          Récapitulatif de mon compte - Mangas'Fan
        </div>
        <div class="card-body">
          <p>Pseudonyme : <em><?= sanitize($utilisateur['username']); ?></em></p>
          <p>Adresse email : <em><?= sanitize($utilisateur['email']); ?></em></p>
          <?php if ($utilisateur['sexe'] != NULL) { ?>
            <p>Sexe : <em><?= sanitize($utilisateur['sexe']); ?></em></p>
          <?php } ?>
          <?php if ($utilisateur['manga'] != NULL) { ?>
            <p>Manga préféré : <em><?= sanitize($utilisateur['manga']); ?></em></p>
          <?php } ?>
          <?php if ($utilisateur['anime'] != NULL) { ?>
            <p>Anime préféré : <em><?= sanitize($utilisateur['anime']); ?></em></p>
          <?php } ?>
          <?php if ($utilisateur['date_anniv'] != NULL){ ?>
            <p>Date d'anniversaire : <em><?php $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
            $date_anniversaire = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2})#",function ($key) use ($liste_mois){
              return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; 
            }, $utilisateur['date_anniv']);
            echo sanitize($date_anniversaire); ?></em></p>
          <?php } ?>
          <p>Rang : <?php if($utilisateur['chef'] != 0){ 
            echo chef(sanitize($utilisateur['chef'])); 
          } else { 
            echo statut($utilisateur['grade'], $utilisateur['sexe']); 
          } ?></p>
          <?php if ($utilisateur['description'] != NULL){ ?>
            <p>Description : <em>« <?= bbcode(sanitize($utilisateur['description'])); ?> »</em></p>
          <?php } ?>
          <?php if ($utilisateur['grade'] >= 3 && $utilisateur['role'] != NULL){ ?>
            <p>Rôle : <em>« <?= bbcode(sanitize($utilisateur['role'])); ?> »</em></p>
          <?php } ?>
          <?php if ($utilisateur['site'] != NULL){ ?>
            <p>Site web : <em><a href="<?= sanitize($utilisateur['site']); ?>" target="_blank"><?= sanitize($utilisateur['site']); ?></a></em></p>
          <?php } ?>
          <p>Mangas'Points : <em><?= sanitize($utilisateur['points']); ?> points.</em></p>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          Mes badges - <?= $recuperer_badges->rowCount(); ?> / <?= $total_badges->rowCount(); ?> possédés
        </div>
        <div class="card-body">
          <?php if ($recuperer_badges->rowCount() > 0) {
            while($badges_affiche = $recuperer_badges->fetch()){ ?>
             <img src="<?php echo $badges_affiche['badges_image']; ?>" alt="Badge" class="image_enveloppe_mp_accueil" title="<?= sanitize($badges_affiche['badges_name']); ?> - <?= sanitize($badges_affiche['badges_description']); ?> Obtenu le <?= date('d M Y', strtotime(sanitize($badges_affiche['attribued_at']))); ?>" />
           <?php  }
         } else {
          echo "Vous n'avez aucun badge !";
        } ?>
      </div>
    </div>
      <div class="card">
        <div class="card-header">
          Modération de votre compte - Mangas'Fan
        </div>
        <div class="card-body">
          <?php if($utilisateur['grade'] >= 3){ ?>
            <a href="data/demission.php" class="btn btn-outline-danger">Démissionner de mon rôle</a><br/><br/>
          <?php } ?>
          <a href="data/suppression.php" class="btn btn-outline-danger">Supprimer mon compte</a>
        </div>
      </div>
  </div>
</div>
</div>
</section>
<?php include('../elements/footer.php'); ?>
</body>
</html>