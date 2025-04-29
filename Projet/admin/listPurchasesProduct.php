<?php
    require_once("../common/db.php");
    $GLOBALS['purchases_per_page']= 10;
    function afficheCommandesProduit($db,$page,$idProduit){
        try{
            $result = getPurchasesForProduct($db,$page,$GLOBALS['purchases_per_page'],$idProduit);
            echo "<table class=\"list-table\">";
            echo "<thead><tr><td>Acheteur</td><td>Quantité</td><td>Date d'achat</td></tr></thead>";
            foreach($result as $commande){
                $nom = $commande['identifiant'];
                $qte = $commande['qte'];
                $date = $commande['date'];
                $day = date('d/m/Y', strtotime($date));
                $hour = date('H:i', strtotime($date));
                echo "<tr>
                <td>
                    <div class=\"list-table-element\">$nom</div>
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
                </tr>";
            }
            echo "<caption>";
            $name ="Commandes";
            $number_of_purchases = getNumberOfPurchasesForProduct($db,$idProduit);
            $nombre_pages=floor( $number_of_purchases/$GLOBALS['purchases_per_page'] + (($number_of_purchases%$GLOBALS['purchases_per_page']==0)?0:1));
            generatePagination("admin.php",$page,$nombre_pages,"HPD",$number_of_purchases ,$name);
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
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }else{
        header("Location:admin.php?action=HSH");
        exit();
    }
    afficheCommandesProduit($db,$page,$id);
    signout($db);
?>