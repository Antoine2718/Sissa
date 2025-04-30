<?php echo $title ?>

<?php if (!empty($message)): ?>
    <div class="message-container success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if (!empty($erreur)): ?>
    <div class="message-container error"><?= htmlspecialchars($erreur) ?></div>
<?php endif; ?>

<?php if (empty($commandesParDate)&&isset($isMessageToggled)&&$isMessageToggled==true) : ?>
    <div class="no-commandes">
        <h2>Vous n'avez pas encore passé de commande</h2>
        <p>Découvrez nos produits et commencez vos achats</p>
        <a href="shop.php" class="bouton-retour">Parcourir la boutique</a>
    </div>
<?php else : ?>
    <?php foreach ($commandesParDate as $date => $articles) : ?>
        <div class="commande-card">
            <div class="commande-header">
                <div class="commande-date">Commande du <?= date('d/m/Y', strtotime($date)) ?></div>
                <div class="commande-time">à <?= date('H:i', strtotime($articles[0]['date_achat'])) ?></div>
            </div>
            <div class="commande-items">
                <?php $totalCommande = 0; ?>
                <?php foreach ($articles as $article) : ?>
                    <?php 
                    // Utiliser le prix d'achat réel et non le prix actuel
                    $prixUnitaire = $article['prix_achat'] ?? $article['prix'];
                    $totalArticle = $prixUnitaire * $article['quantité_achat']; 
                    $totalCommande += $totalArticle; 
                    ?>
                    <div class="commande-item">
                        <div class="produit-image">
                            <?php 
                            $image_path = null;
                            if (!empty($article['lien_image'])) {
                                $image_path = $article['lien_image'];
                                // Si le chemin ne commence pas par un chemin relatif, ajouter le préfixe
                                if (strpos($image_path, "..\\") !== 0 && strpos($image_path, "../") !== 0) {
                                    $image_path = "../images/Shop-images/" . $image_path;
                                }
                                // Remplacer les backslashes par des slashes pour la compatibilité web
                                $image_path = str_replace("\\", "/", $image_path);
                            ?>
                                <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($article['nom']) ?>">
                            <?php } else { ?>
                                <?= strtoupper(substr($article['nom'], 0, 1)) ?>
                            <?php } ?>
                        </div>
                        <div class="produit-details">
                            <div class="produit-nom"><?= htmlspecialchars($article['nom']) ?></div>
                            <div class="produit-meta">
                            Quantité: <?= $article['quantité_achat'] ?> × <?= number_format($prixUnitaire, 2, ',', ' ') ?> €
                                <?php if (isset($article['prix_original']) && isset($article['prix_achat']) && $article['prix_achat'] < $article['prix_original']) : ?>
                                    <span class="prix-reduit">
                                        <?php if (isset($article['promotion_appliquee']) && $article['promotion_appliquee'] > 0) : ?>
                                            (Réduction de <?= number_format($article['promotion_appliquee']*100, 0) ?>%, prix original: <?= number_format($article['prix_original'], 2, ',', ' ') ?> €)
                                        <?php else : ?>
                                            (Prix réduit, prix original: <?= number_format($article['prix_original'], 2, ',', ' ') ?> €)
                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (isset($article['nom_promotion']) && !empty($article['nom_promotion'])) : ?>
                                    <span class="promotion-nom"><?= htmlspecialchars($article['nom_promotion']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="produit-prix"><?= number_format($totalArticle, 2, ',', ' ') ?> €</div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="commande-footer">
                <div class="total-commande">Total: <?= number_format($totalCommande, 2, ',', ' ') ?> €</div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>