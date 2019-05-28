?php 
	session_start();
	require_once '../inc/base.php';

	$temps_actuel = date("U");
	$user = $pdo->prepare("SELECT * FROM users WHERE username = ?");
	$user->execute(array($_SESSION['auth']['username']));
	$membre = $user->fetch();

   	$update_ip = $pdo->prepare('UPDATE qeel SET time_co = ? WHERE membre = ?');
   	$update_ip->execute(array($temps_actuel, $membre['username']));
   
?>