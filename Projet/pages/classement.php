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
</head>
<body>

    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php")
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
            $stmt = $db->prepare("SELECT u.identifiant as name,u.points as pts,r.couleur_rang as couleur,r.nomRang as rang FROM utilisateur u inner join rang r on r.idRang = u.idRang order by u.points DESC limit 15");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i=1;
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