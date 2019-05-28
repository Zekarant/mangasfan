<?php
    $lienCss = '../style.css';
    $phrase_actu = 'NOTRE SITE';
    $image = '';
    $class_image = '';
    $titre_1 = "M";
    $titre_2 = "ANGAS'";
    $slogan = 'Votre référence Mangas';
    $slogan_actu = 'Pour parler de notre passion commune';
    if (date('d-m') === '30-10'){ 
        $lienCss = 'https://www.mangasfan.fr/halloween.css';
        $image = 'http://miam-images.m.i.pic.centerblog.net/c3871628.png';
        $class_image = 'citrouille';
        $slogan = 'Votre reference Mangas';
        $phrase_actu ='JOYEUX HALLOWEEN !';
        $titre_1 = "M";
        $titre_2 = "ANGAS'";
        $slogan_actu ='Toute l\'équipe du site vous souhaite un joyeux halloween !';
      }
      elseif (date('d-m') === '01-04'){ 
        $lienCss = 'https://www.mangasfan.fr/avril.css';
        $image = 'https://orig00.deviantart.net/1ef4/f/2016/169/e/d/happy_old_background_by_sakamileo-da6rvxj.png';
        $class_image = 'poisson';
        $slogan = 'Votre référence Fairy\'Tail';
        $phrase_actu ='NOUVEAU CHANGEMENT !';
        $titre_1 = "F";
        $titre_2 = "AIRY'";
        $slogan_actu ='Toute l\'équipe du site est fière de vous annoncer sa reconversion !';
      }
    elseif (date('d') >= 01 AND date('d') <= 31 AND date('m') == 12) {
        $lienCss = '../noel.css'; 
        $image = 'https://www.mangasfan.fr/images/logo_noel.png';
        $class_image = 'noel';
        $slogan = 'Votre reference Mangas';
        $phrase_actu = 'JOYEUX NOËL !'; 
        $slogan_actu ='Toute l\'équipe du site vous souhaite un joyeux Noël !';
      }
?>