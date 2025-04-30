<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sissa</title>
    <link rel="stylesheet" href="style_page_perso.css">
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
                <h2> Bruno D. L.</h2>
            </div>
            <div class="right">
                Etudiant en Licence d'Informatique, passionné d'informatique et de technologies.
            </div>
        </div>
        <br>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>
