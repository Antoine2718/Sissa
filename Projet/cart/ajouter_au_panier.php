<?php
session_start();
// Récupérer les données du formulaire
$idArticle = $_POST['idArticle'] ?? null;
$nom = $_POST['nom'] ?? '';
$prix = $_POST['prix'] ?? 0;
$prix_original = $_POST['prix_original'] ?? $prix;
$promotion_active = $_POST['promotion_active'] ?? null;
$nom_promotion = $_POST['nom_promotion'] ?? '';
$quantite = $_POST['quantite'] ?? 1;

if ($idArticle) {
    // Initialiser le panier s'il n'existe pas
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    
    // Ajouter ou mettre à jour l'article dans le panier
    if (isset($_SESSION['panier'][$idArticle])) {
        $_SESSION['panier'][$idArticle]['quantite'] += $quantite;
    } else {
        $_SESSION['panier'][$idArticle] = [
            'nom' => $nom,
            'prix' => $prix,
            'prix_original' => $prix_original,
            'promotion_active' => $promotion_active,
            'nom_promotion' => $nom_promotion,
            'quantite' => $quantite
        ];
    }
    
    $_SESSION['message'] = "Article ajouté au panier avec succès !";
} else {
    $_SESSION['erreur'] = "Erreur lors de l'ajout au panier";
}

// Rediriger vers la page précédente ou le panier
header("Location: ../pages/panier.php");
exit;