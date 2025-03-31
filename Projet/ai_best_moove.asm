; Ceci est un algo en pseudo code pour nous aider a concevoir une IA performante au morpion

DEBUT

; Déclaration des constantes
CONSTANTE VIDE = 0
CONSTANTE JOUEUR_X = 1
CONSTANTE JOUEUR_O = 2

; Grille de morpion : matrice 3x3
MATRICE IF_3 grille[3][3]

; Fonction d'évaluation pour déterminer le score d'un coup
FONCTION EvaluerCoup(grille):
    SI VerifierGagnant(grille, JOUEUR_X) ALORS
        RETOURNER 10 ; Joueur X gagne
    SINON SI VerifierGagnant(grille, JOUEUR_O) ALORS
        RETOURNER -10 ; Joueur O gagne
    SINON
        RETOURNER 0 ; Match nul ou jeu non terminé
    FIN SI
FIN FONCTION

; Fonction principale pour trouver le meilleur coup
FONCTION MeilleurCoup(grille):
    INITIALISER meilleurScore à -INFINI
    INITIALISER meilleurCoupX à -1
    INITIALISER meilleurCoupY à -1

    POUR chaque ligne de 0 à 2 FAIRE
        POUR chaque colonne de 0 à 2 FAIRE
            SI grille[ligne][colonne] EST VIDE ALORS
                ; Simuler le coup
                grille[ligne][colonne] <- JOUEUR_X
                score <- EvaluerCoup(grille)
                
                // Si le score est meilleur, mettre à jour le meilleur coup
                SI score > meilleurScore ALORS
                    meilleurScore <- score
                    meilleurCoupX <- ligne
                    meilleurCoupY <- colonne
                FIN SI
                
                ; Revenir à l'état original
                grille[ligne][colonne] <- VIDE
            FIN SI
        FIN POUR
    FIN POUR
    
    RETOURNER meilleurCoupX, meilleurCoupY

; Fonction pour vérifier si un joueur a gagné
FONCTION VerifierGagnant(grille, joueur):
    ; Vérification des lignes
    POUR chaque ligne de 0 à 2 FAIRE
        SI grille[ligne][0] = joueur ET grille[ligne][1] = joueur ET grille[ligne][2] = joueur ALORS
            RETOURNER VRAI
        FIN SI
    FIN POUR

    ; Vérification des colonnes
    POUR chaque colonne de 0 à 2 FAIRE
        SI grille[0][colonne] = joueur ET grille[1][colonne] = joueur ET grille[2][colonne] = joueur ALORS
            RETOURNER VRAI
        FIN SI
    FIN POUR

    ; Vérification des diagonales
    SI grille[0][0] = joueur ET grille[1][1] = joueur ET grille[2][2] = joueur ALORS
        RETOURNER VRAI
    FIN SI
    
    SI grille[0][2] = joueur ET grille[1][1] = joueur ET grille[2][0] = joueur ALORS
        RETOURNER VRAI
    FIN SI
    
    RETOURNER FAUX
FIN FONCTION

FIN
