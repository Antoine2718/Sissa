<?php

require_once("../common/db.php");
//On s'assure que la personne est bien connectée en tant qu'admin
if (!isset($_SESSION)) {
    session_start();
}
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}


$db = connect();

// Vérifie si un ID de promotion est spécifié
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?action=PRM');
    exit();
}

$id_promo = $_GET['id'];

// Récupére les informations de la promotion
$stmt = $db->prepare("SELECT * FROM Promotion WHERE idPromotion = ?");
$stmt->execute([$id_promo]);
$promotion = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si la promotion existe
if (!$promotion) {
    $_SESSION['message'] = '<div class="message-container error">La promotion demandée n\'existe pas.</div>';
    header('Location: admin.php?action=PRM');
    exit();
}

// Traitement du formulaire de modification
if (isset($_POST['update_promotion'])) {
    $nom = htmlspecialchars($_POST['nom_promotion']);
    $proportion = $_POST['proportion_promotion'];
    $debut = $_POST['debut_promotion'];
    $fin = $_POST['fin_promotion'];
    
    // Validation des données
    if (empty($nom) || empty($proportion) || empty($debut) || empty($fin)) {
        $_SESSION['message'] = '<div class="message-container error">Tous les champs sont obligatoires.</div>';
    } elseif (!is_numeric($proportion) || $proportion <= 0 || $proportion > 1) {
        $_SESSION['message'] = '<div class="message-container error">La proportion doit être un nombre décimal entre 0 et 1 (ex: 0.20 pour 20%).</div>';
    } elseif (strtotime($debut) >= strtotime($fin)) {
        $_SESSION['message'] = '<div class="message-container error">La date de fin doit être postérieure à la date de début.</div>';
    } else {
        // Mise à jour de la promotion
        $stmt = $db->prepare("UPDATE Promotion SET nom_promotion = ?, proportion_promotion = ?, debut_promotion = ?, fin_promotion = ? WHERE idPromotion = ?");
        $stmt->execute([$nom, $proportion, $debut, $fin, $id_promo]);
        $_SESSION['message'] = '<div class="success">La promotion a été mise à jour avec succès.</div>';
        
        // Redirection vers la liste des promotions
        header('Location: admin.php?action=PRM');
        exit();
    }
}

// Formatage des dates pour le formulaire HTML
$debut_formatted = date('Y-m-d\TH:i', strtotime($promotion['debut_promotion']));
$fin_formatted = date('Y-m-d\TH:i', strtotime($promotion['fin_promotion']));
?>

<h2>Modifier une promotion</h2>

<!-- Formulaire de modification de promotion -->
<div class="commande-card">
    <div class="commande-header">
        <span class="commande-date">Modifier la promotion #<?= $promotion['idPromotion'] ?></span>
    </div>
    <div class="commande-items">
        <form method="post" action="">
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Nom de la promotion</div>
                    <input type="text" name="nom_promotion" value="<?= htmlspecialchars($promotion['nom_promotion']) ?>" required class="form-control">
                </div>
            </div>
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Proportion (0-1)</div>
                    <input type="number" name="proportion_promotion" step="0.01" min="0.01" max="1" value="<?= $promotion['proportion_promotion'] ?>" required class="form-control" placeholder="Ex: 0.20 pour 20%">
                </div>
            </div>
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Date de début</div>
                    <input type="datetime-local" name="debut_promotion" value="<?= $debut_formatted ?>" required class="form-control">
                </div>
            </div>
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Date de fin</div>
                    <input type="datetime-local" name="fin_promotion" value="<?= $fin_formatted ?>" required class="form-control">
                </div>
            </div>
            <div class="commande-footer">
                <button type="submit" name="update_promotion" class="color-button">Mettre à jour la promotion</button>
                <a href="admin.php?action=PRM" class="bouton-retour">Annuler</a>
            </div>
        </form>
    </div>
</div>

<!-- Liste des articles associés à cette promotion -->
<h3>Articles avec cette promotion</h3>
<?php
// Récupére les articles associés à cette promotion
$stmt = $db->prepare("
    select a.idArticle, a.nom, a.prix
    from a_la_promotion ap
    join Article a ON ap.idArticle = a.idArticle
    where ap.idPromotion = ?
    order by a.nom
");
$stmt->execute([$id_promo]);
$articles_with_promotion = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="list-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom de l'article</th>
            <th>Prix normal</th>
            <th>Prix après promotion</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles_with_promotion as $article): ?>
        <tr>
            <td><?= $article['idArticle'] ?></td>
            <td><?= htmlspecialchars($article['nom']) ?></td>
            <td><?= number_format($article['prix'], 2, ',', ' ') ?> €</td>
            <td><?= number_format($article['prix'] * (1 - $promotion['proportion_promotion']), 2, ',', ' ') ?> €</td>
            <td>
                <a href="admin.php?action=PRM&remove_assign=1&promo_id=<?= $id_promo ?>&article_id=<?= $article['idArticle'] ?>" class="color-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette association ?');">Retirer la promotion</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($articles_with_promotion)): ?>
        <tr>
            <td colspan="5" class="text-center">Aucun article associé à cette promotion</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>