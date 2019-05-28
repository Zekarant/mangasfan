<?php
session_start();
include('base.php');
include('functions.php');
include('bbcode.php');
logged_only();
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
$password = isset( $_POST['password'] ) ? $_POST['password'] : '';
$manga = isset($_POST['manga']) ? $_POST['manga'] : '';
$anime = isset($_POST['anime']) ? $_POST['anime'] : '';
$avatar_site = isset($_POST['avatar_site']) ? $_POST['avatar_site'] : '';
$role = isset($_POST['role']) ? $_POST['role'] : '';
$date_anniv = isset($_POST['date_anniv']) ? $_POST['date_anniv'] : '';
$site = isset($_POST['site']) ? $_POST['site'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$sqdd = $_POST['sqdd'] ?? "";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Mangas'Fan - Compte de <?php echo sanitize($utilisateur['username']);?></title>
  <link rel="icon" href="../images/favicon.png"/>
  <meta http-equiv="pragma" content="no-cache" />
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<div id="bloc_page">
    <?php include('../elements/header.php'); ?>
       <h2 class="titre_principal_news">
        Profil de <?php echo sanitize($utilisateur['username']); ?>
      </h2><br/>
      <?php include("../elements/messages.php"); ?>
      <?php
      if ($utilisateur['grade'] == 1) {
      ?>
      <div class="alert alert-danger" id="animation_compte" role="alert">
        <h5 class="alert-heading"><span class="glyphicon glyphicon-star"></span> 
          Vous êtes actuellement bannis du site !<span class="glyphicon glyphicon-star"></span>
        </h5>
        <hr>
        Cher <?php echo sanitize($utilisateur['username']); ?>,<br/>
        Si vous voyez ce message, c'est que votre compte est actuellement bannis du site.<br/><br/>
        Que-ce que cela veut dire ?<br/><br/>
        Etre banni du site signifique que les accès suivants vous sont supprimés : <br/>
        <ul>
          <li>Impossibilité d'envoyer/répondre à des MP.</li>
          <li>Impossibilité de commenter les news/articles du site.</li>
          <li>Vous apparaissez désormais en noir sur le site montrant que vous êtes bannis.</li>
        </ul>
      </div>
      <?php
      }
      $select_anim = $pdo->prepare("SELECT * FROM animation");
      $select_anim->execute();
      $animation = $select_anim->fetch();
      if ($animation['visible'] == 1) {
      if($animation['title'] == "animation")
      { ?>
        <div class="alert alert-success" id="animation_compte" role="alert">
           <h4 class="alert-heading"><span class="glyphicon glyphicon-star"></span> 
              Animation en cours <span class="glyphicon glyphicon-star"></span>
            </h4>
           <hr>
        <?php   
          echo bbcode(sanitize($animation['contenu']));
        ?> </div> 
      <?php } elseif($animation['title'] == "recrutements") { ?>
      <div class="alert alert-info" id="animation_compte" role="alert">
         <h4 class="alert-heading"><span class="glyphicon glyphicon-bullhorn"></span> <center>Recrutements en cours </center><span class="glyphicon glyphicon-bullhorn"></span>
            </h4>
           <hr>
        <?php   
          echo bbcode(sanitize($animation['contenu']));
        ?> </div> 
      <?php } elseif($animation['title'] == "annonce") { ?>
      <div class="alert alert-warning" id="animation_compte" role="alert">
         <h4 class="alert-heading"><span class="glyphicon glyphicon-bell"></span> <center>Annonce importante </center><span class="glyphicon glyphicon-bell"></span>
            </h4>
           <hr>
        <?php   
          echo bbcode(sanitize($animation['contenu']));
        ?> </div> 
      <?php } elseif($animation['title'] == "maj") { ?>
      <div class="alert alert-dark" id="bloc_animation_maj" role="alert">
         <h4 class="alert-heading"><span class="glyphicon glyphicon-cog"></span> <center>Dernière mise à jour </center><span class="glyphicon glyphicon-cog"></span>
            </h4>
           <hr>
        <?php   
          echo bbcode(sanitize($animation['contenu']));
        ?> </div> 
      <?php } elseif($animation['title'] == "alerte") { ?>
      <div class="alert alert-danger" id="animation_compte" role="alert">
         <h4 class="alert-heading"><span class="glyphicon glyphicon-remove"></span> <center>Alerte importante </center><span class="glyphicon glyphicon-remove"></span>
            </h4>
           <hr>
        <?php   
          echo bbcode(sanitize($animation['contenu']));
        ?> </div> 
      <?php } } 
      if (!empty($_POST['changer_mdp'])) {
        if(!empty(sanitize($password)) && sanitize($password) == sanitize($_POST['password_confirm'])){
          $user_id = $utilisateur['id'];
          $password = password_hash($password, PASSWORD_BCRYPT);
          $mdp = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
          $mdp->execute([$password, $user_id]);
        ?>
          <div class='alert alert-success' role='alert'>
            Votre mot de passe a bien été modifié !
          </div>
        <?php } else { ?>
          <div class='alert alert-danger' role='alert'>
            Les deux mots de passe ne correspondent pas. Veuillez recommencer.
          </div>
        <?php } }
        if (!empty($_POST['valider_avatar'])){
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
               'id' => $utilisateur['id']
             ));
          ?>
            <script>location.href="compte.php";</script>
            <div class='alert alert-success' role='alert'>
              Votre avatar a bien été upload sur le serveur
            </div>
          <?php } else { ?>
            <div class='alert alert-danger' role='alert'>
              Erreur durant l'importation de votre photo de profil
            </div>
          <?php } } else { ?>
            <div class='alert alert-danger' role='alert'>
              Votre photo de profil doit être au format jpg, jpeg, gif ou png.
            </div>
          <?php } } else { ?>
            <div class='alert alert-danger' role='alert'>
              Votre photo de profil ne doit pas dépasser 2Mo.
            </div>
          <?php } }  else { ?>
            <div class='alert alert-danger' role='alert'>
              Vous n'avez pas sélectionné d'image pour votre avatar. Veuillez recommencer.
            </div>
          <?php } }
          if (!empty($_POST['valider_informations'])) {
             $modifier_compte = $pdo->prepare('UPDATE users SET site = ?, description = ?, role = ? WHERE id = ?');
             $modifier_compte->execute(array($_POST['site'], $_POST['description'], $_POST['role'], $utilisateur['id']));
          ?>
            <div class='alert alert-success' role='alert'>
              Vos informations ont bien été modifiées !
            </div>
          <?php } 
          if (!empty($_POST['valider_autre'])) {
             $modifier_compte = $pdo->prepare('UPDATE users SET manga = ?, anime = ? WHERE id = ?');
             $modifier_compte->execute(array($_POST['manga'], $_POST['anime'], $utilisateur['id']));
          ?>
            <div class='alert alert-success' role='alert'>
              Vos informations supplémentaires ont bien été modifiées !
            </div>
          <?php }  
          


            $recuperer_compte = $pdo->prepare('SELECT * FROM users WHERE id = ?'); 
            $recuperer_compte->execute(array($utilisateur['id']));
            $informations = $recuperer_compte->fetch();
          ?>
