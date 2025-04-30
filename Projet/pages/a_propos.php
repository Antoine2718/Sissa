<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <link rel="stylesheet" href="style_apropos.css">
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
