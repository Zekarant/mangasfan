<?php 
// PHP_SESSION_NONE : session activé mais non existante
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
}
// LES CONNEXIONS
//Partie concernant les membres connectés actuellement | Rappel : On met régulièrement à jour le temps 'time_co' depuis header.php si le membre est encore présent sur l'une des pages
$temps_session = 300; //5 minutes
$temps_actuel = date("U");

$session_connexion = $temps_actuel - $temps_session;

// Partie concernant les membres étant déco au bout de 5 minutes
$update_rang_co = $pdo->prepare("UPDATE qeel SET connexion = 1 WHERE time_co < ?");
$update_rang_co->execute(array($session_connexion));

// Partie concernant les membres étant connecté
$show_user_nbr = $pdo->prepare("SELECT * FROM qeel WHERE time_co >= ? AND connexion = 0 ORDER BY membre");
$show_user_nbr->execute(array($session_connexion));
$user_nbr = $show_user_nbr->rowCount();

// Partie concernant les invités étant déco au bout de 5 min
$del_invite = $pdo->prepare("DELETE FROM qeel WHERE time_co < ? AND membre is NULL AND membre_id is NULL");
$del_invite->execute(array($session_connexion));

// Partie concernant les invités étant connecté
$invite_pst = $pdo->prepare("SELECT * FROM qeel WHERE membre is NULL");
$invite_pst->execute();
$nbr_invite_present = $invite_pst->rowCount();

// Partie concernant les membres connectés dans l'intervalle des 24 dernières heures
$temps_session_vqh = 86400; //24 heures
$session_vqheures = $temps_actuel - $temps_session_vqh;

// Partie concernant les membres ne s'étant pas connecté depuis 24h            
$del_ip = $pdo->prepare("DELETE FROM qeel WHERE time_co < ? ORDER BY membre");
$del_ip->execute(array($session_vqheures));

// Partie concernant les membres étant connecté dans l'intervalle des 24 dernières heures
$membre_vqh = $pdo->prepare("SELECT * FROM qeel WHERE time_co >= ? AND membre is NOT NULL ORDER BY membre");
$membre_vqh->execute(array($session_vqheures));
$nombre_cos_vqh = $membre_vqh->rowCount();


// LES ANNIVERSAIRES
// anniversaire du jour
$date_du_jour = date('Y/m/d');

$jour_today = preg_replace("#[0-9]{4}\/[0-9]{2}\/([0-9]{2})#",'$1',$date_du_jour);
$mois_today = preg_replace("#[0-9]{4}\/([0-9]{2})\/[0-9]{2}#",'$1',$date_du_jour);
$annee_today = preg_replace("#([0-9]{4})\/[0-9]{2}\/[0-9]{2}#",'$1',$date_du_jour);

$anniv_today = $pdo->prepare("SELECT * FROM users WHERE DAY(date_anniv) = ? AND MONTH(date_anniv) = ? ORDER BY username");
$anniv_today->execute(array($jour_today, $mois_today));
$anniversaire_today = $anniv_today->rowCount();


// anniversaire prévu
$anniversaire_proche = $pdo->prepare('SELECT * FROM users WHERE date_anniv is NOT NULL ORDER BY username');
$anniversaire_proche->execute();
$anniversaire_proche_verif = $anniversaire_proche->rowCount();
?>


<div id="titre_news" class="titre_qeel">
	<img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
	 Q<span class="couleur_mangas">ui</span> e<span class="couleur_mangas">st</span><span class="couleur_fans"> en ligne ?</span> 
	 <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
	</div> 
