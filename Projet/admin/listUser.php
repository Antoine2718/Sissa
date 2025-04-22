<?php
    require_once("../common/db.php");
    $GLOBALS['user_per_page']= 10;
    function afficheUtilisateurs($db,$page){
        try{
            $stmt = $db->prepare("SELECT identifiant,points as pts,type FROM utilisateur limit ?,?");
            $debut = ($page-1) * $GLOBALS['user_per_page'];
            $stmt->bindParam(1,$debut, PDO::PARAM_INT);
            $stmt->bindParam(2,$GLOBALS['user_per_page'], PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<table class=\"list-table\">";
            echo "<thead><tr><td>Nom</td><td>Points</td><td>Role</td></tr></thead>";
            foreach($result as $user){
                $nom = $user['identifiant'];
                $type = $user['type'];
                $pts = $user['pts'];
                echo "<tr>
                <td>
                    <div class=\"list-table-element\">$nom</div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $pts
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    $type
                </div>
                </td>
                </tr>";
            }
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
    afficheUtilisateurs($db,$page);
    signout($db);
?>