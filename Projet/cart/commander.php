<?php
require_once("../common/utilisateur.php");
session_start();
require_once '../common/db.php';
$pdo=connect();
// On démarre la session pour accéder aux variables de session
// === Ce script permet de commander les articles présents dans le panier ===
if (!isConnected()){
    header("Location: ../pages/login.php");
    exit();
}

if (empty($_SESSION['panier'])) {
    header("Location: ../pages/panier.php");
    exit;
}

try {
$pdo->beginTransaction();

    // Vérifier si l'utilisateur a un compte
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Utilisateur WHERE idUtilisateur = ?");
    $stmt->execute([getUser()->getID()]);
    if ($stmt->fetchColumn() == 0) {
        throw new Exception("Utilisateur non trouvé.");
    }

// Enregistrer l'achat avec les informations de promotion
$stmt = $pdo->prepare("INSERT INTO achete (idUtilisateur, idArticle, date_achat, quantité_achat, prix_achat, prix_original, promotion_appliquee, nom_promotion) 
                       VALUES (?, ?, NOW(), ?, ?, ?, ?, ?)");

    foreach ($_SESSION['panier'] as $idArticle => $item) {
        // Vérifier le stock
        $stmt_stock = $pdo->prepare("SELECT stock FROM Article WHERE idArticle = ? FOR UPDATE");
        $stmt_stock->execute([$idArticle]);
        $stock = $stmt_stock->fetchColumn();

        if ($stock < $item['quantite']) {
            throw new Exception("Stock insuffisant pour l'article ID $idArticle");
        }

        // Mettre à jour le stock
        $stmt_update = $pdo->prepare("UPDATE Article SET stock = stock - ? WHERE idArticle = ?");
        $stmt_update->execute([$item['quantite'], $idArticle]);

        // Récupérer les dernières informations de promotion
        $stmt_promo = $pdo->prepare("SELECT a.prix,
            (SELECT MAX(p.proportion_promotion) FROM Promotion p 
            JOIN a_la_promotion ap ON p.idPromotion = ap.idPromotion 
            WHERE ap.idArticle = a.idArticle 
            AND p.debut_promotion <= NOW() 
            AND p.fin_promotion >= NOW()) as promotion_active,
            (SELECT p.nom_promotion FROM Promotion p 
            JOIN a_la_promotion ap ON p.idPromotion = ap.idPromotion 
            WHERE ap.idArticle = a.idArticle 
            AND p.debut_promotion <= NOW() 
            AND p.fin_promotion >= NOW()
            ORDER BY p.proportion_promotion DESC LIMIT 1) as nom_promotion
            FROM Article a WHERE idArticle = ?");
        $stmt_promo->execute([$idArticle]);
        $promo_info = $stmt_promo->fetch(PDO::FETCH_ASSOC);
        
        $prix_original = $promo_info['prix'];
        $promotion_active = $promo_info['promotion_active'] ?? null;
        $prix_achat = $promotion_active ? $prix_original * (1 - $promotion_active) : $prix_original;
        $nom_promotion = $promo_info['nom_promotion'] ?? null;

    // Enregistrer l'achat
    $stmt->execute([
        getUser()->getID(),
        $idArticle,
        $item['quantite'],
        $prix_achat,
        $prix_original,
        $promotion_active,
        $nom_promotion
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