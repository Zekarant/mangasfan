<script>
	function showSpoiler(obj)
		{
			var inner = obj.parentNode.getElementsByTagName("div")[0];
		if (inner.style.display == "none")
			inner.style.display = "";
		else
			inner.style.display = "none";
		}
</script>
<style>
	.underline {background:	#FFD700;display:block; color: #000000;}
	.underline2 {background:#DAA520;display:block; color: #000000; }
	.spoiler {width: 400px;}
</style>

<?php
	function bbcode($texte){

	$texte = preg_replace('#\[b\](.+)\[/b\]#isU', '<strong>$1</strong>', $texte);
	$texte = preg_replace('#\[img\](.+)\[/img\]#isU', '<img src="$1" weight="400" heigth="300""/>', $texte);
	$texte = preg_replace('#\[video\](.+)\[/video\]#isU', '<video src="$1" controls  width="100%"><video src="sintel.webm" controls poster="sintel.jpg" width="600">
	    Il est temps de mettre à jour votre navigateur !
	</video></video>', $texte);
	$texte = preg_replace('#\[i\](.+)\[/i\]#isU', '<em>$1</em>', $texte);
	$texte = preg_replace('#\[color=(.+)\](.+)\[/color\]#isU', '<span style="color: $1;">$2</span>', $texte);
	$texte = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>', $texte);
	$texte = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $texte);
	$texte = preg_replace('`\[titre\](.+)\[/titre\]`isU', '<h2>$1</h2>', $texte);
	$texte = preg_replace('`\[centre\](.+)\[/centre\]`isU', '<center>$1</center>', $texte);
	$texte = preg_replace('#\[url=(.+)\](.+)\[/url\]#isU', '<a href="$1" target="_blank">$2</a>', $texte);
	$texte = preg_replace('#\[under\](.+)\[/under\]#isU', '<span class="underline">$1</span>', $texte);
	$texte = preg_replace('#\[under2\](.+)\[/under2\]#isU', '<span class="underline2">$1</span>', $texte);
	$texte = preg_replace('#\[code\](.+)\[/code\]#isU', '<div style="border: 1px dashed black;"><code>$1</code></div>', $texte);
	$texte = preg_replace('#\[spoiler\](.+)\[/spoiler\]#isU', '<div class="spoiler"><br>
	<input onclick="showSpoiler(this);" value="Spoiler"
	 font-size="10px" type="button" >
	<div class="inner" style="display: none;">$1
	</div>
	</div>', $texte);
	$texte = str_replace(':D', '<img src="images/emoticons/heureux.png"/>', $texte);
	$texte = str_replace(':)', '<img src="images/emoticons/sourire.png"/>', $texte);
	$texte = str_replace(';)', '<img src="images/emoticons/clin_oeuil.png"/>', $texte);
	$texte = str_replace(':(', '<img src="images/emoticons/decu.png"/>', $texte);
	$texte = str_replace('8)', '<img src="images/emoticons/star.png"/>', $texte);
	$texte = str_replace(':O', '<img src="images/emoticons/choque.png"/>', $texte);
	$texte = str_replace(':P', '<img src="images/emoticons/langue.png"/>', $texte);
	$texte = str_replace(':@', '<img src="images/emoticons/no_content.png"/>', $texte);
	$texte = str_replace(':X', '<img src="images/emoticons/pete.png"/>', $texte);
	$texte = str_replace(':d', '<img src="images/emoticons/heureux.png"/>', $texte);
	$texte = str_replace('=)', '<img src="images/emoticons/sourire.png"/>', $texte);
	$texte = str_replace('=(', '<img src="images/emoticons/decu.png"/>', $texte);
	$texte = str_replace('=/', '<img src="images/emoticons/bouche_coin.png"/>', $texte);
	$texte = str_replace(':o', '<img src="images/emoticons/choque.png"/>', $texte);
	$texte = str_replace(':p', '<img src="images/emoticons/langue.png"/>', $texte);
	$texte = str_replace(':x', '<img src="images/emoticons/pete.png"/>', $texte);

return $texte;
}
?>
