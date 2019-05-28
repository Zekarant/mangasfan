<center>
	<div id="titre_news">Dernière <span class="couleur_mangas">animation</span><span class="couleur_fans"> postée</p></span>
	</div>
</center>
<center>
	<?php 
    if(!empty($_POST['animation'])){
            if ($_POST['titre_animation'] != NULL){
                  $insert_anim = $pdo->prepare("INSERT INTO animation(contenu,title,date_contenu) VALUES(?,?,NOW())");
                  $insert_anim->execute(array($_POST['animation'],$_POST['titre_animation']));

                  echo "<div class='alert alert-success' role='alert'>Votre animation à été ajoutée.</div>";
              }
        }
    ?>
	<br/>
	<i>
		<span class="resume_animation">« <?php
			$select_anim = $pdo->prepare("SELECT * FROM animation ORDER BY id DESC");
			$select_anim->execute();
			$animation = $select_anim->fetch();
			echo bbcode($animation['contenu']);
										?> » 
		</span>
	</i>
		<h1 class="ajouter_animation">Ajouter une nouvelle animation</h1>
			<a href="../inc/bbcode_active.html" class="bbcode" target="_blank">Cliquez ici pour voir la liste de BBCode disponible !</a>
		<form method="post" action=""><br/>
			Choisir la catégorie de ce que vous allez poster : (Est-ce un recrutement, une animation, une annonce ?)
			<select name="titre_animation" id="titre_animation" style="width: 100%;" class="form-control">
		 	 	 <option value='recrutements' for='titre_animation'>Recrutements</option>
				 <option value='animation' for='titre_animation'>Animation</option>
		 	 	 <option value='annonce' for='titre_animation'>Annonce</option>
		 	  	 <option value='maj' for='titre_animation'>Mise à jour</option>
		 	   	 <option value='alerte' for='titre_animation'>Alerte</option>
		 </select><br/>
		 Entrez le texte de votre post, il peut contenir des images :
			<textarea class="form-control" name="animation" placeholder="Entrez ici le texte que vous souhaitez ajouter à l'accueil, il apparaîtra à coup sûr si vous appuyez sur Valider !"></textarea>
			<input name="valide_animation" class="btn btn-sm btn-info" type="submit" value="Valider">
		</form>
</center>