<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Détails du produit sur la boutique Sissa. Découvrez nos équipements de qualité pour les joueurs et compétiteurs.">
    <meta name="keywords" content="produit, boutique, Sissa, équipements, joueurs, compétiteurs, morpion, détails, achat">
    <?php
    require_once("../common/utilisateur.php");
    session_start(); // On démarre la session pour pouvoir utiliser les variables de session
    // Initialisation des variables
    $product = null;
    $pageTitle = "Produit";
    $error = null;

    // Traitement de l'ID
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($id > 0) {
            require_once '../common/db.php';
            try {
                $pdo = connect();
                
                // Récupération des best-sellers des 30 derniers jours
                $sql_bestsellers = "SELECT a.idArticle, SUM(ac.quantité_achat) as total_ventes
                    FROM Article a
                    JOIN achete ac ON a.idArticle = ac.idArticle
                    WHERE ac.date_achat >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    GROUP BY a.idArticle
                    ORDER BY total_ventes DESC
                    LIMIT 3";
                $stmt_bestsellers = $pdo->query($sql_bestsellers);
                $bestSellers = $stmt_bestsellers->fetchAll(PDO::FETCH_COLUMN, 0); // Récupère seulement les IDs
                
                // Requête unique pour récupérer toutes les données
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
                    FROM Article a WHERE idArticle = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($product) {
                    $pageTitle = htmlspecialchars($product['nom']);
                } else {
                    $error = "Produit introuvable";
                }
            } catch (PDOException $e) {
                $error = "Erreur de connexion à la base de données";
                error_log("Erreur SQL (produit.php): " . $e->getMessage());
            }
        } else {
            $error = "ID de produit invalide";
        }
    } else {
        $error = "Aucun produit sélectionné";
    }
    echo "<title>$pageTitle - Boutique Sissa</title>";
    ?>
    <?php include("../common/styles.php"); ?>
    <link rel ="stylesheet" href="produit.css">
    <style>

    </style>
