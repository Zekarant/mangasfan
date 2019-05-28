$("div.bloc_contenu").css("display","none");
$("div#navigation span.en-tete").addClass("clique_onglet");
$("div#en-tete").css("display","block");
var active = $("span.en-tete").attr("class");
var type = $('input[type=\'hidden\']#type_news').attr('class');
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


	$("span.suppr_page").on("click",function(){
		var can_suppr = confirm("Êtes-vous sûr de vouloir supprimer cette page ?");
		var num_elt_page = $("span.suppr_page").index(this);
		if(can_suppr){
			$.ajax({
			url : "../action_redaction.php?id_jeu=" + id_jeu + "&action=suppr&page_id=" + (num_elt_page + 1) + "&type=" + type,
			type : 'GET',
			success : function(data){
					window.location.reload();
				}
			});
		}
	});


	$("span.suppr_cat").on("click",function(){
		var can_suppr = confirm("Êtes-vous sûr de vouloir supprimer cette catégorie ?\nATTENTION : En effectuant cette opération, vous supprimez également les pages liées.");
		if(can_suppr){
			$.ajax({
			url : "../action_redaction.php?type=jeux&id_jeu=" + id_jeu + "&action=suppr_cat&page_id=" + ($("span.suppr_cat").index(this) + 1) + "&type=" + type,
			type : 'GET',
			success : function(data){
					window.location.reload();
				}
			});
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
					url : "../action_redaction.php?&type=" + type + "&id_jeu=" + id_jeu + "&action=modif_cat&page_id=" + ($("span.modif_cat").index(this) + 1) + "&new_name=" + nouveau_nom,
					type : 'GET',
					success : function(data){
						window.location.reload();
					}
				});
			}
		}
	});



	var name_title = false, picture_ok = false, categorie = false, texte_form = true;
	// SYSTEME VALIDATION REDACTION
	$("div#rediger input[type='text'], div#rediger select, div#rediger textarea").blur(function(){
		var name_id = $(this).attr("id");
		switch(name_id){
			case "titre_article_redac":
				if($(this).val().length > 3 && $(this).val().length < 21){
					$(this).css("border-color","green");
					$("span.message1").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green;margin-left:5px\"></span>");
					name_title = true;
				} else {
					$(this).css("border-color","red");
					$("span.message1").html("<span class=\"glyphicon glyphicon-remove\"></span> Le titre doit comporter entre 4 et 20 caractères.").css("color","red").css("margin-left","5px");
					name_title = false;
				}
			break;

			case "modif_image_redac":
				if(/^(?:https|http|ftp):\/\/[a-zA-Z0-9@#\/\.]{2,}\.(?:png|jpg|jpeg|gif)$/i.test($(this).val())){
					$(this).css("border-color","green");
					$("span.message2").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green;margin-left:5px\"></span>");
					picture_ok = true;
				} else {
					$(this).css("border-color","red");
					$("span.message2").html("<span class=\"glyphicon glyphicon-remove\"></span> Image incorrecte (Format autorisé : png, jpg, jpeg, gif)").css("color","red").css("margin-left","5px");
					picture_ok = false;
				}
			break;

			case "select_cat_redac":
				console.log($(this).val());
				if($(this).val() != "Sélectionner une catégorie"){
					$(this).css("border-color","green");
					$("span.message3").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green;margin-left:5px\"></span>");
					categorie = true;
				} else {
					$(this).css("border-color","red");
					$("span.message3").html("<span class=\"glyphicon glyphicon-remove\"></span> Sélectionnez une catégorie.</span>").css("color","red").css("margin-left","5px");
					categorie = false;
				}
			break;
		}
	});

	$("#text_redac").on('blur', function () {
		console.log("AYAAA");
	    if($(this).val() !== ""){
			$(this).css("border-color","green");
			$("span.message4").html("<span class=\"glyphicon glyphicon-ok\" style=\"color:green;\"></span>").css("margin-left","25%");
			texte_form = true;
		} else {
			$(this).css("border-color","red");
			$("span.message4").html("<span class=\"glyphicon glyphicon-remove\"></span> Sélectionnez une catégorie.</span>").css("color","red").css("margin-left","25%");
			texte_form = false;
		}
	});



	$("div#rediger input[type='submit']").on('click',function(e){
		console.log("Titre : " + name_title);
		console.log("Image : " + picture_ok);
		console.log("Categorie : " + categorie);
		console.log("Texte : " + texte_form);
		if(name_title && picture_ok && categorie && texte_form){
			$("div#rediger form").css("display","none");
			var elt = document.createElement("div"),
			texte = document.createTextNode("Votre article a bien été créé !");
			$("div#rediger").append(elt);
			//$("div#rediger form").submit();
		} else {
			alert("Veuillez remplir correctement tous les champs.");
			e.preventDefault();
			return false;
		}
	});








});
	


