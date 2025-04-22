<?php
require_once("../common/db.php");
$db = connect();
//On vérifie qu'il n'y a aucune erreurs
if(empty($_POST)|| !isset($_POST['username'])|| !isset($_POST['points']) || !isset($_POST['id']) || !isset($_POST['type'])){
    header("Location: ../pages/error_pages.php");
    exit();
}

$username = $_POST['username'];
$points = $_POST['points'];
$id = $_POST['id'];
$type = $_POST['type'];
//On traite le cas ou le mot de passe n'est pas a modifier
if(!isset($_POST['password']) || empty($_POST['password'])){
    try{
        $stmt = $db->prepare("UPDATE utilisateur set identifiant = ?, points = ?,type = ? where idUtilisateur = ?");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $points, PDO::PARAM_INT);
        $stmt->bindParam(3, $type, PDO::PARAM_STR);
        $stmt->bindParam(4, $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['message'] = "<p class =\"sucess\">Modification terminée</p>";
    }catch(PDOException $e){
        die("Erreur dans la requete.");
    }
}else{
    $password = $_POST['password'];
    $hash_password = password_hash($password,PASSWORD_DEFAULT);
    try{
        $stmt = $db->prepare("UPDATE utilisateur set identifiant = ?, points = ?,mdp = ?,type = ? where idUtilisateur = ?");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $points, PDO::PARAM_INT);
        $stmt->bindParam(3, $hash_password, PDO::PARAM_STR);
        $stmt->bindParam(4, $type, PDO::PARAM_STR);
        $stmt->bindParam(5, $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['message'] = "<p class =\"sucess\">Modification terminée</p>";
    }catch(PDOException $e){
        die("Erreur dans la requete. $e");
    }
}
updateRank($db,$id);
signout($db);
header("Location: ../pages/admin.php?action=USR");
exit();
?>