<?php
    require_once("../common/db.php");
    $GLOBALS['product_per_page']= 10;
    function afficheArticles($db,$page){
        try{
            $result = getProducts($db,$page,$GLOBALS['product_per_page']);
            echo "<table class=\"list-table\">";
            echo "<thead><tr><td>ID</td><td>Nom Produit</td><td>Statut du Stock</td><td>Stock</td><td>Prix</td><td>Catégorie</td><td></td></tr></thead>";
            foreach($result as $product){
                $id= $product['id'];
                $name= $product['name'];
                $status_style = $product['stk']==0?"#ff2500":"#008b8b";
                $status= $product['stk']==0?"Rupture de stock":"En Stock";
                $stock= $product['stk'];
                $prix = $product['prix'];
                $categorie = $product['ctg'];
                echo "<tr>
                <td>
                    <div class=\"list-table-element\">$id</div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $name
                </div>
                </td>
                <td>
                <div style=\"color: $status_style;\"class=\"list-table-element\">
                    $status
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $stock
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $prix
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $categorie
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    <a class =\"color-button table-button\" href=\"../pages/admin.php?action=MPS&id=$id\">Modifier le stock</a>
                </div>
                </td>
                </tr>";
            }
            echo "<caption>";
            $name ="Produits";
            $numberofproducts = getNumberOfProducts($db);
            $nombre_pages=floor( $numberofproducts/$GLOBALS['product_per_page'] + (($numberofproducts%$GLOBALS['product_per_page']==0)?0:1));
            generatePagination("admin.php",$page,$nombre_pages,"STK",$numberofproducts ,$name);
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
    afficheArticles($db,$page);
    signout($db);
?>