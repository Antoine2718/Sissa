<?php
session_start();
require_once '../common/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idArticle = filter_input(INPUT_POST, 'idArticle', FILTER_VALIDATE_INT); //On récupère l'id de l'article en s'assurant qu'il s'agit d'un entier
    if (!$idArticle) {
        $_SESSION['erreur'] = 'ID article invalide';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
    $quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_INT) ?: 1; //On récupère la quantité en s'assurant qu'il s'agit d'un entier, sinon on met 1 par défaut
    if (!$quantite || $quantite <= 0) {
        $_SESSION['erreur'] = 'Quantité invalide';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        $pdo = connect();
        $stmt = $pdo->prepare("select stock from Article where idArticle = ?");//On prépare la requête pour récupérer le stock de l'article
        $stmt->execute([$idArticle]);
        $stock = $stmt->fetchColumn();

        if ($stock >= $quantite) { //Si le stock est suffisant
            if (!isset($_SESSION['panier'])) {//On vérifie si le panier existe déjà, sinon on le crée
                $_SESSION['panier'] = [];
            }

            if (isset($_SESSION['panier'][$idArticle])) {//Si l'article est déjà dans le panier, on met à jour la quantité
                $_SESSION['panier'][$idArticle]['quantite'] += $quantite;
            } else {//Sinon, on l'ajoute au panier
                $_SESSION['panier'][$idArticle] = [
                    'quantite' => $quantite,//On ajoute la quantité de l'article au panier
                    'date_ajout' => date('Y-m-d H:i:s')//On ajoute la date d'ajout de l'article au panier
                ];
            }
            
            $_SESSION['message'] = 'Produit ajouté au panier !';
        } else {
            $_SESSION['erreur'] = 'Stock insuffisant';
        }
    } catch (PDOException $e) {
        error_log("Erreur panier: " . $e->getMessage());
        $_SESSION['erreur'] = 'Erreur technique';
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;