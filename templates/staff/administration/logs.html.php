<div class="container-fluid contenu">
	<div class="row">
		<?php include('navigation_admin.php'); ?>
		<div class="col-lg-10">
			<h2 class="titre">Derniers logs du site</h2>
			<hr>
			<div class="card">
				<div class="card-header">
					Logs du site
				</div>
				<ul class="list-group list-group-flush">
					<?php foreach($logs as $log): ?>
						<li class="list-group-item"><strong><?= \Rewritting::sanitize($log['area_website']) ?></strong> : <?= \Rewritting::sanitize($log['username']) ?> <?= htmlspecialchars_decode(\Rewritting::sanitize($log['contenu'])) ?> le <?= date('d/m/Y à H:i', strtotime(\Rewritting::sanitize($log['logs_date']))); ?></li>
					<?php endforeach; ?>	
				</ul>
			</div>
		</div>
	</div>
</div>