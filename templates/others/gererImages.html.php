<h2 class="titre">Gérer les images de Mangas'Fan</h2>
<a href="index.php" class="btn btn-outline-info">Retourner à l'hébergeur d'images</a>
<hr>
<?php $nomRepertoire = "uploads";
if (is_dir($nomRepertoire)){
	$dossier = opendir($nomRepertoire);
	?>
	<div class="container-fluid">
		<div class="row justify-content-center">
			<?php while ($Fichier = readdir($dossier)){
				if ($Fichier != "." AND $Fichier != ".." AND (stristr($Fichier,'.gif') OR stristr($Fichier,'.jpg') OR stristr($Fichier,'.png') OR stristr($Fichier,'.jpeg'))){ ?>
					<div class="col-lg-2">
						<div class="card" style="width: 18rem;">
							<div class="card-body">
								<a target="_blank" href="<?= $nomRepertoire, '/',$Fichier ?>">
									<img src="<?= $nomRepertoire, '/',$Fichier ?>" width="100%">
								</a>
							</div>
							<div class="card-footer">
								<p>Image "<?= $Fichier ?>"</p>
								<form method="POST" action="">
									<input type="hidden" name="supprimer_image" value="<?= $Fichier ?>">
									<input type="submit" name="delete" value="Supprimer cette image" class="btn btn-outline-danger" onclick="return window.confirm(`Êtes vous sûr de vouloir supprimer cette image ?`)">
								</form>
							</div>
						</div>
					</div>
				<?php } 
			}?>
		</div>
	</div>
<?php } ?>
