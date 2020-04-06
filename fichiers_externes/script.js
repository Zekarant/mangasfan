$(function() {
	$(".butnum0").css("color","#B07D86");
	$("span.onglet").css("background-color",categorie($("span.onglet").text()));
	
	//recupère les éléments de l'url
	function recup_type() {
		var url = location.pathname;
		var tableau = url.split('');
		tableau.shift();
		var elt = tableau.join('');
		if(/^jeux-video/.test(elt)){
			return "jeux";
		} else if(/^mangas/.test(elt)){
			return "mangas";
		} else {
			return null;
		}
	}

	var type = recup_type();
	var id_jeu = parseInt($("span#titre_news").attr("class"));
	$("span#titre_news").remove();
	var name_page = 0;
	
	function mouvement(){
		var action = ($(this).attr("class") == "right_arrow") ? "right" : "left";

		$(".butnum"+name_page).css("color","#A9A9A9");
		$.ajax({
			url : "../fichiers_externes/traitement_derniers_articles.php?id_elt=" + id_jeu + "&type=" + type + "&id_page=" + name_page + "&action="+action,
			type : 'GET',
			success : function(data){
				var tableau = data.split(";;;");
				name_page = tableau[tableau.length-1];
				$("div#last_article").css("background","url('" + tableau[0] +"')");
				$("span.onglet").text(tableau[1]).css("background-color",categorie(tableau[1]));
				$("span.title_last_art").text(tableau[2]);
				$(".butnum"+name_page).css("color","#B07D86");
			}
		});
	}

	$(".right_arrow").on('click',mouvement);
	$(".left_arrow").on('click',mouvement);

	$(".button_js").on('click',function(){
		$(".button_js").css("color","#A9A9A9");

		var name_class = $(this).attr("class");
		name_page = name_class.substr(name_class.length-1);

		$.ajax({
			url : "../fichiers_externes/traitement_derniers_articles.php?id_elt=" + id_jeu + "&type=" + type + "&id_page=" + name_page + "&action=none",
			type : 'GET',
			success : function(data){
				var tableau = data.split(";;;");
				name_page = tableau[tableau.length-1];
				$("div#last_article").css("background","url('" + tableau[0] +"')");
				$("span.onglet").text(tableau[1]).css("background-color",categorie(tableau[1]));
				$("span.title_last_art").text(tableau[2]);
				$(".butnum"+name_page).css("color","#B07D86");
			}
		});
	});

	$("span.name_cat,span.cat_active").on("click",function(){
		var name_cat = $(this).text();
		$("span.cat_active").attr("class","name_cat");

		$(this).removeClass("name_cat").addClass('cat_active');

		$.ajax({
			url : "../fichiers_externes/traitement_listes_pages.php?id_elt=" + id_jeu + "&type=" + type + "&name_cat=" + name_cat,
			type : 'GET',
			success : function(data){
				$('div#liste_pages').html(data);
			}
		});
	});


});


var id_mangas = parseInt($("span.id_elt").text());
	$('#recherche').autocomplete({
		minLength:3, 
		source:"listePages.php",
			
});





/*
var id_jeu = parseInt($("span.id_jeu").text());
$('#recherche').autocomplete({
	minLength:3, 
	source:"listePages.php",
	select:function(event, ui){
		$.ajax({
			url : "selection_page.php?id_jeu=" + id_jeu + "&name_page=" + $(this).val(ui.item.id),
			type : 'GET',
			success : function(data){
				window.location.assign("index2.php?jeux="+id_jeu+"&page="+data);
			}
		});
		console.log($(this).val(ui.item.id));
		console.log(ui);
	}
});*/