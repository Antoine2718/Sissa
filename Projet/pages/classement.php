<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
    <link rel="stylesheet" href="shop.css">
</head>
<body>

    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php");
        $GLOBALS['user_per_page']=25;
        require_once("../admin/pagination.php");
    ?>
    
    <!-- Contenu principal -->
    <div class="content">
        <div class="leaderboard-global-wrapper">

        
        <div class="leaderboard">
        <div class="leaderboard-head">
            <h1 >Classement Général</h1>
            <hr>
        </div>
        <?php 
        //Affiche les 10 meilleurs joueurs
        $db = connect();
        try{
            $page = 1;
            if(isset($_GET['page'])){
                $page= $_GET['page'];
            }
            $stmt = $db->prepare("SELECT u.identifiant as name,u.points as pts,r.couleur_rang as couleur,r.nomRang as rang FROM utilisateur u inner join rang r on r.idRang = u.idRang order by u.points DESC 
            limit ?, ?");
            $page_size =  $GLOBALS['user_per_page'];
            $debut =($page-1) * $page_size;
            $stmt->bindParam(1,$debut, PDO::PARAM_INT);
            $stmt->bindParam(2,$page_size, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i=($page-1)*$page_size +1;
            echo "<table>";
            echo "<thead><tr class=\"leaderboard-element\"><th>Position</th><th>Nom</th><th>Rang</th><th>Points</th></tr></thead>";
            foreach($result as $user){
                $name = $user['name'];
                $pts = $user['pts'];
                $color = $user['couleur'];
                $rang = $user['rang'];
                
                echo "<tr class=\"leaderboard-element\"><td>$i</td><td>$name</td> <td style = \"color:$color;\">$rang</td> <td class =\"leaderboard-pts\">$pts</td></tr>";
                
                $i=$i+1;
            }
            echo "</table>";
            
            $name ="Utilisateurs";
            $numberofproducts = getNumberOfUsers($db);
            $nombre_pages=floor( $numberofproducts/$GLOBALS['user_per_page'] + (($numberofproducts%$GLOBALS['user_per_page']==0)?0:1));
            generatePagination("classement.php",$page,$nombre_pages,"test",$numberofproducts ,$name);
        }catch(PDOException $e){
            header("Location: ../pages/error_page.php");
            exit();
        }
        ?>
        </div>
        </div>
    </div>
    
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>