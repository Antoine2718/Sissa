<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="historique_commande.css">
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
                <a class ="color-button" href="profile.php?action=PRF">Voir le profil</a>
            </div>
            <div class="profile-nav-element">
                <a class ="color-button" href="profile.php?action=HST">Historique d'achat</a>
            </div>
        </div>
        <div class ="data">
            <?php 
            $pdo = connect();
            if(!isConnected()){
                header("Location: ../pages/error_page.php");
                exit();
            }
            $user = getUser();
            if($action =="HST"){
                
                $idUtilisateur = getUser()->getID();
                $commandes = getPurchaseHistory($pdo,$idUtilisateur);
                // Regrouper les commandes par date
                $commandesParDate = groupPurchasesPerDay($commandes);
                $isMessageToggled=false;
                $title="Historique de vos commandes";
                include("../cart/afficherHistoriqueCommande.php");
                signout($pdo);
            }else if($action =="PRF"){
                $name = $user->getIdentifiant();
                $rank = getRang($pdo,$user->getID());
                $rank_name =$rank['name'];
                $rank_color = $rank['color'];
                $pts = $user->getPoints();
                echo "<div class =\"profile-container\">";
                echo "<h1>Votre profil</h1>";
                echo "<div class =\"profile-group\"><h2>Nom d'Utilisateur</h2><p>$name</p></div><hr>";
                echo "<div class =\"profile-group\"><h2>Classement</h2><p style =\"color:$rank_color;\">Rang: $rank_name</p><p>Points: $pts</p></div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
    
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
