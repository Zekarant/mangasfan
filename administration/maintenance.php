<?php

const IP_ADMIN = '78.231.160.226';
const FILEPATH_MAINTENANCE = '../inc/data/maintenance';

$existe = (@file_exists(FILEPATH_MAINTENANCE)? 'oui' : 'non');

$estMaintenance = isset ( $_GET['estMaintenance'] ) ? $_GET['estMaintenance'] : '';

if( $estMaintenance !== ''){

	@unlink(FILEPATH_MAINTENANCE);

	if( $estMaintenance === 'oui' ){
		file_put_contents(FILEPATH_MAINTENANCE, IP_ADMIN);
	}

	header('Location: ?');

	exit;
}

?>
Site en maintenance : <?php echo $existe;  ?><br />
<?php 
	if( 'oui' === $existe ){
?>
	Adresse IP autorisée : <?php echo file_get_contents(FILEPATH_MAINTENANCE); ?><br />
<?php
	}
?>
Modifier l'état : <a href="?estMaintenance=non">terminer la maintenance</a> <a href="?estMaintenance=oui">passer en maintenance</a>