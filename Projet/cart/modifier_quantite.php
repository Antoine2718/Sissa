<?php
session_start();
// ==== Modifier la quantité d'un article dans le panier ===
// Ce script permet de modifier la quantité d'un article dans le panier de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idArticle = filter_input(INPUT_POST, 'idArticle', FILTER_VALIDATE_INT);// On récupère l'ID de l'article
    // On utilise filter_input pour valider l'ID de l'article
    $quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT);// On récupère la quantité souhaitée
    // On utilise filter_input pour valider la quantité

    if ($idArticle && $quantite > 0) {
        require_once '../common/db.php';
        $pdo = connect();
        
        $stmt = $pdo->prepare("select stock from Article where idArticle = ?");// On prépare la requête pour récupérer le stock de l'article
        // On utilise une requête préparée pour éviter les injections SQL
        $stmt->execute([$idArticle]);
        $stock = $stmt->fetchColumn();

        if ($quantite <= $stock) {//Si la quantité souhaitée est inférieure ou égale au stock
            // On met à jour la quantité de l'article dans le panier
            $_SESSION['panier'][$idArticle]['quantite'] = $quantite;
        } else {//Sinon
            // On affiche un message d'erreur
            $_SESSION['erreur'] = "Quantité maximale disponible : $stock";
        }
    }
}

header("Location: ../pages/panier.php");
exit;