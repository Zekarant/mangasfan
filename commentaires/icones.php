<?php if ($member['grade'] >= 12){ ?><span class="glyphicon glyphicon-star" style="color: red; padding-bottom: 10px;" title="Fondateur de Mangas'Fan"></span><?php } elseif ($member['grade'] == 11) {
        	?><span class="glyphicon glyphicon-star" style="color: darkblue;padding-bottom: 10px;" title="Administrateur du site"></span><?php }  elseif ($member['grade'] == 10) {
        	?><span class="glyphicon glyphicon-star" style="color: #4080BF;padding-bottom: 10px;" title="Développeur du site"></span><?php } elseif ($member['grade'] == 9) {
        	?><span class="glyphicon glyphicon-star" style="color: #31B404;padding-bottom: 10px;" title="Modérateur du site"></span><?php } elseif ($member['grade'] >= 5 AND $member['grade'] <= 8) {
        	?><span class="glyphicon glyphicon-star" style="color: #40A497;padding-bottom: 10px;" title="Plumes du site"></span><?php } elseif ($member['grade'] == 4) {
        	?><span class="glyphicon glyphicon-star" style="color: #632569;padding-bottom: 10px;" title="Community Manager"></span><?php } elseif ($member['grade'] == 3) {
        	?><span class="glyphicon glyphicon-star" style="color: orange;padding-bottom: 10px;" title="Animateur du site"></span><?php } ?>

        	<?php if ($member['testeurs'] == 1){ ?><span class="glyphicon glyphicon-wrench" style="color: DarkSlateGray;padding-bottom: 10px;" title="Bêta-Testeur de Mangas'Fan"></span>
        	<?php } elseif ($member['testeurs'] == 2) {
        	?><span class="glyphicon glyphicon-wrench" style="color: #D35400;padding-bottom: 10px;" title="Chef des Bêta-Testeurs"></span><?php } elseif ($member['testeurs'] == 3) {
        	?><span class="glyphicon glyphicon-heart" style="color: #6fb6bd;padding-bottom: 10px;" title="Partenaire officiel"></span>
        	<?php } elseif ($member['testeurs'] == 4) {
        	?><span class="glyphicon glyphicon-alert" style="color: #D7BDE2;padding-bottom: 10px;" title="NSFW"></span>
        	<?php } ?>

        	<?php if ($member['testeurs_deux'] == 1){ ?><span class="glyphicon glyphicon-wrench" style="color: DarkSlateGray;padding-bottom: 5px;" title="Bêta-Testeur de Mangas'Fan"></span>
        	<?php } elseif ($member['testeurs_deux'] == 2) {
        	?><span class="glyphicon glyphicon-wrench" style="color: #D35400;padding-bottom: 10px;" title="Chef des Bêta-Testeurs"></span><?php } elseif ($member['testeurs_deux'] == 3) {
        	?><span class="glyphicon glyphicon-heart" style="color: #6fb6bd;padding-bottom: 10px;" title="Partenaire officiel"></span>
        	<?php } elseif ($member['testeurs_deux'] == 4) {
        	?><span class="glyphicon glyphicon-alert" style="color: #D7BDE2;padding-bottom: 10px;" title="NSFW"></span>
        	<?php } ?>