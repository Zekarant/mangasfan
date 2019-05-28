<?php
	session_start();
	require_once 'inc/base.php';
	include('inc/functions.php');
	include('inc/bbcode.php'); 
	include('theme_temporaire.php');
?>
<!doctype html>
	<html lang="fr">
		<head>
  			<meta charset="utf-8" />
  			<title>Mangas'Fan - Mentions légales</title>
  			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link rel="icon" href="images/favicon.png"/>
			<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
			<script>
			    window.dataLayer = window.dataLayer || [];
			    function gtag(){dataLayer.push(arguments);}
			    gtag('js', new Date());

			    gtag('config', 'UA-129397962-1');
			</script>
			<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
			<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
			<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
			<link href="https://fonts.googleapis.com/css?family=Nosifer" rel="stylesheet">
		    <link href="https://fonts.googleapis.com/css?family=Emilys+Candy" rel="stylesheet">
		    <link href="https://fonts.googleapis.com/css?family=Butcherman" rel="stylesheet">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  			<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
			<link rel="stylesheet" type="text/css" href="<?php echo $lienCss; ?>">
			<link rel="stylesheet" type="text/css" href="overlay.css" />
		</head>
	<body>
		<div id="bloc_page">
 		<header>
			<div id="banniere_image">
			<div id="titre_site"><span class="couleur_mangas">M</span>ANGAS'<span class="couleur_fans">F</span>AN</div>
			<div class="slogan_site"><?php echo $slogan; ?></div>
        		<?php include("elements/navigation.php") ?>
			<h2 id="actu_moment"><?php echo $phrase_actu; ?></h2>
			<h5 id="slogan_actu"><?php echo $slogan_actu; ?></h5>
			<div class="bouton_fofo"><a href="https://www.twitter.com/Mangas_Fans" target="_blank">Twitter</a></div>
				<?php include('elements/header.php'); ?>
			</div>
      </header>
		<section class="marge_page">
 			<h3 id="titre_news">
 				<img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
 				 Mentions Légales de <span class="couleur_mangas">Mangas</span>'<span class="couleur_fans">Fan</span>
 				<img src="<?php echo $image; ?>" id="<?php echo $class_image; ?>" />
 			</h3><br/>

<h4 id="titre_legale">INFORMATIONS RELATIVES AU <span class="couleur_mangas">SITE</span> <span class="couleur_fans">INTERNET</span></h4>
<p>Conformément à l’article n°6 de la Loi n°2004-575 du 21 Juin 2004 pour la confiance dans l’économie numérique, vous serez en mesure de consulter ici nos informations liées à <a href="https://www.mangasfan.fr/" class="mail_liens">https://www.mangasfan.fr/. </a></p>

<p>Mangas'Fan est hébergé de façon gratuite sur les services de Nexgate <a href="https://www.nexgate.ch/" class="mail_liens">(https://www.nexgate.ch/)</a>, service gratuit s'occupant d'hébergements webs. </p>

<p>Mangas'Fan a été crée par Zekarant, âgé de 17 ans, lycéen en filière STI2D mais soutenu par le co-développeur Nico. 
<br/>Tout contact concernant le site se fait à l'aide de cette adresse Mail : <a href="mailto:contact@mangasfan.fr" class="mail_liens">contact@mangasfan.fr.</a></p>

<p>Vous pourrez retrouver sur Mangas'Fan, un espace membre afin de discuter avec les membres, des blogs, des news, des idées, mises à jour, ect...Vous pouvez aussi nous retrouver sur les réseaux sociaux afin d'être informé des avancées du site, de son actualité, ou encore des petites animations pouvant être organisées par nos Community Manager. </p>

<p><b>Comme mentionné sur les réseaux sociaux, Mangas'Fan est toujours dans une phase de développement, des modules seront rajoutés au fur et à mesure sur le site !</b><br/>
La prochaine grosse version de Mangas'Fan est par ailleurs en développement pour une réfonte majeure prévue courant 2019.</p><br/>

<h4 id="titre_legale">CONDITIONS <span class="couleur_mangas">GÉNÉRALES</span> <span class="couleur_fans">D'UTILISATION</span></h4>
<p>En visitant le site de <a href="https://www.mangasfan.fr/" class="mail_liens">https://www.mangasfan.fr/</a>,vous acceptez les Conditions Générales d'utilisations qui vont se voir énoncées ci-dessous :</p><br/>

<h5 id="titre_legale">Conditions d'accès au site</h5>

<p>Mangas'Fan est un site web disponible et optimisé sur les navigateurs suivants : Google Chrome, Firefox, Edge, ainsi que Opéra. Nous nous engageons à rendre le site toujours meilleur sur les navigateurs cités ci-dessus. L'affichage étant optimal sur les versions PC et majoritairement optimal sur les versions mobiles Android et IOS confondus.</p>

<p>Tous les utilisateurs s'engagent à ne posséder qu'un seul et unique compte par personne sous peine de suppression des multi-comptes sans préavis. Si plusieurs membres utilisent le même réseau Internet, merci de tenir l'équipe de modération informée.</p>

<p>Soyez assurés que Mangas'Fan se voit subit des mises à jour régulièrement et que nous nous efforçons de vous proposer un site de qualité.</p>

