<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
    <style> 
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        
        body {
            color: #007BFF;
            background-color: white;
            font-family: Helvetica, sans-serif;
        }
        
        h1 {
            text-align: center;
            font-size: 2em;
            text-decoration: underline;
            margin: 0;
        }
        
        h2 {
            font-size: 1em;
            margin-top: 0;
        }
        
        .contenu {
            display: flex; /*utilisation de Flexbox pour l'agencement*/
            flex-direction: row; /*Les enfants sont alignés en ligne.*/
            align-items: center; /*Centré verticalement et horizontalement*/
            justify-content: center;
        }
        
        #game {
            flex: 2; /*L'élément occupe deux fois plus d'espace que les autres*/
        }
        
        td {
            border: 2px solid black;
            border-collapse: collapse; /*Fusionne les bordures*/
            width: 25%;
            height: 150px;
            padding: 0;
            text-align: center;
            font-size: 4em;
            
        }
        
        table {
            width: 80%; /*Occupe 80% de la largeur de l'élément parent*/
            margin: auto;
            min-width: 300px;
            max-height: 600px;
            border-collapse: collapse; /*Fusionne les bordures*/
            background-color: white;
        }
    </style>
</head>
<body>
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php")
    ?>
    

    <!-- Contenu principal -->
    <div class="content">
                        <?php include 'minimax_ai.php'; ?> <!--Intégration de l'IA Minimax -->
                        <script src="engine.js" defer></script> <!--Intégration du moteur de jeu.-->


                        <h1> </h1>
                        <div class="contenu">
                            <div id="Score" class="menuInvisible">
                                <h2>Score :</h2>
                                <p id="score1">X :  <span></span></p>
                                <p id="score2"> π :  <span></span></p>
                                <p id="scoreEgalite">Nul : <span></span></p>
                        
                                <button id="score">Réinitialiser le score </button>
                                <button id="mode"></button>
                                <button id="difficulte"></button>
                                <button id="sortir">Sortir</button>
                            </div>
                        
                            <div id="game">
                                <br>
                                <button id="menu">Menu</button>
                                <table> <!--Le tableau sert de grille du Morpion, de plateau de jeu.-->
                                <tr> <!--Chaque case (td) est identifiée par un id.-->
                                    <td id="C00"></td> <!--C00 : en haut à gauche.-->
                                    <td id="C01"></td>
                                    <td id="C02"></td>
                                </tr>
                                <tr>
                                    <td id="C10"></td>
                                    <td id="C11"></td>
                                    <td id="C12"></td>
                                </tr>
                                <tr>
                                    <td id="C20"></td>
                                    <td id="C21"></td>
                                    <td id="C22"></td> <!--C22 : en bas à droite.--> 
                                </tr>
                                </table>
                            </div>

    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
