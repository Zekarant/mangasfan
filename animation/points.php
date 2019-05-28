<?php
$new_points = isset($_POST['new_points'] ) ? $_POST['new_points'] : '';
if(!empty(stripslashes(htmlspecialchars($new_points))))
	{
		$points = stripslashes(htmlspecialchars($_POST['points']));
		$id_membre = stripslashes(htmlspecialchars($_POST['membres']));
	  	$new_points = $pdo->prepare("UPDATE users SET points = ? WHERE id = ?");
	  	$new_points->execute(array($points, $id_membre));
	  	$select_pseudo_grade_new = $pdo->prepare("SELECT username FROM users WHERE id = ?"); 
	  	$select_pseudo_grade_new->execute(array($id_membre));
	  	$grade_new = $select_pseudo_grade_new->fetch();
	  	?><br/>
	<?php
	  echo '<div class="alert alert-success" role="alert">Les points de <i>'.$grade_new['username'].' </i>ont bien été changés !<br/></div>';
	}
?>
 <span class="titre_commentaire_news" id="titre_grade"><center>Points des membres de <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fan</span></center></span>
<table border="1" cellpadding="10" cellspacing="1" width="35%" class="table table-striped" id="tableau_grade">
	     <tr>
		  <th>Promouvoir un membre</th>
				<th>Points à donner (Mettre la nouvelle valeur uniquement)</th>
				<th>Valider</th>
	   </tr>
	<form method="post" action="">
 <p><tr>
      <th> 
       <select name="membres" id="membres" style="width: 100%;" class="form-control">
<?php
$sql = $pdo->prepare("SELECT * FROM users ORDER BY id ASC");
$sql->execute();
while($don = $sql->fetch())
{
echo"<option value='".$don['id']."' for='membres'>".(utf8_encode($don['username']))." » ".$don['points']."</option>";
}
?>
       </select></th>
   </p>
<th><input type="text" name="points" class="form-control" style="width: 100%"; placeholder="Points"><br/></th>
 <th><input type="submit" name="new_points" value="Valider les points !" class="btn btn-sm btn-info"/></th>
</form>
</tr>
</table>
