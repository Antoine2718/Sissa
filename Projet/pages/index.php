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
        <?php 
        include("../common/leaderboard.php");
        ?>
        <h2>Bienvenue sur Sissa</h2>
        <h1>Découvrer le meilleur site pour jouer au morpion <br> contre vos amis ou contre notre algorithme</h1>
        <button class="color-button"><a href="jeu.php">Jouer maintenant</a></button>
    </div>
    
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
