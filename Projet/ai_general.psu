section .data
    board db 0, 0, 0, 0, 0, 0, 0, 0, 0  ; Matrice 3x3
    empty_cell db 0
    player_x db 1
    player_o db 2

section .bss
    best_score resb 1
    best_move resb 1

; Fonction pour vérifier les conditions de victoire
check_winner:
    ; vérification des lignes, colonnes et diagonales
    ; Retourn le joueur gagnant ou 0 si personne n'a gagné.
    ret

; Fonction Minimax
minimax:
    push ebp
    mov ebp, esp
    ; Vérification si le jeu est terminé
    call check_winner
    ; Si un joueur a gagné, retourn le score
    ; Return le score
    
    ; Pour chaque case vide, évaluation de la position
    ; boucle pour itérer à travers le tableau

    ; Pour chaque cellule vide
    ;   Effectuer un déplacement
    ;   Appeler minimax récursivement
    ;   Annuler le déplacement
    ;   Stocker le meilleur score et le déplacement

    pop ebp
    ret

; Fonction principale de jeu
main:
    ; Initialiser le plateau
    ; Appel minimax pour évaluer tous les coups possibles
    ; Affichage le meilleur coup
    ret
