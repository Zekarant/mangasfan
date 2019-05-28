<?php 
	function syst_not($nbr){
		if($nbr < 2){
			return "red";
		} else if($nbr >= 2 && $nbr < 3){
			return "orange";
		} else if($nbr >= 3 && $nbr < 4){
			return "green";
		} else {
			return "darkgreen";
		}
	}

	function use_note($pdo,$id_elt,$type_elt){
		$moyenne_note = $pdo->query("SELECT * FROM note_members WHERE type_elt = '$type_elt' AND id_elt = '$id_elt'");
			if($moyenne_note->rowCount() != 0){
				$somme_note = $pdo->query("SELECT SUM(val_note) AS sum_notes FROM note_members WHERE type_elt = '$type_elt' AND id_elt = '$id_elt'")->fetch();
				$vote = ($moyenne_note->rowCount()>1) ? " votes" : " vote";
				$rst_moy = round($somme_note['sum_notes'] / $moyenne_note->rowCount(),2);

				echo '<center><span id="titre_newsp" style="text-align:left;padding-left:8px;font-size:22px;">Note : <span style="color:'.syst_not($rst_moy).'">'.$rst_moy.' / 5</span></span> (<i style=\"padding-left:8px\">'.$moyenne_note->rowCount().$vote.'</i>)</center>';
			} else {
				echo "<center><b>Pas de note pour l'instant</b></center>";
			}
		if($type_elt == "anime" || $type_elt == "manga" || $type_elt == "jeux"){ // verification non nécessaire
			if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
				{ 
        			$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        			$user->execute(array($_SESSION['auth']['id']));
        			$utilisateur = $user->fetch(); 
				$vote_pst = $pdo->prepare("SELECT * FROM note_members WHERE member = ? AND type_elt = ? AND id_elt = ?");
				$vote_pst->execute(array($utilisateur['username'], $type_elt, $id_elt));
				if ($vote_pst->rowCount() == 0){ 
					$vote = true;
					$vote_ok = $pdo->prepare("INSERT INTO note_members(member,val_note,type_elt,id_elt) VALUES(?,?,?,?)");
					if(isset($_POST['etoile1'])){
						$vote_ok->execute(array($utilisateur['username'],1,$type_elt,$id_elt));
						$vote = false;
					} elseif (isset($_POST['etoile2'])){
						$vote_ok->execute(array($utilisateur['username'],2,$type_elt,$id_elt));
						$vote = false;
					} elseif (isset($_POST['etoile3'])){
						$vote_ok->execute(array($utilisateur['username'],3,$type_elt,$id_elt));
						$vote = false;
					} elseif (isset($_POST['etoile4'])){
						$vote_ok->execute(array($utilisateur['username'],4,$type_elt,$id_elt));
						$vote = false;
					} elseif (isset($_POST['etoile5'])){
						$vote_ok->execute(array($utilisateur['username'],5,$type_elt,$id_elt));
						$vote = false;
					}?>
					<center><form method="POST" action="">
					    <input type="submit" name="etoile1" value="★" class="color_no etoile" />
					    <input type="submit" name="etoile2" value="★" class="color_no etoile" />
					    <input type="submit" name="etoile3" value="★" class="color_no etoile" />
					    <input type="submit" name="etoile4" value="★" class="color_no etoile" />
					    <input type="submit" name="etoile5" value="★" class="color_no etoile" />
					</form></center>

					<style>
						input[type="submit"]{
							display:inline-block;
							background:none;
							width:25px;
							height:25px;
							border:none;
							padding:none;
							margin:none;
						}

						.color_yes{
							color:orange !important;
						}

						.color_no{
							color:black;
						}
					</style>

					<script>
						var etoile = document.getElementsByClassName('etoile');

						for(var i = 0; i < etoile.length; i++) {
							var temp = i+1;

						  etoile[i].addEventListener('mouseover', function(e) {
						   var save = e.target.myParam;
						  	for(var j = 0; j < save; j++) {
						    	etoile[j].className="color_yes etoile";
						    }
						  }, false);
						  
						  etoile[i].myParam = temp;
						  
						  etoile[i].addEventListener('mouseout', function(e) {
						  	var save = e.target.myParam;
						  	for(var j = 0; j < save; j++) {
						    	etoile[j].className="etoile";
						    }
						  }, false);
						}
					</script>
					<?php
				}
			}
		}
	}
?>

