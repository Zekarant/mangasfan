<?php
$new_grade = isset($_POST['new_grade'] ) ? $_POST['new_grade'] : '';
if(!empty(stripslashes(htmlspecialchars($new_grade))))
	{
		$grade = stripslashes(htmlspecialchars($_POST['grades']));
		$id_membre = stripslashes(htmlspecialchars($_POST['membres']));
	  	$new_grade = $pdo->prepare("UPDATE users SET grade = ? WHERE id = ?");
	  	$new_grade->execute(array($grade, $id_membre));
	  	$select_pseudo_grade_new = $pdo->prepare("SELECT username FROM users WHERE id = ?");
	  	$select_pseudo_grade_new->execute(array($id_membre));
	  	$grade_news = $select_pseudo_grade_new->fetch(); ?><br/>
	<?php
	  echo '<div class="alert alert-success" role="alert">Le grade de <i>'.sanitize($grade_news['username']).' </i>a bien été changé !<br/></div>';
if (!empty($_POST['ban']))
		{
			$select_pseudo_membre = $pdo->prepare("SELECT * FROM users WHERE id = ?");
			$select_pseudo_membre->execute(array($_POST['membres']));
			$pseudo_membre = $select_pseudo_membre->fetch();
			$insert_ban = $pdo->prepare("INSERT INTO bannissement VALUES(NULL, ?, ?, ?, ?)");
			$insert_ban->execute(array($_POST['ban'], $_POST['duree'], $pseudo_membre['username'], $_POST['membres']));
			$raison_ban = $pdo->prepare("SELECT * FROM bannissement WHERE pseudo = ?");
			$raison_ban->execute(array($pseudo_membre['username']));
			$ban_valide = $raison_ban->fetch();
      		$header="MIME-Version: 1.0\r\n";
      		$header.='From: "Mangas\'Fan" <contact@mangasfan.fr>'."\n";
      		$header.='Content-Type:text/html; charset="utf-8"'."\n";
      		$header.='Content-Transfer-Encoding: 8bit';
      		$message='
      <!doctype html>
       <html lang="fr">
         <body>
               <br />
               <i>Pseudo : </i>'.$pseudo_membre['username'].'<br />
               <i>Mail : </i>'.$pseudo_membre['email'].'<br />
               <i>Sujet du Mail : </i>Bannissement de votre compte Mangas\'Fan<br/>
               <i>Contenu : </i><br/>
               <br />
               « Bonjour, <br/>Nous avons décidé de vous bannir des services de <i>Mangas\'Fan</i> pour le motif suivant :  "'.$ban_valide['raison'].'" <br/>Votre bannissement durera jusqu\'au '.$ban_valide['duree'].'. » <br/><br/>
			A la suite de cette date, vous serez débanni et aurez de nouveau accès à votre compte en tant que membre ! <br/>
			Pour toute question, nous vous invitons à nous tenir informé afin que de nouvelles mesures soient prises seulement si cela est nécessaire ! <br/><br/>
               <i>Ce message est un message automatique afin de vous tenir informé de votre bannissement de notre site !</i><br/>
               L\'équipe d\'administration de Mangas\'Fan
         </body>
      </html>
      ';
      mail($pseudo_membre['email'], "Bannissement de Mangas'Fan", $message, $header);
		}
		else
		{}
  }
	else
	{}

?>
 <span class="titre_commentaire_news" id="titre_grade"><center>Grade des membres de <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fan</span></center></span>
<table border="1" cellpadding="10" cellspacing="1" width="35%" class="table table-striped" id="tableau_grade">
	     <tr>
		  <th>Promouvoir un membre</th>
				<th>Grade à donner</th>
				<th>Raison du bannissement (Ne rien mettre si non ban)</th>
				<th>Date de fin du bannissement (XX/MM/YY)</th>
				<th>Action</th>
	   </tr>
	<form method="post" action="">
 <p><tr>
      <th> 
       <select name="membres" id="membres" style="width: 100%;" class="form-control">
						<?php
						$sql = $pdo->prepare("SELECT * FROM users WHERE grade <= 8 ORDER BY id ASC");
						$sql->execute();
						while($don = $sql->fetch())
						{
					echo"<option value='".$don['id']."' for='membres'><b>".(utf8_encode($don['username']))."</b> ".$don['grade']."</option>";
						}
						?>
      				</select></th>
   </p>
 <p>
		<th> 
		 <select name="grades" id="grades" style="width: 100%;" class="form-control">
		 	 	 <option value='1' for='grades'>Banni (1)</option>
				 <option value='2' for='grades'>Membre (2)</option>
		 	 	 <option value='3' for='grades'>Animateur (3)</option>
		 	  	 <option value='4' for='grades'>Community Manager (4)</option>
		 	   	 <option value='5' for='grades'>Newseur (5)</option>
		 	     <option value='6' for='grades'>Rédacteur anime (6)</option>
				 <option value='7' for='grades'>Rédacteur mangas (7)</option>
				 <option value='8' for='grades'>Rédacteur jeux vidéos (8)</option>
		 </select></th>
 </p>
<th><input type="text" name="ban" class="form-control" style="width: 100%"; placeholder="Raison du bannissement si le membre est concerné"><br/></th>
<th><input type="text" name="duree" class="form-control" style="width: 100%"; placeholder="Durée du bannissement"></th>
 <th><input type="submit" name="new_grade" value="Valider le grade !" class="btn btn-sm btn-info"/></th>
</form>
</tr>
</table>
