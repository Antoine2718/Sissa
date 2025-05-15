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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['mode_selection'])) {
        $_SESSION['mode'] = $_GET['mode_selection'];
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
            <link rel="stylesheet" href="jeu.css">
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
            <div class="selection">
                <div class="selection-title">
                    <h2>Choisissez le mode de jeu</h2>
                </div>
                <div class ="selection-content">
                    <a class="color-button" href="jeu.php?mode_selection=computer">Jouer contre notre IA 🧠</a>
                    <a class="color-button" href="jeu.php?mode_selection=human">Jouer contre un ami</a>
                </div>
                
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
            $display ="none";
            if(isset($_GET)&&isset($_GET['mode_selection'])){
                if($_GET['mode_selection']=="computer"){
                    $display = 'block';
                }
            }
        ?>
        <form method="post">
            <div id="difficulty-container" <?php echo"style=\"display: $display;\";"?>>
                <label for="difficulty"> <h4> Niveau de difficulté pour l'IA </h4> </label>
                <div class="robots">
                    <h1>Découvrez notre technologie unique<br>d'intelligence artificielle</h1>
                    <br> <br>
                    <?php if (!empty($robots)): ?>
                        <ul>
                                
                            <table style="border: 0px; border-spacing: 10px; border-collapse: collapse; max-width : 80%; margin-left: auto, margin-right : auto;">
                            <tr>
                            <?php $i = 0; 
                            foreach ($robots as $robot): ?>
                                <th>
                                <img src="<?= $robot['lien_icone'] ?>" style="width:200px;height:200px; vertical-align: middle; border-radius: 45%; border: 1px solid lightgray;">
                                <br>
                                <h3> <?= $robot['nomRobot'] ?> </h3> Niveau : <?= $robot['niveauRobot'] ?>
                                <input id="difficulty" type="radio" name="difficulty" value="<?= $robot['niveauRobot'] ?>">
                                <br><br><br>
                                </th>
                                <?php 
                                // Indice qui permet de faire l'affichage souhaité
                                $i += 1;
                                if($i % 5 == 0) : // Si $i congrut à 0 modulo 5 faire :
                                    ?>
                                    </tr> <tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </tr>
                            </table>
                                
                        </ul>
                    <?php else: ?>
                        <p>Aucun robot disponible.</p>
                    <?php endif; ?>
                </div>
            <?php /* endif; */ ?>

            </div>
            <?php if (isset($_GET)&&isset($_GET['mode_selection'])) :?>
                <input type="submit" value="Commencer" class="color-button">
            <?php endif ?>
            <br>
            </div>
        </form>
            
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
    unset($_SESSION['first_player']);
    unset($_SESSION['board']);
    unset($_SESSION['difficulty']);
    unset($GLOBALS['difficulty']);
    unset($_SESSION['mode']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// --- Initialisation du plateau et des données de session ---
if (!isset($_SESSION['board'])) {
    // Isomorphe au matrice 3x3
    $_SESSION['board'] = [' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' '];
    $first_player = array('X','O');
    $_SESSION['current_player'] = $first_player[array_rand($first_player)];
    $_SESSION['first_player'] = $_SESSION['current_player'];
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
/* 
Lorsqu'un joueur effectue un coup, cet acte génère un nouvel état du 
jeu, entraînant une ramification dans l'arbre de décision. Chaque nœud de cet 
arbre est évalué en fonction de la stratégie gagnante du joueur courant, qui peut 
être soit le joueur maximiseur, soit le joueur minimiseur.

Joueur Maximiseur : Cherche à maximiser son score. Cela se produit lors du 
tour d’un joueur qui cherche à gagner.
Joueur Minimiseur : Cherche à minimiser le score du joueur maximiseur. Cela se produit 
lors du tour de l'adversaire, qui essaie d'éviter que le joueur maximiseur ne gagne.
Algorithme Minimax

L'algorithme Minimax fonctionne de manière récursive comme suit :

Évaluation des Positions Terminales : À chaque feuille de l'arbre (état final du jeu), on 
calcule une valeur d'évaluation. Cela peut être :
+1 si le joueur maximiseur gagne.
-1 si le joueur minimiseur gagne.
0 si c'est une égalité.
Propagation des Valeurs : Pour chaque nœud interne, on évalue le score en fonction du tour du joueur :
Si c'est le tour du joueur maximiseur, on prend le maximum des valeurs des nœuds enfants.
Si c'est le tour du joueur minimiseur, on prend le minimum.
Ceci se traduit par les relations suivantes :
[
V(n) = \max { V(child_1), V(child_2), \ldots, V(child_k) } \quad \text{(pour le joueur maximiseur)}
]
[
V(n) = \min { V(child_1), V(child_2), \ldots, V(child_k) } \quad \text{(pour le joueur minimiseur)}
]
Sélection du Meilleur Coup : Le processus s’arrête une fois que la recherche atteint une profondeur 
déterminée, ou un état terminal. Le joueur choisit le coup correspondant au nœud avec la valeur 
d’évaluation optimale.
*/
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
        /* 0 de l'Algèbre Max Plus*/
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
        /* 0 de l'Algèbre Min Plus */
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
/* Effectuer un appel recursif sur chaques nœuds 
de l'arbre 
*/
function best_move($board) {
    $bestScore = -INF;
    // $move = -1 pour commencer
    $move = -1;
    for ($i = 0; $i < count($board); $i++) {
        // Si la case est libre
        if ($board[$i] === ' ') {
            /* Si le coef de la matrice est null alors : */
            $board[$i] = 'O';
            $score = minimax($board, 0, false);
            $board[$i] = ' ';
            if ($score > $bestScore) {
                /* Si on trouve mieux que $bestScore, alors : */
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
    if($_SESSION['current_player'])
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
        $date = date('Y-m-d H:i:s');
        $char = $_SESSION['current_player'];
        $id = $player->getID();
        $stmt = $pdo->prepare("INSERT INTO partie (date_premier_coup, premier_joueur, idRobot, idUtilisateur) VALUES (:date_pc, :firs, :id1, :id2)");
        $stmt->execute([':date_pc' => $date, ':firs' => $char, ':id1' => 11-$difficulty, ':id2' => $id]);
        $idPartie = $pdo->lastInsertId();
        $_SESSION['partie'] = $idPartie;
    }
    // $IDpartie et $IDmove a récupérer
    $player = getUser();
    $date = date('Y-m-d H:i:s');
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
    $GLOBALS['difficulty'] = $difficulty;
    // Calcul de la probabilité d'utiliser l'algorithme minimax :
    // Pour le niveau 1 --> (1-1)/9 = 0 (coup aléatoire)
    // Pour le niveau 10 --> (10-1)/9 = 1 (coup optimisé)
    // Pour la selection de l'IA, tout ce passe dans minimaxProbability
    $minimaxProbability = ($difficulty - 1) / 9;
    // Fabrication d'un reel aléatoire en 0 et 1
    $rand = mt_rand(0, 100000) / 100000;

    // Si l'indice aleatoire est inférieur a la constante de difficultée, alors on prend le meilleur coup
    if ($rand < $minimaxProbability) {
        $aiMove = best_move($_SESSION['board']);
    } else {
        // Choix aléatoire parmi les coups disponibles
        
        $aiMove = $available_moves[array_rand($available_moves)];
    }
    if ($aiMove !== -1 && $_SESSION['board'][$aiMove] === ' ') {
        $_SESSION['board'][$aiMove] = 'O';
        $_SESSION['history_O'][] = $aiMove;
        $_SESSION['current_player'] = 'X';
        $winner = checkWinner($_SESSION['board']);
        logMove($aiMove);
    }
    
}
//TODO vérifie une victoire match null ou victoire bot ou victoire joueur
//Uniquement lorsque la partie est terminée on affiche nouvelle partie bouton
// Fonction Win pour comptage points
function Win(){
    $data = [];
    $winner = checkWinner($_SESSION['board']);
    $available_moves = [];
    // Calcul des coup possibles restants
    foreach ($_SESSION['board'] as $i => $cell) {
        if ($cell === ' ') {
            // Si la cellule est libre, on l'ajoute
            $available_moves[] = $i;
        }
    }
    // Parité :
    /* 
    Cout restant pair = > IA perdu
    Cout restant impair = > IA gagné
    */
    if($winner) {
        $data['win'] = True;
        if($_SESSION['current_player']=='X') {
            $data['winner'] = 'IA';
            $data['IdWinner'] = $difficulty??10;
        } else {
            $data['winner'] = 'Player';
            $player = getUser();
            $data['IdWinner'] = $player->getID();
        }
    } else if(count($available_moves)==0){
        $data['win'] = true;
        $data['draw'] = true;
    }else {
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
    <link rel="stylesheet" href="jeu.css">
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
</head>
<body>
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php");
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
        <?php elseif (!in_array(' ', $_SESSION['board'])): 
            $winner = 'N';
            ?>
            <h2>Match nul !</h2>
        <?php endif; ?>

        <!-- Bouton de réinitialisation 
        Uniquement en fin de partie -->
        <?php if ($winner OR $winner == 'N'): ?>
            <form method="post" class="reset-btn">
                <input type="submit" name="reset" value="Nouvelle partie" class="color-button"/>
            </form>
        <?php endif;?>
        <!-- Affichage de l'historique des coups -->
        <div class="history">
            <div>
                <h3>Joueur X</h3>
                <ul>
                    <?php foreach ($_SESSION['history_X'] as $move): 
                        // Pour chaque coup dans l'historique :
                        ?>
                        <li>Joué en <?= $move ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3>Joueur O</h3>
                <ul>
                    <?php foreach ($_SESSION['history_O'] as $move): // Pour chaque coup dans l'historique : ?>
                        <li>Joué en <?= $move ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    </div>
    <?php 
    $state = Win();
    if($state['win']&&$_SESSION['mode']=="computer"){
        //TODO afficher le rapport partie
        require_once("result.php");
        getResultForm($state,$_SESSION['board'],$_SESSION['difficulty']??-1);
    }
    ?>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
