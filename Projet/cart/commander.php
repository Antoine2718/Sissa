<?php
session_start();
require_once '../common/db.php';
$pdo=connect();
// On démarre la session pour accéder aux variables de session
// === Ce script permet de commander les articles présents dans le panier ===
if (!isset($_SESSION['idUtilisateur'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['panier'])) {
    header("Location: ../pages/panier.php");
    exit;
}

try {
    $pdo->beginTransaction();
    
    foreach ($_SESSION['panier'] as $idArticle => $item) {
        // Vérifier le stock
        $stmt = $pdo->prepare("select stock from Article where idArticle = ? for update");// On utilise "for update" pour verrouiller la ligne
        // Cela empêche d'autres transactions de modifier le stock pendant que nous l'utilisons
        $stmt->execute([$idArticle]);
        $stock = $stmt->fetchColumn();

        if ($stock < $item['quantite']) {
            throw new Exception("Stock insuffisant pour l'article ID $idArticle");
        }

        // Mettre à jour le stock
        $stmt = $pdo->prepare("update Article set stock = stock - ? where idArticle = ?");
        $stmt->execute([$item['quantite'], $idArticle]);

        // Enregistrer l'achat
        $stmt = $pdo->prepare("insert into achete (idUtilisateur, idArticle, date_achat, quantité_achat) 
                              values (?, ?, NOW(), ?)"); // On utilise NOW() pour la date d'achat
        // On utilise une requête préparée pour éviter les injections SQL
        $stmt->execute([
            $_SESSION['idUtilisateur'],
            $idArticle,
            $item['quantite']
        ]);
    }

    $pdo->commit();
    unset($_SESSION['panier']);
    $_SESSION['succes'] = "Commande validée avec succès !";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['erreur'] = "Erreur lors de la commande : " . $e->getMessage();
}

header("Location: ../pages/historique_commande.php"); // Redirige vers la page d'historique des commandes
exit;