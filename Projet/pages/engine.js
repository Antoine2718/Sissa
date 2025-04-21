/* Engine en JS pour le moment, partie algo en PHP */

let IF_3_affichage = document.querySelector("table");
        IF_3_affichage.addEventListener('click',jouer);

        // INITIALISATION :

        let Matrice_IF_3 =
                [[0,0,0],       // 0 : case vide
                [0,0,0],        // 1 : joueur 1
                [0,0,0]]        // 2 : joueur 2

        let idJoueur;
        let boutonMode = document.querySelector("#mode"); // Bouton pour le choix du mode de jeu

        // Permet de stocker le mode de jeu entre les parties :
        if (!localStorage.getItem('modeJeu'))
            localStorage.setItem('modeJeu', "2J");
        let GAMEMODE = localStorage.getItem('modeJeu') // "IA" ou "2J"


        let boutonDifficulte = document.querySelector("#difficulte");
        // Permet de stocker la difficulté entre les parties
        if (!localStorage.getItem('difficulte'))
            localStorage.setItem('difficulte', "Facile");
        let DIFFICULTE = localStorage.getItem('difficulte') // "facile" ou "extrême" 
        boutonDifficulte.textContent = "Difficulté : "+DIFFICULTE;


        // Initialisation du texte du bouton 
        //+ inversion 1er joueur (celui qui débute la partie) pour le mode 2J :
        
        // ou faire Jouer l'IA au 1er tour, 1 partie sur 2.
        
        if (GAMEMODE === "2J") // Mode joueur contre joueur
        {
            boutonDifficulte.style.display = "none"; // Cache le bouton de la difficulté
            boutonMode.textContent = "Mode de jeu : 1 VS 1"; // Initialisation du texte du bouton

            // Inversion du 1er joueur :
            console.log(parseInt(localStorage.getItem('idJoueur')));
            if (!localStorage.getItem('idJoueur'))
                localStorage.setItem('idJoueur', "2");
            
            if (parseInt(localStorage.getItem('idJoueur')) === 1)
                localStorage.setItem('idJoueur', "2");
            else
                localStorage.setItem('idJoueur', "1");
            
            
            idJoueur = parseInt(localStorage.getItem('idJoueur'));
        }
        else // Mode joueur contre l'IA
        {
            // Si IAcommence n'est pas défini dans le stockage local,
            if (!localStorage.getItem('IAcommence'))
            // alors on l'initialise à 1 (utile seulement au 1er chargement)
            localStorage.setItem('IAcommence',1);

            // On récupère la valeur (0 : le joueur commence, 1 : l'IA commence.) :
            let IAcommence = parseInt(localStorage.getItem('IAcommence')); 
            boutonMode.textContent = "Mode de jeu : IA"; // Mise à jour du texte du bouton
            
            if (IAcommence == 1) // L'IA commence :
            {
                idJoueur = 2; // On fixe idJoueur = 2 -> l'IA jourra avec les "O".
                // On passe IAcommence à 0, pour qu'elle joue en 2ème lors de la prochaine partie :
                localStorage.setItem('IAcommence',0); 
                jouerIA(); // L'IA joue et inverse l'idJoueur
            }
            else // Le joueur commence :
            {
                idJoueur = 1; // On fixe idJoueur = 1 -> le joueur jouera avec les "X".
                // On passe IAcommence à 1, pour qu'elle commence lors de la prochaine partie :
                localStorage.setItem('IAcommence',1);
            }       
        }
       
        // Initialise les scores et le compteur d'égalités à zéro lors de la première partie :
        if (!localStorage.getItem('scoreJoueur1')) {
            localStorage.setItem('scoreJoueur1', 0);
        }
        if (!localStorage.getItem('scoreJoueur2')) {
            localStorage.setItem('scoreJoueur2', 0);
        }
        if (!localStorage.getItem('scoreEgalite')) {
            localStorage.setItem('scoreEgalite', 0);
        }

        // Récupère les scores des joueurs depuis le localStorage à chaque nouvelle partie
        let scoreJoueur1 = parseInt(localStorage.getItem('scoreJoueur1'));
        let scoreJoueur2 = parseInt(localStorage.getItem('scoreJoueur2'));
        let scoreEgalite = parseInt(localStorage.getItem('scoreEgalite'));

        // Variable du score pour l'affichage du score (span HTML) :
        let score1 = document.querySelector("#score1 span");
        let score2 = document.querySelector("#score2 span");
        let scoreEgaliteAffichage = document.querySelector("#scoreEgalite span");

        // Mise à jour de l'affichage du score : 
        score1.textContent = scoreJoueur1;
        score2.textContent = scoreJoueur2;
        scoreEgaliteAffichage.textContent = scoreEgalite;


        // FONCTIONS :

        function resetScore()
        {
            //localStorage.clear(); efface tout : pas bon -> on veut garder le mode de jeu, même après le reset
            localStorage.removeItem('scoreJoueur1'); // Permet de supprimer seulement l'item passé en paramètre
            localStorage.removeItem('scoreJoueur2');
            localStorage.removeItem('scoreEgalite');
            scoreJoueur1 = 0;
            scoreJoueur2 = 0;
            scoreEgalite = 0;
            score1.textContent = scoreJoueur1; 
            score2.textContent = scoreJoueur2;
            scoreEgaliteAffichage.textContent = scoreEgalite; 
        }

        // Bouton pour le reset du score :
        let boutonScore = document.querySelector("#score");
        // Configure le boutonScore pour qu'il exécute la fonction resetScore, au clic :
        boutonScore.addEventListener('click',resetScore);

        
       
        function ChangerModeJeu()
        {
            resetScore(); // On reset le score, avant d'inverser le mode de jeu.
            if (GAMEMODE === "2J")
            {
                GAMEMODE = "IA";
                localStorage.setItem('modeJeu', "IA");
                window.location.reload(); // permet d'actualiser la page, pour lancer une nouvelle partie.
            }
            else
            {
                GAMEMODE = "2J";
                localStorage.setItem('modeJeu', "2J");
                window.location.reload(); // permet d'actualiser la page, pour lancer une nouvelle partie.
            }
        }
        // Configure le boutonMode pour qu'il exécute la fonction ChangerModeJeu, au clic :
        boutonMode.addEventListener('click',ChangerModeJeu);


        function ChangerDifficulte()
        {
            resetScore(); // On reset le score, avant de changer la difficulté.
            if (DIFFICULTE === "Facile")
            {
                DIFFICULTE = "Algorithme parfait";
                localStorage.setItem('difficulte', "Algorithme parfait");
                window.location.reload(); // permet d'actualiser la page, pour lancer une nouvelle partie.
            }
            else
            {
                DIFFICULTE = "Facile";
                localStorage.setItem('difficulte', "Facile");
                window.location.reload(); // permet d'actualiser la page, pour lancer une nouvelle partie.
            }
        }
        // Configure le boutonDifficulte pour qu'il exécute la fonction ChangerDifficulte, au clic  :
        boutonDifficulte.addEventListener('click',ChangerDifficulte);

        // RESPONSIVE :
        // Menu mobile :
        function afficherMasquerMenu(event) // Permet d'afficher/masquer le menu sur mobile
        {
            let nomBoutonClique = event.target.id;
            // Autant utiliser classList.toggle avec 1 class à la place des 2 class,
            // Avec par défaut, l'élément en display:none; 
            // Exemple : element.classList.toggle("menuVisible");

            // Sinon, autre manière :
            if (nomBoutonClique === "menu") 
                document.querySelector("#Score").className = "menuVisible"; 
            else
                document.querySelector("#Score").className = "menuInvisible";
        }

        let boutonMenu = document.querySelector("#menu");
        // Configure le boutonMenu pour qu'il exécute la fonction afficherMasquerMenu, au clic :
        boutonMenu.addEventListener('click',afficherMasquerMenu);

        let boutonSortir = document.querySelector("#sortir");
        // Configure le boutonSortir pour qu'il exécute la fonction afficherMasquerMenu, au clic :
        boutonSortir.addEventListener('click',afficherMasquerMenu);


        function egalite() // Vérifie l'égalité (= la grille est pleine et personne n'a gagné)
        {
            // Il ne doit pas rester de case vide (0) :
            if (!Matrice_IF_3[0].includes(0) && !Matrice_IF_3[1].includes(0) && !Matrice_IF_3[2].includes(0))
                return true;
            else
                return false;
        }

        function gagner() // Vérifie si un joueur a gagné : renvoie l'id du gagnant le cas échéant, null sinon.
        {
            let idGagnant = null;
            let i=0;
            while (i<3 && idGagnant == null)
            {
                //Lignes :
                if ( Matrice_IF_3[i][0] !=0 && Matrice_IF_3[i][0] == Matrice_IF_3[i][1] && Matrice_IF_3[i][1] == Matrice_IF_3[i][2] )
                    idGagnant = Matrice_IF_3[i][0];
                //Colonnes :
                else if ( Matrice_IF_3[0][i] != 0 && Matrice_IF_3[0][i] == Matrice_IF_3[1][i] && Matrice_IF_3[1][i] == Matrice_IF_3[2][i] )
                    idGagnant = Matrice_IF_3[0][i];
                i++
            }
            //Diagonales
            if(Matrice_IF_3[1][1] != 0) // Matrice_IF_3[1][1] == 0 : Pas de diagonales
            {

                if( (Matrice_IF_3[0][0] == Matrice_IF_3[1][1] && Matrice_IF_3[1][1] == Matrice_IF_3[2][2]))
                    idGagnant = Matrice_IF_3[0][0];
                else if(Matrice_IF_3[0][2] == Matrice_IF_3[1][1] && Matrice_IF_3[1][1] == Matrice_IF_3[2][0]) 
                    idGagnant = Matrice_IF_3[0][2];    
            }    

            return idGagnant;
        }


        function alerteGagner(idGagnant) // Affichage d'une alerte en cas de victoire.
        {
            let message = `Le ${idGagnant} joueur a gagné !`; 
            setTimeout(function()
            { // setTimeout : permet d'attendre (de continuer le code) 
              // et donc d'afficher le symbole, avant l'alerte.
                alert(message);
                // Sauvegarde du score dans le stockage local avant de recharger la page
                if (idGagnant == 1)
                {
                    scoreJoueur1++
                    localStorage.setItem('scoreJoueur1', scoreJoueur1);
                }
                else
                {    
                    scoreJoueur2++
                    localStorage.setItem('scoreJoueur2', scoreJoueur2);
                }
                window.location.reload();
        }, 100); // Attend 100 millisecondes avant d'afficher l'alerte   
            
        }

        function alerteEgalite() // Affichage d'une alerte en cas d'égalité.
        {
            console.log("égalité");
            scoreEgalite++;
            localStorage.setItem('scoreEgalite', scoreEgalite);
            setTimeout(function() {
                alert("Égalité !");
                window.location.reload();
            }, 100); // Attend 100 millisecondes avant d'afficher l'alerte

        } 

        function jouer(event)
        {
            //console.log(grille);
            let CASE = event.target; // récupération de la case cliquée

            let x = CASE.id[1]; // récupération de x (grâce à l'id)
            let y =   CASE.id[2]; // récupération de y (grâce à l'id)
            //console.log( x+ " " + y);

            if (Matrice_IF_3[x][y] == 0) // Si la case est vide
            {
                Matrice_IF_3[x][y] = idJoueur; // Mise à jour de la grille avec le numéro du joueur

                let idGagnant = gagner(); 
                if (idGagnant != null) // Vérifier la victoire après chaque coup
                    alerteGagner(idGagnant);
                
                else if(egalite()) // Vérifier l'égalité après chaque coup
                    alerteEgalite();
                
                // Pas de victoire, ni d'égalité : la partie continue
                if (idJoueur === 1)
                {
                    CASE.textContent = "X"; // MAJ de l'affichage
                    CASE.style.color = "black";
                    idJoueur = 2; // Changement de joueur
                }
                else if (idJoueur === 2)
                {
                    CASE.textContent = "O"; // MAJ de l'affichage
                    CASE.style.color = "blue";
                    idJoueur = 1; // Changement de joueur
                }
                
                if (GAMEMODE=="IA") // Faire jouer l'IA, si le mode est activé
                    jouerIA();    
                            
            }
            else // Si la case est déjà occupée
                alert("Impossible !")
        }


        function jouerIA() {
            
            if (!egalite() && gagner()==null) //Evite que l'IA joue après une égalité ou la victoire du joueur
            {
                let coup;
                
                if (DIFFICULTE === "Facile")
                    coup = coupAleatoire(); // Jouer un coup pour l'IA
                else
                    coup = meilleurCoup(); // Jouer le meilleur coup pour l'IA
                grille[coup.i][coup.j] = 2; 

                // Accès à la case TD (i,j) grâce à l'id :
                let CASE = document.querySelector(`#C${coup.i}${coup.j}`); 
                
                setTimeout(function() // ajoute un délai à l'affichage, pour que l'IA ne joue pas instantanément 
                {
                    // Si l'IA gagne ou en cas d'égalité : afficher le résultat et recharger la page
                    let idGagnant = gagner();
                    if (idGagnant != null)
                        alerteGagner(idGagnant);
                    // Vérifier l'égalité après chaque coup
                    else if(egalite())
                        alerteEgalite();

                    CASE.textContent = "O"; // MAJ de l'affichage
                    CASE.style.color = "blue";
                    idJoueur = 1; // Changement de joueur
                }
                ,700) 
            }
            
        }