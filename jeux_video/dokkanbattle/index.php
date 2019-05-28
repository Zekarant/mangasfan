<?php
  session_start();
  require_once '../../inc/base.php';
  $user = $pdo->query("SELECT * FROM users WHERE username = '".$_SESSION['auth']->username."'")->fetch();
  include('../../inc/functions.php');
  include('../../inc/bbcode.php');?>
<html>
  <head>
    <title>Mangas'Fan - Accueil de Dragon Ball : Dokkan Battle</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href="../../images/favicon.png"/>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../../overlay.css" />
  </head>
  <body>
    <header>
    	<div id="banniere_image">
    	<div id="titre_site"><span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN</div>
    	<div class="slogan_site">Votre référence Mangas</div>
            <?php include("../../elements/navigation.php") ?>
    	<h2 id="actu_moment">NOTRE FORUM</h2>
    	<h5 id="slogan_actu">Pour parler de notre passion commune</h5>
    	<div class="bouton_fofo"><a href="https://mangasfan.000webhostapp.com/forum/index.php">Forum</a></div>
       <?php include('../../elements/header.php'); ?>
     </div>
   </header>
   <section>
   <div id="conteneur">
	   <center>
      <div class="alert alert-info" role="alert">
        <b>Avis : </b>Cette page est encore en construction ! On ne peut pas donner de dates de fin ! Elle est encore en développement !
      </div>
    </center>
      <?php include("../../elements/messages.php"); ?>
    <center>
      <img src="https://www.pixenli.com/images/1497/1497282402032646900.jpg" style='border: 2px solid black; width: 600px;'/>
    </center>
  </div>
  <div class="list-group">
    <a class="list-group-item active" style="text-align: center;">
     Accueil de la section
    </a>
    <a href="#" class="list-group-item"><s>Informations sur le jeu</s></a>
    <a href="#" class="list-group-item"><s>Débuter sur le jeu</s></a>
    <a href="#" class="list-group-item"><s>Télécharger le jeu sur le Play Store</s></a>
      <a href="#" class="list-group-item"><s>Télécharger le jeu sur l'Apple Store</s></a>
      <a class="list-group-item active" style="text-align: center;">
       Dragon Ball Z : Dokkan Battle
      </a>
      <a href="#" class="list-group-item"><s>L'aventure</s></a>
      <a href="#" class="list-group-item"><s>Les types</s></a>
      <a href="#" class="list-group-item"><s>Les quêtes</s></a>
      <a href="#" class="list-group-item"><s>Les événements</s></a>
      <a href="#" class="list-group-item"><s>Les Tenkaichi Budokai</s></a>
      <a href="#" class="list-group-item"><s>L'arbre de compétence</s></a>
      <a href="#" class="list-group-item"><s>Les missions</s></a>
      <a href="#" class="list-group-item"><s>Les rangs</s></a>
      <a class="list-group-item active" style="text-align: center;">
      Les objets
      </a>
      <a href="#" class="list-group-item"><s>Les objets d'entraînements</s></a>
      <a href="#" class="list-group-item"><s>Les objets de soutiens</s></a>
      <a href="#" class="list-group-item"><s>Les médailles d'éveils </s></a>
      <a href="#" class="list-group-item"><s>Les tickets </s></a>
      <a class="list-group-item active" style="text-align: center;">
      Les extras
      </a>
      <a href="#" class="list-group-item"><s>Le BabaShop</s></a>
      <a href="#" class="list-group-item"><s>Taux de drop des portails</s></a>
      <a href="#" class="list-group-item"><s>Mises à jour</s></a>
  </div>
<div id="banniere_image_deux">
            <div id="twitter"><?php include('../../elements/twitter.php') ?></div>
            <div id="facebook"><?php include('elements/facebook.php') ?></div>
            <div id="discord"><?php include('../../elements/discord.php') ?></div>
	        </div>
			</section>
			<?php include('../../elements/footer.php'); ?>
		
	</body>
</html>


