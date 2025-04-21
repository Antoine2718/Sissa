<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique Sissa</title>
    <meta name="description" content="Découvrez notre boutique en ligne avec une sélection de produits exclusifs. Trouvez le cadeau parfait pour vous ou vos amis passionnés de stratégie.">
    <meta name="keywords" content="boutique, produits, Sissa, jeux de société, t-shirts, mugs, cadeaux, passionnés de stratégie, morpion, jeux de plateau">
    <?php
        require_once("../common/utilisateur.php");
        session_start(); // Démarre la session pour accéder aux variables de session
        include("../common/styles.php"); 
    ?>
    <!-- Ajoute Montserrat depuis Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="shop.css">
</head>
<body>
    <?php 
    // Inclusion de la navigation et connexion à la base de données
    require_once("../common/db.php");
    include("../common/nav.php");
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
        <p>Découvrez nos produits exclusifs pour les fans de Sissa. Des t-shirts, aux mugs, en passant par des jeux de plateau, trouvez le cadeau parfait pour vous ou vos amis passionnés de stratégie.</p>
    </div>

    <div class="content">
        <!-- Section des produits vedettes -->
        <section class="produits-vedettes">
            <h2>Nos Produits Vedettes</h2>
            <p class="sous-titre-vedette">Découvrez les meilleurs articles de notre collection</p>
            
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