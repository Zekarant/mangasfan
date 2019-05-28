<?php
$texte = preg_replace('#\[b\](.+)\[/b\]#isU', '<strong>$1</strong>', $texte);
$texte = preg_replace('#\[i\](.+)\[/i\]#isU', '<em>$1</em>', $texte);
$texte = preg_replace('#\[color=(red|green|blue|yellow|purple|olive)\](.+)\[/color\]#isU', '<span style="color:$1">$2</span>', $texte);
$texte = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $texte);
$texte = str_replace(':D', '<img src="../inc/images/emoticons/heureux.png"/>', $texte);
$texte = str_replace(':)', '<img src="../inc/images/emoticons/sourire.png"/>', $texte);
$texte = str_replace(';)', '<img src="../inc/images/emoticons/clin_oeuil.png"/>', $texte);
$texte = str_replace(':(', '<img src="../inc/images/emoticons/decu.png"/>', $texte);
$texte = str_replace('8)', '<img src="../inc/images/emoticons/star.png"/>', $texte);
$texte = str_replace(':/', '<img src="../inc/images/emoticons/bouche_coin.png"/>', $texte);
$texte = str_replace(':O', '<img src="../inc/images/emoticons/choque.png"/>', $texte);
$texte = str_replace(':P', '<img src="../inc/images/emoticons/langue.png"/>', $texte);
$texte = str_replace(':@', '<img src="../inc/images/emoticons/no_content.png"/>', $texte);
$texte = str_replace(':X', '<img src="../inc/images/emoticons/pete.png"/>', $texte);
$texte = str_replace(':d', '<img src="../inc/images/emoticons/heureux.png"/>', $texte);
$texte = str_replace('=)', '<img src="../inc/images/emoticons/sourire.png"/>', $texte);
$texte = str_replace('=(', '<img src="../inc/images/emoticons/decu.png"/>', $texte);
$texte = str_replace('=/', '<img src="../inc/images/emoticons/bouche_coin.png"/>', $texte);
$texte = str_replace(':o', '<img src="../inc/images/emoticons/choque.png"/>', $texte);
$texte = str_replace(':p', '<img src="../inc/images/emoticons/langue.png"/>', $texte);
$texte = str_replace(':x', '<img src="../inc/images/emoticons/pete.png"/>', $texte);
?>