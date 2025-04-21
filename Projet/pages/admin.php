<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php");
    ?>
    <!-- Contenu principal -->
    <div class="content">
        <div class="admin-nav">
            <div class="admin-nav-element">
                <a class ="color-button" href="admin.php?action=USR">Liste des utilisateurs</a>
            </div>
            <div class="admin-nav-element">
                <a class ="color-button" href="admin.php?action=HGM">Historique des parties</a>
            </div>
            <div class="admin-nav-element">
                <a class ="color-button" href="admin.php?action=HSH">Historique du Shop</a>
            </div>
        </div>
    </div>
    <?php 
        if(!empty($_GET) && isset($_GET['action'])){
            $action = $_GET['action'];

        }
    ?>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>