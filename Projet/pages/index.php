<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
</head>
<body>
    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php")
    ?>
    
    <!-- Contenu principal -->
    <div class="content">
        <h2>Bienvenue sur Sissa</h2>
        <h1>Découvrez le meilleur site pour jouer au morpion <br> contre vos amis ou contre notre algorithme</h1>
        <button class="color-button"><a href="jeu.php">Jouer maintenant</a></button>
        <div class ="index-content">
            <div class ="content-carte">
                <a href="jeu.php?mode_selection=computer">
                    <h2>Mode IA</h2>
                    <p>Affrontez notre IA à différents niveaux pour monter dans le classement</p>
                </a>
            </div>
            <div class ="content-carte">
                <a href="jeu.php?mode_selection=human">
                    <h2>Mode Multijoueur Local</h2>
                    <p>Jouez avec vos amis sur le même appareil et montrez qui est le meilleur</p>
                </a>
            </div>
            <div class ="content-carte">
                <a href="classement.php">
                    <h2>Classement</h2>
                    <p>Grimpez dans notre classement et devenez une légende du morpion</p>
                </a>
            </div>
            
        </div>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
