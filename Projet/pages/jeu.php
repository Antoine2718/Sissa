<?php
require_once("../common/db.php");
require_once("../common/utilisateur.php");
session_start();

// --- Connexion à la base de données ---
// À adapter
$pdo = connect();

// --- Sélection du mode de jeu ---
if (!isset($_SESSION['mode'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode_selection'])) {
        $_SESSION['mode'] = $_POST['mode_selection'];
        // On gère la difficulté uniquement pour le mode IA
        if ($_SESSION['mode'] === 'computer' && isset($_POST['difficulty'])) {
            $difficulty = intval($_POST['difficulty']);
            $GLOBALS['difficulty'] = $difficulty;
            if ($difficulty < 1) { 
                $difficulty = 1; 
            }
            if ($difficulty > 10) { 
                $difficulty = 10; 
            }
            $_SESSION['difficulty'] = $difficulty;
        } else {
            $_SESSION['difficulty'] = 10; // Valeur par défaut pour le mode 'human'
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
            <h2>Choisissez le mode de jeu</h2>
            <form method="post">
                <input type="radio" name="mode_selection" value="computer" id="computer">
                <label for="computer"><h3>Jouer contre notre IA 🧠 </h3></label><br>

                <input type="radio" name="mode_selection" value="human" id="human" required>
                <label for="human"><h3>Jouer contre un ami</h3></label><br><br>
        <!-- Contenu principal -->
        <div class="content">
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
                                        <img src="<?= $robot['lien_icone'] ?>" style="width:30px;height:30px; vertical-align: middle;">
                                        <?= $robot['nomRobot'] ?> - Niveau : <?= $robot['niveauRobot'] ?>
                                        <input id="difficulty" type="radio" name="difficulty" value="<?= $robot['niveauRobot'] ?>" required>
                                        <br>
                                <?php endforeach; ?>
                                <input type="submit" value="Commencer">
                                </form>
                            </ul>
                        <?php else: ?>
                            <p>Aucun robot disponible.</p>
                        <?php endif; ?>
                    </div>
                <?php /* endif; */ ?>
                </div>
                <br>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var computerRadio = document.getElementById("computer");
                    var humanRadio = document.getElementById("human");
                    var difficultyContainer = document.getElementById("difficulty-container");
                    
                    function toggleDifficulty() {
                        if (computerRadio.checked) {
                            difficultyContainer.style.display = "block";
                        } else {
                            difficultyContainer.style.display = "none";
                        }
                    }
                    computerRadio.addEventListener('change', toggleDifficulty);
                    humanRadio.addEventListener('change', toggleDifficulty);
                    toggleDifficulty();
                });
            </script>
            <div>
            <?php
            include("../common/footer.php");
            ?>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}



// --- Réinitialisation du jeu ---
if (isset($_POST['reset'])) {
    session_destroy();
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

    // $IDpartie et $IDmove a récupérer
    $player = getUser();
    $date = date('m-d-Y');
    $stmt = $pdo->prepare("INSERT INTO joue_coup (idPartie, idCoup, date_coup) VALUES (:idPartie, :idCoup, :date_coup)");
    $stmt->execute([':idPartie' => $IDpartie, ':idCoup' => $IDmove, ':date_coup' => $date]);
}

// Gestion nouvelle partie
$player = getUser();
global $pdo;
$date = date('m-d-Y');
$difficulty = $GLOBALS['difficulty'];
$stmt = $pdo->prepare("INSERT INTO partie (date_premier_coup, premier_joueur, idRobot, idUtilisateur) VALUES (:date_pc, :firs, :id1, :id2)");
$stmt->execute([':date_pc' => $date, ':firs' => $player, ':id1' => $difficulty, ':id2' => $player]);

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

// --- Si le mode est ordinateur et c'est le tour de l'IA, jouer le coup de l'ordinateur ---
if ($_SESSION['mode'] === 'computer' && $_SESSION['current_player'] === 'O' && $winner === null) {
    // Récupère le niveau de difficulté (1 = toujours aléatoire, 10 = toujours minimax)
    $difficulty = isset($_SESSION['difficulty']) ? (int)$_SESSION['difficulty'] : 10;
    // Calcul de la probabilité d'utiliser l'algorithme minimax :
    // Pour le niveau 1 --> (1-1)/9 = 0 (coup aléatoire)
    // Pour le niveau 10 --> (10-1)/9 = 1 (coup optimisé)
    $minimaxProbability = ($difficulty - 1) / 9;
    $rand = mt_rand(0, 100000) / 100000;
    
    if ($rand < $minimaxProbability) {
        $aiMove = best_move($_SESSION['board']);
    } else {
        // Choix aléatoire parmi les coups disponibles
        $available_moves = [];
        foreach ($_SESSION['board'] as $i => $cell) {
            if ($cell === ' ') {
                $available_moves[] = $i;
            }
        }
        $aiMove = $available_moves[array_rand($available_moves)];
    }
    if ($aiMove !== -1 && $_SESSION['board'][$aiMove] === ' ') {
        $_SESSION['board'][$aiMove] = 'O';
        $_SESSION['history_O'][] = $aiMove;
        $_SESSION['current_player'] = 'X';
        $winner = checkWinner($_SESSION['board']);
    }
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

        <!-- Bouton de réinitialisation visible à tout moment -->
        <form method="post" class="reset-btn">
            <input type="submit" name="reset" value="Nouvelle partie" />
        </form>

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
