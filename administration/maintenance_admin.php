				<center>
					<span class="titre_commentaire_news" id="titre_grade">
						Gestion <span class="couleur_mangas">des</span> <span class="couleur_fans">maintenances</span>
					</span>
				</center>
		<?php 
				$siteMaintenance = isset ($_GET['siteMaintenance'] ) ? $_GET['siteMaintenance'] : '';
				$jeuxMaintenance = isset ($_GET['jeuxMaintenance'] ) ? $_GET['jeuxMaintenance'] : '';
				$mangasMaintenance = isset ($_GET['mangasMaintenance'] ) ? $_GET['mangasMaintenance'] : '';
				$blogsMaintenance = isset ($_GET['blogsMaintenance'] ) ? $_GET['blogsMaintenance'] : '';
				$voir = $pdo->query('SELECT * FROM maintenance')->fetch();
				?>
				<table class="table table-striped">
					<thread>
						<tr>
							<th>Statut</th>
							<th>Partie du site</th>
							<th>Action</th>
						</tr>
					</thead>
					<tr>
						<?php if($siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($siteMaintenance === 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_site'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } 
					if($siteMaintenance === 'oui'){
						$update = $pdo->query('UPDATE maintenance SET maintenance_site = 1, maintenance_jeux = 1, maintenance_mangas = 1, maintenance_blogs = 1');
						echo "Le site est en maintenance.<br/>";
					}
					else if($siteMaintenance === 'non')
					{
						$update = $pdo->query('UPDATE maintenance SET maintenance_site = 0, maintenance_jeux = 0, maintenance_mangas = 0, maintenance_blogs = 0');
						echo "Pas de maintenance pour le site.<br/>";
					}
					else if($voir['maintenance_site'] == 1) {
						echo "Le site est en maintenance.<br/>";
					}
					else
					{
						echo "Pas de maintenance pour le site.<br/>";
					}
					?>	
					</th>
					<?php if($siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($siteMaintenance === 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_site'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } ?>
						Maintenance du site.
					</th>
					<?php if($siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($siteMaintenance === 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_site'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } ?>
						<a href="?siteMaintenance=oui">Oui</a> - <a href="?siteMaintenance=non">Non</a><br/>
					</th>
					</tr>
					<tr>
					<?php if($jeuxMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($jeuxMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_jeux'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } 
					if($jeuxMaintenance === 'oui' || $siteMaintenance === 'oui'){
						$update = $pdo->query('UPDATE maintenance SET maintenance_jeux = 1');
						echo "La partie jeux du site est en maintenance.<br/>";
					}
					else if($jeuxMaintenance === 'non')
					{
						$update = $pdo->query('UPDATE maintenance SET maintenance_jeux = 0');
						echo "Pas de maintenance pour la partie jeux du site.<br/>";
					}
					else if($voir['maintenance_jeux'] == 1) {
						echo "La partie jeux du site est en maintenance.<br/>";
					}
					else
					{
						echo "Pas de maintenance pour la partie jeux du site.<br/>";
					}
					?>
					</th>
					<?php if($jeuxMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($jeuxMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_jeux'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } ?>
						Maintenance de la partie jeux.
					</th>
					<?php if($jeuxMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($jeuxMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_jeux'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } ?>
						<a href="?jeuxMaintenance=oui">Oui</a> - <a href="?jeuxMaintenance=non">Non</a><br/>
					</th>
				</tr>
				<tr>
					<?php if($mangasMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($mangasMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_mangas'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } 
					if($mangasMaintenance === 'oui' || $siteMaintenance === 'oui'){
						$update = $pdo->query('UPDATE maintenance SET maintenance_mangas = 1');
						echo "La partie des mangas est en maintenance.<br/>";
					}
					else if($mangasMaintenance === 'non')
					{
						$update = $pdo->query('UPDATE maintenance SET maintenance_mangas = 0');
						echo "Pas de maintenance pour la partie mangas du site.<br/>";
					}
					else if($voir['maintenance_mangas'] == 1) {
						echo "La partie mangas du site est en maintenance.<br/>";
					}
					else
					{
						echo "Pas de maintenance pour la partie mangas du site.<br/>";
					}
					?>
				</th>
				<?php if($mangasMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($mangasMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_mangas'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } ?>
					Maintenance de la partie mangas.
					</th>
					<?php if($mangasMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($mangasMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_mangas'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } ?>
						<a href="?mangasMaintenance=oui">Oui</a> - <a href="?mangasMaintenance=non">Non</a><br/>
					</th>
				</tr>
				<tr>
					<?php if($blogsMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($blogsMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_blogs'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } 
					if($blogsMaintenance === 'oui' || $siteMaintenance === 'oui'){
						$update = $pdo->query('UPDATE maintenance SET maintenance_blogs = 1');
						echo "Les blogs sont en maintenance.<br/>";
					}
					else if($blogsMaintenance === 'non')
					{
						$update = $pdo->query('UPDATE maintenance SET maintenance_blogs = 0');
						echo "Pas de maintenance pour les blogs du site.<br/>";
					}
					else if($voir['maintenance_blogs'] == 1) {
						echo "Les blogs du site sont maintenance.<br/>";
					}
					else
					{
						echo "Pas de maintenance pour les blogs du site.<br/>";
					}
				?>
				</th>
				<?php if($blogsMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($blogsMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_blogs'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } ?>
					Maintenance des blogs.
				</th>
				<?php if($blogsMaintenance === 'oui' || $siteMaintenance == 'oui'){?>
				      <th class="warning">
				      <?php } else if($blogsMaintenance === 'non' || $siteMaintenance == 'non') { ?>
				      	<th class="success">
				      	<?php } else if ($voir['maintenance_blogs'] == 1){ ?><th class="warning"> <?php } else {?><th class="success"> <?php } ?>
					<a href="?blogsMaintenance=oui">Oui</a> - <a href="?blogsMaintenance=non">Non</a>
				</th>
				</table>