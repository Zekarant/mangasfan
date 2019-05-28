<?php 
session_start();
require_once 'inc/base.php'; 
require('inc/functions.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
include('theme_temporaire.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mangas'Fan - Quartier du staff</title>
	<link rel="icon" href="images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  	<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
  	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
  	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
  	<link rel="stylesheet" href="<?php echo $lienCss; ?>">
  	<link rel="stylesheet" type="text/css" href="staff.css">
</head>
<body>
	<div id="bloc_page">
		<header>
      		<div id="banniere_image">
        		<div id="titre_site">
            		<span class="couleur_mangas"><?php echo $titre_1; ?></span><?php echo $titre_2; ?><span class="couleur_fans">F</span>AN
         		 </div>
          		<div class="slogan_site"><?php echo $slogan; ?></div>
        		<?php include("elements/navigation.php") ?>
		        <h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
		        <h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
		        <div class="bouton_fofo">
		        	<a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a>
		        </div>
        		<?php include('elements/header.php');
        		include('inc/bbcode.php'); ?>
      		</div>
    	</header>
		<section>
			<?php
			 if (!isset($_SESSION['auth'])) { ?>
			 <div class='alert alert-danger' role='alert'>Vous n'êtes pas connecté !</div>
			 <script>location.href="../index.php";</script>
			 <?php 
			 exit;
			 }
			 elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] < 3) { ?>
			 	<div class='alert alert-danger' role='alert'>Vous n'avez pas les conditions requises pour accéder à cette page.</div>
			 	<script>location.href="../index.php";</script>
			 <?php
			 exit;
			 }
			 else
			 { 
			 ?>
			 	<h2 class="titre_staff">
			 		Quartier Général <span class="couleur_mangas">d</span>u <span class="couleur_fans">S</span>taff
			 	</h2>
			 	<hr>
			 	<div class="container">
  					<div class="row justify-content-around">
    						<div class="col-md-6">
	      						<div class="card">
								  	<div class="card-header red">
								   		Panneau d'administation de Mangas'Fan
								  	</div>
								  	<div class="card-body">
								    	<p class="card-text">
								    		<?php 
							        			$users = $pdo->prepare('SELECT * FROM users');
		                  						$users->execute();
                  							?>
                  							Nombre de membres inscrits sur Mangas'Fan : <strong><?php echo $users->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$staff = $pdo->prepare('SELECT * FROM users WHERE grade >= 3');
		                  						$staff->execute();
                  							?>
                  							Nombre de membres étant du staff : <strong><?php echo $staff->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$newsletters = $pdo->prepare('SELECT * FROM newsletters_historique');
		                  						$newsletters->execute();
                  							?>
                  							Nombre de newsletters envoyées : <strong><?php echo $newsletters->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$newsletters_membres = $pdo->prepare('SELECT * FROM newsletter');
		                  						$newsletters_membres->execute();
                  							?>
                  							Nombre de membres inscrits aux newsletters : <strong><?php echo $newsletters_membres->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$admin = $pdo->prepare('SELECT * FROM users WHERE grade >= 10');
		                  						$admin->execute();
                  							?>
                  							Nombre de membres gérant l'administration : <strong><?php echo $admin->rowCount(); ?></strong>
								    	</p>
								    	<?php if($utilisateur['grade'] >= 10){ ?>
								    		<a href="administration/" class="btn btn-primary">Accéder à l'administation</a>
								    	<?php } else { ?>
								    	<p>
								    		<strong>
								    			<u>
								    				Vos droits ne sont pas suffisant pour accéder à cette page.
								    			</u>
								    		</strong>
								    	</p>
								    <?php } ?>
								  	</div>
								</div>
	    					</div>
	    					<div class="col-md-6">
	      						<div class="card">
								  	<div class="card-header green">
								   		Panneau de modération de Mangas'Fan
								  	</div>
								  	<div class="card-body">
								    	<p class="card-text">
								    		<?php 
							        			$banni = $pdo->prepare('SELECT * FROM users WHERE grade = 1');
		                  						$banni->execute();
                  							?>
                  							Nombre de membres bannis : <strong><?php echo $banni->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$invalide = $pdo->prepare('SELECT * FROM users WHERE confirmation_token IS NOT NULL');
		                  						$invalide->execute();
                  							?>
                  							Nombre de membres n'ayant pas validé leur compte : <strong><?php echo $invalide->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$galeries = $pdo->prepare('SELECT * FROM galerie');
		                  						$galeries->execute();
                  							?>
                  							Nombre d'images dans les galeries : <strong><?php echo $galeries->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$commentaires_news = $pdo->prepare('SELECT * FROM commentaires');
		                  						$commentaires_news->execute();
                  							?>
                  							Nombre de commentaires sur les news : <strong><?php echo $commentaires_news->rowCount(); ?></strong>
                  							<br/>
								    		<?php 
							        			$modo = $pdo->prepare('SELECT * FROM users WHERE grade >= 10');
		                  						$modo->execute();
                  							?>
                  							Nombre de membres gérant la modération : <strong><?php echo $modo->rowCount(); ?></strong>
								    	</p>
								    	<?php if($utilisateur['grade'] >= 9){ ?>
								    		<a href="moderation/" class="btn btn-primary">Accéder à la modération</a>
								    	<?php } else { ?>
								    	<p>
								    		<strong>
								    			<u>
								    				Vos droits ne sont pas suffisant pour accéder à cette page.
								    			</u>
								    		</strong>
								    	</p>
								    <?php } ?>
								  	</div>
								</div>
	    					</div>
	    					<div class="col-md-6">
	      						<div class="card">
								  	<div class="card-header blue">
								   		Panneau de rédaction de Mangas'Fan
								  	</div>
								  	<div class="card-body">
								    	<p class="card-text">
								    		<?php 
							        			$news = $pdo->prepare('SELECT * FROM billets');
		                  						$news->execute();
                  							?>
                  							Nombre de news sur le site : <strong><?php echo $news->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$jeux = $pdo->prepare('SELECT * FROM billets_jeux');
		                  						$jeux->execute();
                  							?>
                  							Nombre de jeux sur le site : <strong><?php echo $jeux->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$mangas = $pdo->prepare('SELECT * FROM billets_mangas');
		                  						$mangas->execute();
                  							?>
                  							Nombre de mangas sur le site : <strong><?php echo $mangas->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$articles_jeux = $pdo->prepare('SELECT * FROM billets_jeux_pages');
		                  						$articles_jeux->execute();
                  							?>
                  							Nombre de d'articles dans la catégorie jeux : <strong><?php echo $articles_jeux->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$articles_mangas = $pdo->prepare('SELECT * FROM billets_mangas_pages');
		                  						$articles_mangas->execute();
                  							?>
                  							Nombre de d'articles dans la catégorie mangas : <strong><?php echo $articles_mangas->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$total = $articles_jeux->rowCount() + $articles_mangas->rowCount();
                  							?>
                  							Nombre de d'articles total : <strong><?php echo $total; ?></strong>
                  							<br/>
                  							<?php 
							        			$membres_redaction = $pdo->prepare('SELECT * FROM users WHERE grade >= 5 AND grade <= 8');
		                  						$membres_redaction->execute();
                  							?>
                  							Nombre de membres gérant la rédaction : <strong><?php echo $membres_redaction->rowCount(); ?></strong>
                  							<br/>
								    	</p>
								    	<?php if($utilisateur['grade'] >= 5){ ?>
								    		<a href="redaction/" class="btn btn-primary">Accéder à la rédaction</a>
								    	<?php } else { ?>
								    	<p>
								    		<strong>
								    			<u>
								    				Vos droits ne sont pas suffisant pour accéder à cette page.
								    			</u>
								    		</strong>
								    	</p>
								    <?php } ?>
								  	</div>
								</div>
	    					</div>
	    					<div class="col-md-6">
	      						<div class="card">
								  	<div class="card-header orange">
								   		Panneau d'animation de Mangas'Fan
								  	</div>
								  	<div class="card-body">
								    	<p class="card-text">
								    		<?php 
							        			$animation = $pdo->prepare('SELECT * FROM anim_seul');
		                  						$animation->execute();
                  							?>
                  							Nombre de participations à l'animation « Noël » : <strong><?php echo $animation->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$comptes_animation = $pdo->prepare('SELECT * FROM animation WHERE title = "animation"');
		                  						$comptes_animation->execute();
                  							?>
                  							Nombre de billets concernant les animations postées sur les pages comptes : <strong><?php echo $comptes_animation->rowCount(); ?></strong>
                  							<br/>
                  							<?php 
							        			$membres_animation = $pdo->prepare('SELECT * FROM users WHERE grade = 3');
		                  						$membres_animation->execute();
                  							?>
                  							Nombre de membres gérant l'animation : <strong><?php echo $membres_animation->rowCount(); ?></strong>
                  							<br/>
								    	</p>
								    	<?php if($utilisateur['grade'] == 3 OR $utilisateur['grade'] >= 8){ ?>
								    		<a href="animation/" class="btn btn-primary">Accéder à l'animation</a>
								    	<?php } else { ?>
								    	<p>
								    		<strong>
								    			<u>
								    				Vos droits ne sont pas suffisant pour accéder à cette page.
								    			</u>
								    		</strong>
								    	</p>
								    <?php } ?>
								  	</div>
								</div>
	    					</div>
  					</div>
    			</div>
			 <?php 
			 }
			 ?>
		</section>
		<?php 
			include('elements/footer.php');
		?>
	</div>
</body>
</html>