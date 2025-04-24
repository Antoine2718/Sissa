<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <!--Ajoute les pages de styles-->
    <style>
        body {
            height: 100vh;
            margin: 0;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            animation: bgChange 10s infinite alternate;
        }

        .container {
            text-align: center;
            align-items: center;
        }

        .board {
            color: #005eff;
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 5px;
            margin-right: 40px; /* Espace entre le plateau et l'historique */
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

        #status {
            font-family: 'Menlo', monospace;
            margin-top: 30px;
            font-size: x-large;
        }

        .historique {
            max-width: 200px;
            margin-left: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            text-align: center;
            height: 30vh;
        }

        .historique h3 {
            margin: 0;
            font-size: 1.5em;
            color: #005eff;
        }
        .container {
            display: grid;
            grid-template-columns: 30% 70%; /* Définir les largeurs des colonnes */
        }
        .left {
            padding: 20px;
        }

        .right {
            padding: 20px;
        }

        .messenger-container {
            background-color: white;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        a, button {
            font-family: 'Menlo', monospace;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1em;
            color: white;
            background-color: #007BFF;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            color: white;
            background-color: #005eff;
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
    

    <!-- Contenu principal -->
    <div class="content">
        <table>
        <div class="container">
        <div class="left">
            <div class="historique" id="historique">
                <h3>Coups:</h3>
                <br>
                <div id="historiqueList"></div>
            </div>
        </div>
        <div class="right">
            <div class="board" id="board"></div>
        </div>
        </div>
        <div class="messenger-container">
            <div id="reset">
                <a href="jeu_v2.html" class="button">recommencer</a>
            </div>
            <div id="status"></div>
        </div>
        </table>
        <script>
            const board = document.getElementById('board');
            const statusDisplay = document.getElementById('status');
            const historiqueList = document.getElementById('historiqueList');
            let boardState = ['', '', '', '', '', '', '', '', ''];
            let currentPlayer = 'X';
            let gameActive = true;
            let human_display = [
                "(1,1)", "(1,2)", "(1,3)", "(2,1)", "(2,2)", "(2,3)", 
                "(3,1)", "(3,2)", "(3,3)", 
            ];
    
            function initializeBoard() {
                boardState = ['', '', '', '', '', '', '', '', ''];
                currentPlayer = 'X';
                gameActive = true;
                statusDisplay.innerText = "C'est à votre tour!";
                board.innerHTML = '';
                historiqueList.innerHTML = ''; // Réinitialiser l'historique
    
                coup_nb = 0;
    
                for (let i = 0; i < 9; i++) {
                    const cell = document.createElement('div');
                    cell.classList.add('cell');
                    cell.setAttribute('data-index', i);
                    cell.addEventListener('click', humanPlay);
                    board.appendChild(cell);
                }
            }
    
            function humanPlay(e) {
                const cellIndex = e.target.getAttribute('data-index');
                if(currentPlayer != "X") {return;}
    
                if (boardState[cellIndex] !== '' || !gameActive) {
                    return;
                }
                
                boardState[cellIndex] = currentPlayer;
                e.target.innerText = currentPlayer;
    
                // Ajouter le coup à l'historique
                updateHistorique(currentPlayer, cellIndex);
    
                if (checkWinner()) {
                    statusDisplay.innerText = `${currentPlayer} a gagné!`;
                    gameActive = false;
                    return;
                }
    
                currentPlayer = 'π';
                statusDisplay.innerText = "🧠 Réflexion......";
                if(!gameActive) {
                    statusDisplay.innerText = "Egalité !";
                }
    
    
                setTimeout(computerPlay, 500); // Attendre avant que l'ordinateur joue
            }
    
            // ******** Partie AI ********
            function computerPlay() {
                const emptyCells = boardState.map((cell, index) => cell === '' ? index : null).filter(index => index !== null);
                const randomIndex = Math.floor(Math.random() * emptyCells.length);
                const cellIndex = emptyCells[randomIndex];
                
                boardState[cellIndex] = currentPlayer;
                const cell = board.querySelector(`[data-index='${cellIndex}']`);
                cell.innerText = currentPlayer;
    
                // Ajouter le coup à l'historique
                updateHistorique(currentPlayer, cellIndex);
                
                if (checkWinner()) {
                    statusDisplay.innerText = `${currentPlayer} a gagné!`;
                    gameActive = false;
                    return;
                }
                
                currentPlayer = 'X';
                statusDisplay.innerText = "C'est à votre tour!";
            }
    
            function checkWinner() {
                const winningConditions = [
                    [0, 1, 2], [3, 4, 5], [6, 7, 8], // lignes
                    [0, 3, 6], [1, 4, 7], [2, 5, 8], // colonnes
                    [0, 4, 8], [2, 4, 6]             // diagonales
                ];
    
                for (let condition of winningConditions) {
                    const [a, b, c] = condition;
                    if (boardState[a] && boardState[a] === boardState[b] && boardState[a] === boardState[c]) {
                        return true;
                    }
                }
                return false;
            }
    
            function updateHistorique(player, cellIndex) {
                coup_nb += 1;
                const move = document.createElement('div');
                move.innerText = `${coup_nb}.   ${player} : ${human_display[parseInt(cellIndex)]}`;
                historiqueList.appendChild(move);
            }
    
            document.getElementById('reset').addEventListener('click', initializeBoard);
            window.onload = initializeBoard; // Initialiser le plateau au chargement de la page
        </script>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
