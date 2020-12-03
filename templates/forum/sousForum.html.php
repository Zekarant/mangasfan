<p><em>Votre localisation : <a href="../forum">Accueil du forum</a> -> <?= $sousForum['forum_name'] ?></em></p>
<hr>
<h2 class="titre"><?= \Rewritting::sanitize($sousForum['forum_name']) ?></h2>
<hr>
<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#ajoutSection">
	Ajouter une nouvelle section
</button>
<div class="modal" id="ajoutSection" tabindex="-1" role="dialog" aria-labelledby="ajoutSectionTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLongTitle">Ajout d'un nouveau topic</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<form method="POST" action="">
						<div class="row">
							<div class="col-lg-4">
								Titre du topic :
							</div>
							<div class="col-lg-8">
								<input type="text" name="titleTopic" class="form-control" placeholder="Saisir le titre de votre topic" />
								<br/>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4">
								Type de topic :
							</div>
							<div class="col-lg-8">
								<select name="typeTopic" class="form-control">
									<option value="normal">Normal</option>
									<option value="annonce">Annonce</option>
								</select>
								<br/>
							</div>
						</div>
						<div class="row">
							<label>Message :</label>
							<textarea class="form-control" name="messageTopic"></textarea>
							<br/>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Fermer</button>
							<input type="submit" name="topicValider" class="btn btn-outline-primary" value="Poster le topic et mon message" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<br/><br/>
<?php if ($sujets != NULL) { ?>
	<table class="table">
		<thead class="table-info">
			<th></th>
			<th><strong>Titre</strong></th>             
			<th><strong>Réponses</strong></th>
			<th><strong>Vues</strong></th>
			<th><strong>Auteur</strong></th>                       
			<th><strong>Dernier message</strong></th>
		</thead>
		<tbody>
			<?php foreach ($sujets as $sujet): 
				if (!empty($user)){
					if ($sujet['tv_id'] == $user['id_user']){
						if ($sujet['tv_poste'] == '0'){
							if ($sujet['tv_post_id'] == $sujet['topic_last_post']){
								$ico_mess = 'lu.png';
							} else {
								$ico_mess = 'nonlu.png';
							}
						} else {
							if ($sujet['tv_post_id'] == $sujet['topic_last_post']){
								$ico_mess = 'nonlu.png';
							} else {
								$ico_mess = 'nonlu.png';
							}
						}
					} else {
						$ico_mess = 'nonlu.png';
					}
				} else {
					$ico_mess = 'lu.png';
				}
				?>		
				<tr>
					<td><img src="../images/<?= $ico_mess ?>" width="75"/></td>
					<td>
						<a href="./voirtopic.php?t=<?= $sujet['id_topic'] ?>" title="Topic commencé à <?= date('H\hi \l\e d M y', strtotime($sujet['topic_posted'])) ?>">
							[Annonce] <?= stripslashes(htmlspecialchars($sujet['topic_titre'])) ?>
						</a>
					</td>
					<td class="nombremessages"><?= $sujet['topic_post'] ?></td>
					<td><?= $sujet['topic_vu'] ?></td>
					<td>
						<a href="../membres/profil-<?= $sujet['id_utilisateur_posteur'] ?>"><?= htmlspecialchars($sujet['membre_pseudo_createur']) ?></a>
					</td>
					<td><a href="./voirtopic.php?t=<?= $sujet['id_topic'] ?>">[Annonce] <?= stripslashes(htmlspecialchars($sujet['topic_titre'])) ?></a><br/>
						Par <a href="../membres/profil-<?= $sujet['id_utilisateur_derniere_reponse'] ?>"><?= htmlspecialchars($sujet['membre_pseudo_last_posteur']) ?></a>
						le <?= date('H\hi \l\e d M y', strtotime($sujet['date_created'])) ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<hr>
<?php } if ($sujetsNormaux == NULL) { ?>
	<div class="alert alert-info">
		Aucun sujet n'est actuellement posté dans ce forum !
	</div>
<?php } else { ?>
	<table class="table">
		<thead>
			<th></th>
			<th><strong>Titre</strong></th>             
			<th><strong>Réponses</strong></th>
			<th><strong>Vues</strong></th>
			<th><strong>Auteur</strong></th>                       
			<th><strong>Dernier message</strong></th>
		</thead>
		<tbody>
			<?php foreach ($sujetsNormaux as $sujet): 
				if (!empty($user)){
					if ($sujet['tv_id'] == $user['id_user']){
						if ($sujet['tv_poste'] == '0'){
							if ($sujet['tv_post_id'] == $sujet['topic_last_post']){
								$ico_mess = 'lu.png';
							} else {
								$ico_mess = 'nonlu.png';
							}
						} else {
							if ($sujet['tv_post_id'] == $sujet['topic_last_post']){
								$ico_mess = 'nonlu.png';
							} else {
								$ico_mess = 'nonlu.png';
							}
						}
					} else {
						$ico_mess = 'lu.png';
					}
				} else {
					$ico_mess = 'lu.png';
				}?>
				<tr>
					<td><img src="../images/<?= $ico_mess ?>" width="75"/></td>
					<td>
						<a href="./voirtopic.php?t=<?= $sujet['id_topic'] ?>" title="Topic commencé à <?= date('H\hi \l\e d M y', strtotime($sujet['topic_posted'])) ?>">
							<?= stripslashes(htmlspecialchars($sujet['topic_titre'])) ?>
						</a>
					</td>
					<td class="nombremessages"><?= $sujet['topic_post'] ?></td>
					<td><?= $sujet['topic_vu'] ?></td>
					<td>
						<a href="../membres/profil-<?= $sujet['id_utilisateur_posteur'] ?>"><?= htmlspecialchars($sujet['membre_pseudo_createur']) ?></a>
					</td>
					<td><a href="./voirtopic.php?t=<?= $sujet['id_topic'] ?>"><?= stripslashes(htmlspecialchars($sujet['topic_titre'])) ?></a><br/>
						Par <a href="../membres/profil-<?= $sujet['id_utilisateur_derniere_reponse'] ?>"><?= htmlspecialchars($sujet['membre_pseudo_last_posteur']) ?></a>
						le <?= date('H\hi \l\e d M y', strtotime($sujet['date_created'])) ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php } ?>