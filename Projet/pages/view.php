<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revoir une partie</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
    <link rel="stylesheet" href ="jeu.css">
</head>
<body>

    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php")
    ?>
    <?php 
    //début des vérication de sécurité

    //on vérifie que le coup est bien saisi
    $coup = 1;
    if(isset($_GET['coup'])){
        if(!preg_match("/^[0-9]$/", $_GET['coup'])){
            header("Location: ../pages/error_page.php");
            exit();
        }
        $coup = $_GET['coup'];
    }
    //on vérifie que l'id est bien un entier
    if(!isset($_GET['id']) || !preg_match("/^[0-9]+$/", $_GET['id'])){
        header("Location: ../pages/error_page.php");
        exit();
    }
    $partie = $_GET['id'];
    //on vérifie que la partie avec cet id appartient bien au joueur si le joueur n'est pas admin
    if(!isAdmin()){
        try{
            $stmt = $db->prepare("SELECT idUtilisateur as id FROM partie where idPartie =?");
            $stmt->bindParam(1,$partie, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result['id']!=getUser()->getID()){
                header("Location: ../pages/error_page.php");
                exit();
            }
        }catch(PDOException $e){
            header("Location: ../pages/error_page.php");
            exit();
        }

    }
    //on recupère tout les coups de la partie qui correspondent
    try{
        $stmt = $db->prepare("SELECT c.code_coup FROM joue_coup j 
        inner join coup c on c.idCoup = j.idCoup
        where j.idPartie =? and c.numero_coup<= ?");
        $stmt->bindParam(1,$partie, PDO::PARAM_INT);
        $stmt->bindParam(2,$coup, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
    //on récupère le premier joueur et le nom du robot
    try{
        $stmt = $db->prepare("SELECT premier_joueur, nomRobot,niveauRobot FROM partie inner join robot on robot.idRobot = partie.idRobot
        where idPartie =?");
        $stmt->bindParam(1,$partie, PDO::PARAM_INT);
        $stmt->execute();
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        $player = $r['premier_joueur'];
        $robot = $r['nomRobot'];
        $difficulty = $r['niveauRobot'];
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
    $board = [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '];
    foreach($result as $row){
        $board[intval($row['code_coup'])] = $player;
        if($player=='X'){
            $player ="O";
        }else{
            $player ="X";
        }

    }
    ?>
    <!-- Contenu principal -->
    <div class="container">
        <h1>Revoir la partie</h1>
        <h2>Partie contre <?php echo "$robot (Difficulté: $difficulty)";?></h2>
        <div class="board">
            <?php foreach ($board as $index => $value): ?>
                <div class="cell">
                    <?= $value ?>
                    <form id="cellInput<?= $index ?>" method="post" style="display: none;">
                        <input type="hidden" name="cell" value="<?= $index ?>">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <h2>Contrôle des coups</h2>
    </div>
    <div class="control">
            <?php 
            echo "<a class=\"color-button\" href=\"view.php?id=$partie&coup=1\">Premier</a>";
            $previous = max(array($coup-1,1));
            $next = min(array($coup+1,9));
            echo "<a class=\"color-button\" href=\"view.php?id=$partie&coup=$previous\">Précédent</a>";
            echo "<a class=\"color-button\" href=\"view.php?id=$partie&coup=$next\">Suivant</a>";
            echo "<a class=\"color-button\" href=\"view.php?id=$partie&coup=9\">Dernier</a>";
            ?>
    </div>
    
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
