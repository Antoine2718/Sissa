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
    $page_courante = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Page courante, par défaut 1, on s'assure qu'elle est au moins 1, on pourrait limiter le nombre de pages
    $debut = ($page_courante - 1) * $articles_par_page; // Calcul du début de la page, pour la pagination

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
    }//Ici la where clause est construite pour ne pas afficher les articles en rupture de stock, et pour filtrer par catégorie, prix min et max si spécifié
    // On pourrait aussi ajouter un filtre par date d'ajout, mais je ne l'ai pas fait pour garder la page plus simple
    //Pour se faire le plus simple serait de modifier la base de données pour ajouter une colonne date d'ajout en boutique à la table article, puis construire la requête avec une clause WHERE sur cette colonne, mais je ne l'ai pas fait pour garder la base de données simple et éviter de la modifier inutilement
    //Voici le potentiel code pour ajouter une colonne date d'ajout à la table article : alter table Article add column date_ajout datetime default now() pour ajouter la colonne, puis update Article set date_ajout = now() pour mettre à jour la date d'ajout de tous les articles déjà présents dans la base de données
    //Ensuite la requête pour recupérer les articles serait : select * from Article where date_ajout >= date_sub(now(), interval 30 day) and stock > 0 order by date_ajout desc limit ?, ? pour récupérer les articles ajoutés dans les 30 derniers jours, cette requête s'explique comme suit : on récupère tous les articles dont la date d'ajout est supérieure à la date actuelle moins 30 jours, et on les trie par date d'ajout décroissante, puis on limite le nombre de résultats à afficher avec la clause limit ?, ? qui prend en paramètre le début et le nombre d'articles à afficher
    // La requête pour un éventuel tri par nom d'article serait : select * from Article where stock > 0 and nom like ? order by nom asc limit ?, ? pour récupérer les articles dont le nom contient une chaîne de caractères donnée, triés par ordre alphabétique croissant, et limités à un certain nombre de résultats


    // Requête pour compter le nombre total d'articles (pour la pagination)
    $sql_count = "select count(*) from Article where " . $where_clause; // On compte le nombre d'articles qui correspondent aux critères de la requête, on pourrait aussi faire un count distinct pour ne pas compter les doublons, mais ici on ne s'en sert pas
    $stmt_count = $pdo->prepare($sql_count); // Préparation de la requête pour compter le nombre d'articles
    $stmt_count->execute($params); 
    $total_articles = $stmt_count->fetchColumn(); // Récupération du nombre total d'articles
    $nombre_pages = ceil($total_articles / $articles_par_page); // Calcul du nombre total de pages, on utilise la fonction ceil pour arrondir à l'entier supérieur, on pourrait aussi utiliser floor pour arrondir à l'entier inférieur, mais ici on veut afficher le nombre total de pages, donc on utilise ceil

    // Ajouter les paramètres de pagination à $params
    $params[] = $debut;
    $params[] = $articles_par_page;

    // Construction de la requête SQL
    // Récupération des best-sellers des 30 derniers jours (les 3 produits les plus vendus)
    $sql_bestsellers = "SELECT a.idArticle, SUM(ac.quantité_achat) as total_ventes
    FROM Article a
    JOIN achete ac ON a.idArticle = ac.idArticle
    WHERE ac.date_achat >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY a.idArticle
    ORDER BY total_ventes DESC
    LIMIT 3";
    $stmt_bestsellers = $pdo->query($sql_bestsellers);
    $bestSellers = $stmt_bestsellers->fetchAll(PDO::FETCH_COLUMN, 0); // Récupère seulement les IDs

    // Construction de la requête SQL avec information sur les promotions actives
    $sql = "SELECT a.*, 
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
    FROM Article a WHERE " . $where_clause . " LIMIT ?, ?";

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

    // === Fonction pour récupérer les best-sellers ===
    // Cette fonction récupère les articles les plus vendus sur une période donnée (30 jours par défaut)
    function getBestSellers($pdo, $days = 30, $limit = 5) {
        $sql = "select a.idArticle, a.nom, SUM(ac.quantité_achat) as total_ventes
                from Article a
                join achete ac on a.idArticle = ac.idArticle
                where ac.date_achat >= DATE_SUB(NOW(), INTERVAL ? DAY)
                group by a.idArticle, a.nom
                order by total_ventes DESC
                limit ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $days, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'idArticle');
    }

    // === Fonction pour récupérer les promotions actives ===
    // Cette fonction récupère les promotions actives pour un article donné
    function getActivePromotions($pdo, $idArticle) {
        $now = date('Y-m-d H:i:s');
        $sql = "select p.* from Promotion p 
                join a_la_promotion ap on p.idPromotion = ap.idPromotion 
                where ap.idArticle = ? 
                and p.debut_promotion <= ? 
                and p.fin_promotion >= ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idArticle, $now, $now]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // === Fonction pour récupérer le meilleur prix promotionnel ===
    // Cette fonction calcule le meilleur prix après application des promotions
    function getBestPromotionPrice($pdo, $article) {
        $promotions = getActivePromotions($pdo, $article['idArticle']);
        
        if (empty($promotions)) {
            return $article['prix'];
        }
        
        // Trouver la meilleure réduction
        $best_reduction = 0;
        foreach ($promotions as $promo) {
            if ($promo['proportion_promotion'] > $best_reduction) {
                $best_reduction = $promo['proportion_promotion'];
            }
        }
        
        return $article['prix'] * (1 - $best_reduction);
    }

    $bestSellers = getBestSellers($pdo, 30, 3);
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
            FROM article a WHERE stock > 0 order by stock asc limit 3");
            // Récupération des 3 derniers produits en stock, choix arbitraire des 3 articles avec le moins de stock
            // pour mettre en avant les produits qui se vendent le mieux
            // On pourrait aussi faire un tri par date d'ajout ou par popularité, mais ici on reste sur le stock
            $stmt->execute();
            $produits_vedettes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="wrapper-vedettes">
            <?php foreach ($produits_vedettes as $produit): ?>
            <div class="carte-produit carte-vedette">
                <div class="badge-vedette">Vedette</div>
                
                <?php if (!empty($produit['promotion_active'])): ?>
                    <div class="badge-promotion">-<?= round($produit['promotion_active'] * 100) ?>%</div>
                <?php endif; ?>
                
                <?php if (in_array($produit['idArticle'], $bestSellers)): ?> <!-- Vérifie si le produit est un best-seller -->
                    <div class="badge-bestseller">Best-seller</div>
                <?php endif; ?>
                
                <img src="<?= htmlspecialchars($produit['lien_image']) ?>" 
                    alt="<?= htmlspecialchars($produit['nom']) ?>" 
                    class="image-produit">
                <div class="info-produit">
                    <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                    <p><?= htmlspecialchars($produit['description']) ?></p>
                    <div class="prix-produit">
                        <?php if (!empty($produit['promotion_active'])): ?>
                            <span class="prix-original"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</span>
                            <span class="prix-promotion">
                                <?= number_format($produit['prix'] * (1 - $produit['promotion_active']), 2, ',', ' ') ?> €
                            </span>
                        <?php else: ?>
                            <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                        <?php endif; ?>
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
                <a href="?" class="bouton-categorie <?= empty($_GET['categorie']) ? 'actif' : '' ?>"> <!-- Lien vers la page principale sans filtre de catégorie -->
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
                        <?php if (!empty($produit['promotion_active'])): ?>
                            <div class="badge-promotion">-<?= round($produit['promotion_active'] * 100) ?>%</div>
                        <?php endif; ?>

                        <?php if (in_array($produit['idArticle'], $bestSellers)): ?>
                            <div class="badge-bestseller">Best-seller</div>
                        <?php endif; ?>
                            <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                            <p><?= htmlspecialchars($produit['description']) ?></p>
                            <div class="prix-produit">
                                <?php if (!empty($produit['promotion_active'])): ?>
                                    <span class="prix-original"><?= number_format($produit['prix'], 2, ',', ' ') ?> €</span>
                                    <span class="prix-promotion">
                                        <?= number_format($produit['prix'] * (1 - $produit['promotion_active']), 2, ',', ' ') ?> €
                                    </span>
                                <?php else: ?>
                                    <?= number_format($produit['prix'], 2, ',', ' ') ?> €
                                <?php endif; ?>
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
                        <a href="?page=<?= $page_courante - 1 ?><?= !empty($_GET['categorie']) ? '&categorie='.urlencode($_GET['categorie']) : '' ?><?= isset($_GET['min_price']) ? '&min_price='.urlencode($_GET['min_price']) : '' ?><?= isset($_GET['max_price']) ? '&max_price='.urlencode($_GET['max_price']) : '' ?>" class="bouton-pagination">&lsaquo; Précédente</a><!-- Lien vers la page précédente -->
                    <?php endif; ?>
                    
                    <div class="pages-numeros">
                        <?php
                        // Affichage des numéros de page avec un intervalle autour de la page courante
                        $intervalle = 2; // Nombre de pages à afficher avant et après la page courante
                        for ($i = max(1, $page_courante - $intervalle); $i <= min($nombre_pages, $page_courante + $intervalle); $i++) { //On affiche les pages de la page courante moins l'intervalle à la page courante plus l'intervalle, on s'assure que la page courante est bien dans l'intervalle
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