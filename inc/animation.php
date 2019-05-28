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
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Mangas'Fan - Animation spéciale Noël</title>
	<link rel="icon" href="../images/favicon.png" />
	<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" />
  	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" />
  	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
  	<link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
  	<link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
  	<link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>" />
  	<link rel="stylesheet" type="text/css" href="../overlay.css" />
</head>
<body>
<div id="bloc_page">
		<header>
			<div id="banniere_image">
				<div id="titre_site">
					<span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN
				</div>
				<div class="slogan_site"><?php echo $slogan; ?></div>
				<?php include("../elements/navigation.php") ?>
				<h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
				<h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
				<div class="bouton_fofo">
					<a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter
					</a>
				</div>
				<?php include('../inc/bbcode.php'); 
        include('../elements/header.php'); ?>
			</div>
		</header>
    <?php if(!isset($_SESSION['auth']) AND $_SESSION['auth'] == false){
    echo "<div class='alert alert-danger' role='alert'>Vous devez être connecté pour participer !</div>";
  }?>
		<section class="marge_page">
			<div class='alert alert-info' role='alert'>Voici le formulaire afin de participer au concours de Noël de Mangas'Fan ! Gagnez jusqu'à <b>1000 Mangas'Points !</b> <br/><b>Attention : Une fois vos réponses envoyées, il ne sera pas possibles d'en renvoyer de nouvelles, ayez donc une certitude au moment de l'envoi des réponses.</b><br/><br/>
        <b>Note :</b> Cette animation sert aussi de tests ! De meilleures animations seront disponibles dans le futur. </div>

      <?php 
      $ouverture = isset( $_POST['ouverture'] ) ? $_POST['ouverture'] : '';
      $developpeur = isset( $_POST['developpeur'] ) ? $_POST['developpeur'] : '';
      $groupes = isset( $_POST['groupes'] ) ? $_POST['groupes'] : '';
      $theme = isset( $_POST['theme'] ) ? $_POST['theme'] : '';
      $partenaires = isset( $_POST['partenaires'] ) ? $_POST['partenaires'] : '';
      $news = isset( $_POST['news'] ) ? $_POST['news'] : '';
      $modules = isset( $_POST['modules'] ) ? $_POST['modules'] : '';
      $cgu = isset( $_POST['cgu'] ) ? $_POST['cgu'] : '';
      $version = isset( $_POST['version'] ) ? $_POST['version'] : '';
      $design = isset( $_POST['design'] ) ? $_POST['design'] : '';
      if (!empty($_POST['envoi'])){
       $enregistrer_animation = $pdo->prepare('INSERT INTO anim_seul (question1, question2, question3, question4, question5, question6, question7, question8, question9, question10, membre) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
       $enregistrer_animation->execute(array($ouverture, $developpeur, $groupes, $theme, $partenaires, $news, $modules, $version, $cgu, $design, $_SESSION['auth']['username']));
        echo "<div class='alert alert-success' role='alert'>Vos réponses ont bien été envoyées !</div>";
      }
     ?>
     <?php 
    $verif_deja_present = $pdo->prepare('SELECT * FROM anim_seul WHERE membre = ?'); 
    $verif_deja_present->execute(array($_SESSION['auth']['username'])); 
    $verif = $verif_deja_present->fetch();
    if ($verif['membre'] === $_SESSION['auth']['username']) 
    { ?>
       <div class='alert alert-warning' role='alert'>Vous avez déjà envoyé vos réponses. Merci d'attendre le <b> 24 Décembre</b> pour avoir les résultats !</div>
    <?php }
    ?>
<div class="container bootstrap snippets" style="margin: auto; width: 50%; padding: 10px;">
  <div class="row">
      <div class="panel panel-default">
         <div class="panel-heading">
                <h4 class="panel-title"><center>Animation Mangas'Fan - 7 Décembre / 20 Décembre</center></h4>
              </div>
	<form action="" method="post" style="padding: 10px;">
    <label>1. Quand a été déclarée l'ouverture officielle de Mangas'Fan :</label><br/> 
      <input type="radio" name="ouverture" value="20 Décembre 2017"required="" > 20 Décembre 2017<br/>
      <input type="radio" name="ouverture" value="24 Décembre 2017" required=""> 24 Décembre 2017<br/>
      <input type="radio" name="ouverture" value="31 Décembre 2017" required=""> 31 Décembre 2017<br/>
      <br/>
      <label>2. Qui est le premier développeur à avoir aidé Zekarant ?</label><br/> 
      <input type="radio" name="developpeur" value="Nico" required=""> Nico<br/>
      <input type="radio" name="developpeur" value="Lucryio" required=""> Lucryio<br/>
      <input type="radio" name="developpeur" value="Personne" required=""> Personne<br/>
      <br/>
      <label>3. Combien de rôles Mangas'Fan possède-t-il ?</label><br/> 
      <input type="radio" name="groupes" value="5" required=""> 5<br/>
      <input type="radio" name="groupes" value="7" required=""> 7<br/>
      <input type="radio" name="groupes" value="10" required=""> 10
      <br/><br/>
      <label>4. Le thème « Noël » actuellement sur le site est censé disparaître quand ?</label><br/>
      <input type="radio" name="theme" value="25 Décembre" required=""> 25 Décembre<br/>
      <input type="radio" name="theme" value="31 Décembre" required=""> 31 Décembre<br/>
      <input type="radio" name="theme" value="1er Janvier" required=""> 1er Janvier
      <br/><br/>
      <label>5. Avec quel partenaire aimerions-nous faire une animation commune ?</label><br/>
      <input type="radio" name="partenaires" value="PokeLove" required=""> PokéLove<br/>
      <input type="radio" name="partenaires" value="Pokemon Power" required=""> Pokémon-Power<br/>
      <input type="radio" name="partenaires" value="Nexgate" required=""> Nexgate
      <br/><br/>
      <label>6. Quelle est la particularité des news de Mangas'Fan ?</label><br/>
      <input type="radio" name="news" value="Logo" required=""> Le logo des news est assorti à celui du site<br/>
      <input type="radio" name="news" value="Premières News" required=""> Seules les 3 premières news apparaissent, les autres sont effacées.<br/>
      <input type="radio" name="news" value="Code du site" required=""> Les Newseurs doivent toucher au code du site pour poster leurs news.
      <br/><br/>
      <label>7. Quel module était vraiment attendu sur le site dernièrement ?</label><br/>
      <input type="radio" name="modules" value="Module de rédaction" required=""> Module de rédaction<br/>
      <input type="radio" name="modules" value="Module d'animation" required=""> Module d'animation<br/>
      <input type="radio" name="modules" value="Modules de news" required=""> Module de news
      <br/><br/>
      <label>8. Combien de versions <b>majeures</b> a eu Mangas'Fan ?</label><br/>
      <input type="radio" name="version" value="3" required=""> 3<br/>
      <input type="radio" name="version" value="4" required=""> 4<br/>
      <input type="radio" name="version" value="5" required=""> 5
       <br/><br/>
      <label>9. Selon ce qui a écrit dans les CGU, quel système est écrit mais pas disponible sur le site ?</label><br/>
      <input type="radio" name="cgu" value="Système de blogs"> Système de blogs<br/>
      <input type="radio" name="cgu" value="Système d'espace membres"> Système d'espace membres<br/>
      <input type="radio" name="cgu" value="Forum"> Forum
       <br/><br/>
      <label>10. Combien de désign Mangas'Fan a eu ces derniers mois ?</label><br/>
      <input type="radio" name="design" value="1" required=""> 1<br/>
      <input type="radio" name="design" value="2" required=""> 2<br/>
      <input type="radio" name="design" value="3" required=""> 3
      <br/>
      <?php 
    if ($verif['membre'] === $_SESSION['auth']['username']) 
    { }
      else{?>
      <input type="submit" class="btn btn-primary" name="envoi">
    <?php }?>
</form>
		</section>
		<?php include('../elements/footer.php'); ?>
	</div>
</body>
</html>