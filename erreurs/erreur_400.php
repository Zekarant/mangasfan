<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Mangas'Fan - Erreur 400</title>
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
					<h1>Erreur 400 : Erreur d'analyse</h1>
					<img src="../images/400.png" alt="Erreur 400" title="Erreur 400 : L'analyse HTTP n'est pas arrivée à terme" class="image_erreur" />
					<div class="explication_erreur">
						Il semblerait qu'on ait eu un problème avec l'analyse ! Veuillez rafraichir la page.
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
