<div class="titre_commentaire_news">Images en attente <span class="couleur_mangas">de</span> <span class="couleur_fans">validation</span></div><br/>
		<table class="table table-striped">
		 	<thead>
             	<tr>
               		<th>Auteur</th>
               		<th>Image</th>
               		<th>Lien du site</th>
               		<th>Action</th>
             	</tr>
      		</thead>
      		  <?php 
        if (!empty($_POST['valider'])){
          $valider = $pdo->prepare("INSERT INTO images (image, auteur, lien) VALUES (?, ?, ?)");
          $valider->execute(array($_POST['image_image'], $_POST['image_auteur'], $_POST['image_lien']));
          $enlever = $pdo->prepare('DELETE FROM images_attente WHERE images = ?');
          $enlever->execute(array($_POST['image_image']));
        }
        if (!empty($_POST['refuser'])){
           $enlever = $pdo->prepare('DELETE FROM images_attente WHERE images = ?');
          $enlever->execute(array($_POST['image_image']));
        }
                $recuperer = $pdo->prepare('SELECT * FROM images_attente ORDER BY id DESC');
                $recuperer->execute();
                while($images = $recuperer->fetch()){
            ?>
<form method="POST" action="">
<input type="hidden" name="image_lien" value="<?php echo $images['lien']?>">
<input type="hidden" name="image_auteur" value="<?php echo $images['auteur']?>">
<input type="hidden" name="image_image" value="<?php echo $images['images']?>">
          <tbody>
            <tr>
                <td><?php echo $images['auteur']; ?></td>
                <td><a href="<?php echo $images['images']; ?>" target="_blank"><?php echo $images['images']; ?></a></td>
                <td><?php echo $images['lien']; ?></td>
                <td><input type="submit" name="valider" class="btn btn-success" value="Valider"> <input type="submit" name="refuser" class="btn btn-danger" value="Refuser"></td>
            </tr>
          </tbody>
</form>
      <?php } ?>
    </table>