<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_redaction.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Modifier "<?= \Rewritting::sanitize($anime['titre']) ?>"</h2>
			<a href="../" class="btn btn-sm btn-outline-info">Retourner à l'index de la rédaction</a>
			<a href="../../../animes/<?= \Rewritting::sanitize($anime['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-info">Accéder à l'anime</a>
			<hr>
			<div id="navigation">
				<center>
					<span class="en-tete">Informations essentielles</span>
					<span class="presentation">Présentation</span>
					<span class="articles">Catégories & Articles</span>
					<span class="rediger">Rédiger</span>
				</center>
			</div>
			<hr>
			<div id="en-tete" class="bloc_contenu">
				<div class="alert alert-info" type="alert">
					<strong>Information importante : </strong> Les dimensions données ne sont qu'approximatives. Une personne utilisant un petit écran d'ordinateur verra les images moins large, mais elles ne seront pas déformées. Cependant, pour les personnes avec un grand écran, ces dimensions sont idéales.
				</div>
				<form method="POST" action="">
					<div class="row">
						<div class="col-lg-3">
							<label>Modifier le titre de l'anime :</label>
						</div>
						<div class="col-lg-9">
							<input type="text" class="form-control" name="title_game" value="<?= \Rewritting::sanitize($anime['titre']);?>" /><br/>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3">
							<label>Modifier l'image sur l'accueil des animes : <strong>(Environ 320*480 pixels)</strong></label><br/>
						</div>
						<div class="col-lg-9">
							<input type="text" class="form-control" name="picture_game" value="<?= \Rewritting::sanitize($anime['banniere']);?>" />
							<small><a href="<?= \Rewritting::sanitize($anime['banniere']) ?>" target="_blank">Voir l'image (Nouvel onglet)</a></small>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							<label>Modifier l'image de présentation : <strong>(Environ 1120*240 pixels)</strong></label>
						</div>
						<div class="col-lg-9">
							<input type="text" class="form-control" name="picture_pres" value="<?= \Rewritting::sanitize($anime['cover']);?>" />
							<small><a href="<?= \Rewritting::sanitize($anime['cover']) ?>" target="_blank">Voir l'image (Nouvel onglet)</a></small>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							<label>Type de l'anime : </label>
						</div>
						<div class="col-lg-9">
							<?php if ($anime['type'] == "anime") { ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="anime" checked>
										<label class="form-check-label" for="inlineRadio1">Anime</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="manga">
										<label class="form-check-label" for="inlineRadio2">Manga</label>
									</div><br/>
								<?php } else { ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="anime">
										<label class="form-check-label" for="inlineRadio1">Anime</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="manga" checked>
										<label class="form-check-label" for="inlineRadio2">Manga</label>
									</div><br/>
								<?php } 
							?>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							<label>Réservé à un public averti : <strong></strong></label>
						</div>
						<div class="col-lg-9">
							<?php if ($anime['publicAverti'] == 0) { ?>
								<input type="checkbox" name="avertissement" id="publicAverti">
							 	<label class="form-check-label" for="exampleCheck1">Cocher si réservé à un public averti</label>
							<?php } else { ?>
								<input type="checkbox" name="avertissement" id="publicAverti" checked>
								<label class="form-check-label" for="exampleCheck1">Cocher si réservé à un public averti</label>
							<?php } ?>
						</div>
					</div>
					<hr>
					<input type="hidden" id="id_news" class="<?= $anime['id'];?>">
					<input type="submit" class="btn btn-sm btn-outline-info" name="valid_entete" value="Modifier les informations" />
				</form>
				<br/>
			</div>
			<div id="presentation" class="bloc_contenu">
				<form method="POST" action="">
					<label for="text_presentation">Modifier la présentation de l'anime : </label>
					<textarea name="text_pres" class="form-control"  rows="10" cols="70" placeholder="Votre commentaire" ><?= \Rewritting::sanitize($anime['presentation']);?></textarea>
					<hr>
					<input type="submit" class="btn btn-sm btn-outline-info" name="valid_presentation" value="Modifier la description" />
				</form>
				<br/>
			</div>
			<div id="articles" class="bloc_contenu table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Catégorie</th>
							<th>Page</th>
							<th>Modification</th>
							<th>Suppression</th>
							<th>Accès à l'article</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($recupererOnglets as $onglets) { ?> 
							<tr>
								<td><span class="name_cat"><?= \Rewritting::sanitize($onglets['name_category']) ?></span></td>
								<td></td>
								<td><span class="modif_cat btn btn-outline-success">Modifier la catégorie</span></td>
								<td><span class="suppr_cat btn btn-outline-warning">Supprimer la catégorie</span></td>
								<td></td>
							</tr>
							<?php foreach($recupererArticles as $articles) {
								if($articles['id_onglet'] == $onglets['id_category']){ ?> 
									<tr>
										<td></td>
										<td><?= \Rewritting::sanitize($articles['name_article']) ?><strong><?php if ($articles['visible'] == 0) {
											echo " - Article visible";
										}else {
											echo " - Article caché";
										} ?></strong></td>
										<td><a href="../modifierArticleAnimes.php?id=<?= \Rewritting::sanitize($articles['id_article']) ?>&anime=<?= \Rewritting::sanitize($articles['id_anime_mangas']) ?>" class="btn btn-outline-info">Modifier la page</a></td>
										<td><span class="suppr_page btn btn-outline-danger">Supprimer la page</span></td>
										<td><a href="../../../animes/<?= \Rewritting::sanitize($articles['slug']) ?>/<?= \Rewritting::sanitize($articles['slug_article']) ?>" target="_blank" class="btn btn-outline-info">Accéder à l'article</a></td>
									</tr>
								<?php }
							} 
						}?>
					</tbody>
				</table>
				<hr>
				<form method="POST" action="" id="nvl_cat" class="d-flex justify-content">
					<input type="text" class="form-control" name="new_cat" placeholder="Nom de la nouvelle catégorie"/>
					<input type="submit" class="btn btn-outline-success" name="valid_nouvelle_cat" value="Ajouter une catégorie" />
				</form>
				<br/>
			</div>
			<div id="rediger" class="bloc_contenu">
				<div class='alert alert-primary' role='alert'>
					<strong>Information : </strong> Veuillez cliquez directement sur le bouton "Prévisualiser" pour voir votre article, <strong>N'OUVREZ PAS LE PREVISUALISER DANS UN NOUVEL ONGLET</strong>. Utilisez les flèches retour de votre navigateur pour revenir à la page d'avant. <strong>Rien ne sera perdu de votre article</strong> mais vous devrez "réactiver" les différents champs juste en cliquant dessus. Ce système n'est qu'une première version, une version meilleure sera proposée en V8 et certaines améliorations pourront être apportées en V7.
				</div>
				<form method="POST" action="">
					<label for="titre_article_redac">Titre de l'article :<span class="message1"></span></label>
					<input type="text" class="form-control" id="titre_article_redac" name="title_page" placeholder="Titre compris entre 1 et 30 caractères." />
					<br />
					<label for="modif_image_redac">Modifier l'image de l'anime : <span style="font-weight:normal; font-style:italic;">Environ 780*400 pixels</span> <span class="message2"></span></label>
					<input type="text" class="form-control" id="modif_image_redac" name="picture_game" placeholder='Image pour "Derniers articles" sur le profil du jeu.'/><br />
					<label for="select_cat_redac">Catégorie : <span class="message3"></span></label>
					<select class="form-control" id="select_cat_redac" name="liste_categories">
						<option>Sélectionner une catégorie</option>
						<?php foreach($recupererOnglets as $onglets) { ?>
							<option><?= \Rewritting::sanitize($onglets['name_category']) ?></option>
						<?php } ?>
					</select><br/>
					<label>Visibilité :</label>
					<select class="form-control" id="visible" name="visibilite">
						<option value="0">Visible</option>
						<option value="1">Caché</option>
					</select>
					<br/>
					<label for="text_redac">Texte : <span class="message4"></span></label>
					<textarea name="text_pres" class="form-control" id="text_redac" rows="10" cols="70" placeholder="Rédiger votre page ici." ></textarea>
					<hr>
					<input type="submit" class="btn btn-outline-info" name="valid_nouvelle_page" value="Publier cet article" />
					<input type="submit" class="btn btn-outline-primary" name="preview" value="Prévisualiser l'article"/>
				</form>
				<br/>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("div.bloc_contenu").css("display","none");
	$("div#navigation span.en-tete").addClass("clique_onglet");
	$("div#en-tete").css("display","block");
	var active = $("span.en-tete").attr("class");
	$(function() {
		var id_jeu = $("input[type=\"hidden\"]").attr('class');

		$("div#navigation span").on('click',function(){
			if($(this).attr('class') != active){
				var elt_clique = $(this).attr('class');
				$("div.bloc_contenu").css("display","none");
				$("div#"+elt_clique).css("display","block");
				$("div#navigation span").removeClass("clique_onglet");
				$("div#navigation span."+elt_clique).addClass("clique_onglet");

				active = $(this).attr("class");
			}
		});

		$("span.modif_cat").on("click",function(){
			var nouveau_nom = prompt("Modifier le nom de la catégorie.",$("span.name_cat").get($("span.modif_cat").index(this)).textContent);
			var taille = (nouveau_nom != null) ? nouveau_nom.length : -1;
			if(taille >= 0) {
				if(taille < 3 || taille > 12){
					alert("Veuillez définir un onglet de taille comprise entre 4 et 12 caractères.");
				} else {
					$.ajax({
						url : "../categoriesAnimes.php?id_anime=" + id_jeu + "&page_id=" + ($("span.modif_cat").index(this) + 1) + "&new_name=" + nouveau_nom,
						type : 'GET',
						success : function(data){
							window.location.reload();
						}
					});
				}
			}
		});

		$("span.suppr_cat").on("click",function(){
			var can_suppr = confirm("Êtes-vous sûr de vouloir supprimer cette catégorie ?\nATTENTION : En effectuant cette opération, vous supprimez également les pages liées.");
			if(can_suppr){
				$.ajax({
					url : "../supprimerCategoriesAnimes.php?id_anime=" + id_jeu + "&page_id=" + ($("span.suppr_cat").index(this) + 1),
					type : 'GET',
					success : function(data){
						window.location.reload();
					}
				});
			}
		});

		$("span.suppr_page").on("click",function(){
			var can_suppr = confirm("Êtes-vous sûr de vouloir supprimer cette page ?");
			var num_elt_page = $("span.suppr_page").index(this);
			if(can_suppr){
				$.ajax({
					url : "../supprimerPageAnimes.php?id_anime=" + id_jeu + "&page_id=" + (num_elt_page + 1),
					type : 'GET',
					success : function(data){
						window.location.reload();
					}
				});
			}
		});

		var name_title = false, picture_ok = false, categorie = false, texte_form = true;
	// SYSTEME VALIDATION REDACTION
	$("div#rediger input[type='text'], div#rediger select, div#rediger textarea").blur(function(){
		var name_id = $(this).attr("id");
		switch(name_id){
			case "titre_article_redac":
			if($(this).val().length > 0 && $(this).val().length < 31){
				$(this).css("border-color","green");
				$("span.message1").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green;margin-left:5px\">Titre valide !</span>");
				name_title = true;
			} else {
				$(this).css("border-color","red");
				$("span.message1").html("<span class=\"glyphicon glyphicon-remove\"></span>Le titre doit comporter entre 1 et 30 caractères.").css("color","red").css("margin-left","5px");
				name_title = false;
			}
			break;

			case "modif_image_redac":
			if(/^(?:https|http|ftp):\/\/[a-zA-Z0-9@#\/\.]{2,}\.(?:png|jpg|jpeg|gif)$/i.test($(this).val())){
				$(this).css("border-color","green");
				$("span.message2").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green;margin-left:5px\">Lien de l'image valide !</span>");
				picture_ok = true;
			} else {
				$(this).css("border-color","red");
				$("span.message2").html("<span class=\"glyphicon glyphicon-remove\"></span Image incorrecte (Formatd autoriséd : png, jpg, jpeg, gif)").css("color","red").css("margin-left","5px");
				picture_ok = false;
			}
			break;

			case "select_cat_redac":
			if($(this).val() != "Sélectionner une catégorie"){
				$(this).css("border-color","green");
				$("span.message3").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green;margin-left:5px\">Catégorie valide !</span>");
				categorie = true;
			} else {
				$(this).css("border-color","red");
				$("span.message3").html("<span class=\"glyphicon glyphicon-remove\"></span>Sélectionnez une catégorie.</span>").css("color","red").css("margin-left","5px");
				categorie = false;
			}
			break;
		}
	});

	$("div#rediger input[type='submit']").on('click',function(e){
		if(name_title && picture_ok && categorie && texte_form){
			$("div#rediger form").css("display","none");
			var elt = document.createElement("div"),
			texte = document.createTextNode("Votre article a bien été créé !");
			$("div#rediger").append(elt);
		} else {
			alert("Veuillez remplir correctement tous les champs.");
			e.preventDefault();
			return false;
		}
	});

});
</script>