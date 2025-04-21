<?php
require_once("../common/utilisateur.php");
session_start();
require_once '../common/db.php';
$pdo = connect();

$panier = $_SESSION['panier'] ?? [];
$articles = [];
$total = 0;

if (!empty($panier)) {
    $ids = array_keys($panier);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    
    $stmt = $pdo->prepare("select * from Article where idArticle IN ($placeholders)");
    $stmt->execute($ids);
    
    while ($article = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $quantite = $panier[$article['idArticle']]['quantite'];
        $articles[] = [
            'infos' => $article,
            'quantite' => $quantite,
            'total' => $quantite * $article['prix']
        ];
        $total += $quantite * $article['prix'];
    }
}

// Afficher les messages d'erreur ou de succès
$message = $_SESSION['message'] ?? '';
$erreur = $_SESSION['erreur'] ?? '';
unset($_SESSION['message'], $_SESSION['erreur']);
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("../common/styles.php"); ?>
    <link rel="stylesheet" href="panier.css">
    <title>Mon Panier - Sissa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Votre panier d'achats en ligne. Consultez vos articles, modifiez les quantités ou passez à la caisse.">
    <meta name="keywords" content="panier, achats, e-commerce, boutique en ligne, morpion, produits, commande">
</head>
<body>
    <?php include("../common/nav.php"); ?>

    <div class="content">
        <h1>Votre Panier</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message-container success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($erreur)): ?>
            <div class="message-container error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <?php if (empty($articles)) : ?>
            <div class="panier-vide">
                <i>🛒</i>
                <h2>Votre panier est vide</h2>
                <p>Découvrez nos produits et ajoutez-les à votre panier</p>
                <a href="shop.php" class="bouton-retour">Parcourir la boutique</a>
            </div>
        <?php else : ?>
            <table class="table-panier">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $item) : ?>
                    <tr>
                        <td>
                            <div class="produit-info">
                                <div class="produit-image">
                                    <?php if (!empty($item['infos']['lien_image'])): ?>
                                        <img src="<?= htmlspecialchars($item['infos']['lien_image']) ?>" alt="<?= htmlspecialchars($item['infos']['nom']) ?>">
                                    <?php else: ?>
                                        <?= strtoupper(substr($item['infos']['nom'], 0, 1)) ?>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <div class="produit-nom"><?= htmlspecialchars($item['infos']['nom']) ?></div>
                                    <?php if (isset($item['infos']['idArticle'])) : ?>
                                    <div class="produit-details">Réf: <?= htmlspecialchars($item['infos']['idArticle']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?= number_format($item['infos']['prix'], 2, ',', ' ') ?> €</td>
                        <td>
                            <div class="quantite-container">
                                <!-- Formulaire pour la diminution -->
                                <form method="post" action="../cart/modifier_quantite.php" style="display: inline;">
                                    <input type="hidden" name="idArticle" value="<?= $item['infos']['idArticle'] ?>">
                                    <input type="hidden" name="quantite" value="<?= max(1, $item['quantite'] - 1) ?>">
                                    <button type="submit" class="btn-moins">-</button>
                                </form>
                                
                                <!-- Affichage de la quantité actuelle -->
                                <span class="quantite-input"><?= $item['quantite'] ?></span>
                                
                                <!-- Formulaire pour l'augmentation -->
                                <form method="post" action="../cart/modifier_quantite.php" style="display: inline;">
                                    <input type="hidden" name="idArticle" value="<?= $item['infos']['idArticle'] ?>">
                                    <input type="hidden" name="quantite" value="<?= min($item['infos']['stock'], $item['quantite'] + 1) ?>">
                                    <button type="submit" class="btn-plus">+</button>
                                </form>
                            </div>
                        </td>
                        <td><?= number_format($item['total'], 2, ',', ' ') ?> €</td>
                        <td>
                            <a href="../cart/supprimer_panier.php?id=<?= $item['infos']['idArticle'] ?>" class="bouton-supprimer" title="Supprimer cet article">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total général</td>
                        <td colspan="2"><strong><?= number_format($total, 2, ',', ' ') ?> €</strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="actions-panier">
                <a href="shop.php" class="bouton-retour">← Continuer mes achats</a>
                <a href="../cart/commander.php" class="bouton-commander">Valider ma commande →</a>
            </div>
        <?php endif; ?>
    </div>
    <?php include("../common/footer.php"); ?>
</body>
</html>