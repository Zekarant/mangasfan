<?php
session_start();
include('../membres/base.php');
include('../membres/functions.php');
if (empty($_SESSION['auth'])) {
	header('Location: ../');
	exit();
}
if (isset($_SESSION['auth'])) {
	if ($utilisateur['grade'] <= 2 || $utilisateur['grade'] <= 6 && $utilisateur['chef'] == 0) {
		header('Location: ../');
		exit();
	}
}
if ($utilisateur['grade'] == 3) {
	if (isset($_POST['activer'])) {
		if ($utilisateur['grade'] == 3 && $utilisateur['chef'] == 3) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 1 WHERE link = "animateurs"');
			$modifier_status->execute();
			$couleur = "success";
			$texte = "Le recrutement des animateurs a bien été activé !";
		}
	}
	if (isset($_POST['desactiver'])) {
		if ($utilisateur['grade'] == 3 && $utilisateur['chef'] == 3) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 0 WHERE link = "animateurs"');
			$modifier_status->execute();
			$couleur = "success";
			$texte = "Le recrutement des animateurs a bien été désactivé !";
		}
	}
	$recuperer_recrutements = $pdo->prepare('SELECT * FROM recrutements WHERE link = "animateurs"');
	$recuperer_recrutements->execute();
	$recrutements = $recuperer_recrutements->fetch();
} elseif ($utilisateur['grade'] == 4) {
	if (isset($_POST['activer'])) {
		if ($utilisateur['grade'] == 4 && $utilisateur['chef'] == 4) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 1 WHERE link = "community-manager"');
			$modifier_status->execute();
			$couleur = "success";
			$texte = "Le recrutement des community manager a bien été activé !";
		}
	}
	if (isset($_POST['desactiver'])) {
		if ($utilisateur['grade'] == 4 && $utilisateur['chef'] == 4) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 0 WHERE link = "community-manager"');
			$modifier_status->execute();
			$couleur = "success";
			$texte = "Le recrutement des community manager a bien été désactivé !";
		}
	}
	$recuperer_recrutements = $pdo->prepare('SELECT * FROM recrutements WHERE link = "community-manager"');
	$recuperer_recrutements->execute();
	$recrutements = $recuperer_recrutements->fetch();
} elseif ($utilisateur['grade'] == 5) {
	if (isset($_POST['activer'])) {
		if ($utilisateur['grade'] == 5 && $utilisateur['chef'] == 5) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 1 WHERE link = "newseurs"');
			$modifier_status->execute();
			$couleur = "success";
			$texte = "Le recrutement des newseurs a bien été activé !";
		}
	}
	if (isset($_POST['desactiver'])) {
		if ($utilisateur['grade'] == 5 && $utilisateur['chef'] == 5) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 0 WHERE link = "newseurs"');
			$modifier_status->execute();
			$couleur = "success";
			$texte = "Le recrutement des newseurs a bien été désactivé !";
		}
	}
	$recuperer_recrutements = $pdo->prepare('SELECT * FROM recrutements WHERE link = "newseurs"');
	$recuperer_recrutements->execute();
	$recrutements = $recuperer_recrutements->fetch();
} elseif ($utilisateur['grade'] == 6) {
	if (isset($_POST['activer'])) {
		if ($utilisateur['grade'] == 6 && $utilisateur['chef'] == 6) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 1 WHERE link = "redacteurs"');
			$modifier_status->execute();
			$couleur = "success";
			$texte = "Le recrutement des rédacteurs a bien été activé !";
		}
	}
	if (isset($_POST['desactiver'])) {
		if ($utilisateur['grade'] == 6 && $utilisateur['chef'] == 6) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 0 WHERE link = "redacteurs"');
			$modifier_status->execute();
			$couleur = "success";
			$texte = "Le recrutement des rédacteurs a bien été désactivé !";
		}
	}
	$recuperer_recrutements = $pdo->prepare('SELECT * FROM recrutements WHERE link = "redacteurs"');
	$recuperer_recrutements->execute();
	$recrutements = $recuperer_recrutements->fetch();
} elseif ($utilisateur['grade'] == 7) {
	if (isset($_POST['activer'])) {
		if ($utilisateur['grade'] == 7) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 1 WHERE link = ?');
			$modifier_status->execute(array($_POST['id']));
			$couleur = "success";
			$texte = "Le recrutement a bien été activé !";
		}
	}
	if (isset($_POST['desactiver'])) {
		if ($utilisateur['grade'] == 7) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 0 WHERE link = ?');
			$modifier_status->execute(array($_POST['id']));
			$couleur = "success";
			$texte = "Le recrutement a bien été désactivé !";
		}
	}
	$recuperer_recrutements = $pdo->prepare('SELECT * FROM recrutements WHERE link != "developpeurs" && link != "administrateurs"');
	$recuperer_recrutements->execute();
} elseif ($utilisateur['grade'] == 8) {
	if (isset($_POST['activer'])) {
		if ($utilisateur['grade'] == 8) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 1 WHERE link = ?');
			$modifier_status->execute(array($_POST['id']));
			$couleur = "success";
			$texte = "Le recrutement a bien été activé !";
		}
	}
	if (isset($_POST['desactiver'])) {
		if ($utilisateur['grade'] == 8) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 0 WHERE link = ?');
			$modifier_status->execute(array($_POST['id']));
			$couleur = "success";
			$texte = "Le recrutement a bien été désactivé !";
		}
	}
	$recuperer_recrutements_dev = $pdo->prepare('SELECT * FROM recrutements WHERE link != "administrateurs"');
	$recuperer_recrutements_dev->execute();
} elseif ($utilisateur['grade'] >= 9) {
	if (isset($_POST['activer'])) {
		if ($utilisateur['grade'] >= 9) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 1 WHERE link = ?');
			$modifier_status->execute(array($_POST['id']));
			$couleur = "success";
			$texte = "Le recrutement a bien été activé !";
		}
	}
	if (isset($_POST['desactiver'])) {
		if ($utilisateur['grade'] >= 9) {
			$modifier_status = $pdo->prepare('UPDATE recrutements SET recrutement = 0 WHERE link = ?');
			$modifier_status->execute(array($_POST['id']));
			$couleur = "success";
			$texte = "Le recrutement a bien été désactivé !";
		}
	}
	$recuperer_recrutements_admin = $pdo->prepare('SELECT * FROM recrutements');
	$recuperer_recrutements_admin->execute();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Gestion des recrutements - Mangas'Fan</title>
	<link rel="icon" href="../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="../style.css" />
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="bg-recrutements">
		<?php include('../elements/navigation_principale.php'); ?>
		<h1 class="titre-principal-recrutements">Accès Staff - Gestion des recrutements</h1>
	</div>
	<section>
		<div class="alert alert-info">
			<strong>Important :</strong> Chers membres du staff, plus exactement, chers chef de groupe ! Vous avez tous accès à cette page dans le but de pouvoir gérer vos recrutements ! Ouvrez-les et fermez-les quand vous en avez envie !
		</div>
		<?php if (isset($_POST['activer']) || isset($_POST['desactiver'])) { ?>
			<div class="alert alert-<?= sanitize($couleur); ?>" role="alert">
				<?= sanitize($texte); ?>
			</div>
		<?php } ?>
		<table class="table table-bordered">
			<thead>
				<th>Nom du recrutement</th>
				<th>Status du recrutement</th>
				<th>Action</th>
			</thead>
			<tbody>
				<?php if ($utilisateur['grade'] == 3) {
					if ($recrutements['recrutement'] == 1) { ?>
						<tr class="table-success">
							<td>Recrutement des animateurs</td>
							<td><strong>Recrutements ouverts</strong></td>
							<td>
								<form method="POST" action="">
									<input type="submit" name="desactiver" class="btn btn-outline-danger" value="Fermer">
								</form>
							</td>
						</tr>
					<?php } else { ?>
						<tr class="table-warning">
							<td>Recrutement des animateurs</td>
							<td><strong>Recrutements fermés</strong></td>
							<td>
								<form method="POST" action="">
									<input type="submit" name="activer" class="btn btn-outline-success" value="Ouvrir">	
								</form>
							</td>
						</tr>
					<?php }
				} elseif ($utilisateur['grade'] == 4) {
					if ($recrutements['recrutement'] == 1) { ?>
						<tr class="table-success">
							<td>Recrutement des community manager</td>
							<td><strong>Recrutements ouverts</strong></td>
							<td>
								<form method="POST" action="">
									<input type="submit" name="desactiver" class="btn btn-outline-danger" value="Fermer">	
								</form>
							</td>
						</tr>
					<?php } else { ?>
						<tr class="table-warning">
							<td>Recrutement des community manager</td>
							<td><strong>Recrutements fermés</strong></td>
							<td>
								<form method="POST" action="">
									<input type="submit" name="activer" class="btn btn-outline-success" value="Ouvrir">	
								</form>
							</td>
						</tr>
					<?php }
				} elseif ($utilisateur['grade'] == 5) {
					if ($recrutements['recrutement'] == 1) { ?>
						<tr class="table-success">
							<td>Recrutement des newseurs</td>
							<td><strong>Recrutements ouverts</strong></td>
							<td>
								<form method="POST" action="">
									<input type="submit" name="desactiver" class="btn btn-outline-danger" value="Fermer">	
								</form>
							</td>
						</tr>
					<?php } else { ?>
						<tr class="table-warning">
							<td>Recrutement des newseurs</td>
							<td><strong>Recrutements fermés</strong></td>
							<td>
								<form method="POST" action="">
									<input type="submit" name="activer" class="btn btn-outline-success" value="Ouvrir">	
								</form>
							</td>
						</tr>
					<?php }
				} elseif ($utilisateur['grade'] == 6) {
					if ($recrutements['recrutement'] == 1) { ?>
						<tr class="table-success">
							<td>Recrutement des rédacteurs</td>
							<td><strong>Recrutements ouverts</strong></td>
							<td>
								<form method="POST" action="">
									<input type="submit" name="desactiver" class="btn btn-outline-danger" value="Fermer">	
								</form>
							</td>
						</tr>
					<?php } else { ?>
						<tr class="table-warning">
							<td>Recrutement des rédacteurs</td>
							<td><strong>Recrutements fermés</strong></td>
							<td>
								<form method="POST" action="">
									<input type="submit" name="activer" class="btn btn-outline-success" value="Ouvrir">	
								</form>
							</td>
						</tr>
					<?php }
				} elseif ($utilisateur['grade'] == 7) {
					while($recrutements = $recuperer_recrutements->fetch()){
						if ($recrutements['recrutement'] == 1) { ?>
							<tr class="table-success">
								<td>Recrutement <?= sanitize($recrutements['name']); ?></td>
								<td><strong>Recrutements ouverts</strong></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="id" class="btn btn-outline-danger" value="<?= sanitize($recrutements['link']); ?>">
										<input type="submit" name="desactiver" class="btn btn-outline-danger" value="Fermer">	
									</form>
								</td>
							</tr>
						<?php } else { ?>
							<tr class="table-warning">
								<td>Recrutement <?= sanitize($recrutements['name']); ?></td>
								<td><strong>Recrutements fermés</strong></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="id" class="btn btn-outline-danger" value="<?= sanitize($recrutements['link']); ?>">
										<input type="submit" name="activer" class="btn btn-outline-success" value="Ouvrir">	
									</form>
								</td>
							</tr>
						<?php }
					}
				} elseif ($utilisateur['grade'] == 8) {
					while($recrutements = $recuperer_recrutements_dev->fetch()){
						if ($recrutements['recrutement'] == 1) { ?>
							<tr class="table-success">
								<td>Recrutement <?= sanitize($recrutements['name']); ?></td>
								<td><strong>Recrutements ouverts</strong></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="id" class="btn btn-outline-danger" value="<?= sanitize($recrutements['link']); ?>">
										<input type="submit" name="desactiver" class="btn btn-outline-danger" value="Fermer">	
									</form>
								</td>
							</tr>
						<?php } else { ?>
							<tr class="table-warning">
								<td>Recrutement <?= sanitize($recrutements['name']); ?></td>
								<td><strong>Recrutements fermés</strong></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="id" class="btn btn-outline-danger" value="<?= sanitize($recrutements['link']); ?>">
										<input type="submit" name="activer" class="btn btn-outline-success" value="Ouvrir">	
									</form>
								</td>
							</tr>
						<?php }
					}
				} elseif ($utilisateur['grade'] >= 9) {
					while($recrutements = $recuperer_recrutements_admin->fetch()){
						if ($recrutements['recrutement'] == 1) { ?>
							<tr class="table-success">
								<td>Recrutement <?= sanitize($recrutements['name']); ?></td>
								<td><strong>Recrutements ouverts</strong></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="id" class="btn btn-outline-danger" value="<?= sanitize($recrutements['link']); ?>">
										<input type="submit" name="desactiver" class="btn btn-outline-danger" value="Fermer">	
									</form>
								</td>
							</tr>
						<?php } else { ?>
							<tr class="table-warning">
								<td>Recrutement <?= sanitize($recrutements['name']); ?></td>
								<td><strong>Recrutements fermés</strong></td>
								<td>
									<form method="POST" action="">
										<input type="hidden" name="id" class="btn btn-outline-danger" value="<?= sanitize($recrutements['link']); ?>">
										<input type="submit" name="activer" class="btn btn-outline-success" value="Ouvrir">	
									</form>
								</td>
							</tr>
						<?php }
					}
				}
				?>
			</tbody>
		</table>
	</section>
	<?php include('../elements/footer.php'); ?>
</body>
</html>