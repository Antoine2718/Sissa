<?php
$target = $_SERVER['HTTP_REFERER'];
$regex_datetime = '/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/';
if(!isset($_GET['id']) || !isset($_GET['idP']) || !isset($_GET['date']) ){
    header("Location: ../pages/error_page.php");
    exit();
}
$id = $_GET['id'];
$idP = $_GET['idP'];
$date = $_GET['date'];
if(!preg_match($regex_datetime,$date)|| !preg_match("/^[0-9]+$/",$id)|| !preg_match("/^[0-9]+$/",$idP) ){
    header("Location: ../pages/error_page.php");
    exit();
}
//on récupère le prix acheté
try{
    $stmt = $db->prepare("SELECT prix_achat as prix, quantité_achat as qte FROM achete where idUtilisateur = ? and idArticle = ? and date_achat = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->bindParam(2, $idP, PDO::PARAM_INT);
    $stmt->bindParam(3, $date, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $prix = $result['prix'];
    $qte = $result['qte'];
}catch(PDOException $e){
    header("Location: ../pages/error_page.php");
    exit();
}
//on insere le remboursement
try{
    $stmt = $db->prepare("INSERT INTO remboursement (prix_remboursé,date_remboursement,idUtilisateur,idArticle) VALUES (?,NOW(),?,?)");
    $prix_rembourse = $prix * $qte;
    $stmt->bindParam(1, $prix_rembourse, PDO::PARAM_STR);
    $stmt->bindParam(2, $id, PDO::PARAM_INT);
    $stmt->bindParam(3, $idP, PDO::PARAM_INT);
    $stmt->execute();
}catch(PDOException $e){
    header("Location: ../pages/error_page.php");
    exit();
}
//on supprime l'achat
try{
    $stmt = $db->prepare("DELETE FROM achete where date_achat =? and idUtilisateur = ? and idArticle = ?");
    $stmt->bindParam(1, $date, PDO::PARAM_STR);
    $stmt->bindParam(2, $id, PDO::PARAM_INT);
    $stmt->bindParam(3, $idP, PDO::PARAM_INT);
    $stmt->execute();
}catch(PDOException $e){
    header("Location: ../pages/error_page.php");
    exit();
}
header("Location: $target");
?>