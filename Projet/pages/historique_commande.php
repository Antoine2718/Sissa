<?php
require_once("../common/utilisateur.php");
session_start();
require_once '../common/db.php';
$pdo = connect();

// Vérifier si l'utilisateur est connecté
if (!isConnected()) {
    $_SESSION['erreur'] = "Vous devez être connecté pour voir votre historique de commandes.";
    header("Location: login.php");
    exit;
}

$message = $_SESSION['succes'] ?? '';
$erreur = $_SESSION['erreur'] ?? '';
unset($_SESSION['succes'], $_SESSION['erreur']);
// Récupérer l'historique des commandes de l'utilisateur
$idUtilisateur = getUser()->getID();
$commandes = getPurchaseHistory($pdo,$idUtilisateur);
// Regrouper les commandes par date
$commandesParDate = groupPurchasesPerDay($commandes);
$isMessageToggled=true;
$title="Historique de vos commandes";
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("../common/styles.php"); ?>
    <link rel="stylesheet" href="historique_commande.css">
</head>
<body>
    <?php include("../common/nav.php"); ?>

    <div class="content">
        <?php //Centralisation de l'affichage de l'historique de commande
            include("../cart/afficherHistoriqueCommande.php");
        ?>
    </div>
    
    <?php include("../common/footer.php"); ?>
</body>
</html>