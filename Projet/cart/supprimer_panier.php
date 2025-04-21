<?php
session_start();
// === Supprimer un article du panier ===
// Ce script permet de supprimer un article du panier de l'utilisateur
$idArticle = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($idArticle && isset($_SESSION['panier'][$idArticle])) {
    unset($_SESSION['panier'][$idArticle]);// On supprime l'article du panier
    // On utilise unset pour supprimer l'article du tableau de session
}

header("Location: ../pages/panier.php");
exit;