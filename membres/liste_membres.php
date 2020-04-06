<?php
session_start(); 
include('base.php');
include('functions.php');
// Paginaiton
if (!empty($_GET['page']) && is_numeric($_GET['page'])){
    $page = stripslashes($_GET['page']);
} else {
    $page = 1;
}
$pagination = 20;
// Numéro du 1er enregistrement à lire
$limit_start = ($page - 1) * $pagination;
$nb_total = $pdo->prepare('SELECT COUNT(*) AS nb_total FROM users');
$nb_total->execute();
$nb_total = $nb_total->fetchColumn();
                            // Pagination
$nb_pages = ceil($nb_total / $pagination);
// Membres
$select_all_membres = $pdo->prepare("SELECT * FROM users WHERE confirmation_token IS NULL AND grade >= 2 ORDER BY id LIMIT $limit_start, $pagination");
$select_all_membres->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Liste des membres - Mangas'Fan</title>
    <link rel="icon" href="../images/favicon.png"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129397962-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-129397962-1');
    </script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include('../elements/header.php'); ?>
    <section>
        <h1 class="titre_principal_news">Liste des membres de Mangas'Fan</h1>
        <hr>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Pages :</a>
                </li>
                <?php for ($i = 1; $i <= $nb_pages; $i++) {
                    if ($i == $page) { ?>
                        <li class="page-item">
                            <a class="page-link" href="#"><?= sanitize($i); ?></a>
                        </li>
                    <?php } else { ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= "?page=" . sanitize($i); ?>"><?= sanitize($i);?></a>
                        </li>
                    <?php }
                } ?>
            </ul>
        </nav>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <th>Avatar</th>
                    <th>Pseudo</th>
                    <th>Inscription</th>
                    <th>Rang</th>
                    <th>Manga</th>
                    <th>Anime</th>
                    <th>Profil</th>
                </thead>
                <tbody>
                    <?php while ($membre_all = $select_all_membres->fetch()){ ?>
                        <tr>
                            <td><?php if (!empty($membre_all['avatar'])){
                                if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $membre_all['avatar'])) { ?>
                                    <img src="../membres/images/avatars/<?= sanitize($membre_all['avatar']); ?>" alt="avatar" style="height: 60px;" title="Avatar de <?= sanitize($membre_all['username']); ?>"/>
                                <?php } else { ?>
                                    <img src="<?= sanitize($membre_all['avatar']); ?>" alt="avatar" style="height: 60px;" title="Avatar de <?= sanitize($membre_all['username']); ?>"/>
                                <?php }
                            } ?></td>
                            <td><?= sanitize($membre_all['username']); ?></td>
                            <td><?= date('d/m/Y', strtotime(sanitize($membre_all['confirmed_at']))); ?></td>
                            <td><?php if($membre_all['chef'] != 0){ 
                                echo chef(sanitize($membre_all['chef'])); 
                            } else { 
                                echo statut($membre_all['grade'], $membre_all['sexe']); 
                            } ?></td>
                            <td><?php if ($membre_all['manga'] != NULL) {
                                echo sanitize($membre_all['manga']);
                            } else {
                                echo "Non renseigné.";
                            } ?></td>
                            <td><?php if ($membre_all['anime'] != NULL) {
                                echo sanitize($membre_all['anime']);
                            } else {
                                echo "Non renseigné.";
                            } ?></td>
                            <td><a href="../profil/voirprofil.php?membre=<?= sanitize($membre_all['id']); ?>" target="_blank">Profil de <?= sanitize($membre_all['username']); ?></a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br/>
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Pages :</a>
                </li>
                <?php for ($i = 1; $i <= $nb_pages; $i++) {
                    if ($i == $page) { ?>
                        <li class="page-item">
                            <a class="page-link" href="#"><?= sanitize($i); ?></a>
                        </li>
                    <?php } else { ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= "?page=" . sanitize($i); ?>"><?= sanitize($i);?></a>
                        </li>
                    <?php }
                } ?>
            </ul>
        </nav>
    </section>
    <?php include('../elements/footer.php'); ?>
</body>
</html>