<article id="em">
	<center>
		<?php
			$select_anim = $pdo->query("SELECT * FROM animation ORDER BY id DESC")->fetch();
			echo bbcode($select_anim->contenu);
		?>
	</center>
</article>

