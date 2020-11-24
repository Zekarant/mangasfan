<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#ajoutSection">
	Ajouter une nouvelle section
</button>
<div class="modal fade" id="ajoutSection" tabindex="-1" role="dialog" aria-labelledby="ajoutSectionTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Ajout d'une nouvelle section</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="">
					<div class="row">
						<div class="col-lg-5">
							Nom de la section :
						</div>
						<div class="col-lg-7">
							<input type="text" name="sectionName" class="form-control" placeholder="Saisir le titre de votre sujet" />
							<br/>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Fermer</button>
						<input type="submit" name="sectionSubmit" class="btn btn-outline-primary" value="Poster la section" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#ajoutCatego">
	Ajouter une nouvelle catégorie
</button>
<div class="modal fade" id="ajoutCatego" tabindex="-1" role="dialog" aria-labelledby="ajoutCategoTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Ajout d'une nouvelle catégorie</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="">
					<div class="row">
						<div class="col-lg-3">
							Titre de la catégorie :
						</div>
						<div class="col-lg-9">
							<input type="text" name="titreCategorie" class="form-control" placeholder="Saisir le titre de votre catégorie" />
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							Section concernée :
						</div>
						<div class="col-lg-9">
							<select class="form-control" name="categoriesAdd">
								<?php foreach ($recupererCategoriesPrincipales as $categoriesPrincipales): ?>
									<option value="<?= \Rewritting::sanitize($categoriesPrincipales['id']) ?>"><?= \Rewritting::sanitize($categoriesPrincipales['name']) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<br/>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Fermer</button>
						<input type="submit" name="categorieSubmit" class="btn btn-outline-primary" value="Poster la catégorie" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addsousCategorie">
	Ajouter une nouvelle sous-catégorie
</button>
<div class="modal fade" id="addsousCategorie" tabindex="-1" role="dialog" aria-labelledby="addsousCategorieTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Ajout d'une nouvelle sous-catégorie</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="">
					<div class="row">
						<div class="col-lg-3">
							Titre de la sous-catégorie :
						</div>
						<div class="col-lg-9">
							<input type="text" name="titreSousCategorie" class="form-control" placeholder="Saisir le titre de votre sous-catégorie" />
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							Catégorie concernée :
						</div>
						<div class="col-lg-9">
							<select class="form-control" name="sousCategoriesAdd">
								<?php foreach ($recupererSousCategoriesPrincipales as $sousCategorie): ?>
									<option value="<?= \Rewritting::sanitize($sousCategorie['id']) ?>"><?= \Rewritting::sanitize($sousCategorie['name']) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<br/>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Fermer</button>
						<input type="submit" name="sousCategorieSubmit" class="btn btn-outline-primary" value="Poster la sous-catégorie" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<h1 class="titre">Index du forum</h1>
<hr>
<table class="table">
	<?php foreach($categories AS $titleCategory):
		if ($titleCategory['parents'] == 0): ?>
			<tr class="table-info">
				<th><?= \Rewritting::sanitize($titleCategory['name']) ?></th>
				<th>Messages</th>
				<th>Dernier message</th>
			</tr>
			<?php
			foreach($categories AS $category): 
				if($titleCategory['id'] == $category['parents']): ?>
					<tr>
						<td>
							<h4><a href="<?= \Rewritting::sanitize($category['slug']) ?>"><?= \Rewritting::sanitize($category['name']); ?></a></h4>
							<?php $sousCategorie = "";
							foreach($categories as $cats):
								if($category['id'] == $cats['parents']):
									$sousCategorie .=  "<a href='". \Rewritting::sanitize($category['slug']) . "/" . \Rewritting::sanitize($cats['slug']) ."'>" . \Rewritting::sanitize($cats['name']) . "</a> - ";
								endif;
							endforeach; 
							$sousCategorie = substr($sousCategorie, 0, -3); ?>
							<?= $sousCategorie; ?>
						</td>
						<td>5 messages</td>
						<td>Posté par <a href="#">Admin</a></td>
					</tr>
				<?php endif;
			endforeach;
		endif;
	endforeach; ?>
</table>