</head>
<body>
    <?php 
    include("../common/nav.php"); ?>

    <div class="banniere-produit">
        <h1><?= $error ? "Erreur" : html_entity_decode($pageTitle) ?></h1> <!-- Ici html_entity_decode est nécessaire, autrement il peut y avoir des erreurs d'affichage comme un %quot; au lieu de " " -->
    <?php if (!$error): ?>
        <p class="intro-produit">
            Chez Sissa, nous avons sélectionné avec soin ce
            <span class="nom-produit"><?= htmlspecialchars($product['nom']) ?></span> 
            pour vous offrir le meilleur équipement, conçu spécialement pour les joueurs et compétiteurs exigeants.
        </p>
    <?php else: ?>
        <p>Un problème est survenu</p>
    <?php endif; ?>
    </div>

    <div class="content">
        <?php if ($error): ?>
            <div class="error-message">
                <h2>Oops!</h2>
                <p><?= $error ?></p>
                <div class="text-center" style="margin-top: 20px;">
                    <a href="shop.php" class="color-button">Retour à la boutique</a>
                </div>
            </div>
        <?php elseif ($product): ?>
            <div class="produit-container">
                <div class="produit-header">
                    <?php if ($product['stock'] > 0): ?>
                        <div class="badge-produit">En stock</div>
                    <?php else: ?>
                        <div class="badge-produit" style="background-color: #f8d7da; color: #721c24;">Rupture de stock</div>
                    <?php endif; ?>
                </div>
                
                <div class="produit-contenu">
                    <div class="produit-image-wrapper">
                        <?php if (in_array($product['idArticle'], $bestSellers)): ?>
                            <div class="badge-bestseller">Best-seller</div>
                        <?php endif; ?>
                        <img src="<?= htmlspecialchars($product['lien_image']) ?>" 
                             alt="<?= htmlspecialchars($product['nom']) ?>" 
                             class="produit-image">
                    </div>
                    
                    <div class="produit-details">
                        <h1 class="produit-titre"><?= htmlspecialchars($product['nom']) ?></h1>
                        
                        <div class="produit-prix">
                            <?php if (!empty($product['promotion_active'])): ?>
                                <span class="prix-original"><?= number_format($product['prix'], 2, ',', ' ') ?> €</span>
                                <span class="prix-promotion">
                                    <?= number_format($product['prix'] * (1 - $product['promotion_active']), 2, ',', ' ') ?> €
                                </span>
                                <div class="badge-promo">-<?= round($product['promotion_active'] * 100) ?>% <?= htmlspecialchars($product['nom_promotion']) ?></div>
                            <?php else: ?>
                                <?= number_format($product['prix'], 2, ',', ' ') ?> €
                            <?php endif; ?>
                        </div>
                        
                        <p class="produit-description">
                            <?= nl2br(htmlspecialchars($product['description'])) ?>
                        </p>
                        
                        <div class="produit-meta">
                            <div class="meta-item">
                                <span class="meta-label">Catégorie:</span>
                                <span><?= htmlspecialchars($product['categorie']) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Disponibilité:</span>
                                <?php if ($product['stock'] > 0): ?>
                                    <span class="stock-disponible">En stock</span>
                                <?php else: ?>
                                    <span class="stock-rupture">Rupture de stock</span>
                                <?php endif; ?>
                                <!-- On pourrait ajouter une icône en plus du message concernant le stock, peut-être peut-on ajouter le nb de produits en stock ? -->
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="shop.php" class="bouton-retour">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/> <!-- Ce sont des SVG, on peut les remplacer par des images si besoin, il s'agit d'instructions afin de dessiner des flèches -->
                                </svg>
                                Retour à la boutique
                            </a>
                            
                            <?php if ($product['stock'] > 0): ?>
                                <form method="post" action="../cart/ajouter_au_panier.php">
                                    <input type="hidden" name="idArticle" value="<?= $product['idArticle'] ?>">
                                    <input type="hidden" name="nom" value="<?= htmlspecialchars($product['nom']) ?>">
                                    <input type="hidden" name="prix" value="<?= !empty($product['promotion_active']) ? $product['prix'] * (1 - $product['promotion_active']) : $product['prix'] ?>">
                                    <input type="hidden" name="prix_original" value="<?= $product['prix'] ?>">
                                    <input type="hidden" name="promotion_active" value="<?= $product['promotion_active'] ?? '' ?>">
                                    <input type="hidden" name="nom_promotion" value="<?= $product['nom_promotion'] ?? '' ?>">
                                    <input type="hidden" name="quantite" value="1">
                                    <button type="submit" class="bouton-panier">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                        </svg>
                                        Ajouter au panier
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="bouton-panier" style="background-color: #6c757d; cursor: not-allowed;" disabled>
                                    Produit indisponible
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Section pour les produits similaires -->
            <?php
            // On pourrait ajouter une requête ici pour afficher des produits similaires
            // basés sur la même catégorie
            if ($product) {
                try {
                    $categorie = $product['categorie'];
                    $id = $product['idArticle'];
                    
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
                        FROM Article a WHERE categorie = :categorie AND idArticle != :id AND stock > 0 ORDER BY RAND() LIMIT 3");
                    $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $produits_similaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($produits_similaires) > 0):
            ?>
            <!-- TO DO améliorer le rendu de cette section, pour l'instant brut et pas très agréable -->
            <section class="section-produits-similaires">
            <h2>Produits similaires</h2>
            <div class="liste-produits">
                <?php foreach ($produits_similaires as $produit): ?>
                <div class="produit-similaire">
                    <?php if (in_array($produit['idArticle'], $bestSellers)): ?>
                        <div class="badge-bestseller">Best-seller</div>
                    <?php endif; ?>
                    <a href="produit.php?id=<?= $produit['idArticle'] ?>">
                        <img src="<?= htmlspecialchars($produit['lien_image']) ?>" 
                            alt="<?= htmlspecialchars($produit['nom']) ?>">
                    </a>
                    <h3 class="nom-produit"><?= htmlspecialchars($produit['nom']) ?></h3>
                    <div class="prix-produit">
                        <?php if (!empty($produit['promotion_active'])): ?>
                            <span class="prix-original"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</span>
                            <span class="prix-promotion">
                                <?= number_format($produit['prix'] * (1 - $produit['promotion_active']), 2, ',', ' ') ?> €
                            </span>
                            <div class="badge-promo">-<?= round($produit['promotion_active'] * 100) ?>%</div>
                        <?php else: ?>
                            <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                        <?php endif; ?>
                    </div>
                    <p class="description-courte"><?= htmlspecialchars(substr($produit['description'], 0, 80)) ?>...</p>
                </div>
                <?php endforeach; ?>
            </div>
            </section>
            <?php
                    endif;
                } catch (PDOException $e) {
                    // Gestion silencieuse de l'erreur pour ne pas affecter l'affichage principal
                    error_log("Erreur lors de la récupération des produits similaires: " . $e->getMessage());
                }
            }
            ?>
        <?php endif; ?>
    </div>
    <?php include("../common/footer.php"); ?>
</body>
</html>