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
    <?php
        //Enum en php 8 
        class Issue
        {
            const FINE = 'FINE';
            const ALREADY_USED_USERNAME = 'ALREADY_USED_USERNAME';
            const UNMATCHED_PASSWORD = 'UNMATCHED_PASSWORD';
        }
    ?>
    <?php 
    $ok = Issue::FINE;

    $call_page = $_SERVER['HTTP_REFERER'];
    //La page appelante doit être la page login.php ou signin.php
    if(! ( strpos($call_page,"signin.php") || strpos($call_page,"login.php") )){
        header('Location:error_page.php');
        exit;  
    }
    //Les champs sont partiellement remplis
    if(empty($_POST) || empty($_POST['password'] || empty($_POST['username']) || empty($_POST['cpassword']))){
        $ok = Issue::FINE; //On envoie un formulaire vide comme si les champs était vide
    }else{

    }
    ?>
    <div class="login-container">
        <h1>Inscription</h1>
        <form action="signin_process.php" method="POST">
            <div class="form-group">
                <label for="username">Nom d'Utilisateur:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="cpassword">Confirmation du mot de passe:</label>
                <input type="password" id="cpassword" name="password" required>
            </div>
            <div class ="form-group">
                <button class ="color-button" type="submit">S'inscrire</button>
            </div>
        </form>
    </div>
    <?php
        include("../common/footer.php");
    ?>
</body>
</html>