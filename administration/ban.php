<center>
	<div class="titre_commentaire_news" id="titre_grade">Liste des bannis de <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fan</p></span>
	</div>
</center>
	<table border="1" cellpadding="10" cellspacing="1" width="35%" class="table table-striped" id="tableau_grade">
	   <tr>
		  	<th>Pseudo</th>
			<th>Date d'inscription</th>
			<th>Raison du bannissement</th>
			<th>Date de fin du bannissement (XX/MM/YY)</th>
			<th>Action</th>
	   </tr>
<?php
	if(!empty($_GET['deban']))
	{
		$var = $_GET['deban'];
		$deban_membre = $pdo->prepare("UPDATE users SET grade = '2' WHERE username = ?");
		$deban_membre->execute(array($var));
		$deban = $pdo->prepare("DELETE FROM bannissement WHERE pseudo= ?");
		$deban->execute(array($var));
		echo '<div class="alert alert-success" role="alert">Le membre '.$select_pseudo_grade_new['username'].' a été debanni !</div>';
		echo '<script>location.href="admin.php";</script>';
	}
	else
	{}

		$reponse = $pdo->prepare('SELECT * FROM users AS u INNER JOIN bannissement AS b ON u.id = b.id_membre WHERE u.grade=1');
		$reponse->execute();
	while ($donnees = $reponse->fetch())
	{
	?>

	<p>
		<tr>
	  		<td><i><?php echo $donnees['username']; ?></i><br/></td>
			<td><?php echo $donnees['confirmed_at']; ?><br/></td>
			<td><?php echo $donnees['raison']; ?><br/></td>
			<td><?php echo $donnees['duree']; ?><br/></td>
			<td><a href="?deban=<?php echo $donnees['pseudo'];?>">Débannir</a><br/></td>
		</tr>
	</p>

<?php
	}
		$reponse->closeCursor(); // Termine le traitement de la requête
?>
</table>
