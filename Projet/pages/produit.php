<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
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
                // Requête unique pour récupérer toutes les données
                $stmt = $pdo->prepare("SELECT * FROM Article WHERE idArticle = :id");
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
    <style>
        /* Styles pour la bannière du produit */
        .banniere-produit {
            background: linear-gradient(rgba(0, 51, 102, 0.8), rgba(0, 51, 102, 0.8)), 
                        url('../assets/images/banner-shop.jpg');
            background-size: cover;
            color: white;
            padding: 40px 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .error-message {
            color: #d9534f;
            text-align: center;
            margin: 50px 0;
            font-size: 1.2rem;
            background: #fff5f5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-left: 4px solid #d9534f;
        }

        /* Nouveau design pour la page produit */
        .produit-container {
            display: flex;
            flex-direction: column;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin: 30px auto;
            max-width: 1200px;
            overflow: hidden;
        }

        .produit-header {
            position: relative;
            padding-bottom: 30px;
        }

        .badge-produit {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #ffd700;
            color: #003366;
            padding: 8px 20px;
            font-weight: bold;
            border-radius: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .produit-contenu {
            display: flex;
            flex-direction: column;
            gap: 30px;
            padding: 0 20px 30px;
        }

        @media (min-width: 768px) {
            .produit-contenu {
                flex-direction: row;
            }
        }

        .produit-image-wrapper {
            flex: 1;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .produit-image-wrapper:hover {
            transform: translateY(-5px);
        }

        .produit-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .produit-image-wrapper:hover .produit-image {
            transform: scale(1.03);
        }

        .produit-details {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .produit-titre {
            font-size: 2.2rem;
            color: #003366;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
            position: relative;
        }

        .produit-titre::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background-color: #003366;
        }

        .produit-prix {
            font-size: 1.8rem;
            font-weight: bold;
            color: #003366;
            display: inline-block;
            background: #f1f5f9;
            padding: 10px 25px;
            border-radius: 30px;
        }

        .produit-description {
            font-size: 1.1rem;
            line-height: 1.7;
            color: #444;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 3px solid #003366;
        }

        .produit-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 10px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border: 1px solid #e1e7ef;
        }

        .meta-label {
            font-weight: 600;
            color: #003366;
        }

        .stock-disponible {
            color: #28a745;
            font-weight: 600;
        }

        .stock-rupture {
            color: #dc3545;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        .bouton-retour {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            border: 2px solid #003366;
            color: #003366;
            background: transparent;
            transition: all 0.3s ease;
        }

        .bouton-retour:hover {
            background: #e6f0ff;
        }

        .bouton-panier {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            background: #003366;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,51,102,0.2);
        }

        .bouton-panier:hover {
            background: #00254d;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,51,102,0.3);
        }

        /* Recommandations de produits similaires */
        .produits-similaires {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 3px solid #f1f5f9;
        }

        .produits-similaires h2 {
            text-align: center;
            color: #003366;
            font-size: 1.8rem;
            margin-bottom: 30px;
            position: relative;
        }

        .produits-similaires h2::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #003366;
        }
    </style>
</head>
<body>
    <?php include("../common/nav.php"); ?>

    <div class="banniere-produit">
        <h1><?= $error ? "Erreur" : html_entity_decode($pageTitle) ?></h1> <!-- Ici html_entity_decode est nécessaire, autrement il peut y avoir des erreurs d'affichage comme un %quot; au lieu de " " -->
        <p><?= $error ? "Un problème est survenu" : "Découvrez ce produit en détail" ?></p>
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
                        <img src="<?= htmlspecialchars($product['lien_image']) ?>" 
                             alt="<?= htmlspecialchars($product['nom']) ?>" 
                             class="produit-image">
                    </div>
                    
                    <div class="produit-details">
                        <h1 class="produit-titre"><?= htmlspecialchars($product['nom']) ?></h1>
                        
                        <div class="produit-prix">
                            <?= number_format($product['prix'], 2, ',', ' ') ?> €
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
                                <button class="bouton-panier">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                    Ajouter au panier
                                </button>
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
                    
                    $stmt = $pdo->prepare("SELECT * FROM Article WHERE categorie = :categorie AND idArticle != :id AND stock > 0 ORDER BY RAND() LIMIT 3");
                    $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    $produits_similaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($produits_similaires) > 0):
            ?>
            <!-- TO DO améliorer le rendu de cette section, pour l'instant brut et pas très agréable -->
            <section class="produits-similaires">
                <h2>Produits similaires</h2>
                <div class="grille-produits">
                    <?php foreach ($produits_similaires as $produit): ?>
                    <div class="carte-produit">
                        <img src="<?= htmlspecialchars($produit['lien_image']) ?>" 
                             alt="<?= htmlspecialchars($produit['nom']) ?>" 
                             class="image-produit">
                        <div class="info-produit">
                            <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                            <p><?= htmlspecialchars(substr($produit['description'], 0, 100)) ?>...</p>
                            <div class="prix-produit">
                                <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                            </div>
                            <a href="produit.php?id=<?= $produit['idArticle'] ?>" class="color-button">Voir le produit</a>
                        </div>
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