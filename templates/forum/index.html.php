<?php if (isset($_SESSION['auth']) && $user['grade'] >= 7) { ?>
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
<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#ajouterForum">
	Ajouter un nouveau forum
</button>
<div class="modal fade" id="ajouterForum" tabindex="-1" role="dialog" aria-labelledby="ajouterForumTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Ajout d'un nouveau forum</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="">
					<div class="row">
						<div class="col-lg-3">
							Titre du forum :
						</div>
						<div class="col-lg-9">
							<input type="text" name="titreForum" class="form-control" placeholder="Saisir le titre de votre forum" />
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							Description du forum :
						</div>
						<div class="col-lg-9">
							<input type="text" name="descriptionForum" class="form-control" placeholder="Saisir la description de votre forum !" />
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							Catégorie concernée :
						</div>
						<div class="col-lg-9">
							<select class="form-control" name="addCategorie">
								<?php foreach ($categories as $categorie): ?>
									<option value="<?= \Rewritting::sanitize($categorie['id']) ?>"><?= \Rewritting::sanitize($categorie['name']) ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							Permission pour voir le forum :
						</div>
						<div class="col-lg-9">
							<select class="form-control" name="addPermission">
								<option value="0">Tout le monde</option>
								<option value="1">Membre</option>
								<option value="2">Community Manager</option>
								<option value="3">Animateur</option>
								<option value="4">Newseur</option>
								<option value="5">Rédacteur</option>
								<option value="6">Modérateur</option>
								<option value="7">Développeur</option>
								<option value="8">Administrateur</option>
							</select>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-lg-3">
							Status du forum :
						</div>
						<div class="col-lg-9">
							<select class="form-control" name="statusForum">
								<option value="0">Déverrouillé</option>
								<option value="1">Verrouillé</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Fermer</button>
						<input type="submit" name="addForum" class="btn btn-outline-primary" value="Poster le forum" />
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<h1 class="titre">Index du forum</h1>
<hr>
<div class="alert alert-info">
	<strong>Information : </strong>Le forum est tout neuf et peu donc comporter de nombreux bugs, nous comptons sur vous pour les signaler dans la section appropriée !
</div>
<table class="table">
	<?php
	$categorie = 0;
	foreach ($forums as $category):
		if ((isset($_SESSION['auth']) && $user['grade'] >= $category['permission']) || ($user == NULL AND $category['permission'] == 0) || isset($_SESSION['auth']) && $user['grade'] > 7) {
			if (!empty($user)){
				$controller = new \models\Forum();
				$test = $controller->chercher($category['forum_id']);
				if ($category['tv_poste'] == 0 && $test[0] != 0){
					if ($category['tv_post_id'] == $category['topic_last_post']){
						if ($category['forum_locked'] == 0) {
							$ico_mess = 'lu.png';
						} else {
							$ico_mess = 'locked.png';
						}
					} else {
						if ($category['forum_locked'] == 0) {
							$ico_mess = 'nonlu.png';
						} else {
							$ico_mess = 'locknonlu.png';
						}
					}
				} elseif ($test[0] == 0) {
					if ($test[0] == 0) {
						if ($category['forum_locked'] == 0) {
							$ico_mess = 'lu.png';
						} else {
							$ico_mess = 'locked.png';
						}
					} else {
						if ($category['forum_locked'] == 0) {
							$ico_mess = 'nonlu.png';
						} else {
							$ico_mess = 'locknonlu.png';
						}
					}
				} else {
					if ($test[0] == 0 && $category['tv_post_id'] == $category['topic_last_post']){
						if ($category['forum_locked'] == 0) {
							$ico_mess = 'nonlu.png';
						} else {
							$ico_mess = 'locknonlu.png';
						}
					} else {
						if ($category['forum_locked'] == 0) {
							$ico_mess = 'nonlu.png';
						} else {
							$ico_mess = 'locknonlu.png';
						}
					}
				}

			} else {
				if ($category['forum_locked'] == 0) {
					$ico_mess = 'lu.png';
				} else {
					$ico_mess = 'locked.png';
				}
			}
			if ($categorie != $category['id']) {
				$categorie = $category['id'];
				?>
				<tr class="table-info">
					<th></th>
					<th><?= \Rewritting::sanitize($category['name']); ?></th>             
					<th class="tableau_fofo">Sujets</th>       
					<th class="tableau_fofo">Messages</th>       
					<th>Dernier message</th>   
				</tr>
			<?php } ?>
			<tr>
				<td><img src="../images/<?= \Rewritting::sanitize($ico_mess) ?>" width="75"/></td>
				<td>
					<a href="./voirforum.php?f=<?= \Rewritting::sanitize($category['forum_id']) ?>"><?= \Rewritting::sanitize($category['forum_name']) ?></a><br/>
					<span class="tableau_fofo"><em><?= \Rewritting::sanitize($category['forum_description']) ?></em></span>
				</td>
				<td class="tableau_fofo"><?= \Rewritting::sanitize($category['forum_topic']) ?></td>
				<td class="tableau_fofo"><?= \Rewritting::sanitize($category['forum_post']) ?></td>
				<td><?php if (!empty($category['forum_post'])){ ?>
					Posté le <?= date('d/m/Y à H:i', strtotime($category['date_created'])) ?> dans <a href="voirtopic.php?t=<?= \Rewritting::sanitize($category['id_topic']) ?>"><?= \Rewritting::sanitize($category['topic_titre']) ?></a>	<br/>
					par <a href="../membres/profil-<?= \Rewritting::sanitize($category['id_utilisateur']) ?>" style="color: <?= \Color::rang_etat($category['grade']) ?>"><?= \Rewritting::sanitize($category['username']) ?></a> - 
					<a href="voirtopic.php?t=<?= \Rewritting::sanitize($category['id_topic']) ?>#<?= \Rewritting::sanitize($category['id_message']) ?>">Accéder au message</a>
				<?php } else { ?>
					Pas de message
				<?php } ?>
			</td>
		</tr>
	<?php } 
endforeach; ?>
</table>

