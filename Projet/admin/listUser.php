<?php
    require_once("../common/db.php");
    function afficheUtilisateurs($db){
        try{
            $stmt = $db->prepare("SELECT identifiant,type FROM utilisateur");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<table>";
            echo "<thead><tr><td>Nom</td><td>Role</td></tr></thead>";
            foreach($result as $user){
                
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
    afficheUtilisateurs($db);
    signout($db);
?>