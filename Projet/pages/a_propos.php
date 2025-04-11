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
</head>
<body>

    <!--Barre de navigation-->
    <?php //Ajoute la barre de navigation
        include("../common/nav.php")
    ?>

    <!-- Contenu principal -->
    <div class="content">
        <h2>A propos...</h2>
        <h1>L'algorithme :</h1>
        <p>Notre algorithme se base sur la méthode minimax, cette algorithme peut être vu sous la forme d'un arbre algorithmique, 
            et consiste a selectionner le coup qui minimise le nombre maximal de coup avant de gagner.
        <p>
    </div>

</body>
</html>