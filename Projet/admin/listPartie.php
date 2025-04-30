<?php
    require_once("../common/db.php");
    $GLOBALS['game_per_page']= 10;
    function affichePartie($db,$page){
        try{
            $result = getPartie($db,$page,$GLOBALS['game_per_page']);
            echo "<table class=\"list-table\">";
            echo "<thead><tr><td>ID Partie</td><td>Utilisateur</td><td>Nombre de coups</td><td>Premier joueur</td><td>Premier coup</td><td>Adversaire</td><td>Difficulté</td><td></td></tr></thead>";
            foreach($result as $partie){
                $id= $partie['id'];
                $name= $partie['player'];
                $nb_coup = $partie['nb_coup'];
                $date = $partie['first_coup'];
                $difficulty = $partie['lvl'];
                $day = date('d/m/Y', strtotime($date));
                $hour = date('H:i', strtotime($date));
                $robot_name = $partie['robot_name'];
                $first_player = ($partie['first_player']=='X')?"Joueur":"Robot";
                echo "<tr>
                <td>
                    <div class=\"list-table-element\">$id</div>
                </td>
                <td>
                    <div class=\"list-table-element\">$name</div>
                </td>
                <td>
                    <div class=\"list-table-element\">$nb_coup</div>
                </td>
                <td>
                    <div class=\"list-table-element\">$first_player</div>
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
            $numberofproducts = getNumberOfGames($db);
            $nombre_pages=floor( $numberofproducts/$GLOBALS['game_per_page'] + (($numberofproducts%$GLOBALS['game_per_page']==0)?0:1));
            generatePagination("admin.php",$page,$nombre_pages,"HGM",$numberofproducts ,$name);
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
    affichePartie($db,$page);
    signout($db);
?>