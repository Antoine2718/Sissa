<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
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

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
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

        #Message_bleu {
            color: #007BFF;
        }

        .equipe-container {
            display: grid;
            grid-template-columns: 25% 25% 25% 25% ; /* Définir les largeurs des colonnes */
        }

        .lucas, .andrieu, .dl, .Owen {
            padding: 20px;

            background-color: #007BFF;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
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
        <h1> À Propos de Sissa</h1>
        <div class="contact-container">
            <div>
                <h1 id="Message_bleu">Notre Mission</h1>
                Sissa est né d'une passion partagée pour les jeux de stratégie simples mais profonds. <br>
                Notre mission est de transformer le jeu classique du morpion en une expérience moderne et captivante,
                 accessible à tous et partout. <br>
                 Nous croyons que les jeux les plus simples peuvent créer les moments les plus mémorables et
                 les connexions les plus fortes.
                <br>
                 C'est pourquoi nous avons créé cette plateforme qui combine l'aspect traditionnel du morpion avec des
                 technologies modernes et une interface conviviale.
            </div>
            <br>
            <h1 id="Message_bleu"> Notre Équipe</h1>
            <div>
                Derrière Sissa se trouve une équipe passionnée. <br>
                <br>
                <div class="equipe-container">
                    <div class="lucas">
                        <a class ="page_selected" href="lucas.php">Antoine L.</a>
                    </div>
                    <div class="andrieu">
                        <a class ="page_selected" href="andrieu.php">Paul A.</a>
                    </div>
                    <div class="dl">
                        <a class ="page_selected" href="dl.php">Bruno D. L. </a>
                    </div>
                    <div class="Owen">
                        <a class ="page_selected" href="error_page.php">Owen</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
