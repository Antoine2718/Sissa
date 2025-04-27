<?php
    require_once("../common/db.php");
    $GLOBALS['user_per_page']= 10;
    function afficheUtilisateurs($db,$page){
        try{
            $stmt = $db->prepare(
                "SELECT idUtilisateur as id,identifiant,points as pts,type, r.NomRang as rang,r.couleur_rang as color FROM utilisateur u 
                inner join rang r on u.idRang = r.idRang
                order by 1
                limit ?,?
                "
            );
            $debut = ($page-1) * $GLOBALS['user_per_page'];
            $stmt->bindParam(1,$debut, PDO::PARAM_INT);
            $stmt->bindParam(2,$GLOBALS['user_per_page'], PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<table class=\"list-table\">";
            echo "<thead><tr><td>ID</td><td>Nom</td><td>Points</td><td>Role</td><td>Rang</td><td></td></tr></thead>";
            foreach($result as $user){
                $nom = $user['identifiant'];
                $type = $user['type'];
                $pts = $user['pts'];
                $id = $user['id'];
                $rang = $user['rang'];
                $color = $user['color'];
                echo "<tr>
                <td>
                    <div class=\"list-table-element\">$id</div>
                </td>
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
                <td>
                <div style =\"color:$color;\" class=\"list-table-element\">
                    $rang
                </div>
                </td>
                <td>
                <div class=\"list-table-element\">
                    <a class =\"color-button table-button\" href=\"../pages/admin.php?action=UPD&id=$id\">Modifier</a>
                    <a class =\"color-button table-button\" href=\"../pages/admin.php?action=HST&id=$id\">Commandes</a>
                </div>
                </td>
                </tr>";
            }
            echo "<caption>";
            $name ="Utilisateurs";
            $number_of_users = getNumberOfUsers($db);
            $nombre_pages=floor( $number_of_users/$GLOBALS['user_per_page'] + ($number_of_users%$GLOBALS['user_per_page']==0?0:1));
            generatePagination("admin.php",$page,$nombre_pages,"USR",$number_of_users ,$name);
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
    afficheUtilisateurs($db,$page);
    signout($db);
?>