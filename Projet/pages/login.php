<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <?php
        include("../common/nav.php");
        include("../common/db.php");
        $db = connect();
    ?>
    <div class="login-container">
        <h1>Connexion</h1>
        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label for="username">Identifiant:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class ="form-group">
                <button class ="color-button" type="submit">Se connecter</button>
            </div>
        </form>
        <div class ="form-group">
            <a href ="signin.php">S'inscrire</a>
        </div>
        
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>