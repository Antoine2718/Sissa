<?php
require_once("../common/db.php");
$db = connect();
if(empty($_POST)|| !isset($_POST['qte'])||!isset($_POST['id'])){
    header("Location: ../pages/error_page.php");
    exit();
}
$id = $_POST['id'];
$qte = $_POST['qte'];
try{
    $stmt = $db->prepare("UPDATE article set stock = ? where idArticle = ?");
    $stmt->bindParam(1, $qte, PDO::PARAM_INT);
    $stmt->bindParam(2, $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->fetch(PDO::FETCH_ASSOC);
    session_start();
    $_SESSION['message'] = "<p class =\"success\">Modification du stock terminée</p>";
}catch(PDOException $e){
    die("Erreur dans la requete.");
}
signout($db);
header("Location: ../pages/admin.php?action=STK");
exit();
?>