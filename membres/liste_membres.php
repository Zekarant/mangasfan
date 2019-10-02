<?php
  session_start(); 
  require_once 'base.php';
  if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false)
{ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
    if ($utilisateur['grade'] == 1) 
    {
      echo '<script>location.href="bannis.php";</script>';
    }
}
  include('functions.php'); 
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Mangas'Fan - Liste des membres</title>
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <link rel="icon" href="../images/favicon.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/jquery.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">
  </head>
  <body>
    <?php include('../elements/header.php'); ?>
   <section>
          <h2 class="titre_principal_news">
            Liste des membres de Mangas'Fan
        </h2>
         <?php
         if (!empty($_GET['page']) && is_numeric($_GET['page']) )
         $page = stripslashes($_GET['page']);
         else
         $page = 1;
         $pagination = 10;
         // Numéro du 1er enregistrement à lire
         $limit_start = ($page - 1) * $pagination;
         $nb_total = $pdo->query('SELECT COUNT(*) AS nb_total FROM users');
         $nb_total->execute();
         $nb_total = $nb_total->fetchColumn();
         // Pagination
         $nb_pages = ceil($nb_total / $pagination);

         echo '<table style="width:50%"><th style="width:33%"><span class="pagination_mobile_membres">[ Page :';
         // Boucle sur les pages
         for ($i = 1 ; $i <= $nb_pages ; $i++) {
         if ($i == $page )
         echo " $i";
         else
         echo " <a href=\"?page=$i\">$i</a> ";
         }
         echo ' ]</span></th></table>'; ?>
<hr>
<div class="container bootstrap snippet">
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box no-header clearfix">
                <div class="main-box-body clearfix">
                    <div class="table-responsive">
                        <table class="table user-list">
                            <thead>
                                <tr>
                                <th><span>Pseudo</span></th>
                                <th><span>Inscription</span></th>
                                <th class="text-center"><span>Rang</span></th>
                                <th><span>Manga</span></th>
                                <th><span>Anime</span></th>
                                <th>&nbsp;</th>
                                </tr>
                                 <?php
         $select_all_membres = $pdo->prepare("SELECT *, DATE_FORMAT(confirmed_at,'%d/%m/%Y') AS date_inscription FROM users WHERE grade >= 2 ORDER BY id ASC LIMIT $limit_start, $pagination");
         $select_all_membres->execute();
         while ($membre_all = $select_all_membres->fetch())
         { ?>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php if (!empty($membre_all['avatar'])){
    if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $membre_all['avatar'])) { ?>
        <img src="../membres/images/avatars/<?php echo sanitize($membre_all['avatar']); ?>" alt="avatar" style="height: 50px;" title="Avatar de <?php echo sanitize($membre_all['username']); ?>"/><a href="https://www.mangasfan.fr/profil/voirprofil.php?membre=<?php echo sanitize($membre_all['id']) ;?>&action=consulter"> » <?php echo sanitize($membre_all['username']);?></a> <!-- via fichier -->
    <?php } else { ?>
        <img src="<?php echo sanitize($membre_all['avatar']); ?>" alt="avatar" style="height: 50px;" title="Avatar de <?php echo sanitize($membre_all['username']); ?>"/><br/><a href="https://www.mangasfan.fr/profil/voirprofil.php?membre=<?php echo sanitize($membre_all['id']);?>&action=consulter"> » <?php echo sanitize($membre_all['username']);?></a> <!-- via site url -->
    <?php } 
} ?>
                                    </td>
                                    <td><?php echo $membre_all['date_inscription'];?></td>
                                    <td class="text-center">
                                        <font color="red"><i><?php echo statut(sanitize($membre_all['grade']));?></i></font>
                                    </td>
                                    <td>
                                       <?php if($membre_all['manga'] == ""){ echo "<i>Non renseigné</i>"; } else { echo sanitize($membre_all['manga']); }?>
                                    </td>
                                     <td>
                                       <?php if($membre_all['anime'] == ""){ echo "<i>Non renseigné</i>"; } else { echo sanitize($membre_all['anime']); }?>
                                    </td>
                                    <td style="width: 20%;">
                                        
                                        
                                    </td>
                                </tr>
                               <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<?php include('../elements/footer.php') ?>
</body>
</html>