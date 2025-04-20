<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique Sissa</title>
    <?php 
        include("../common/styles.php"); 
    ?>
    <style>
        /* Styles principaux de la page shop */
        .banniere-principale {
            background: linear-gradient(rgba(0, 51, 102, 0.8), rgba(0, 51, 102, 0.8));
            background-size: cover;
            color: white;
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .section-filtres {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .grille-produits {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        .carte-produit {
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            background: white;
            display: flex;
            flex-direction: column;
            height: 100%;
        }


        
        .carte-produit:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .image-produit {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .info-produit {
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            justify-content: space-between;
        }

        .info-produit h3, 
        .info-produit p, 
        .info-produit .prix-produit {
            margin-bottom: 10px;
        }

        .info-produit .color-button {
            margin-top: auto;
            align-self: center;
            width: 80%;
        }        
        .prix-produit {
            font-weight: bold;
            color: #003366;
            font-size: 1.2em;
            margin: 10px 0;
        }

        /* Styles pour les produits vedettes */
        .badge-vedette {
            position: absolute;
            top: 10px;
            right: -10px;
            background: #ffd700;
            color: #003366;
            padding: 5px 15px;
            font-weight: bold;
            border-radius: 3px;
            transform: rotate(5deg);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .carte-vedette {
            border: 3px solid #003366 !important;
            background: #f8f9fa;
        }

        .carte-vedette:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,51,102,0.2);
        }

        .produits-vedettes {
            border-bottom: 5px solid #003366;
            margin-bottom: 40px;
            padding-bottom: 40px;
            position: relative;
        }

        .produits-vedettes::after {
            content: "";
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.5rem;
            background: white;
            padding: 0 10px;
        }

        .produits-vedettes h2 {
            font-size: 2.5rem;
            text-transform: uppercase;
            color: #003366;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }

        /* Style pour les filtres*/
        .filtres-categories {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
            justify-content: center;
        }
        
        .bouton-categorie {
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid #003366;
            background-color: white;
            color: #003366;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .bouton-categorie:hover {
            background-color: #e6f0ff;
        }
        
        .bouton-categorie.actif {
            background-color: #003366;
            color: white;
        }
        
        /* Style spécifiquement pour le filtre prix */
        .filtre-prix {
            background: white;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 15px;
            justify-content: center;
        }
        
        .groupe-prix {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filtre-prix label {
            font-weight: 500;
            color: #555;
        }
        
        .filtre-prix input {
            width: 12em;
            padding: 0.5em 0.8em;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 1em;
            transition: border 0.3s;
        }
        
        .filtre-prix input:focus {
            outline: none;
            border-color: #003366;
        }
        
        .bouton-appliquer {
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: 500;
        }
        
        .bouton-appliquer:hover {
            background-color: #00254d;
        }
        /* Styles pour la pagination */
        .pagination {
            margin: 40px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .controles-pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            align-items: center;
        }

        .bouton-pagination {
            padding: 8px 15px;
            background-color: #f1f5f9;
            color: #003366;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            border: 1px solid #e1e7ef;
        }

        .bouton-pagination:hover {
            background-color: #e1e7ef;
        }

        .pages-numeros {
            display: flex;
            gap: 5px;
        }

        .numero-page {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            text-decoration: none;
            font-weight: 500;
            color: #003366;
            background-color: #f1f5f9;
            transition: all 0.3s ease;
        }

        .numero-page:hover {
            background-color: #e1e7ef;
        }

        .numero-page.page-active {
            background-color: #003366;
            color: white;
        }

        .info-pagination {
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .controles-pagination {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php 
    // Inclusion de la navigation et connexion à la base de données
    include("../common/nav.php"); 
    require_once("../common/db.php");
    $pdo = connect();
    ?>
    <?php
    // Configuration de la pagination
    $articles_par_page = 5; // Nombre d'articles à afficher par page, peut éventuellement être modifié, lorsque je l'ai fait j'ai essayé avec 6, mais le rendu était pas top
    $page_courante = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $debut = ($page_courante - 1) * $articles_par_page;

    // Construction des filtres (utilisé pour les deux requêtes)
    $where = [];
    $params = [];

    // Ajout du filtre par catégorie si spécifié
    if (!empty($_GET['categorie'])) {
        $where[] = "categorie = ?";
        $params[] = $_GET['categorie'];
    }

    // Ajout du filtre prix minimum si spécifié
    if (!empty($_GET['min_price'])) {
        $where[] = "prix >= ?";
        $params[] = (float)$_GET['min_price'];
    }

    // Ajout du filtre prix maximum si spécifié
    if (!empty($_GET['max_price'])) {
        $where[] = "prix <= ?";
        $params[] = (float)$_GET['max_price'];
    }

    // Construction de la condition WHERE commune
    $where_clause = "stock > 0";
    if ($where) {
        $where_clause .= " and " . implode(" and ", $where);
    }

    // Requête pour compter le nombre total d'articles (pour la pagination)
    $sql_count = "select count(*) from Article where " . $where_clause;
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute($params);
    $total_articles = $stmt_count->fetchColumn();
    $nombre_pages = ceil($total_articles / $articles_par_page);

    // Ajouter les paramètres de pagination à $params
    $params[] = $debut;
    $params[] = $articles_par_page;

    // Construction de la requête SQL
    $sql = "select * from Article where " . $where_clause . " limit ?, ?";

    // Préparation et exécution
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres avec gestion des types
    foreach ($params as $key => $value) {
        $param_type = PDO::PARAM_STR; // Type par défaut
        // Si c'est un paramètre de limit, forcer le type int
        if ($key >= count($params) - 2) { // Les 2 derniers paramètres
            $param_type = PDO::PARAM_INT;
        }
        $stmt->bindValue($key + 1, $value, $param_type);
    }

    $stmt->execute();
    $articles = $stmt->fetchAll();
?>
    <!-- Bannière principale de la boutique -->
    <div class="banniere-principale">
        <h1>Boutique Sissa</h1>
        <p>Découvrez notre collection exclusive</p>
    </div>

    <div class="content">
        <!-- Section des produits vedettes -->
        <section class="produits-vedettes">
            <h2>Nos Produits Vedettes</h2>
            <p>Découvrez les meilleurs articles de notre collection</p>
            
            <?php
            // Récupération des 3 derniers produits en stock, choix arbitraire des 3 articles avec le moins de stock
            // pour mettre en avant les produits qui se vendent le mieux
            // On pourrait aussi faire un tri par date d'ajout ou par popularité, mais ici on reste sur le stock
            $stmt = $pdo->prepare("select * from article where stock > 0 order by stock asc limit 3");
            $stmt->execute();
            $produits_vedettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="grille-produits">
                <?php foreach ($produits_vedettes as $produit): ?>
                <div class="carte-produit carte-vedette">
                    <div class="badge-vedette">Vedette</div>
                    <img src="<?= htmlspecialchars($produit['lien_image']) ?>" 
                         alt="<?= htmlspecialchars($produit['nom']) ?>" 
                         class="image-produit">
                    <div class="info-produit">
                        <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                        <p><?= htmlspecialchars($produit['description']) ?></p>
                        <div class="prix-produit">
                            <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                        </div>
                        <a href="produit.php?id=<?= $produit['idArticle'] ?>" class="color-button">Voir le produit</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Section de tous les produits avec filtres -->
        <section class="tous-produits">
            <h2>Tous nos produits</h2>
            
            <?php
            // Récupération des catégories disponibles
            $stmt = $pdo->query("select distinct categorie from Article order by categorie");
            $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
            ?>
            
            <!-- Filtres par catégorie -->
            <div class="filtres-categories">
                <a href="?" class="bouton-categorie <?= empty($_GET['categorie']) ? 'actif' : '' ?>">
                    Tous les produits
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="?categorie=<?= urlencode($cat) ?>" 
                       class="bouton-categorie <?= (isset($_GET['categorie']) && $_GET['categorie'] === $cat) ? 'actif' : '' ?>">
                        <?= ucfirst(htmlspecialchars($cat)) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Formulaire de filtre par prix -->
            <!-- Le filtre par prix n'était pas originalement prévu, mais je l'ai ajouté pour rendre la page plus dynamique et permettre aux utilisateurs de trouver des produits dans leur budget. -->
            <form method="GET" class="filtre-prix">
                <input type="hidden" name="categorie" value="<?= isset($_GET['categorie']) ? htmlspecialchars($_GET['categorie']) : '' ?>">
                
                <div class="groupe-prix">
                    <label for="min_price">Prix minimum:</label>
                    <input type="number" name="min_price" id="min_price" min="0" value="<?= isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '' ?>" placeholder="Minimum en €">
                </div>
                
                <div class="groupe-prix">
                    <label for="max_price">Prix maximum:</label>
                    <input type="number" name="max_price" id="max_price" min="0" value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>" placeholder="Maximum en €">
                </div>
                
                <button type="submit" class="bouton-appliquer">Filtrer</button>
            </form>

            <!-- Affichage des produits filtrés (utilise $articles déjà récupéré ci-dessus) -->
            <?php if (count($articles) > 0): ?>
                <div class="grille-produits">
                    <?php foreach ($articles as $produit): ?>
                    <div class="carte-produit">
                        <img src="<?= htmlspecialchars($produit['lien_image']) ?>" 
                             alt="<?= htmlspecialchars($produit['nom']) ?>" 
                             class="image-produit">
                        <div class="info-produit">
                            <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                            <p><?= htmlspecialchars($produit['description']) ?></p>
                            <div class="prix-produit">
                                <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                            </div>
                            <a href="produit.php?id=<?= $produit['idArticle'] ?>" class="color-button">Voir le produit</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Message si aucun produit ne correspond aux critères -->
                <p class="text-center">Aucun produit ne correspond à vos critères de recherche.</p>
            <?php endif; ?>
            <!-- Navigation de la pagination -->
            <?php if ($nombre_pages > 1): ?>
            <div class="pagination">
                <div class="controles-pagination">
                    <?php if ($page_courante > 1): ?>
                        <a href="?page=1<?= !empty($_GET['categorie']) ? '&categorie='.urlencode($_GET['categorie']) : '' ?><?= isset($_GET['min_price']) ? '&min_price='.urlencode($_GET['min_price']) : '' ?><?= isset($_GET['max_price']) ? '&max_price='.urlencode($_GET['max_price']) : '' ?>" class="bouton-pagination">&laquo; Première</a>
                        <a href="?page=<?= $page_courante - 1 ?><?= !empty($_GET['categorie']) ? '&categorie='.urlencode($_GET['categorie']) : '' ?><?= isset($_GET['min_price']) ? '&min_price='.urlencode($_GET['min_price']) : '' ?><?= isset($_GET['max_price']) ? '&max_price='.urlencode($_GET['max_price']) : '' ?>" class="bouton-pagination">&lsaquo; Précédente</a>
                    <?php endif; ?>
                    
                    <div class="pages-numeros">
                        <?php
                        // Affichage des numéros de page avec un intervalle autour de la page courante
                        $intervalle = 2; // Nombre de pages à afficher avant et après la page courante
                        
                        for ($i = max(1, $page_courante - $intervalle); $i <= min($nombre_pages, $page_courante + $intervalle); $i++) {
                            $classe_page = ($i == $page_courante) ? 'page-active' : '';
                            echo '<a href="?page='.$i.(!empty($_GET['categorie']) ? '&categorie='.urlencode($_GET['categorie']) : '').(isset($_GET['min_price']) ? '&min_price='.urlencode($_GET['min_price']) : '').(isset($_GET['max_price']) ? '&max_price='.urlencode($_GET['max_price']) : '').'" class="numero-page '.$classe_page.'">'.$i.'</a>';
                        }
                        ?>
                    </div>
                    
                    <?php if ($page_courante < $nombre_pages): ?>
                        <a href="?page=<?= $page_courante + 1 ?><?= !empty($_GET['categorie']) ? '&categorie='.urlencode($_GET['categorie']) : '' ?><?= isset($_GET['min_price']) ? '&min_price='.urlencode($_GET['min_price']) : '' ?><?= isset($_GET['max_price']) ? '&max_price='.urlencode($_GET['max_price']) : '' ?>" class="bouton-pagination">Suivante &rsaquo;</a>
                        <a href="?page=<?= $nombre_pages ?><?= !empty($_GET['categorie']) ? '&categorie='.urlencode($_GET['categorie']) : '' ?><?= isset($_GET['min_price']) ? '&min_price='.urlencode($_GET['min_price']) : '' ?><?= isset($_GET['max_price']) ? '&max_price='.urlencode($_GET['max_price']) : '' ?>" class="bouton-pagination">Dernière &raquo;</a>
                    <?php endif; ?>
                </div>
                
                <div class="info-pagination">
                    Page <?= $page_courante ?> sur <?= $nombre_pages ?> (<?= $total_articles ?> produits)
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>
    
    <?php include("../common/footer.php"); ?>
</body>
</html>