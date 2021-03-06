<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mangas'Fan - Erreur 502</title>
	<link rel="icon" href="../images/favicon.png"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="../style.css">
</head>
<body id="erreur">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="bloc_erreur">
					<h1>Erreur 502 : Mauvaise passerelle !</h1>
					<img src="../images/502.png" alt="Erreur 502" title="Erreur 502 : Aïe, on a pas prit la bonne passerelle !" class="image_erreur" />
					<div class="explication_erreur">
						On s'est trompé de passerelle, on doit retourner à l'accueil pour prendre la bonne !
					</div>
					<div class="boutons_erreurs">
						<a href="../" class="btn btn-primary">
							Retourner à l'accueil
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>	
</html>
