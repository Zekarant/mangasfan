<div class="footer">
  <div class="container">
    <div class="row">   
      <div class="col-lg-5">    
        <h3>A propos de nous</h3>                       
          <p>Mangas'Fan est un site communautaire parlant des différents mangas et animes qui existent. Vous trouverez les actualités récentes ainsi que des pages concernant chaque mangas et animes.</p>						               
      </div>     
      <div class="col-lg-3">    
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
      </div>               
      <div class="col-lg-4">                
        <h3> Nos partenaires </h3>                       
          <a href="https://www.pokelove.fr/" target="_blank">
            <img src="https://www.mangasfan.fr/images/pokelove.png" alt="" width="88" height="31" />
          </a>
          <a href="http://pokemon-power.frbb.net" target="_blank" title="Pokémon-Power">
            <img src="https://www.pixenli.com/image/inMZTqw2" alt="Pokémon-Power" width="88" height="31">
          </a>
          <a href="https://pokemon-sunshine.com" target="_blank" title="Pokémon-Sunshine">
            <img src="https://pokemon-sunshine.com/design/bouton_sunshine.png" alt="Pokémon-Power" width="88" height="31">
          </a>
          <a href="http://www.nexgate.ch">
            <img style="border:0;" src="https://www.nexgate.ch/images/button8831.png" alt="Hébergement gratuit !" title="Hébergement gratuit - nexgate.ch" />
          </a>          
      </div>           
    </div>        
  </div>
</div>    
<div class="footer-bottom">       
  <div class="container">           
    <p class="pull-left"> Version 5.1.0 de Mangas'Fan © 2017 - 2019. Développé par Zekarant, Nico et Lucryio. Tous droits réservés. Toute atteinte au droit d'auteur n'est pas désirée.<br/> Propulsé par <a href="https://www.nexgate.ch/">https://www.nexgate.ch/.</a></p>        
  </div>    
</div>