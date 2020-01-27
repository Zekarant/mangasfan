<div class="espace_commentaire">
		<h3>Espace commentaires</h3>
		<?php if ($nbr_commentary < 1){ ?>
			<div class='alert alert-warning' style="width:90%;text-align:center;margin:auto;margin-bottom:5px;" role='alert'> Il n'y a actuellement aucun commentaire. Soyez le premier à nous en faire un pour cette page ! </div>
		<?php } else { 
			echo $str_page;
	        #$req = $pdo->query("SELECT * FROM commentary_page ORDER BY id LIMIT '$limit_start','$pagination'");
	        ?>
			<table>
				<?php while($recup_all_commentary = $all_commentary->fetch()){ 
					$member = $pdo->query("SELECT * FROM users WHERE id = ".$recup_all_commentary['id_member'])->fetch();
					$editation = $recup_all_commentary['editation']; ?>

					<tr>
						<td align="center" width="100px">

							<?php if(preg_match("#http|https#",$member['avatar'])){
								$lien_avatar = $member['avatar'];
							} else {
								$lien_avatar = "https://mangasfan.fr/inc/images/avatars/".$member['avatar'];
							} ?>

							<div class="avatar" style="box-shadow: 0px 0px 2px 2px <?= avatar_color($member['grade']) ?>;background:url('<?= $lien_avatar; ?>');background-size:100px;background-position: center;" /></div>
							<span class="pseudo"> <?= rang_etat($member['grade'],$member['username']); ?></span>
							<?php include('icones.php'); ?>
						</td>
						<td>
							<span class="pointe2"></span><span class="pointe"></span>
							<span class="contenu"><span style="display:block;padding-bottom:10px;"><?php $texte = nl2br(htmlspecialchars($recup_all_commentary['commentary']));

							$d = preg_replace('/\r/', '', $texte);
    						$clean = preg_replace('/\n{2,}/', '\n', preg_replace('/^\s+$/m', '', $d));
    						$sqdd = str_replace('\n', '<br />',$clean);
    						$sqdd = str_replace('\r', '<br />',$sqdd);
    						$sqdd = str_replace('\r\n', '<br />',$sqdd);
							echo bbcode($sqdd);
							?></span>
								<span class="bottom"><?php $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];

								$date_post = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})#",function ($key) use ($liste_mois){ 
									return 'Posté le '.$key[3].' '.$liste_mois[$key[2]-1].' '.$key[1].' à '.$key[4].':'.$key[5]; },$recup_all_commentary['time_post']);
									echo $date_post;?><?php if($editation > 0){ echo ' (édité '.$recup_all_commentary['editation'].' fois)';} ?></span>
							</span>		
							
							<?php 
							if($can_commentary){ 
								if ($member['grade'] <= $utilisateur['grade'] && $utilisateur['grade'] > 8 || $member['id'] == $utilisateur['id']) {
									if(isset($_POST['erase_commentary'])){
										$message = $pdo->prepare("DELETE FROM commentary_page WHERE id = ?");
										$message->execute(array($recup_all_commentary['id']));
									}
								?>
								<span class="bouton_app">
									<a href="../../commentaires/edit_com.php?id=<?= $recup_all_commentary['id'] ?>" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-edit"></span> Editer</a>

									<a href="../../commentaires/delete_com.php?id=<?= $recup_all_commentary['id'] ?>" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-erase"></span> Supprimer</a>
								</span>
							<?php } 
						}?>
		
						</td>	
					</tr>
				<?php } $all_commentary->closeCursor(); ?>
			</table>
			
		<?php echo $str_page; } 
		if($all_commentary->rowCount()==0){
			$not_last_com = true;
		}

		if($can_commentary AND $not_last_com){ ?>
		<form action="" method="post">
		    <center><label class="col-sm-2" for="commentary">Ajouter un commentaire : <br />
		    <a href="../inc/bbcode_active.html" class="lien_bbcode" target="blank">Voici la liste des bbcodes possibles</a></label>
		    <textarea name="description" class="form-control" id="commentary" rows="10" cols="70" placeholder="Votre commentaire" ></textarea>
			<button class="btn btn-sm btn-info" name="valid_send"><span class="glyphicon glyphicon-pencil"></span> Envoyer</button></center>
		</form>
		<?php } elseif ($can_commentary AND !$not_last_com) { ?><br/>
			<div class='alert alert-danger' style="width:90%;text-align:center;margin:auto;" role='alert'><b>Info : </b>Vous êtes l'auteur du dernier commentaire. Pour ajouter quelques choses, utilisez l'option d'édition disponible en survolant la "ligne" de votre commentaire.</div>
		<?php } ?>
	</div>
	
