<?php
    if(session_status() == PHP_SESSION_NONE){
    require_once 'inc/base.php';
    $user = $pdo->query("SELECT * FROM users WHERE username = '".$_SESSION['auth']->username."'")->fetch();
}
  if(isset($_POST['mailform'])) {
     if(!empty($_POST['nom']) AND !empty($_POST['mail']) AND !empty($_POST['message'])) {
        $header="MIME-Version: 1.0\r\n";
        $header.="From: Mangas'Fan <contact@mangasfan.fr>\n";
        $header.='Content-Type:text/html; charset="utf-8"'."\n";
        $header.='Content-Transfer-Encoding: 8bit';
        $message = 'Content-Type:text/html; charset="utf-8"'."\n";
        $message .= 'Content-Transfer-Encoding: 8bit';
        $message='
       <html>
          <body>
               <i>Pseudo : </i>'.$_POST['nom'].'<br /><br />
               <i>Mail : </i>'.$_POST['mail'].'<br /><br />
               <i>Demande : </i><br/>
               <br />
               « '.nl2br($_POST['message']).' »
               <br/><br/>
               <i>Ce message a été envoyé depuis le formulaire de contact du site de Mangas\'Fan !</i>
          </body>
        </html>
      ';
      mail("contact@mangasfan.fr", "Demande de membre", $message, $header);
     $msg='<div class="alert alert-success" role="alert">Votre message a bien été envoyé à l\'administrateur du site, une réponse vous sera fournie sous 24h !</div>';
   } else {
     $msg='<div class="alert alert-danger" role="alert">Des champs sont vides ! Afin que la demande soit traitée, nous devons posséder toutes les informations demandées !</div>';
   }
}
?>
<div id="titre_news">
        <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" /> N<span class="couleur_mangas">ous</span><span class="couleur_fans"> Contacter</span> 
        <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
</div>
<div id="formulaire_contact">
  <form method="POST" class="contact-form row">
    <div class="col-sm-6">
      <input id="name" name="nom" class="input-text js-input" type="text" required>
      <label class="label" for="name">Votre pseudo</label>
    </div>
    <div class="col-sm-6" id="mail_contact">
      <input id="email" name="mail" class="input-text js-input" type="email" required>
      <label class="label" for="email">Votre Email</label>
    </div>
    <div class="col-sm-12">
    <br/>
      <input id="message" name="message" class="input-text js-input" type="text" required>
      <label class="label" for="message">Message</label>
    </div>
    <span class="envoi_contact">
      <button class="btn btn btn-info" type="submit" name="mailform">
        Envoyer le mail
      </button>
    </span>
  </form>
  <?php 
      if(isset($msg)) 
      {
         echo $msg;
      }
      ?>
</div>