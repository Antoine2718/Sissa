<?php
function ajoutUtilisateur($db,$username,$hashed_password){
    try{
        $stmt = $db->prepare("INSERT INTO Utilisateur (points,identifiant,mdp,type,idRang) values (0,?,?,\"user\",1) ");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $hashed_password, PDO::PARAM_STR);
        $stmt->execute();
        return true;
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
    <title>Login</title>
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
        
        require_once("../common/utilisateur.php");
        $db = connect();
    ?>
    <?php
        //Enum en php 8 
        class Issue
        {
            const FINE = 'FINE';
            const ALREADY_USED_USERNAME = 'ALREADY_USED_USERNAME';
            const UNMATCHED_PASSWORD = 'UNMATCHED_PASSWORD';
            const MALFORMATED_INPUTS = 'MALFORMATED_INPUTS';
            const REQUEST_ERROR = 'REQUEST_ERROR';
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
    $db = connect();
    //Les champs sont partiellement remplis
    if(empty($_POST) || empty($_POST['password']) || empty($_POST['username']) || empty($_POST['cpassword'])){
        $ok = Issue::FINE; //On envoie un formulaire vide comme si les champs était vide
    }else{
        $username = $_POST['username'];
        $password =  $_POST['password'];
        $cpassword = $_POST['cpassword'];
        if($password != $cpassword){
            $ok = Issue::UNMATCHED_PASSWORD;
        }else{
            $resultUsed = isUsernameUsed($db,$username);
            if($resultUsed instanceof string){
                $ok = Issue::REQUEST_ERROR;
            }else{
                //TEST SI Le nom d'utilisateur est déja utilisé
                if($resultUsed == true){
                    $ok = Issue::ALREADY_USED_USERNAME;
                }else{
                    $hashed_password = password_hash($password,PASSWORD_DEFAULT);
                    $result = ajoutUtilisateur($db,$username,$hashed_password);
                    if($result instanceof string){
                        $ok = Issue::REQUEST_ERROR;
                    }else{
                        $result = connectUser($db,$username);
                        if($result==false){
                            $ok = Issue::REQUEST_ERROR;
                        }else{
                            session_start();
                            $_SESSION['user']= $result;
                            header("Location:index.php");
                            exit();
                        }
                        
                    }
                }
            }
        }
    }
    signout($db);
    ?>
    <div class="login-container">
        <h1>Inscription</h1>
        <?php 
            if($ok ==Issue::UNMATCHED_PASSWORD){
                echo "<p class =\"error\">Les mots de passes ne correspondent pas.</p>";
            }else if($ok == Issue::MALFORMATED_INPUTS){
                echo "<p class =\"error\">Erreur de format.</p>";
            }else if($ok == Issue::REQUEST_ERROR){
                echo "<p class =\"error\">Erreur de requête à la base de donnée.</p>";
            }else if($ok == Issue::ALREADY_USED_USERNAME){
                echo "<p class =\"error\">Ce nom d'utilisateur est déja utilisé.</p>";
            }
        ?>
        <form action="signin.php" method="POST">
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
                <input type="password" id="cpassword" name="cpassword" required>
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