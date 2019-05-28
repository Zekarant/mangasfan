<?php
$new_titre = isset($_POST['new_titre'] ) ? $_POST['new_titre'] : '';
if(!empty(sanitize($new_titre)))
	{
		//recup des variables
		$titre = sanitize($_POST['titre']);
		$id_membre = sanitize($_POST['membres']);
		$new_titre = $pdo->prepare("UPDATE users SET testeurs = ? WHERE id = ?");
		$new_titre->execute(array($titre, $id_membre));
	  	$select_pseudo_titre_new = $pdo->prepare("SELECT username FROM users WHERE id = ?");
	  	$select_pseudo_titre_new->execute(array($id_membre));
	  	$pseudo_titre = $select_pseudo_titre_new->fetch(); ?><?php
	  	echo '<div class="alert alert-success" role="alert">Le titre de <i>'.$pseudo_titre['username'].' </i>a bien été changé <br/></div>';
	  	echo '<script>location.href="modo.php";</script>';
	 
  	}
else
	{
	}

?>
 <span class="titre_commentaire_news">
 	<center>Titre des membres de <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fan</span>
 	</center>
 </span>
<table border="1" cellpadding="10" cellspacing="1" width="35%" class="table table-striped">
	    <tr>
		 	<th>Donner un titre à un membre</th>
			<th>Titre principal</th>
			<th>Action</th>
	   	</tr>
	<form method="post" action="">
 	<p>
 		<tr>
      		<th> 
       			<select name="membres" id="membres" style="width: 100%;" class="form-control">
				<?php
					$sql = $pdo->prepare("SELECT * FROM users ORDER BY id ASC");
					$sql->execute();
					while($don = $sql->fetch())
				{
					echo"<option value='".$don['id']."' for='membres'>".(utf8_encode($don['username']))." » ".$don['testeurs']."</option>";
				}
				?>

       			</select>
       		</th>
    </p>
 	<p>
			<th> 
		  		<select name="titre" id="titre" style="width: 100%;" class="form-control">
				 	<option value='0' for='titre'>Aucun titre (0)</option>
				 	<option value='1' for='titre'>Partenaire (1)</option>
				 </select>
			</th>
 	</p>
 			<th>
 				<input type="submit" name="new_titre" value="Valider le titre !" class="btn btn-sm btn-info"/>
 			</th>
 		</tr>
	</form>
</table>
