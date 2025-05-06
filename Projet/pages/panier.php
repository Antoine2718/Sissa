<?php
require_once("../common/utilisateur.php");
session_start();
require_once '../common/db.php';
$pdo = connect();

$panier = $_SESSION['panier'] ?? []; // Récupérer le panier de la session, ou un tableau vide si non défini
$articles = [];
$total = 0;

if (!empty($panier)) {
    $ids = array_keys($panier);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));// Créer des placeholders pour la requête préparée, la fonction array_fill crée un tableau de la même taille que le nombre d'articles dans le panier, et implode les transforme en une chaîne de caractères séparée par des virgules
    
    $stmt = $pdo->prepare("SELECT a.*, 
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
    FROM Article a WHERE a.idArticle IN ($placeholders)");
    $stmt->execute($ids);
    
    while ($article = $stmt->fetch(PDO::FETCH_ASSOC)) { // Récupérer les informations de chaque article dans le panier
        $quantite = $panier[$article['idArticle']]['quantite']; // Récupérer la quantité de l'article dans le panier
        $prix_unitaire = !empty($article['promotion_active']) ? 
            $article['prix'] * (1 - $article['promotion_active']) : 
            $article['prix'];
        
        $articles[] = [
            'infos' => $article,
            'quantite' => $quantite,
            'prix_unitaire' => $prix_unitaire,
            'prix_original' => $article['prix'],
            'promotion_active' => $article['promotion_active'] ?? null,
            'nom_promotion' => $article['nom_promotion'] ?? '',
            'total' => $quantite * $prix_unitaire
        ];
        $total += $quantite * $prix_unitaire;
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
                <i>🛒</i> <!-- La balise <i> est utilisée ici car elle est historiquement destinée à représenter du texte en italique, mais elle est souvent utilisée pour des icônes dans les pratiques modernes. Elle est légère, stylable avec CSS, et sémantiquement neutre, ce qui la rend appropriée pour afficher une icône comme celle-ci. -->
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
                                        <img src="<?= htmlspecialchars($item['infos']['lien_image']) ?>" alt="<?= htmlspecialchars($item['infos']['nom']) ?>"><!-- Notons que < ?= est l'équivalent php de < ?php echo -->
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
                        <td>
                            <?php if (!empty($item['promotion_active'])): ?>
                                <span class="prix-original"><?= number_format($item['prix_original'], 2, ',', ' ') ?> €</span>
                                <span class="prix-promotion"><?= number_format($item['prix_unitaire'], 2, ',', ' ') ?> €</span>
                                <div class="badge-promo">-<?= round($item['promotion_active'] * 100) ?>%</div>
                            <?php else: ?>
                                <?= number_format($item['prix_unitaire'], 2, ',', ' ') ?> €
                            <?php endif; ?>
                        </td>
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