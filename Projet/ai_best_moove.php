<?php

/* 
Ceci est une "conversion" si l'on puis dire du fichier ai_best_moove.ASM 
(adapter au PHP)
*/

function isWinning($board, $player) {
    // Vérifie les lignes, les colonnes et les diagonales
    for ($i = 0; $i < 3; $i++) {
        if (($board[$i][0] === $player && $board[$i][1] === $player && $board[$i][2] === $player) || 
            ($board[0][$i] === $player && $board[1][$i] === $player && $board[2][$i] === $player)) {
            return true;
        }
    }
    if (($board[0][0] === $player && $board[1][1] === $player && $board[2][2] === $player) || 
        ($board[0][2] === $player && $board[1][1] === $player && $board[2][0] === $player)) {
        return true;
    }
    return false;
}

function isDraw($board) {
    foreach ($board as $row) {
        if (in_array('', $row)) {
            return false;
        }
    }
    return true;
}

function minimax($board, $depth, $isMaximizing) {
    if (isWinning($board, 'O')) return 10 - $depth; // O gagne
    if (isWinning($board, 'X')) return $depth - 10; // X gagne
    if (isDraw($board)) return 0; // Match nul

    if ($isMaximizing) {
        $bestScore = -INF;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($board[$i][$j] === '') {
                    $board[$i][$j] = 'O'; // Essaye le coup pour O
                    $score = minimax($board, $depth + 1, false);
                    $board[$i][$j] = ''; // Annule le coup
                    $bestScore = max($bestScore, $score);
                }
            }
        }
        return $bestScore;
    } else {
        $bestScore = INF;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($board[$i][$j] === '') {
                    $board[$i][$j] = 'X'; // Essaye le coup pour X
                    $score = minimax($board, $depth + 1, true);
                    $board[$i][$j] = ''; // Annule le coup
                    $bestScore = min($bestScore, $score);
                }
            }
        }
        return $bestScore;
    }
}

function findBestMove($board) {
    $bestScore = -INF;
    $bestMove = null;

    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($board[$i][$j] === '') {
                $board[$i][$j] = 'O'; // Assigne O à la case
                $score = minimax($board, 0, false);
                $board[$i][$j] = ''; // Annule le coup

                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestMove = [$i, $j];
                }
            }
        }
    }
    return $bestMove;
}

// Utilisation : Evidamment de type Matrice IF_3 definie dans structure.php
$board = [
    ['', '', ''],
    ['', '', ''],
    ['', '', '']
];

// Essai avec affichage classique pour prototypage l'ordinateur (O) joue contre l'utilisateur (X)
$bestMove = findBestMove($board);
if ($bestMove) {
    echo "Le meilleur coup est: " . implode(", ", $bestMove);
} else {
    echo "Aucun coup disponible.";
}