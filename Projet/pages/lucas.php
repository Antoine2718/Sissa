<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <link rel="stylesheet" href="page_perso_jeu.css">
    <style>
        body {
            font-family: Helvetica, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .contact-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .questions-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        textarea {
            height: 100px;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 25%;
        }

        button:hover {
            background-color: #3b4252;
        }

        #Message_bleu {
            color: #007BFF;
        }

        .contact-container {
            display: grid;
            grid-template-columns: 50% 50%; /* Définir les largeurs des colonnes */
            height: fit-content;
        }
        .left {
            padding: 20px;
        }

        .right {
            padding: 20px;
        }

    </style>
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
</head>
<body>
    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        require_once("../common/db.php");
        include("../common/nav.php")
    ?>

    <!-- Contenu principal -->
    <div class="content">
        <div class="contact-container">
            <div class="left">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/09/Man_Silhouette.png" style="width:200px;height:200px; vertical-align: middle; border-radius: 40%;">
                <h2> Antoine L.</h2>
            </div>
            <div class="right">
                Etudiant en Licence de Mathématiques, astronome amateur a mes heures perdues, et passionné de maths et de physique.
            </div>
        </div>
        <br>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
