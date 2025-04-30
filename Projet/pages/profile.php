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
    <link rel="stylesheet" href="shop.css">
</head>
<body>
    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        require_once("../admin/pagination.php");
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
            <div class="profile-nav-element">
                <a class ="color-button" href="profile.php?action=HGM">Historique des parties</a>
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
            if(isset($_GET['page'])){
                if(!preg_match("/^[0-9]+$/", $_GET['page'])){
                    header("Location: ../pages/error_page.php");
                    exit();
                }
            }
            $page = 1;
            $idUtilisateur = getUser()->getID();
            if(isset($_GET['page'])){
                $page= $_GET['page'];
            }
            if($action =="HST"){
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
            }else if($action =="HGM"){
                try{
                    $game_per_page= 10;
                    $result = getPartieforUser($db,$page,$game_per_page,getUser()->getID());
                    echo "<table class=\"list-table\">";
                    echo "<thead><tr><td>Premier joueur</td><td>Nombre de coups</td><td>Premier coup</td><td>Adversaire</td><td>Difficulté</td><td></td></tr></thead>";
                    foreach($result as $partie){
                    $id= $partie['id'];
                    $nb_coup = $partie['nb_coup'];
                    $date = $partie['first_coup'];
                    $difficulty = $partie['lvl'];
                    $day = date('d/m/Y', strtotime($date));
                    $hour = date('H:i', strtotime($date));
                    $robot_name = $partie['robot_name'];
                    $first_player = ($partie['first_player']=='X')?"Vous":"Robot";
                    echo "<tr>
                    
                    <td>
                        <div class=\"list-table-element\">$first_player</div>
                    </td>
                    <td>
                        <div class=\"list-table-element\">$nb_coup</div>
                    </td>
                    <td>
                        <div class=\"list-table-element\">
                        $day à $hour
                        </div>
                    </td>
                    <td>
                        <div class=\"list-table-element\">$robot_name</div>
                    </td>
                    <td>
                        <div class=\"list-table-element\">$difficulty</div>
                    </td>
                    <td>
                    <div class=\"list-table-element\">
                        <a class =\"color-button table-button\" href=\"../pages/view.php?id=$id\">Voir la partie</a>
                    </div>
                    </td>
                    </tr>";
                }
                echo "<caption>";
                $name ="Parties";
                $numberofproducts = getNumberOfGamesOfUser($db,$idUtilisateur);
                $nombre_pages=floor( $numberofproducts/$game_per_page + (($numberofproducts%$game_per_page==0)?0:1));
                generatePagination("profile.php",$page,$nombre_pages,"HGM",$numberofproducts ,$name);
                echo "</caption>";
                echo "</table>";
            }catch(PDOException $e){
                echo "Une erreur est survenue";
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
