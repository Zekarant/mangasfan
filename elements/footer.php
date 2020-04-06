<div class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h3>Newsletter</h3>
	        <?php 
	        include('traitement_newsletter.php');
	        if($newsletterexist == 0) { ?>
	          <form method="POST">
	            <label>Tenez-vous informés des dernières nouveautés</label><br />
	              <input type="email" class="form-control" name="newsletter" placeholder="Entrez votre adresse mail" />
	              <input type="submit" class="btn btn-sm btn-info" name="newsletterform" value="Envoyer"/>
	          </form>
	          <?php } else { ?>
	            <label>Adresse e-mail</label><br />
	              <input type="email" class="form-control" name="newsletter" placeholder="Vous êtes déjà inscrit à la newsletter." disabled/>
	              <?php while($news = $reqnewsletter->fetch()) { ?>
	                <a href="?deinscription=<?= $news['id'] ?>" style="color: white;"><u>Me déinscrire de la Newsletter</u></a>
	              <?php } ?>
	              <?php }
	              if (!empty($_POST['newsletter'])) {
	                 echo $erreur;
	               } 
	                 ?>   
				<div class="row">
					<div class="col-md-12">
						<h3>Liens utiles</h3>
						<nav class="lien_site">
							<ul>
								<li><a href="https://www.mangasfan.fr/">Index</a> - </li>
								<li><a href="https://www.mangasfan.fr/membres/liste_membres.php">Liste des membres</a> - </li>
								<li><a href="https://www.mangasfan.fr/changelog.php">Mises à jour</a> - </li>
								<li><a href="https://www.mangasfan.fr/partenaires.php">Partenaires</a> - </li>
								<li><a href="https://www.mangasfan.fr/foire-aux-questions.php">F.A.Q</a> - </li>
								<li><a href="https://www.mangasfan.fr/recrutements">Recrutements</a> - </li>
								<li><a href="https://www.mangasfan.fr/mentions_legales.php">Mentions Légales</a></li>
							</ul>
						</nav>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<h3>Nos partenaires</h3>
				<a href="https://www.pokelove.fr/" target="_blank">
					<img src="https://www.mangasfan.fr/images/pokelove.png" alt="Logo de Pokélove" width="88" height="31" />
				</a>
				<a href="http://www.nexgate.ch" target="_blank">
					<img style="border:0;" src="https://www.nexgate.ch/images/button8831.png" alt="Hébergement gratuit !" title="Hébergement gratuit - nexgate.ch" />
				</a>
				<a href="https://www.bclover.net/" target="_blank">
					<img style="border:0;" src="https://www.mangasfan.fr/images/bryx.png" alt="Logo de Black Clover" width="88" height="31" />
				</a>
				<a href="http://pokemon-boutique.fr/?afmc=1r&utm_campaign=1r&utm_source=leaddyno&utm_medium=affiliate" target="_blank">
					<img style="border:0;" src="https://www.mangasfan.fr/images/mf-petit.png" alt="Logo pour Pokémon Boutique" width="88" height="31" />
				</a>
				<div class="row">
					<div class="col-md-12">
						<h3>Nos réseaux</h3>
						<a href="https://www.facebook.com/MangasFanOff/" target="_blank">
							<img src="https://www.mangasfan.fr/images/fb.png" alt="Facebook - Mangas'Fan" class="image_reseaux" />
						</a>
						<a href="https://twitter.com/MangasFanOff" target="_blank">
							<img src="https://www.mangasfan.fr/images/tw.png" alt="Twitter - Mangas'Fan"  class="image_reseaux" />
						</a>
						<a href="https://discord.gg/KK43VKd" target="_blank">
							<img src="https://www.mangasfan.fr/images/discord.png" alt="Discord - Mangas'Fan"  class="image_reseaux" />
						</a>
						<a href="https://www.instagram.com/mangasfanoff/" target="_blank">
							<img src="https://www.mangasfan.fr/images/insta.png" alt="Instagram - Mangas'Fan"  class="image_reseaux" />
						</a>
					</div>
				</div>  
			</div>
		</div>
	</div>
</div>
<div class="footer-bottom">       
	<div class="container">           
		<p class="pull-left">Version 6.6.0 de Mangas'Fan © 2017 - 2020. Développé par Zekarant et Nico. Design by Asami. Header by よねやままい. Tous droits réservés. Toute atteinte au droit d'auteur n'est pas désirée.<br/> Propulsé par <a href="https://www.nexgate.ch/">https://www.nexgate.ch/.</a></p>        
	</div>    
</div>