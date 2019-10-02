<?php
function debug($variable){
  echo '<pre>' . print_r($variable, true) . '</pre>';
}

function str_random($length){
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

function logged_only()
{
    if(!isset($_SESSION['auth'])){
        $_SESSION['flash']['danger'] = "<div class='alert alert-danger' role='alert'>Vous n'avez pas le droit d'accéder à cette page</div>";
        header('Location: ../connexion.php');
        exit();
    }
}
function sanitize(string $string): string {
        return htmlentities(trim($string), ENT_QUOTES, 'UTF-8');
    }
function statut($rang)
{
if($rang == 1)
{
echo '<span class="badge badge-dark">Membre banni</span>';
}
else if($rang == 2)
{
echo '<span class="badge badge-membre">Membre</span>';
}
else if($rang == 3)
{
echo '<span class="badge badge-warning">Animateur</span>';
}
else if($rang == 4)
{
echo '<span class="badge badge-community">Community Manager</span>';
}
else if($rang == 5)
{
echo '<span class="badge badge-redacteur">Newseur</span>';
}
else if($rang == 6)
{
echo '<span class="badge badge-redacteur">Rédacteur</span>';
}
else if($rang == 9)
{
echo '<span class="badge badge-success">Modérateur</span>';
}
else if($rang == 10)
{
echo '<span class="badge badge-info">Développeur</span>';
}
else if($rang == 11)
{
echo '<span class="badge badge-admin">Administrateur</span>';
}
else if($rang == 12)
{
echo '<span class="badge badge-danger">Propriétaire</span>';
}
else if($rang == 13)
{
echo '<span class="badge badge-bot">Mangas\'Bot</span>';
}
}

function rang_etat($rang, $texte)
{
  $color = "";
if($rang == 1)
{
$color = '<span style="color: black;">'.$texte.'</span>';
}
else if($rang == 2)
{
$color = '<span style="color: #2E9AFE;">'.$texte.'</span>';
}
else if($rang == 3)
{
$color = '<span style="color: orange;">'.$texte.'</span>';
}
else if($rang == 4)
{
  $color = '<span style="color: #632569">'.$texte.'</span>';
}
else if($rang == 5)
{
$color = '<span style="color: #40A497;">'.$texte.'</span>';
}
else if($rang == 6)
{
$color = '<span style="color: #40A497;">'.$texte.'</span>';
}
else if($rang == 9)
{
$color = '<span style="color: #31B404;">'.$texte.'</span>';
}
else if($rang == 10)
{
$color = '<span style="color: #4080BF;">'.$texte.'</span>';
}
else if($rang == 11)
{
$color = '<span style="color: darkblue;">'.$texte.'</span>';
}
else if($rang == 12)
{
$color = '<span style="color: red;">'.$texte.'</span>';
}
else if($rang == 13)
{
$color = '<span style="color: #1BB078;">'.$texte.'</span>';
}
return $color;
}

function statut_testeur($testeurs)
{
if($testeurs == 1)
{
echo '<span style="color: #6fb6bd;">Partenaire</span>';
}
}

function avatar_color($rang){
  if($rang == 1){
    $color = 'black';
  }
  else if($rang == 2){
    $color = '#2E9AFE';
  }
  else if($rang == 3){
    $color = 'orange';
  }
   else if($rang == 4){
    $color = '#632569';
  }
  else if($rang == 5 || $rang == 6 || $rang == 7 || $rang == 8){
    $color = '#40A497';
  }
  else if($rang == 9){
    $color = '#31B404';
  }
   else if($rang == 10){
    $color = '#4080BF';
  }
  else if($rang == 11){
    $color = 'darkblue';
  }
  else if($rang == 12){
    $color = 'red';
  }
  return $color;
}

function chef($chef)
{
if($chef == 3)
{
echo '<span class="badge badge-warning">Chef des animateurs</span>';
}
else if($chef == 4)
{
echo '<span class="badge badge-community">Chef des Community Manager</span>';
}
else if($chef == 5)
{
echo '<span class="badge badge-redacteur">Chef des newseurs</span>';
}
else if($chef == 6)
{
echo '<span class="badge badge-redacteur">Chef des rédacteurs</span>';
}
else if($chef == 9)
{
echo '<span class="badge badge-success">Chef des modérateurs</span>';
}
else if($chef == 10)
{
echo '<span class="badge badge-info">Chef des développeurs</span>';
}
else if($chef == 11)
{
echo '<span class="badge badge-admin">Chef des administrateurs</span>';
}
else if($chef == 12)
{
echo '<span class="badge badge-danger">Propriétaire</span>';
}
}

function traduire_nom($elt){
  // traduit le nom en paramètre pour pouvoir l'appeler dans l'url
  $elt = suppr_accents($elt);
  $remplacement = preg_replace('#\'s#','_',strtolower($elt));
  $remplacement = preg_replace('#([\s|:|-|_]+)#i','_',strtolower($remplacement));
  $remplacement = preg_replace('#([!?,.\']+)#i','',strtolower($remplacement));
  $remplacement = rtrim($remplacement,'_');
  return $remplacement;
}


function suppr_accents($str, $encoding='utf-8')
{
    // transformer les caractères accentués en entités HTML
    $str = htmlentities($str, ENT_NOQUOTES, $encoding);
 
    // remplacer les entités HTML pour avoir juste le premier caractères non accentués
    // Exemple : "&ecute;" => "e", "&Ecute;" => "E", "à" => "a" ...
    $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);
 
    // Remplacer les ligatures tel que : , Æ ...
    // Exemple "œ" => "oe"
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);

    $str = preg_replace('#&amp;#','et',$str);
    $str = preg_replace('#amp;#','',$str);

    // Supprimer tout le reste
    $str = preg_replace('#&[^;]+;#', '', $str);
 
    return $str;
}

function write_date($date_post,$pseudo_member){
    $liste_mois = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    $post = preg_replace_callback("#([0-9]{4})-([0-9]{2})-([0-9]{2}) [0-9]{2}:[0-9]{2}:[0-9]{2}#",function ($key) use ($liste_mois) { return $key[3].' '.$liste_mois[$key[2]-1].' '.$key[1]; },$date_post);

    return "Posté le ". $post ." par ".$pseudo_member;
  }