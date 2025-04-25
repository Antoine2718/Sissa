<?php
session_start();

// --- Sélection du mode de jeu ---
if (!isset($_SESSION['mode'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mode_selection'])) {
        $_SESSION['mode'] = $_POST['mode_selection'];
        // Si le mode est "computer", on récupère le niveau de difficulté choisi
        if ($_SESSION['mode'] === 'computer' && isset($_POST['difficulty'])) {
            $_SESSION['difficulty'] = $_POST['difficulty'];
        }
    } else {
        // Affichage du formulaire de choix de mode avec menu de difficulté pour l'IA
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Sissa</title>
            <style>
                body { 
                    font-family: Helvetica, sans-serif; 
                    text-align: center; 
                }
                h2 { 
                    color: #005eff; 
                }
                /* Le menu de difficulté est caché par défaut */
                #difficulty-menu {
                    display: none;
                    margin-top: 20px;
                }
                /* Grâce à la pseudo-classe :checked, on affiche le menu si le bouton "computer" est coché */
                input[type="radio"]#computer:checked ~ #difficulty-menu {
                    display: block;
                }
            </style>
        </head>
        <body>
            <h2>Choisissez le mode de jeu</h2>
            <form method="post">
                <!-- Option de jouer contre notre IA -->
                <input type="radio" name="mode_selection" value="computer" id="computer">
                <label for="computer">Jouer contre notre IA 🧠</label><br>
                
                <!-- Menu de choix de niveau, visible uniquement si "computer" est sélectionné -->
                <div id="difficulty-menu">
                    <label for="difficulty">Choisissez le niveau de difficulté :</label><br>
                    <select name="difficulty" id="difficulty">
                        <option value="10">Niveau 10 : Algorithme minimax </option>
                        <option value="9">Niveau 9 : Très doué </option>
                        <option value="8">Niveau 8 : Confirmé </option>
                        <option value="7">Niveau 7 : Expert </option>
                        <option value="6">Niveau 6 : Avancé </option>
                        <option value="5">Niveau 5 : Intermédiaire </option>
                        <option value="4">Niveau 4 : Moyen </option>
                        <option value="3">Niveau 3 : Facile </option>
                        <option value="2">Niveau 2 : Débutant </option>
                        <option value="1">Niveau 1 : Aléatoire complet </option>
                    </select>
                </div>
                <br>
                <!-- Option de jouer contre un ami -->
                <input type="radio" name="mode_selection" value="human" id="human" required>
                <label for="human">Jouer contre un ami</label><br><br>
    
                <input type="submit" value="Commencer">
            </form>
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

// --- Fonction pour vérifier le gagnant ---
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

// --- Implémentation de l'algorithme Minimax ---
// L'ordinateur joue avec le symbole 'O', et le joueur humain avec 'X'
function minimax($board, $depth, $is_maximizing) {
    $winner = checkWinner($board);
    if ($winner !== null) {
        if ($winner === 'O') {
            return 10 - $depth;
        } elseif ($winner === 'X') {
            return $depth - 10;
        }
    }
    // Si toutes les cases sont remplies sans gagnant, c'est un match nul
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

// Fonction pour déterminer le meilleur coup pour l'ordinateur
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

// --- Gestion du coup du joueur humain ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cell'])) {
    $cell = intval($_POST['cell']);
    
    // On vérifie que la case est libre et que la partie n'est pas déjà terminée
    if ($_SESSION['board'][$cell] === ' ' && checkWinner($_SESSION['board']) === null) {
        $_SESSION['board'][$cell] = $_SESSION['current_player'];
        
        // Enregistrer le coup dans l'historique du joueur
        if ($_SESSION['current_player'] === 'X') {
            $_SESSION['history_X'][] = $cell;
        } else {
            $_SESSION['history_O'][] = $cell;
        }
        
        // Changement de joueur
        $_SESSION['current_player'] = $_SESSION['current_player'] === 'X' ? 'O' : 'X';
    }
}

// Vérification du gagnant après le coup du joueur humain
$winner = checkWinner($_SESSION['board']);

// --- Si le mode est ordinateur et c'est le tour de l'IA, jouer le coup de l'ordinateur ---
if ($_SESSION['mode'] === 'computer' && $_SESSION['current_player'] === 'O' && $winner === null) {
    $aiMove = best_move($_SESSION['board']);
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
        .history { margin-left: 20px; }
        .history ul { list-style-type: none; padding: 0; }
        .history li { background: #eee; margin: 4px 0; padding: 4px 8px; border-radius: 4px; }
        .reset-btn { margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="right">
            <div class="board">
                <?php foreach ($_SESSION['board'] as $index => $value): ?>
                    <div class="cell" onclick="document.getElementById('cellInput<?= $index ?>').submit();">
                        <?= $value ?>
                        <form id="cellInput<?= $index ?>" method="post" style="display: none;">
                            <input type="hidden" name="cell" value="<?= $index ?>">
                        </form>
                    </div>
                <?php endforeach; ?>
                <?php if ($winner): ?>
                    <h2 class="winner"><?= $winner ?> a gagné !</h2>
                <?php elseif (!in_array(' ', $_SESSION['board'])): ?>
                    <h2>Match nul!</h2>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Bouton de réinitialisation visible à tout moment -->
        <form method="post" class="reset-btn">
            <input type="submit" name="reset" value="Nouvelle partie" />
        </form>
        
        <!-- Affichage de l'historique des coups -->
        <div class="left">
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
</body>
</html>