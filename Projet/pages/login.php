<?php 
function matchPassword($db,$username,$password){
    try{
        $stmt = $db->prepare("SELECT mdp FROM utilisateur where identifiant = ?");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db_hash = $result['mdp'];
        return password_verify($password,$db_hash);
    }catch(PDOException $e){
        return "Error";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <!--Ajoute les pages de styles-->
    <?php //Ajoute la barre de navigation
        include("../common/styles.php")
    ?>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <?php
        require_once("../common/db.php");
        include("../common/nav.php");
        $db = connect();
        class Issue
        {
            const FINE = 'FINE';
            const INVALID_USERNAME = 'INVALID_USERNAME';
            const INVALID_PASSWORD = 'INVALID_PASSWORD';
            const REQUEST_ERROR = 'REQUEST_ERROR';
        }
        if(empty($_POST) || empty($_POST['password']) || empty($_POST['username'])){
            $ok = Issue::FINE; //On envoie un formulaire vide comme si les champs était vide
        }else{
            $username = $_POST['username'];
            $password =  $_POST['password'];
            $result = isUsernameUsed($db,$username);
            if(!preg_match("/^[a-zA-Z0-9_]{1,20}$/",$username)){
                $ok = Issue::MALFORMATED_INPUTS;
            }else if($result instanceof string){
                $ok = Issue::REQUEST_ERROR;
            }else{
                //Si le nom d'utilisateur n'existe pas
                if($result == false){
                    $ok = Issue::INVALID_USERNAME;
                }else{
                    //On vérifie le mdp
                    $result = matchPassword($db,$username,$password);
                    //erreur de requete
                    if($result instanceof string){
                        $ok = Issue::REQUEST_ERROR;
                    }else{
                        //Le mot de passe est faux
                        if($result == false){
                            $ok = Issue::INVALID_PASSWORD;
                        }else{
                            //on essaye de connecter l'utilisateur
                            $result = connectUser($db,$username);
                            if($result==false){
                                $ok = Issue::REQUEST_ERROR;
                            }else{
                                session_start();
                                $_SESSION['user'] = $result;
                                header("Location:index.php");
                                exit();
                            }
                        }
                    }
                }
            }
        }
    ?>
    <div class="login-container">
        <h1>Connexion</h1>
        <?php 
            if($ok ==Issue::INVALID_USERNAME){
                echo "<p class =\"error\">Le nom d'utilisateur n'existe pas.</p>";
            }else if($ok == Issue::INVALID_PASSWORD){
                echo "<p class =\"error\">Mot de passe erroné.</p>";
            }else if($ok == Issue::REQUEST_ERROR){
                echo "<p class =\"error\">Erreur de requête à la base de donnée.</p>";
            }
        ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Identifiant:</label>
                <input type="text" id="username" name="username" pattern="^[a-zA-Z0-9_]{1,20}$" required>
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