<div class="leaderboard-wrapper">
<div class="leaderboard">
    <div class="leaderboard-head">
        <h1>Classement</h1>
        <hr>
    </div>
    
    <div class="leaderboard-content">
        <?php 
        //Affiche les 10 meilleurs joueurs
        $db = connect();
        try{
            $stmt = $db->prepare("SELECT u.identifiant as name,u.points as pts,r.couleur_rang as couleur,r.nomRang as rang FROM utilisateur u inner join rang r on r.idRang = u.idRang order by u.points DESC limit 10");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i=1;
            foreach($result as $user){
                $name = $user['name'];
                $pts = $user['pts'];
                $color = $user['couleur'];
                $rang = $user['rang'];
                echo "<div class=\"leaderboard-element\">";
                echo "<p>$name</p> <p style = \"color:$color;\">$rang</p> <p class =\"leaderboard-pts\">$pts</p>";
                echo "</div>";
                $i=$i+1;
            }
        }catch(PDOException $e){
            header("Location: ../pages/error_page.php");
            exit();
        }
        ?>
    </div>
</div>
</div>
