<div id="titre_news">
        <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
         N<span class="couleur_mangas">otre</span><span class="couleur_fans"> Team</span> 
        <img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
</div><br/>
<div class="table-responsive-md">
  <table class="table table-striped">
		 <thead>
             <tr>
               <th>Pseudo</th>
               <th>Rang</th>
               <th>Manga favori</th>
               <th>Date d'inscription</th>
             </tr>
      </thead>
         <?php
		         $select_all_membres = $pdo->prepare("SELECT *, DATE_FORMAT(confirmed_at,'%d/%m/%Y') AS date_inscription FROM users WHERE grade >= 3 AND username != \"Équipe du site\" ORDER BY grade DESC");
             $select_all_membres->execute();
	           while ($membre_all = $select_all_membres->fetch())
	{       ?>
       <tbody>
             <tr>
                <td>» <a href="../profil/voirprofil.php?m=<?php echo $membre_all['id'];?>&action=consulter"><?php echo $membre_all['username'];?></a></td>
               <td><?php echo statut($membre_all['grade']);?></td>
               <td><?php  if($membre_all['manga'] == ""){ echo'Non renseigné';} else {echo $membre_all['manga'];} ?></td>
               <td><?php echo $membre_all['date_inscription'];?></td>
             </tr>
        </tbody>
	<?php }
?>
    </table>
</div>
