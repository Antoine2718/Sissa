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
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        require_once("../admin/pagination.php");
        include("../common/nav.php");
    ?>
    <!-- Contenu principal -->
    <div class="admin-content">
        <div class="admin-nav">
            <div class="admin-nav-element">
                <a class ="color-button" href="admin.php?action=STK">Gestion du Stock</a>
            </div>
            <div class="admin-nav-element">
                <a class ="color-button" href="admin.php?action=USR">Liste des utilisateurs</a>
            </div>
            <div class="admin-nav-element">
                <a class ="color-button" href="admin.php?action=HGM">Historique des parties</a>
            </div>
            <div class="admin-nav-element">
                <a class ="color-button" href="admin.php?action=HSH">Historique de la boutique</a>
            </div>
            <div class="admin-nav-element">
                <a class ="color-button" href="admin.php?action=PRM">Gestion des Promotions</a>
            </div>
        </div>
        <div class="data">
        <?php 
        if(isset($_SESSION['message'])){
            //echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
        $db = connect();
        if(!isAdmin()){
            header("Location: ../pages/error_page.php");
            exit();
        }
        if(!empty($_GET) && isset($_GET['action'])){
            $action = $_GET['action'];
            if($action == "USR"){
                include("../admin/listUser.php");
            }else if($action=="UPD"){
                if(isset($_GET['id'])&&preg_match("/^[0-9]+$/",$_GET['id'])){
                    getUpdateForm();
                }else{
                    header("Location: error_page.php");
                    exit();
                }
                
            }else if($action=="HST"){
                if(isset($_GET['id'])&&preg_match("/^[0-9]+$/",$_GET['id'])){
                    $name =getUserWithId($db,$_GET['id'])->getIdentifiant();
                    $title ="Historique de commande de $name";
                    $commandes = getPurchaseHistory($db,$_GET['id']);
                    $commandesParDate = groupPurchasesPerDay($commandes);
                    echo "<div class =\"sub-content\">";
                    include("../cart/afficherHistoriqueCommande.php");
                    echo"</div>";
                }else{
                    header("Location: error_page.php");
                    exit();
                }
            }else if($action=='HSH'){
                include("../admin/listPurchases.php");
            }else if($action =='STK'){
                include("../admin/listArticles.php");
            }else if($action =="MPS"){
                if(isset($_GET['id'])&&preg_match("/^[0-9]+$/",$_GET['id'])){
                    getStockForm();
                }else{
                    header("Location: error_page.php");
                    exit();
                }
            }
            else if($action == "PRM"){
                include("../admin/listPromotions.php");
            }else if($action == "UPP"){
                if(isset($_GET['id']) && preg_match("/^[0-9]+$/", $_GET['id'])){
                    include("../admin/updatePromotionForm.php");
                }else{
                    header("Location: error_page.php");
                    exit();
                }
            }else if($action == "HPD"){
                include("../admin/listPurchasesProduct.php");
            }
        }
    ?>
    </div>
    </div>
    
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>