<div class="qeel footer">       
	<div class="container">            
		<div class="row">                
			<div class="col-lg-5">                    
				<h3>Les Saiyans connectés !</h3>                        
				<p>Il y a actuellement <?php if($nbr_invite_present > 0){ echo '<b>'.$nbr_invite_present.'</b>'; ?> invité<?php if($nbr_invite_present > 1){ echo 's';} ?> et <?php } ?><b><?php echo $user_nbr ?></b> membre<?php if($user_nbr > 1){ echo 's'; }?> connecté<?php if($user_nbr > 1){ echo 's'; } else if($user_nbr == 0) { echo '.';}?>

				<?php 
				$i = 0;
				while($membre_all = $show_user_nbr->fetch()){
					if ($i == 0){
						echo ':<br/>';
					}
					$i++;
				?>
					<b><a href="../profil/voirprofil.php?m=<?php echo $membre_all['membre_id'];?>&action=consulter">
				<?php 
					$rang_user = $pdo->prepare("SELECT grade FROM users WHERE id = ?");
					$rang_user->execute(array($membre_all['membre_id']));
					$utilisateur = $rang_user->fetch();
					echo rang_etat($utilisateur['grade'], $membre_all['membre']);?></a><?php if ($i<$user_nbr){echo ',';} else if($i==$user_nbr){echo '.';} ?></b>
				<?php } ?>
				</p>
				<br />
				<p>Membre<?php if($nombre_cos_vqh > 1){ echo 's'; }?> connecté<?php if($nombre_cos_vqh > 1){ echo 's'; } ?> durant les 24 dernières heures :
				<?php 
				$i = 0;
				while($membre_all = $membre_vqh->fetch()){
					if ($i == 0){
						echo '';
					}
					$i++;
				?>
					<b><a href="../profil/voirprofil.php?m=<?php echo $membre_all['membre_id'];?>&action=consulter">
				<?php 
					$rang_user = $pdo->prepare("SELECT grade FROM users WHERE id = ?");
					$rang_user->execute(array($membre_all['membre_id']));
					$utilisateur = $rang_user->fetch();
					echo rang_etat($utilisateur['grade'], $membre_all['membre']);?></a><?php if ($i<$nombre_cos_vqh){echo ',';} else {echo '.';} ?></b>
				<?php } ?>	
				</p><br/>	
				 <?php $req = $pdo->prepare('SELECT * FROM users ORDER BY id DESC LIMIT 0,1');
				 $req->execute();
					while ($donnees = $req->fetch())
                        { ?><p><?php
                        	echo "Le dernier membre inscrit sur le site est : "?>
							<a href="../profil/voirprofil.php?m=<?php echo $donnees['id'];?>&action=consulter">
                        		<?php
                        			echo "<b>" . rang_etat($donnees['grade'],$donnees['username']) . "</b>.";
                        }
                        		?>  
                        	</a> 
                        	</p>     
			</div>                
			<div class="col-lg-5 offset-lg-2">                 
				<h3> Les anniversaires </h3>                           
				 <p>
				 	<?php if ($anniversaire_today == 0){ ?>Aucun anniversaire à l'horizon aujourd'hui.<?php }else{?> Souhaitons un joyeux anniversaire à : <?php 

				 		$i = 0;
						while($aniv_all = $anniv_today->fetch()){
							$i++;
						?>
						<b><a href="../profil/voirprofil.php?m=<?php 
						echo $aniv_all['id'];?>&action=consulter">

						<?php 
							$rang_user = $pdo->prepare("SELECT grade FROM users WHERE id = ?");
							$rang_user->execute(array($aniv_all['id']));
							$user = $rang_user->fetch();
							echo rang_etat($user['grade'], $aniv_all['username']);?></a></b><i>
								(<?php $annee_aniv = preg_replace("#([0-9]{4})\/[0-9]{2}\/[0-9]{2}#",'$1', $aniv_all['date_anniv']);
								echo ($annee_today - $annee_aniv); ?>)</i><?php if ($i<$anniversaire_today){echo ',';} ?>
						<?php } ?>

				 	 ! PLUS ULTRAAAA !<?php } ?></p>
				 <br />
				 <p>Anniversaire prévu dans les 7 prochains jours : 

				 <?php 
				 if ($anniversaire_proche_verif != 0){
				 	$y = 0;
					 while($anniv_proche_all = $anniversaire_proche->fetch()){
					 	$ok = true;
					 	$date1 = strtotime($date_du_jour);

					 	$date_anniversaire_membre = $anniv_proche_all['date_anniv'];
					 	$jour_anniv_membre = preg_replace("#[0-9]{4}-[0-9]{2}-([0-9]{2})#",'$1',$date_anniversaire_membre);
					 	$mois_anniv_membre = preg_replace("#[0-9]{4}-([0-9]{2})-[0-9]{2}#",'$1',$date_anniversaire_membre);

					 	if ($mois_anniv_membre > $mois_today || $jour_anniv_membre > $jour_today && $mois_anniv_membre == $mois_today) {
					 		$date2 = strtotime($annee_today.$mois_anniv_membre.$jour_anniv_membre);

					 	}
					 	else if ($mois_anniv_membre == $mois_today && $jour_anniv_membre == $jour_today){
					 		$ok = false;
					 	}
					 	else {
					 		$date2 = strtotime(strval(($annee_today + 1).$mois_anniv_membre.$jour_anniv_membre));
					 	}

					 	if ($ok == true){
							$nbJoursTimestamp = $date2 - $date1;
							$nbJours = $nbJoursTimestamp/86400; 
							
						}

						if ($ok == true && $nbJours <= 7){ $y++;
							if ($y > 1) {
								echo '- ';
							}?>
							<b><a href="../profil/voirprofil.php?m=<?php 
						echo $anniv_proche_all['id'];?>&action=consulter">

						<?php 
							$rang_user = $pdo->prepare("SELECT grade FROM users WHERE id = ?");
							$rang_user->execute(array($anniv_proche_all['id']));
							$utilisateur = $rang_user->fetch();
							echo rang_etat($utilisateur['grade'], $anniv_proche_all['username']);?></a></b><i>
								(<?php $annee_aniv = preg_replace("#([0-9]{4})\/[0-9]{2}\/[0-9]{2}#",'$1',$anniv_proche_all['date_anniv']);
								echo ($annee_today - $annee_aniv); ?>)</i><?php if ($i<$anniversaire_today){echo ',';} ?>
						<?php }
						}
					}
				 	if ($y == 0 || $anniversaire_proche_verif == 0){
				 		echo 'Aucun';
					} 
					echo '.';?>

				 	</p>
			</div>           
		</div>  <br/>    
		<p><i>Légende : <span style="color: red; font-weight: bold;">Propriétaire</span>, <span style="color: darkblue; font-weight: bold;">Administrateurs</span>, <span style="color: #4080BF; font-weight: bold;">Développeurs</span>, <span style="color: #31B404; font-weight: bold;">Modérateurs</span>, <span style="color: #40A497; font-weight: bold;">Rédacteurs</span>, <span style="color: #40A497; font-weight: bold;">Newseurs</span>, <span style="color: #632569; font-weight: bold;">Community Manager</span>, <span style="color: orange; font-weight: bold;">Animateurs</span>, <span style="color: #2E9AFE; font-weight: bold;">Membres</span>, <span style="color: black; font-weight: bold;">Bannis</span></i></p>   
	</div>
</div>    