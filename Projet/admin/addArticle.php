<?php
require_once("../common/db.php");
$db = connect();
if(empty($_POST)|| !isset($_POST['name'])||!isset($_POST['descp'])||!isset($_POST['price'])||!isset($_POST['stock'])||!isset($_POST['categorie'])){
    header("Location: ../pages/error_page.php");
    exit();
}
$name = $_POST['name'];
$description = $_POST['descp'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$lien = $_POST['icone']??"";
$categorie = $_POST['categorie'];
try{
    $stmt = $db->prepare("INSERT INTO article (prix,nom,stock,description,lien_image,categorie)values (?,?,?,?,?,?)");
    $stmt->bindParam(1, $price, PDO::PARAM_STR);
    $stmt->bindParam(2, $name, PDO::PARAM_STR);
    $stmt->bindParam(3, $stock, PDO::PARAM_INT);
    $stmt->bindParam(4, $description, PDO::PARAM_STR);
    $stmt->bindParam(5, $lien, PDO::PARAM_STR);
    $stmt->bindParam(6, $categorie, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->fetch(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    die("Erreur dans la requete.");
}
signout($db);
header("Location: ../pages/admin.php?action=STK");
exit();
?>