<?php
function getResultForm($state,$board,$difficulty){
    $available_moves = [];
    foreach ($board as $i => $cell) {
        if ($cell === ' ') {
            $available_moves[] = $i;
        }
    }
    $move_to_win = 9 - count($available_moves);
    
    if(!isset($state['winner'])){
        $win =0;
        $class="nul";
    }else if($state['winner']=="IA"){
        $win =-1;
        $class="defeat";
    }else{
        $win =1;
        $class="win";
    }
    $pts=calculatePoints($win,$difficulty,$move_to_win);
    ?>
    <div id="result" class ="result">
        <h2 class=<?php echo $class?>><?php 
        if(!isset($state['winner'])){
            echo "Match nul";
        }else if($state['winner']=="IA"){
            echo "Défaite";
        }else{
            echo "Victoire";
        }
        ?></h2>
        <div class="result-content">
            <div class ="statistique">
                <h3>Statistiques</h3>
                <hr>
                <p><?php echo "Fin de partie en $move_to_win coups";?></p>
            </div>
            <div class ="resultat">
                <h3>Résultat</h3>
                <hr>
                
                <?php 
                $user =getUser();
                $delta = $pts;
                if($delta==0){
                    $var_pts = "<span class=\"nul\">0</span>";
                }else if($delta<0){
                    $var_pts = "<span class=\"defeat\">$delta</span>";
                }else{
                    $var_pts = "<span class=\"win\">+$delta</span>";
                }
                
                
                echo "<p>Difficulté: $difficulty</p>";
                echo "<p>Points: $delta</p>";
                ?>
            </div>
            <div class="lead">
            <h2>Classement</h2><br>
            <?php 
            $db = connect();
            if(isset($_SESSION['partie']))updatePoints($db,$user->getID(),$delta);
            
            $pts = getUser()->getPoints();
            echo "<p>Vos points: <span class=\"result-pts\">$pts</span> $var_pts</p>";
            $rank = getRang($db,$user->getID());
            $color = $rank['color'];
            $name = $rank['name'];
            echo "<p>Votre rang: <span style=\"color:$color;\">$name</span></p>";
            signout($db);
            unset($_SESSION['partie']);
            ?>
            <a class="color-button" onclick="document.getElementById('result').style.display='none';">Fermer</a>
        </div>
        </div>
        
        
    </div>
<?php
} 
?>