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
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bgChange 10s infinite alternate;
        }

        .container {
            text-align: center;
            align-items: center;
        }


        #status {
            font-family: 'Menlo', monospace;
            margin-top: 30px;
            font-size: x-large;
        }

        .corps {
            color: black;
            font-family: 'Menlo', monospace;
            margin-bottom: 0px;
        }

        .title {
            color: #005eff;
            font-family: 'Menlo', monospace;
            margin-bottom: 20px;
        }

        .board {
            color: #005eff;
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 5px;
        }

        .cell {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2em;
            background-color: #ffffff;
            border: 2px solid #e1e1e1;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .cell:hover {
            color: white;
            background-color: #005eff;
        }

        button {
            font-family: 'Menlo', monospace;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1em;
            color: black;
            background-color: #ffffff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            color: white;
            background-color: #005eff;
        }


        .container2 {
            display: flex;
            justify-content: space-between;
            width: 50%;
        }

        .bloc {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0px;
            text-align: center;
            flex: 1;
            margin: 0 20px; /* Espace entre les blocs */
        }

        .bloc h2 {
            margin-bottom: 15px;
        }

        .bloc a {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: white;
            color: black;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .bloc a:hover {
            color: #007bff;
            background-color: white;
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
            <tr>
                <td colspan="3">
                    <div class="board" id="board">
                        <div id="board"> </div>
                        </div>
                        <button id="reset">
                            <a href="jeu.php" class="button">recommencer</a>
                        </button>
                    <div id="status"></div>
                </td>
            </tr>
        </div>
        </table>
        <script>
            const board = document.getElementById('board');
            const statusDisplay = document.getElementById('status');
            let boardState = ['', '', '', '', '', '', '', '', ''];
            let currentPlayer = 'X';
            let gameActive = true;
    
        function initializeBoard() {
            boardState = ['', '', '', '', '', '', '', '', ''];
            currentPlayer = 'X';
            gameActive = true;
            statusDisplay.innerText = "C'est à votre tour!";
            board.innerHTML = '';
            
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
    
            if (boardState[cellIndex] !== '' || !gameActive) {
                return;
            }
            
            boardState[cellIndex] = currentPlayer;
            e.target.innerText = currentPlayer;
    
            if (checkWinner()) {
                statusDisplay.innerText = `${currentPlayer} a gagné!`;
                gameActive = false;
                return;
            }
    
            currentPlayer = 'π';
            statusDisplay.innerText = "🧠 Réflexion......";
            setTimeout(computerPlay, 500); // Attendre avant que l'ordinateur joue
        }
    
        // ******** Partie AI ********
    
        function computerPlay() {
            let emptyCells = boardState.map((cell, index) => (cell === '') ? index : null).filter(v => v !== null);
    
            if (emptyCells.length === 0 || !gameActive) {
                return;
            }
    
            const randomIndex = emptyCells[Math.floor(Math.random() * emptyCells.length)];
            boardState[randomIndex] = currentPlayer;
            const cell = document.querySelector(`.cell[data-index='${randomIndex}']`);
            cell.innerText = currentPlayer;
    
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
                [0, 1, 2],
                [3, 4, 5],
                [6, 7, 8],
                [0, 3, 6],
                [1, 4, 7],
                [2, 5, 8],
                [0, 4, 8],
                [2, 4, 6]
            ];
    
            for (let condition of winningConditions) {
                const [a, b, c] = condition;
                if (boardState[a] && boardState[a] === boardState[b] && boardState[a] === boardState[c]) {
                    return true;
                }
            }
            return false;
        }
    
        initializeBoard();
    
        </script>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
