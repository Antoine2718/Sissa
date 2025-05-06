<?php
session_start();
// === Supprimer un article du panier ===
// Ce script permet de supprimer un article du panier de l'utilisateur
$idArticle = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($idArticle && isset($_SESSION['panier'][$idArticle])) {
    unset($_SESSION['panier'][$idArticle]);// On supprime l'article du panier
    // On utilise unset pour supprimer l'article du tableau de session
}
//Le choix d'un fichier à part est fait pour éviter de faire des requêtes inutiles dans le cas où l'on supprime un article du panier
header("Location: ../pages/panier.php");
exit;