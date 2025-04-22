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
    <link rel="stylesheet" href="shop.css">
</head>
<body>
    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        require_once("../admin/pagination.php");
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
        <div class="data">
        <?php 
        if(!empty($_GET) && isset($_GET['action'])){
            $action = $_GET['action'];
            $name ="";
            $page = 1;
            if(isset($_GET['page'])){
                $page= $_GET['page'];
            }
            if($action == "USR"){
                include("../admin/listUser.php");
                $name ="Utilisateurs";
                $number_of_users = getNumberOfUsers($db);
            }
           
            $nombre_pages=round( $number_of_users/10 + ($number_of_users%10==0?0:1));
            generatePagination("admin.php",$page,$nombre_pages,$action,$number_of_users ,$name);
        }
        
    ?>
    </div>
    </div>
    
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>