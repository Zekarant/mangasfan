<center><div class="titre_commentaire_news">Membres et leur <span class="couleur_mangas"> fiche</span><span class="couleur_fans"> admin</p></span></center><br/>

<form method="GET" action="fiche_membre.php" >
<input type="text" name="pseudo" class="form-control" placeholder="Tapez le pseudo du membre">
<span class="boutons_membre"><input type="submit" value="Chercher le membre" class="btn btn-sm btn-info"></span>
</form>
<?php
if (!empty($_GET['page']) && is_numeric($_GET['page']) )
         $page = stripslashes($_GET['page']);
         else
         $page = 1;
         $pagination = 5;
         // Numéro du 1er enregistrement à lire
         $limit_start = ($page - 1) * $pagination;
         $nb_total = $pdo->query('SELECT COUNT(*) AS nb_total FROM users');
         $nb_total->execute();
         $nb_total = $nb_total->fetchColumn();
         // Pagination
         $nb_pages = ceil($nb_total / $pagination);

         echo '<table style="width:50%"><th style="width:33%"><span class="pagination_mobile_admin">[ Page :';
         // Boucle sur les pages
         for ($i = 1 ; $i <= $nb_pages ; $i++) {
         if ($i == $page )
         echo " $i";
         else
         echo " <a href=\"?page=$i\">$i</a> ";
         }
         echo ' ]</th></table>'; 
		 ?>
	   <table border="1" cellpadding="10" cellspacing="1" width="35%" class="table table-striped">
	   <tr>
		  <th>Pseudo</th>
				<th>Action</th>
	   </tr>
		 <?php
$reponse2 = $pdo->prepare("SELECT * FROM users WHERE grade LIMIT $limit_start, $pagination");
$reponse2->execute();

while ($donnees2 = $reponse2->fetch())
{
?>
	
		<p>
				<tr>
	  <td><i><?php echo $donnees2['username']; ?></i><br /></td>
		<td><a href="fiche_membre.php?pseudo=<?php echo $donnees2['username'];?>">Voir la fiche du membre</a><br /></td></tr>
	   </p>
	

<?php
}
?></table>
</center>
