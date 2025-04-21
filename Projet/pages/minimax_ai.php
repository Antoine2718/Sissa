<?php 
function meilleurCoup() {
  
    $meilleurScore = -Infinity;
    $coup;

    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            
            if (grille[$i][$j] == 0) {
                grille[$i][$j] = 2; // 2 représente l\'IA
                let score = minimax(grille, false);
                grille[$i][$j] = 0; // Annuler le coup
                
                if (score > meilleurScore) {
                    meilleurScore = score;
                    coup = { $i, $j };
                }
            }
        }
    }
    return $coup;
}

$GLOBALS["scores"] = {
    "true" : 0,  // Égalité
    1: -1, // Score pour le joueur : -1 (score à minimiser) 
    2: 1,  // Score pour l\'IA : 1 (score à maximiser)
};

function minimax(grille, maximisation) {
    
    $resultat;

    if (($resultat = gagner()) != null) {
        return scores[$resultat];
    } 
    else if (egalite()) {
        return scores["true"];
    }
    
    if (maximisation) {
          $meilleurScore = -Infinity;
          for ($i = 0; $i < 3; $i++) {
              for ($j = 0; $j < 3; $j++) {

                  if (grille[$i][$j] == 0) {

                      grille[$i][$j] = 2; // 2 représente l\'IA
                      $score = minimax(grille, false);
                      grille[$i][$j] = 0; // Annuler le coup
                      meilleurScore = Math.max(score, $meilleurScore);
                  }
              }
          }
          return $meilleurScore;
    } 
    else {
          $meilleurScore = Infinity;
          for ($i = 0; $i < 3; $i++) {
              for ($j = 0; $j < 3; $j++) {

                  if (grille[$i][$j] == 0) {

                      grille[$i][$j] = 1; // 1 représente le joueur
                      $score = minimax(grille, true);
                      grille[$i][$j] = 0; // Annuler le coup
                      meilleurScore = Math.min(score, $meilleurScore);
                      
                  }
              }
          }
          return $meilleurScore;
      }
};