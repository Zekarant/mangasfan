$(function() {
	$(".butnum0").css("color","#B07D86");
	$("span.onglet").css("background-color",categorie($("span.onglet").text()));
	
	function mouvement(){
		var id_jeu = parseInt($("span.id_jeu").text()),
		name_page = parseInt($("span.num_pages").text()),
		action;

		if($(this).attr("class") == "right_arrow"){
			action = "right";
		} else {
			action = "left";
		} 

		$(".butnum"+name_page).css("color","#A9A9A9");
	
		$.ajax({
			url : "traitement_derniers_articles.php?id_jeu=" + id_jeu + "&id_page=" + name_page + "&action="+action,
			type : 'GET',
			success : function(data){
				var tableau = data.split(";;;");
				name_page = tableau[tableau.length-1];
				$("div#last_article").css("background","url('" + tableau[0] +"')");
				$("span.onglet").text(tableau[1]).css("background-color",categorie(tableau[1]));
				$("span.title_last_art").text(tableau[2]);
				$("span.num_pages").text(name_page);
				$(".butnum"+name_page).css("color","#B07D86");
			}
		});
	}

	$(".right_arrow").on('click',mouvement);
	$(".left_arrow").on('click',mouvement);

	$(".button_js").on('click',function(){
		var id_jeu = parseInt($("span.id_jeu").text()),
		name_page = parseInt($("span.num_pages").text()), 
		name_class;

		$(".button_js").css("color","#A9A9A9");

		name_class = $(this).attr("class");
		name_page = name_class.substr(name_class.length-1);

		$.ajax({
			url : "traitement_derniers_articles.php?id_jeu=" + id_jeu + "&id_page=" + name_page + "&action=none",
			type : 'GET',
			success : function(data){
				var tableau = data.split(";;;");
				name_page = tableau[tableau.length-1];
				$("div#last_article").css("background","url('" + tableau[0] +"')");
				$("span.onglet").text(tableau[1]).css("background-color",categorie(tableau[1]));
				$("span.title_last_art").text(tableau[2]);
				$("span.num_pages").text(name_page);
				$(".butnum"+name_page).css("color","#B07D86");
			}
		});
	});



	$("span.name_cat").on("click",function(){
		var name_page = $(this).text(), 
		id_jeu = parseInt($("span.id_jeu").text());

		/** $("div#liste_pages").css("display","none");
		var val = $("span.name_cat").index(this);
		$("div#liste_pages").get(val).css("display","inline-block"); **/

		$.ajax({
			url : "traitement_listes_pages.php?id_jeu=" + id_jeu + "&name_cat=" + name_page,
			type : 'GET',
			success : function(data){
				$('div#liste_pages').html(data);

				$('span.page_off').click(function() {
			    	$('span.page_off').trigger('change_page');
			   	});
			}
		});
	});


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