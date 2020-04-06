<?php
session_start();
require_once '../membres/base.php';
include('../membres/functions.php');
if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
	$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
	$user->execute(array($_SESSION['auth']['id']));
	$utilisateur = $user->fetch(); 
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Forum - Mangas'Fan</title>
</head>
<body>
	<?php 
	if (!isset($_SESSION['auth'])){
		?>
		<div class='alert alert-danger' role='alert'>
			Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
		</div>
		<?php
	}
	elseif (isset($_SESSION['auth']) AND $utilisateur['grade'] < 3) {
		?>
		<div class='alert alert-danger' role='alert'>
			Vous ne pouvez pas accéder à cette page. <a href="../index.php">Retourner sur l'index</a>.
		</div>
		<?php
	}
	else { ?>
		<p>Forum encore en construction.</p>

	<?php } ?>
</body>
</html>