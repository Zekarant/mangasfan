	<?php
	session_start();
	require_once '../inc/base.php'; 
	if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
	{ 
	        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
	        $user->execute(array($_SESSION['auth']['id']));
	        $utilisateur = $user->fetch(); 
	}
	include('../theme_temporaire.php');
	?>
	<!doctype html>
	<html lang="fr">
	<head>
	  <meta charset="utf-8" />
	  <title>Mangas'Fan - Pannel de newsletters</title>
	  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
	  <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
	  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src='http://use.edgefonts.net/nosifer.js'></script>
	  <script src='http://use.edgefonts.net/emilys-candy.js'></script>
	  <script src='http://use.edgefonts.net/butcherman.js'></script>
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <link rel="icon" href="../images/favicon.png"/>
	  <link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>">
	  <script type="text/javascript" src="https://www.mangasfan.fr/tinymce/js/tinymce/tinymce.min.js"></script>
	        <script type="text/javascript" src="https://www.mangasfan.fr/tinymce/js/tinymce/tinymce.js"></script>
	        <script>
	          tinymce.init({
	          selector: 'textarea',
	          height: 250,
	          theme: 'modern',
	          language: 'fr_FR',
	          plugins: ['print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help'],
	          toolbar: 'insert | undo redo |  formatselect | bold italic underline backcolor forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
	          content_css: [
	            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
	            '//www.tinymce.com/css/codepen.min.css']
	        });
	        </script>
	</head>
	<body>
	  <div id="bloc_page">
	    <section>
	      <?php
	      $user = $pdo->prepare("SELECT * FROM users WHERE username = ?");
	      $user->execute(array($utilisateur['username']));
	      $user->fetch();
	      include('../inc/functions.php'); ?>
	      <?php
	       if($_SESSION['auth'] === false AND $utilisateur['grade'] <= 4){
	        echo"<div class='alert alert-danger' role='alert'>Vos droits ne vous permettent pas d'accéder à cette page</div>";
	        exit; }
	  		 include('../elements/nav_admin.php') ?> 
	        <center>
	          <div class="titre_commentaire_news"><img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" /> Bienvenue sur le pannel d'administration de <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fan</p></span> <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
	          </div>
	        </center>
	        <div class="marge_page">
	          <center>
	          	<div class="row">
	            <div class="col-sm-6 col-md-6">
	              <div class="thumbnail">
	                <div class="caption">
	                  <h3><span class="glyphicon glyphicon-book"></span> Nombre de membres inscrits à <span class="couleur_mangas">la</span> <span class="couleur_fans">newsletter</span></h3>
	                  <p><?php $user = $pdo->prepare('SELECT * FROM newsletter');
	                  $user->execute();
	                  echo "Il y a actuellement <b>" . $user->rowCount() . "</b> membre inscrits à la newsletter."; ?></p>
	                </div>
	              </div>
	            </div>
	            <div class="col-sm-6 col-md-6">
	              <div class="thumbnail">
	                <div class="caption">
	                  <h3><span class="glyphicon glyphicon-book"></span> Nombre de <span class="couleur_mangas">newsletters</span> <span class="couleur_fans"> envoyées</span></h3>
	                  <p><?php $user = $pdo->prepare('SELECT * FROM newsletters_historique');
	                  $user->execute();
	                  echo "Il y a actuellement <b>" . $user->rowCount() . "</b> newsletters envoyées."; ?></p>
	                </div>
	              </div>
	            </div>
	          </div>
	        </center>
	  		<?php
				if(isset($_POST['mailform'])){
					$enregistrement = $pdo->prepare("INSERT INTO newsletters_historique (titre, contenu, date_envoi) VALUES(?, ?, NOW())");
	    			$enregistrement->execute(array($_POST['titre_newsletter'], $_POST['news_newsletter']));
	    			$mail = $pdo->prepare('SELECT email FROM newsletter');
	    			$mail->execute();

	    			while ($envoie_mail = $mail->fetch()) {
	    				$header="MIME-Version: 1.0\r\n";
						$header.='From:"Mangas\'Fan"<contact@mangasfan.fr>'."\n";
						$header.='Content-Type:text/html; charset="uft-8"'."\n";
						$header.='Content-Transfer-Encoding: 8bit';

						$message='
				<html>
					<body>
						<div style="border: 2px solid black;">
							<div align="center" style="background-image:linear-gradient(#BAC1C8, #474747);">
	  							<img src="https://zupimages.net/up/17/24/4kp2.png" style="width: 100%;"/>
									<div style="font-family: Oswald; font-size: 23px; color: white;">
	              				Newsletter mensuelle - <span style="color: #ff5980">M</span>angas\'<span style="color: #00daf9">F</span>an
	      							</div>
							<hr/>
	  					</div>
	  					<div style="padding: 5px;">
									 ' . $_POST['news_newsletter'] . '
						</div>
						<div align="center">
							<div style="background-color: #333333; padding: 5px; border-top: 3px solid #DDDDDD; color: white; text-align: center;">Mangas\'Fan © 2017 - 2019. Développé par Zekarant, Nico et Lucryio. Tous droits réservés.
							</div>
						</div>
						</div>
					</body>
				</html>
						';

						mail($envoie_mail['email'], $_POST['titre_newsletter'], $message, $header);

	    			}
	    			echo "<div class='alert alert-success' role='alert'>La newsletter a bien été envoyée !</div>";
	    		}
			?>
	       <form method="POST" action="">
	       		Titre de la newsletter : <input type="text" class="form-control" name="titre_newsletter" placeholder="Entrez le titre de la news"><br/>
	       		Contenu de la newsletter : <textarea name="news_newsletter" id="contenu_newsletter" placeholder="Entrez le contenu de la newsletter"></textarea><br/>
	       		<input type="submit" class="btn btn-sm btn-info" value="Recevoir un mail !" name="mailform"/>
			</form>
	    </div>
	  </section>
	  <?php include('../elements/footer.php') ?>
	</div>
	</body>
	</html>
