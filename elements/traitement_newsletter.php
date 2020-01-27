<?php
$reqnewsletter = $pdo->prepare("SELECT * FROM newsletter WHERE ip = ?");
$reqnewsletter->execute(array($_SERVER['REMOTE_ADDR']));
$newsletterexist = $reqnewsletter->rowCount();
if(isset($_POST['newsletterform'])){
   if(isset($_POST['newsletter'])){
      if(!empty($_POST['newsletter'])){
         $newsletter = htmlspecialchars($_POST['newsletter']);
         if(filter_var($newsletter, FILTER_VALIDATE_EMAIL)) {
               $reqip = $pdo->prepare("SELECT * FROM newsletter WHERE ip = ?");
               $reqip->execute(array($_SERVER['REMOTE_ADDR']));
               $ipexist = $reqip->rowCount();
                  if($ipexist == 0) {
                     $reqmail = $pdo->prepare("SELECT * FROM newsletter WHERE email = ?");
                     $reqmail->execute(array($newsletter));
                     $mailexist = $reqmail->rowCount();
                     if($mailexist == 0){
                        $sql = $pdo->prepare('INSERT INTO newsletter(email,ip,dates) VALUES (?,?,NOW())');
                  $sql->execute(array($newsletter,$_SERVER['REMOTE_ADDR']));
                        echo '<script>location.href="https://www.mangasfan.fr";</script>';
                        echo "<div class='alert alert-success' role='alert'>Vous vous êtes bien inscrit à la newsletter !</div>";
                     } else {
                        $erreur = "<div class='alert alert-danger' role='alert'>Vous êtes déjà inscrit à la newsletter.</div>";
                     }
                  } else {
                     $erreur = "<div class='alert alert-danger' role='alert'>Vous êtes déjà inscrit à la newsletter.</div>";
                  }
         } else {
            $erreur = "<div class='alert alert-danger' role='alert'>Vous devez indiquer une adresse e-mail.</div>";
         }
      } else {
         $erreur = "<div class='alert alert-danger' role='alert'>Vous devez remplir tout les champs vides.</div>";
      }
   }
}
if(isset($_GET['deinscription'])){
   if(!empty($_GET['deinscription'])){
      $deinscription = htmlspecialchars($_GET['deinscription']);
      $reqip = $pdo->prepare("SELECT * FROM newsletter WHERE ip = ?");
       $reqip->execute(array($_SERVER['REMOTE_ADDR']));
         $ipexist = $reqip->rowCount();
       if($ipexist == 1){
          $info = $pdo->prepare('SELECT * FROM newsletter WHERE id = ?');
         $info->execute(array($deinscription));
         $info = $info->fetch();
         if($info['ip'] == $_SERVER['REMOTE_ADDR']){
            $sql = $pdo->prepare('DELETE FROM newsletter WHERE id = ?');
            $sql->execute(array($deinscription));
             echo '<script>location.href="https://www.mangasfan.fr";</script>';
             echo "<div class='alert alert-success' role='alert'>Vous vous êtes bien désinscrit de la newsletter !</div>";
         } else {
            $erreur = "<div class='alert alert-danger' role='alert'>Cette inscription ne vous appartient pas.</div>";
         }
      } else {
         $erreur = "<div class='alert alert-danger' role='alert'>Vous n'êtes pas inscrit à la newsletter.</div>";
      }
   } else {
      $erreur = "<div class='alert alert-danger' role='alert'>L'id de l'inscription n'est pas présent.</div>";
   }
} ?>