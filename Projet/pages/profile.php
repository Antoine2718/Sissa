<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php");
        $action = "PRF";
        if(isset($_GET)&&isset($_GET['action'])){
            $action = $_GET['action'];
        }
    ?>

    <!-- Contenu principal -->
    <div class="profile-content">
        <div class="profile-nav">
            <div class="profile-nav-element">
                <a class ="color-button" href="profile.php?action=PRF">Voir le profile</a>
            </div>
            <div class="profile-nav-element">
                <a class ="color-button" href="profile.php?action=HST">Historique d'achat</a>
            </div>
        </div>
        <div class ="data">

        </div>
    </div>
    
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
