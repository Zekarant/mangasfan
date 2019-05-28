<div id="titre_news">Réponse Animation <span class="couleur_mangas">de</span> <span class="couleur_fans">Noël</span></div><br/>
       <div class='alert alert-info' role='alert'>
         <b>Réponses justes au questionnaire :</b><br/>
         <b>Réponse 1 :</b> 25 Décembre<br/>
         <b>Réponse 2 :</b> Lucryio<br/>
         <b>Réponse 3 :</b> 10<br/>
         <b>Réponse 4 :</b> 1er Janvier<br/>
         <b>Réponse 5 :</b> Pokémon-Power<br/>
         <b>Réponse 6 :</b> Logo<br/>
         <b>Réponse 7 :</b> Module de rédaction<br/>
         <b>Réponse 8 :</b> 4<br/>
         <b>Réponse 9 :</b> Forum<br/>
         <b>Réponse 10 :</b> 3<br/>
       </div>
		<table class="table table-striped">
		 	<thead>
             	<tr>
               		<th>Pseudo</th>
               		<th>Réponse 1</th>
               		<th>Réponse 2</th>
               		<th>Réponse 3</th>
               		<th>Réponse 4</th>
               		<th>Réponse 5</th>
               		<th>Réponse 6</th>
               		<th>Réponse 7</th>
               		<th>Réponse 8</th>
               		<th>Réponse 9</th>
               		<th>Réponse 10</th>
             	</tr>
      		</thead>
      		<?php 
				$recuperer = $pdo->prepare('SELECT * FROM anim_seul ORDER BY id DESC');
				$recuperer->execute();
				while($animation_recuperee = $recuperer->fetch()){
			?>
      	<tbody>
            <tr>
            	<td><?php echo $animation_recuperee['membre']; ?></td>
            	<td><?php echo $animation_recuperee['question1']; ?></td>
            	<td><?php echo $animation_recuperee['question2']; ?></td>
            	<td><?php echo $animation_recuperee['question3']; ?></td>
            	<td><?php echo $animation_recuperee['question4']; ?></td>
            	<td><?php echo $animation_recuperee['question5']; ?></td>
            	<td><?php echo $animation_recuperee['question6']; ?></td>
            	<td><?php echo $animation_recuperee['question7']; ?></td>
            	<td><?php echo $animation_recuperee['question8']; ?></td>
            	<td><?php echo $animation_recuperee['question9']; ?></td>
            	<td><?php echo $animation_recuperee['question10']; ?></td>
            </tr>
      	</tbody>
      <?php } ?>
    </table>