<?php 
require_once("../common/db.php");
$GLOBALS['refund_per_page']= 10;
function afficheRemboursement($db,$page){
    try{
        $result = getRemboursementOf($db,getUser()->getID(),$page,$GLOBALS['refund_per_page']);
        echo "<table class=\"list-table\">";
        echo "<thead><tr><td>Produit</td><td>Date remboursement</td><td>Prix Remboursé</td></tr></thead>";
        foreach($result as $refund){
            $nom = $refund['name'];
            $prix = $refund['prix'];
            $date = $refund['date'];
            $day = date('d/m/Y', strtotime($date));
            $hour = date('H:i', strtotime($date));
            echo "<tr>
            <td>
                <div class=\"list-table-element\">$nom</div>
            </td>
            <td>
            <div class=\"list-table-element\">
                    $day à $hour
            </div>
            </td>
            <td>
                <div class=\"list-table-element\">$prix</div>
            </td>
            </tr>";
        }
        echo "<caption>";
        $name ="Reboursements";
        $number_of_purchases = count($result);
        $nombre_pages=floor( $number_of_purchases/$GLOBALS['refund_per_page'] + (($number_of_purchases%$GLOBALS['refund_per_page']==0)?0:1));
        generatePagination("profile.php",$page,$nombre_pages,"RBS",$number_of_purchases ,$name);
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
afficheRemboursement($db,$page);
signout($db);
?>