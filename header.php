 <?php 
    if(isset($_SESSION['auth']) AND $_SESSION['auth'] !== false){ 
        $user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $user->execute(array($_SESSION['auth']['id']));
        $utilisateur = $user->fetch(); 
        $temps_actuel = date("U");
        $ip_user = $_SERVER['REMOTE_ADDR'];
        $update_ip = $pdo->prepare('UPDATE qeel SET time_co = ?, connexion = ? WHERE membre = ?');
        $update_ip->execute(array($temps_actuel, 0, $utilisateur['username']));
        $dsql = $pdo->prepare("SELECT COUNT(*) FROM forum_mp WHERE mp_receveur = ? AND mp_lu = '0'");
        $dsql->execute(array($utilisateur['id']));
        $mp = $dsql->fetchColumn();?>
        <div class="navbar_deroulante">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" href="#">
                    <span class="bienvenue">Bienvenue </span> <?php echo rang_etat(sanitize($utilisateur['grade']), sanitize($utilisateur['username'])); 
                    if ($mp >= 1) { ?>
                    <img src="https://zupimages.net/up/17/51/mxu2.png" alt="new_mp" class="new_mp" /><?php } ?>
                <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <center>Rang : <span class="menu_rang"><?php echo statut(sanitize($utilisateur['grade'])); ?></span><br/>
                        <?php if ($utilisateur['testeurs'] >= 1){ ?>
                       Titre(s) : <?php echo statut_testeur(sanitize($utilisateur['testeurs'])); ?><br/>
                       <?php echo statut_testeur(sanitize($utilisateur['testeurs_deux'])); }
                        if (!empty($utilisateur['avatar'])){
                            if (preg_match("#[0-9]+\.[png|jpg|jpeg|gif]#i", $utilisateur['avatar'])) { ?>
                                <img src="https://www.mangasfan.fr/inc/images/avatars/<?php echo $utilisateur['avatar']; ?>" alt="avatar" class="avatar_menu" /> <!-- via fichier -->
                            <?php } else { ?>
                                <img src="<?php echo stripslashes(htmlspecialchars($utilisateur['avatar'])); ?>" alt="avatar" class="avatar_menu"/><br/> <!-- via site url -->
                            <?php } } ?>
                    </center>
                     <li class="dropdown-divider"></li>
                    <?php if ($mp >= 1) { ?>
                        <li><a href="../profil/messagesprives.php">Messages Privés - <?php echo stripslashes(nl2br(htmlentities(htmlspecialchars(html_entity_decode($mp))))); ?> nouveau(x)
                        </a></li>
                    <?php } else { ?>
                        <li><a href="../profil/messagesprives.php">Messages privés</a></li>
                    <?php }?>
                    <?php if($utilisateur['grade'] >= 2){ ?>
                        <li><a href="../inc/compte.php">Modifier votre profil</a></li>
                        <li><a href="../profil/voirprofil.php?m=<?php echo $utilisateur['id']; ?>&action=consulter">» Voir votre profil</a></li>
                    <li class="dropdown-divider"></li>
                        <li><a href="https://www.mangasfan.fr/galeries/">Index des galeries</a></li>
                        <li><a href="https://www.mangasfan.fr/galeries/administration_galerie.php">Administration de la galerie</a></li>
                        <li><a href="https://www.mangasfan.fr/galeries/voir_galerie.php">Voir ma galerie</a></li>
                    <li class="dropdown-divider"></li>
                        <?php if ($utilisateur['grade'] >= 10) 
                        { ?>
                            <li><a href="../inc/liste_membres.php" target="blank">Liste des
                            membres</a></li>
                            <li><a href="../staff.php" target="blank">Coin staff</a></li>
                        <?php }
                        elseif ($utilisateur['grade'] == 9) 
                        { ?>
                            <li><a href="../staff.php" target="blank">Coin staff</a></li>
                        <?php } 
                        elseif ($utilisateur['grade'] == 5 || $utilisateur['grade'] == 6 || $utilisateur['grade'] == 7 || $utilisateur['grade'] == 8) 
                        { ?>
                            <li><a href="../staff.php" target="blank">Coin staff</a></li>
                        <?php } elseif ($utilisateur['grade'] == 3) 
                        { ?>
                           <li><a href="../staff.php" target="blank">Coin staff</a></li>
                        <?php } }?>
                        <a href="../inc/deconnexion.php">
                            <span class="glyphicon glyphicon-arrow-right"></span> Deconnexion
                        </a>
                    </li>
                </ul>
            </li>
        </div>
        <script>
            var myVar = setInterval(actu_connexion, 1000);
            function actu_connexion() {
            $("#qeel").load('../elements/load_actu_co.php');
        }
        </script>
        <?php
            } 
            else
            { 
                 $temps_actuel = date("U");
            $ip_user = $_SERVER['REMOTE_ADDR'];
            $invite_exist = $pdo->prepare("SELECT * FROM qeel WHERE ip_user = '$ip_user' AND membre is NULL");
            $invite_exist->execute();
            $invite_existe = $invite_exist->rowCount();

            if($invite_existe == 0){ // si l'utilisateur n'est pas dans la liste
                $add_ip = $pdo->prepare('INSERT INTO qeel(membre,membre_id,ip_user,time_co) VALUES(?,?,?,?)');
                $add_ip->execute(array(null,null,$ip_user,$temps_actuel));
            } else { // si l'utilisateur est dans la liste, alors on le mets à jour
                $update_ip = $pdo->prepare("UPDATE qeel SET time_co = '$temps_actuel' WHERE ip_user = '$ip_user' AND membre is NULL");
                $update_ip->execute();
            }
                ?>
                <section class="overlayConnexionInsc overlayBG flexRowCentered" style="display: none;">
        <section class="conteneurConnexionInsc">
            <header class="bandeauConteneur">
                <div></div>
                <h2 class="connexionDisp">Connexion</h2>
                <h2 class="inscriptionDisp" style="display: none">Inscription</h2>
                <div class="closeOverlay closeOverlay__circleBG flexRowCentered">X</div>
            </header>
        <section class="formulairesConnexionInsc">
                <menu class="ongletsConnexionInsc">
                    <li class="connexion buttonGreenBG">Connexion</li>
                    <li class="inscription">Inscription</li>
                </menu>
                <h3>Inscrit-toi ou connecte-toi sur Mangas'Fan !</h3>
                <form method="post" action="../inc/connexion.php" class="connexionFormulaire">
                    <input type="text" id="username" name="username" title="Username" class="form-control" placeholder="Votre pseudo" required="" />
                    <input type="password" id="password" name="password" title="Password" class="form-control" placeholder="Votre mot de passe" required="" />
                    <a href="https://www.mangasfan.fr/inc/forget.php">Mot de passe oublié ?</a>
                    <input type="submit" value="Se connecter" class="submitFormConnexionInsc buttonGreenBG" />
                </form>
                <form method="post" action="../inc/inscription.php" class="inscriptionFormulaire" style="display: none">
                    <div class="conteneurSplitFormInsc">
                       <div class="splitForm1">
                        <label for="pseudoInsc">Pseudo : </label>
                        <input type="text" name="username" class="form-control" placeholder="Entrez un pseudo" required="" id="pseudoInsc"/><br/>
                        <label for="emailInsc">Email : </label>
                        <input type="text" name="email" class="form-control" placeholder="Entrez une adresse Mail valide" required="" id="emailInsc"/><br/>
                       </div>
                        <div class="splitForm2">
                        <label for="passwordInsc">Mot de passe : </label>
                        <input type="password" name="password_i" class="form-control" placeholder="Saisir votre mot de passe" id="passwordInsc"/><br/>
                        <label for="validPassInsc">Confirmation du mot de passe : </label>
                        <input type="password" name="password_i_confirm" class="form-control"
                               placeholder="Confirmer votre mot de passe" id="validPassInsc"/><br/>
                        </div>
                    </div><br/>
                    <p class="validation_texte_inscription">En validant votre inscription, vous acceptez les <a href="../mentions_legales.php">CGU</a> de Mangas'Fan.</p><br/>
                    <input type="submit" value="M'inscrire" class="submitFormInscriptionInsc buttonGreenBG"/>
                </form>
            </section>
            <footer class="closeOverlay closeButtonConteneur">
                Fermer
            </footer>
        </section>
    </section>
    <div class="bouton_connexion">
        <span class="connexionLink" style="color: white;  cursor: pointer;">Connexion</span> |
        <span class="inscriptionLink" style="color: white; cursor: pointer;">Inscription</span>
    </div>
    <script>
        function displayConnexionOverlay(event) {
            if (event === 'connexionLink') {
                $('.overlayConnexionInsc').show();
            }
            else if (event === 'inscriptionLink') {
                $('.overlayConnexionInsc').show();
                swapOnglet('inscription');
            }
        }

        $('.bouton_connexion').click(function (event) {
            displayConnexionOverlay(event.target.classList.item(0));
        });

        $('.conteneurConnexionInsc').click(function (event) {
            swapOnglet(event.target.classList.item(0));
            hideConnexionOverlay(event.target.classList.item(0));
        })

        function swapOnglet(event) {
            if (event === "inscription" || event === "connexion") {
                $('.formulairesConnexionInsc > form').hide();
                $('.bandeauConteneur > h2').hide();
                $('.ongletsConnexionInsc > li').removeClass('buttonGreenBG');
                $('.' + event).addClass('buttonGreenBG');
                $('.' + event + 'Formulaire').show();
                $('.' + event + 'Disp').show();
            }
        }

        function hideConnexionOverlay(event) {
            if (event === 'closeOverlay') {
                $('.overlayConnexionInsc').hide();
            }
        }
    </script>
                <?php
            }
            ?>
                