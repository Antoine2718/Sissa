<?php
session_start();
require_once '../common/db.php';
$pdo = connect();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    $_SESSION['erreur'] = "Vous devez être connecté pour voir votre historique de commandes.";
    header("Location: login.php");
    exit;
}

$message = $_SESSION['succes'] ?? '';
$erreur = $_SESSION['erreur'] ?? '';
unset($_SESSION['succes'], $_SESSION['erreur']);

// Récupérer l'historique des commandes de l'utilisateur
$idUtilisateur = $_SESSION['idUtilisateur'];
$stmt = $pdo->prepare("
    select a.idArticle, a.nom, ac.quantité_achat, ac.date_achat, a.prix 
    from achete ac
    join article a on ac.idArticle = a.idArticle
    where ac.idUtilisateur = ?
    order by ac.date_achat desc
");
$stmt->execute([$idUtilisateur]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regrouper les commandes par date
$commandesParDate = [];
foreach ($commandes as $commande) {
    $date = date('Y-m-d', strtotime($commande['date_achat']));// Format de la date pour le regroupement
    // On ne garde que la date sans l'heure pour le regroupement
    if (!isset($commandesParDate[$date])) {// Si la date n'existe pas encore dans le tableau
        // On l'initialise avec un tableau vide
        $commandesParDate[$date] = [];
    }
    $commandesParDate[$date][] = $commande;// On ajoute la commande à la date correspondante
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include("../common/styles.php"); ?>
    <link rel="stylesheet" href="historique_commande.css">
</head>
<body>
    <?php include("../common/nav.php"); ?>

    <div class="content">
        <h1>Historique de vos commandes</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message-container success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($erreur)): ?>
            <div class="message-container error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <?php if (empty($commandesParDate)) : ?>
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
                            <?php $totalArticle = $article['prix'] * $article['quantité_achat']; 
                                  $totalCommande += $totalArticle; ?>
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
                                        Quantité: <?= $article['quantité_achat'] ?> × <?= number_format($article['prix'], 2, ',', ' ') ?> €
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
            
            <div class="retour-wrapper">
                <a href="shop.php" class="bouton-retour">Retourner à la boutique</a>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include("../common/footer.php"); ?>
</body>
</html>