<section class="marge_page">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            Vos informations personnelles - <span class="couleur_mangas">M</span>angas'<span class="couleur_fans">F</span>an
          </div>
          <div class="card-body">
            <form action="" method="POST">
              <div class="form-group row">
                <label class="col-md-3">Mot de passe :</label>
                  <div class="col-md-9">
                    <input type="password" name="password" class="form-control" placeholder="Changer le mot de passe" />
                    <input type="password" name="password_confirm" class="form-control" placeholder="Confirmation du mot de passe" />
                    <input type="submit" class="btn btn-sm btn-info" name="changer_mdp" value="Changer mon mot de passe">
                  </div>
              </div>
            </form>
            <hr>
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="row">
                <label class="col-md-3">Mon avatar :</label>
                  <div class="col-md-9">
                    <input type="file" name="avatar" class="file btn btn-info"/><br/>
                    <input type="submit" name="valider_avatar" class="btn btn-sm btn-info" value="Upload mon avatar" />
                  </div>
              </div>
            </form>
            <form action="" method="POST">
              <?php if ($utilisateur['date_anniv'] == NULL){ ?>
                <div class="row">
                    <label class="col-md-3">Mon anniversaire :</label>
                      <div class="col-md-9">
                        <i>Une fois validée, vous ne pourrez plus la changer. Mettez donc la bonne.</i>
                      <input type="date" name="date_anniv" class="form-control" placeholder="Changer ma date d'anniversaire" />
                    </div>
                </div>
              <?php } ?>
            </form>
            <form action="" method="POST">
              <div class="row">
                <label class="col-md-3">Mon site :</label>
                    <div class="col-md-9">
                      <input type="text" name="site" class="form-control" placeholder="Changer mon site (Mettre le lien)" value="<?php if($informations['site'] != NULL){ echo sanitize($informations['site']); } ?>" />
                    </div>
                </div>
                <br/>
                <div class="row">
                <label class="col-md-3">Ma description :</label>
                    <div class="col-md-9">
                      <textarea name="description" class="form-control" rows="10" cols="70" placeholder="Entrez ou modifiez votre description sur vous ou mettez quelque chose que vous avez envie de dire ! Cette partie est à vous."><?php if($informations['description'] != NULL){ echo sanitize($informations['description']); } ?></textarea>
                      <a href="bbcode_active.html" class="lien_bbcode" target="blank">Voici la liste des bbcodes possibles</a><br/>
                      <button class="btn btn-sm btn-danger" type="reset">Défaut</button>
                    </div>
                </div>
                <br/>
                <?php if($utilisateur['grade'] >= 3){ ?>
                <div class="row">
                  <label class="col-md-3">Mon rôle (Staff) :</label>
                    <div class="col-md-9">
                      <textarea name="role" class="form-control" rows="10" cols="30"  placeholder="Entrez ou modifiez votre rôle sur le site, cette partie n'est visible que par les membres du staff et servira à montrer sur votre page de profil le rôle que vous avez !" ><?php if($informations['role'] != NULL){ echo sanitize($informations['role']); } ?></textarea>
                      <button class="btn btn-sm btn-danger" type="reset">Défaut</button>
                    </div>
                </div>
                <br/>
                <?php } ?>
                <label>Valider mes informations :</label>
                  <input type="submit" name="valider_informations" class="btn btn-sm btn-info">
            </form>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            Vos autres informations - <span class="couleur_mangas">M</span>angas'<span class="couleur_fans">F</span>an
          </div>
          <div class="card-body">
            <form method="POST" action="">
              <div class="row">
                  <label class="col-md-3">Mon mangas :</label>
                    <div class="col-md-9">
                      <input type="text" name="manga" class="form-control" placeholder="Renseigner mon manga préféré" value="<?php if($informations['manga'] != NULL){ echo sanitize($informations['manga']); } ?>" />
                    </div>
                </div>
                <br/>
                <div class="row">
                  <label class="col-md-3">Mon anime :</label>
                    <div class="col-md-9">
                      <input type="text" name="anime" class="form-control" placeholder="Renseigner mon anime préféré" value="<?php if($informations['anime'] != NULL){ echo sanitize($informations['anime']); } ?>" />
                    </div>
                </div>
                <br/>
                <label>Valider mes informations :</label>
                  <input type="submit" name="valider_autre" class="btn btn-sm btn-info">
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            Récapitulatif de mes informations
          </div>
          <div class="card-body">
            <p>Mon pseudo : <i><?php echo sanitize($utilisateur['username']); ?></i></p>
            <p>Mon adresse Mail : <i><?php echo sanitize($utilisateur['email']); ?></i></p>
            <p>Mon manga préféré : <i><?php echo sanitize($utilisateur['manga']); ?></i></p>
            <p>Mon anime préféré : <i><?php echo $utilisateur['anime']; ?></i></p>
            <p>Ma date d'anniversaire : <i><?php $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
            $date_anniversaire= preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2})#",function ($key) use ($liste_mois){ 
            return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; }, $utilisateur['date_anniv']);
            echo '<b>'.sanitize($date_anniversaire).'</b>'; ?></i></p>
            <p>Mon rang : <?php echo statut($utilisateur['grade']); ?></p>
            <?php if(($utilisateur['grade'] == 11) || ($utilisateur['grade'] >= 3)){ ?>
            <p>Mon rôle : <i>« <?php $texte = nl2br(sanitize($utilisateur['role'])); echo bbcode($texte); $sqdd = str_replace('\r\n', '<br>', $sqdd); ?> »</i></p><?php } ?>
            <p>Ma description : <i><br/><br/>« <?php $texte = nl2br(sanitize($utilisateur['description'])); echo bbcode($texte); $sqdd = str_replace('\r\n', '<br>', $sqdd); ?> »</i></p>
            <p>Mon site : <i><?php echo sanitize($utilisateur['site']); ?></i></p>
          </div>
        </div>
        <br/><br/>
         <div class="card">
          <div class="card-header">
            Récapitulatif des animations
          </div>
          <div class="card-body">
            <p>Vos Mangas'Points : <i><b><?php echo sanitize($utilisateur['points']); ?></b></i></p>
            <p>Nombre d'animations gagnées : <i><b><?php echo sanitize($utilisateur['animation_gagne']); ?></b></i></p>
            <p>Vos badges : <i>Prochainement...</i></p>
          </div>
        </div>
      </div>
  </div>
</section> 
    <?php include('../elements/footer.php'); ?>
</div>
</body>
</html>
