<?php
require_once("../common/db.php");
require_once("../common/utilisateur.php");
session_start();

// --- Connexion à la base de données ---
// À adapter
// fonction connect() de db.php
$pdo = connect();

// --- Sélection du mode de jeu ---
if (!isset($_SESSION['mode'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode_selection'])) {
        $_SESSION['mode'] = $_POST['mode_selection'];
        // On gère la difficulté uniquement pour le mode IA
        if ($_SESSION['mode'] === 'computer' && isset($_POST['difficulty'])) {
            $difficulty = intval($_POST['difficulty']);
            if ($difficulty < 1) { 
                $difficulty = 1; 
            }
            if ($difficulty > 10) { 
                $difficulty = 10; 
            }
            // Mettre difficulty dans Session et globals
            $_SESSION['difficulty'] = $difficulty;
            $GLOBALS['difficulty'] = $difficulty;
        } else {
            // Sinon, par defaut, algo optimal
            $_SESSION['difficulty'] = 10; // Valeur par défaut pour le mode 'human'
            $GLOBALS['difficulty'] = 10;
        }
        // Si l'utilisateur n'est pas connecté
        if(!isConnected()){
            header("Location: ../pages/login.php");
            exit();
        }
        
    } else {
        // Affichage du formulaire de choix de mode
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Sissa - Choix du mode</title>
            <style>
                body { font-family: Helvetica, sans-serif; text-align: center; }
                h2 { color: #005eff; }
            </style>
                <?php //Ajoute la barre de navigation
                include("../common/styles.php")
                ?>
        </head>
        <body>
        <?php //Ajoute la barre de navigation
            require_once("../common/db.php");
            include("../common/nav.php")
        ?>
        <div class="content">
            <h2>Choisissez le mode de jeu</h2>
            <form method="post">
                <input type="radio" name="mode_selection" value="computer" id="computer">
                <label for="computer"><h3>Jouer contre notre IA 🧠 </h3></label><br>

                <input type="radio" name="mode_selection" value="human" id="human" required>
                <label for="human"><h3>Jouer contre un ami</h3></label><br><br>
                <div id="com-container" style="display: none;">
                    // Ce container sert dans la gestion dynamique en JS ci-dessous
                    <input type="submit" value="Commencer">
                </div>
        <!-- Contenu principal -->
        
        <?php /* if ($_SESSION["mode_selection"] === "computer"): */ ?>
        <!-- Affichage de la liste des robots disponibles -->
        <?php
        // --- Si on joue en mode IA, on récupère tous les robots depuis la BDD ---
            try {
                $stmt = $pdo->query("SELECT idRobot, nomRobot, niveauRobot, lien_icone FROM robot");
                $robots = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Erreur lors de la récupération des robots : " . $e->getMessage();
                $robots = [];
            }
        ?>
    
                <div id="difficulty-container" style="display: none;">
                    <label for="difficulty">Niveau de difficulté pour l'IA : </label>
                    <div class="robots">
                        <h3>IA disponibles</h3>
                        <?php if (!empty($robots)): ?>
                            <ul>
                                <form>
                                <?php foreach ($robots as $robot): ?>
                                        <img src="<?= $robot['lien_icone'] ?>" style="width:200px;height:200px; vertical-align: middle; border-radius: 40%;">
                                        <br>
                                        <h3> <?= $robot['nomRobot'] ?> </h3> Niveau : <?= $robot['niveauRobot'] ?>
                                        <input id="difficulty" type="radio" name="difficulty" value="<?= $robot['niveauRobot'] ?>">
                                            <br>
                                            <br>
                                <?php endforeach; ?>
                                </form>
                            </ul>
                        <?php else: ?>
                            <p>Aucun robot disponible.</p>
                        <?php endif; ?>
                    </div>
                <?php /* endif; */ ?>
                <input type="submit" value="Commencer">   
                </div>
                <br>
            </form>
            </div>
            // JS qui permet d'eviter d'avoir deux commencer dans le formulaire
            // Pour ce faire, gestion dynamique en fonction de la selection
            <script>
                
                document.addEventListener('DOMContentLoaded', function() {
                    var computerRadio = document.getElementById("computer");
                    var humanRadio = document.getElementById("human");
                    var difficultyContainer = document.getElementById("difficulty-container");
                    var comContainer = document.getElementById("com-container");
                    
                    function toggleDifficulty() {
                        if (computerRadio.checked) {
                            difficultyContainer.style.display = "block";
                            comContainer.style.display = "none";
                        } else {
                            difficultyContainer.style.display = "none";
                            comContainer.style.display = "block";
                        }
                    }
                    computerRadio.addEventListener('change', toggleDifficulty);
                    humanRadio.addEventListener('change', toggleDifficulty);
                    toggleDifficulty();
                });
            </script>
            
            <?php
            include("../common/footer.php");
            ?>
            
        </body>
        </html>
        <?php
        exit();
    }
}
// --- Réinitialisation du jeu ---
if (isset($_POST['reset'])) {
    // unset tout ce qui doit l'être (ont evite d'arreter la session
    unset($_SESSION['partie']);
    unset($_SESSION['board']);
    unset($_SESSION['difficulty']);
    unset($GLOBALS['difficulty']);
    unset($_SESSION['mode']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// --- Initialisation du plateau et des données de session ---
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '];
    $_SESSION['current_player'] = 'X';
    $_SESSION['history_X'] = []; // Historique des coups du joueur X (humain)
    $_SESSION['history_O'] = []; // Historique des coups du joueur O (humain ou IA)
}

// --- Fonctions de gestion du jeu ---
// Vérifie s'il y a un gagnant sur le plateau
function checkWinner($board) {
    $winning_combinations = [
        [0, 1, 2], [3, 4, 5], [6, 7, 8], // Lignes
        [0, 3, 6], [1, 4, 7], [2, 5, 8], // Colonnes
        [0, 4, 8], [2, 4, 6]             // Diagonales
    ];
    
    foreach ($winning_combinations as $combination) {
        if ($board[$combination[0]] !== ' ' &&
            $board[$combination[0]] === $board[$combination[1]] &&
            $board[$combination[1]] === $board[$combination[2]]) {
            return $board[$combination[0]];
        }
    }
    return null;
}

// Implémentation de l'algorithme Minimax
// 'O' est joué par l'ordinateur et 'X' par l'humain
function minimax($board, $depth, $is_maximizing) {
    $winner = checkWinner($board);
    if ($winner !== null) {
        if ($winner === 'O') {
            return 10 - $depth;
        } elseif ($winner === 'X') {
            return $depth - 10;
        }
    }
    if (!in_array(' ', $board)) {
        return 0;
    }
    
    if ($is_maximizing) {
        $bestScore = -INF;
        for ($i = 0; $i < count($board); $i++) {
            if ($board[$i] === ' ') {
                $board[$i] = 'O';
                $score = minimax($board, $depth + 1, false);
                $board[$i] = ' ';
                $bestScore = max($score, $bestScore);
            }
        }
        return $bestScore;
    } else {
        $bestScore = INF;
        for ($i = 0; $i < count($board); $i++) {
            if ($board[$i] === ' ') {
                $board[$i] = 'X';
                $score = minimax($board, $depth + 1, true);
                $board[$i] = ' ';
                $bestScore = min($score, $bestScore);
            }
        }
        return $bestScore;
    }
}

// Détermine le meilleur coup pour l'ordinateur
function best_move($board) {
    $bestScore = -INF;
    $move = -1;
    for ($i = 0; $i < count($board); $i++) {
        if ($board[$i] === ' ') {
            $board[$i] = 'O';
            $score = minimax($board, 0, false);
            $board[$i] = ' ';
            if ($score > $bestScore) {
                $bestScore = $score;
                $move = $i;
            }
        }
    }
    return $move;
}

require_once("../common/db.php");
// Fonction qui enregistre un coup dans la base de données
// Chaque coup est caractérisé par un code (ici, l'indice de la case) et un numéro de coup
function logMove($cell) {
    global $pdo;
    $moveNumber = count($_SESSION['history_X']) + count($_SESSION['history_O']);
    $stmt = $pdo->prepare("INSERT INTO coup (code_coup, numero_coup) VALUES (:code, :numero)");
    $stmt->execute([':code' => $cell, ':numero' => $moveNumber]);
    // Dernier ID inseret dans la base pour recuperer idcoup
    $idCoup = $pdo->lastInsertId();
    if(isset($_SESSION['partie'])){
        $idPartie = $_SESSION['partie'];
    }else{
        //crée une nouvelle partie 
        //insere dans la base de donnée
        $difficulty = $_SESSION['difficulty'];
        $player = getUser();
        global $pdo;
        $date = date('m-d-Y');
        $char = 'X';
        $id = $player->getID();
        $stmt = $pdo->prepare("INSERT INTO partie (date_premier_coup, premier_joueur, idRobot, idUtilisateur) VALUES (:date_pc, :firs, :id1, :id2)");
        $stmt->execute([':date_pc' => $date, ':firs' => $char, ':id1' => $difficulty, ':id2' => $id]);
        $idPartie = $pdo->lastInsertId();
        $_SESSION['partie'] = $idPartie;
    }
    // $IDpartie et $IDmove a récupérer
    $player = getUser();
    $date = date('m-d-Y');
    $stmt = $pdo->prepare("INSERT INTO joue_coup (idPartie, idCoup, date_coup) VALUES (:idPartie, :idCoup, :date_coup)");
    $stmt->execute([':idPartie' => $idPartie, ':idCoup' => $idCoup, ':date_coup' => $date]);
}

/*
// Gestion nouvelle partie

*/
// --- Gestion du coup joué par l'humain ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cell'])) {
    $cell = intval($_POST['cell']);
    
    if ($_SESSION['board'][$cell] === ' ' && checkWinner($_SESSION['board']) === null) {
        $_SESSION['board'][$cell] = $_SESSION['current_player'];
        
        if ($_SESSION['current_player'] === 'X') {
            $_SESSION['history_X'][] = $cell;
        } else {
            $_SESSION['history_O'][] = $cell;
        }
        // Envoi du coup dans la BDD
        logMove($cell);
        $_SESSION['current_player'] = $_SESSION['current_player'] === 'X' ? 'O' : 'X';
    }
}

// Vérification du gagnant après le coup joué par l'humain
$winner = checkWinner($_SESSION['board']);
$available_moves = [];
foreach ($_SESSION['board'] as $i => $cell) {
    if ($cell === ' ') {
        $available_moves[] = $i;
    }
}
// --- Si le mode est ordinateur et c'est le tour de l'IA, jouer le coup de l'ordinateur ---
if ($_SESSION['mode'] === 'computer' && $_SESSION['current_player'] === 'O' && $winner == null && count($available_moves)!=0) {
    // Récupère le niveau de difficulté (1 = toujours aléatoire, 10 = toujours minimax)
    $difficulty = isset($_SESSION['difficulty']) ? (int)$_SESSION['difficulty'] : 10;
    // Calcul de la probabilité d'utiliser l'algorithme minimax :
    // Pour le niveau 1 --> (1-1)/9 = 0 (coup aléatoire)
    // Pour le niveau 10 --> (10-1)/9 = 1 (coup optimisé)
    // Pour la selection de l'IA, tout ce passe dans minimaxProbability
    $minimaxProbability = ($difficulty - 1) / 9;
    $rand = mt_rand(0, 100000) / 100000;
    
    if ($rand < $minimaxProbability) {
        $aiMove = best_move($_SESSION['board']);
    } else {
        // Choix aléatoire parmi les coups disponibles
        
        $aiMove = $available_moves[array_rand($available_moves)];
    }
    logMove($aiMove);
    if ($aiMove !== -1 && $_SESSION['board'][$aiMove] === ' ') {
        $_SESSION['board'][$aiMove] = 'O';
        $_SESSION['history_O'][] = $aiMove;
        $_SESSION['current_player'] = 'X';
        $winner = checkWinner($_SESSION['board']);
    }
}
//TODO vérifie une victoire match null ou victoire bot ou victoire joueur
//Uniquement lorsque la partie est terminée on affiche nouvelle partie bouton

// Fonction Win pour comptage points
function Win() {
    $data = [];
    $winner = checkWinner($_SESSION['board']);
    // Parité :
    /* 
    Cout restant pair = > IA perdu
    Cout restant impair = > IA gagné
    */
    if($winner) {
        $data['win'] = True;
    
        if(count($available_moves) % 2 == 1) {
            $data['winner'] = 'IA';
            $data['IdWinner'] = $difficulty;
        } else {
            $player = getUser();
            $data['IdWinner'] = $player->getID();
        }
    } else {
        $data['win'] = False;
    }

    // Retouner : Bool gagné ou non, IA gagné ?, ID IA Gagnante sinon ID utilisateur
    return $data;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <!--Ajoute les pages de styles-->
    <style>
        body { font-family: Helvetica, sans-serif; }
        .container {  
            align-items: center;
            justify-content: center;
            margin: auto;
            position: relative;
            text-align: center;
            width: 306px;
        }
        .board { 
            display: grid; 
            grid-template-columns: repeat(3, 100px); 
            gap: 5px; 
            margin-top: 10px; 
        }
        .cell {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2em;
            background-color: white;
            border: 2px solid #e1e1e1;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .cell:hover {
            color: white;
            background-color: #005eff;
        }
        .winner { color: blue; font-weight: bold; }
        .history { margin-top: 10px; }
        .history ul { list-style-type: none; padding: 0; }
        .history li { background: #eee; margin: 4px 0; padding: 4px 8px; border-radius: 4px; }
        .reset-btn { margin-top: 10px; }
        .robots {
            margin: 20px auto;
            width: 306px;
            text-align: left;
            background-color: #f5f5f5;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .robots ul {
            list-style: none;
            padding: 0;
        }
        .robots li {
            margin: 5px 0;
        }
    </style>
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
</head>
<body>
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php")
    ?>
    <div class="container">
        <div class="board">
            <?php foreach ($_SESSION['board'] as $index => $value): ?>
                <div class="cell" onclick="document.getElementById('cellInput<?= $index ?>').submit();">
                    <?= $value ?>
                    <form id="cellInput<?= $index ?>" method="post" style="display: none;">
                        <input type="hidden" name="cell" value="<?= $index ?>">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Affichage du gagnant ou du match nul -->
        <?php if ($winner): ?>
            <h2 class="winner"><?= $winner ?> a gagné !</h2>
        <?php elseif (!in_array(' ', $_SESSION['board'])): ?>
            <h2>Match nul !</h2>
        <?php endif; ?>

        <!-- Bouton de réinitialisation -->
        // Uniquement en fin de partie
        <?php if ($winner): ?>
            <form method="post" class="reset-btn">
                <input type="submit" name="reset" value="Nouvelle partie" />
            </form>
        <?php endif; ?>


        <!-- Affichage de l'historique des coups -->
        <div class="history">
            <div>
                <h3>Joueur X</h3>
                <ul>
                    <?php foreach ($_SESSION['history_X'] as $move): ?>
                        <li>Joué en <?= $move ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3>Joueur O</h3>
                <ul>
                    <?php foreach ($_SESSION['history_O'] as $move): ?>
                        <li>Joué en <?= $move ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
