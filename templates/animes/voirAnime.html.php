<a href="../animes" class="btn btn-sm btn-outline-info">Retourner sur l'index des animes</a>
<?php if ($utilisateur['grade'] >= 5) { ?>
	<a href="../staff/redaction/modification-animes/<?= \Rewritting::sanitize($anime['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-info">Accéder à la rédaction de cet anime</a>
<?php } ?>
<hr>
<h2 class="titre jv_index"><?= \Rewritting::sanitize($anime['titre']); ?></h2>
<hr>
<div class="d-flex justify-content-center">
	<div class="card card-Animes" style="width: 70rem;">
		<img src="<?= \Rewritting::sanitize($anime['cover']) ?>" class="card-img-top" style="object-fit: cover; height: 15rem;">
	</div>
</div>
<center>
	<?php if ($notes == 0) {
		echo "Il n'y a aucun vote pour cet anime pour l'instant.";
	} else { ?>
		Note attribuée par les membres : <span style="color: <?= \Users::syst_not($rst_moy) ?>"><?= \Rewritting::sanitize($rst_moy) ?>/5</span> - (<i><?= $moyenne_note->rowCount().$vote ?></i>)
	<?php } ?>
</center>
<?php if (isset($_SESSION['auth']) && $verifier->rowCount() == 0) { ?>
	<div class="d-flex justify-content-center">
		<strong>Voter : </strong>
		<form method="POST" action="">
			<input type="submit" name="etoile1" value="★" class="color_no etoile" />
			<input type="submit" name="etoile2" value="★" class="color_no etoile" />
			<input type="submit" name="etoile3" value="★" class="color_no etoile" />
			<input type="submit" name="etoile4" value="★" class="color_no etoile" />
			<input type="submit" name="etoile5" value="★" class="color_no etoile" />
		</form>
	</div>
<?php } if(!empty($anime['presentation'])){ ?>
	<h4 class="title_jeu">Présentation</h4>
	<p><?= htmlspecialchars_decode(\Rewritting::sanitize($anime['presentation'])); ?></p>
<?php } ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-6 dernier_articles">
			<h4 class="title_jeu">Derniers articles</h4>
			<div id="carouselExampleInterval" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">
					<div class="carousel-item active" data-interval="10000" style="object-fit: cover;">
						<span class="onglet"><?= \Rewritting::sanitize($articlesAnimes[0]['name_category']); ?></span>
						<a href="<?= \Rewritting::sanitize($anime['slug'])?>/<?= \Rewritting::sanitize($articlesAnimes[0]['slug_article']) ?>">
							<img src="<?= \Rewritting::sanitize($articlesAnimes[0]['cover_image_article']); ?>" class="d-block w-100" style="height: 25em; object-fit: cover;">
						</a>
						<span class="title_last_art"><?= \Rewritting::sanitize($articlesAnimes[0]['name_article']); ?></span>
					</div>
					<?php foreach ($articlesAnimes as $page): ?>
						<?php if ($page[0] != $lastArticle[0]) { ?>
							<a href="#">
								<div class="carousel-item">
									<span class="onglet"><?= $page['name_category']; ?></span>
									<a href="<?= \Rewritting::sanitize($anime['slug'])?>/<?= \Rewritting::sanitize($page['slug_article']) ?>">
										<img src="<?= \Rewritting::sanitize($page['cover_image_article']); ?>" class="d-block w-100" style="height: 25em; object-fit: cover;">
									</a>
									<span class="title_last_art"><?= \Rewritting::sanitize($page['name_article']) ; ?></span>
								</div>
							</a>
						<?php }
					endforeach; ?>
				</div>
				<a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
		</div>
		<hr class="separation_article">
		<div class="col-lg-6">
			<h3 class="title_jeu">Tous nos articles</h3>
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-4">
						<center><span id="titre" style="text-align:left;" class="<?= \Rewritting::sanitize($anime['id']) ?>"><img src="https://zupimages.net/up/18/25/es4a.png" style="padding-right:10px;font-size:21px;"/>Catégories</span></center>
						<hr>
						<div id="onglet">
							<?php $i = 0;
							while($i < $recup_all_category->rowCount()){ ?>
								<span class="<?= ($i == 0) ? "cat_active" : "name_cat" ?>"><?= \Rewritting::sanitize($parcours_category[$i]['name_onglet']); ?></span>
								<?php $i++; 
							} ?>
						</div>
					</div>
					<div class="col-lg-8">
						<?php if($verifierCategory->rowCount() == 0){
							echo 'Une erreur est survenue.';
						} else { ?>
							<span class="entete_liste_page"><b>Pages de :</b> <span class="titre_name_cat"><?= \Rewritting::sanitize($animes['name_category']) ?></span></span>
							<hr>
							<div id="liste_articles"></div>
						<p><i>Limitation à 10 articles</i></p>
						<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	$(function() {
		$("span.name_cat,span.cat_active").on("click",function(){
			var id_jeu = parseInt($("span#titre").attr("class"));
			var name_cat = $(this).text();
			var type = "mangas";
			$("span.cat_active").attr("class","name_cat");

			$(this).removeClass("name_cat").addClass('cat_active');
			$.ajax({
				url : "../animes/categories.php?id_elt=" + id_jeu + "&type=" + type + "&name_cat=" + name_cat,
				type: 'GET',
				success: function(data) {
					$('div#liste_articles').html(data);
					$('span.titre_name_cat').text(name_cat);
				}, 
				error: function(error) {
					alert("Error : " + error);
				}
			});

		});
		$("span.cat_active").first().click();
	})
</script>
<script>
	var etoile = document.getElementsByClassName('etoile');

	for(var i = 0; i < etoile.length; i++) {
		var temp = i+1;

		etoile[i].addEventListener('mouseover', function(e) {
			var save = e.target.myParam;
			for(var j = 0; j < save; j++) {
				etoile[j].className="color_yes etoile";
			}
		}, false);

		etoile[i].myParam = temp;

		etoile[i].addEventListener('mouseout', function(e) {
			var save = e.target.myParam;
			for(var j = 0; j < save; j++) {
				etoile[j].className="etoile";
			}
		}, false);
	}
</script>