<p>Les problèmes techniques peuvent nous être signalés via le Mail mis à disposition au début. Toutes les remarques rapportées seront traitées au plus vite afin de satisfaire les utilisateurs.</p><br/>

<h5 id="titre_legale">Les services proposés</h5>

<p>Le site n'est pas géré que par le fondateur mais par un groupe de personnes regroupant les développeurs, les modérateurs, les rédacteurs, les animateurs ainsi que les Community Manager. Nous nous assurons de répondre aux besoin des utilisateurs et de fournir des informations justes et exactes. Toutes informations fausses ne vient pas de nos services.</p><br/>


<h5 id="titre_legale">La propriété intellectuelle</h5>

<p>Zekarant, actuellement propriétaire et développeur de la plateforme <a href="https://www.mangasfan.fr/" class="mail_liens">https://www.mangasfan.fr/</a> affirment que toutes les images, vidéos, logos, bannières et textes appartiennent à leurs auteurs respectifs.</p>

<p>Toute reproduction, modification, publication ou adaptation ne serait-ce que partielle, est strictement interdite sans l'accord de l'auteur en question.</p>

<p>Tout contenu à caractère raciste, diffamatoire, pornographique, incitant à la haine, à la violence est strictement interdit et sera sanctionné par un bannissement définitif et sans préavis.</p>

<p>Toutes les exploitations non autorisées du site seront considérées comme une preuve d'une contrefaçon et seront poursuivies devant la justice Française conformément aux dispositions des articles L.335-2 et suivants du Code de Propriété Intellectuelle. </p><br/>

<h5 id="titre_legale">Protection des données personnelles</h5>

<p>Lors de votre visite sur Mangas'Fan, aucun donnée personnelle n'est demandée ou relevée. Cependant, en cas d'inscription, donc participation à nos services, des données peuvent vous être demandées comme votre adresse email, une description de vous, ainsi que vos goûts ou encore, nous pouvons récupérer votre adresse IP dans le but d'éviter tout spam d'inscriptionn. Ce système est en place pour le système de newsletter, une inscription par adresse IP pour éviter de remplir la base de données.</p>

<p>Cependant, l'utilisateur dispose de la liberté de refuser de fournir les informations mentionnées ci-dessus, mais dans ce cas-là, la totalité des services de Mangas'Fan ne pourra lui être offerte, comme l'accès à des pages réservées aux membres inscrits et donc, à la commuauté du site ou encore l'accès aux newsletters du site si il refuse de communiquer son adresse mail/IP.</p>

<p>En revanche, le simple fait de visiter <a href="https://www.mangasfan.fr/" class="mail_liens">https://www.mangasfan.fr/</a> permet de manière automatique de récupérer des informations vous concernant, comme des données sur l’utilisation du site, les pages visitées et votre navigateur.</p>

<p>Ces données ne sont pas utilisées pour des fins personnelles mais pour des fins statistiques afin de pouvoir offrir un meilleur service aux utilisateurs. Aucune donnée ne sera communiquée à des tiers.</p>

<p>Les bases de données sont protégées par les dispositions de la loi du 1er juillet 1998 transposant la directive 96/9 du 11 mars 1996 relative à la protection juridique des bases de données.</p><br/>

<h5 id="titre_legale">Liens hypertextes</h5>

<p>Vous pourrez trouver sur Mangas'Fan, des liens menant vers des pages externes au site, pouvant mener au site d'un partenaire ou à des informations supplémentaires sur la page du site en question. Certains peuvent ne présenter aucun risque mais d'autres peuvent mener vers des sites extérieux. L'équipe de développement de Mangas'Fan n'est malheureusement pas en mesure de contrôler les ressources externes à Mangas'Fan.<br/>Nous ne pouvons donc pas garantir la connexion sécurisée de ces sites, ainsi que leur fiabilité. Nous ne contrôlons pas les sites externes à notre plateforme, ils sont sous la propriété de leur(s) propriétaire(s).</p>

<p>C'est pour cela que notre équipe ne se tient en aucun cas responsable des dommages causés par ces sites, quelque soit la nature de ces derniers. Nous ne pouvons pas nous tenir responsables des informations récupérées sur ces sites, des services ou/et des produits proposés.</p><br/>

<h5 id="titre_legale">Litiges</h5>

<p>Tout litige avec <a href="https://www.mangasfan.fr/" class="mail_liens">https://www.mangasfan.fr/</a> est soumis à la législation française. Toute contestation ou litige qui pourrait intervenir est de la compétence exclusive des tribunaux.<br/>
En cas d'interrogation de la part des utilisateurs, nous restons disponible via le Mail laissé en début de page, nous garantissons une réponse en 24 heures.</p>


<p>Pour toute question, nous restons disponible à l'adresse de messagerie citée en haut de la page.<br/>
Retourner à l'accueil de <a href="https://www.mangasfan.fr/" class="mail_liens">https://www.mangasfan.fr/</a></p>
		</section>

	<div id="banniere_reseaux">
            <div id="twitter"><?php include('elements/twitter.php') ?></div>
            <div id="facebook"><?php include('elements/facebook.php') ?></div>
            <div id="discord"><?php include('elements/discord.php') ?></div>
	</div>
	</section>
		<?php include('elements/footer.php') ?>
	</div>
	</body>
</html>