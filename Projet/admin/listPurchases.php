<?php
    require_once("../common/db.php");
    $GLOBALS['purchases_per_page']= 10;
    function afficheCommandes($db,$page){
        try{
            $result = getPurchases($db,$page,$GLOBALS['purchases_per_page']);
            echo "<table class=\"list-table\">";
            echo "<thead><tr><td>Acheteur</td><td>Produit</td><td>Quantité</td><td>Date d'achat</td><td></td></tr></thead>";
            foreach($result as $commande){
                $nom = $commande['identifiant'];
                $produit = $commande['produit'];
                $qte = $commande['qte'];
                $id = $commande['id'];
                $idP = $commande['idP'];
                $date = $commande['date'];
                $day = date('d/m/Y', strtotime($date));
                $hour = date('H:i', strtotime($date));
                echo "<tr>
                <td>
                    <div class=\"list-table-element\">$nom</div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $produit
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $qte
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $day à $hour
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    <a class =\"color-button table-button\" href=\"../pages/admin.php?action=HST&id=$id\">Voir pour cet utilisateur</a>
                    <a class =\"color-button table-button\" href=\"../pages/admin.php?action=HPD&id=$idP\">Voir pour ce produit</a>
                </div>
                </td>
                </tr>";
            }
            echo "<caption>";
            $name ="Commandes";
            $number_of_purchases = getNumberOfPurchases($db);
            $nombre_pages=floor( $number_of_purchases/$GLOBALS['purchases_per_page'] + (($number_of_purchases%$GLOBALS['purchases_per_page']==0)?0:1));
            generatePagination("admin.php",$page,$nombre_pages,"HSH",$number_of_purchases ,$name);
            echo "</caption>";
            echo "</table>";
        }catch(PDOException $e){
            echo "Une erreur est survenue";
        }
    }
    $db = connect();
    $page = 1;
    if(isset($_GET['page'])){
        $page= $_GET['page'];
    }
    afficheCommandes($db,$page);
    signout($db);
?>