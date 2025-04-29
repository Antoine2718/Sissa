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

// Traitement du formulaire d'ajout de promotion
if (isset($_POST['add_promotion'])) {
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
        // Ajout de la promotion
        $stmt = $db->prepare("insert into Promotion (nom_promotion, proportion_promotion, debut_promotion, fin_promotion) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $proportion, $debut, $fin]);
        $_SESSION['message'] = '<div class="success">La promotion a été ajoutée avec succès.</div>';
    }
    
    // Redirection pour éviter la soumission multiple du formulaire
    header('Location: admin.php?action=PRM');
    exit();
}

// Traitement de la suppression d'une promotion
if (isset($_GET['delete_promo']) && is_numeric($_GET['delete_promo'])) {
    $id_promo = $_GET['delete_promo'];
    
    // Vérifie si des articles sont associés à cette promotion
    $stmt = $db->prepare("select COUNT(*) from a_la_promotion where idPromotion = ?");
    $stmt->execute([$id_promo]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        // Supprime d'abord les associations avec les articles
        $stmt = $db->prepare("delete from a_la_promotion where idPromotion = ?");
        $stmt->execute([$id_promo]);
    }
    
    // Puis supprime la promotion
    $stmt = $db->prepare("delete from Promotion where idPromotion = ?");
    $stmt->execute([$id_promo]);
    
    $_SESSION['message'] = '<div class="success">La promotion a été supprimée avec succès.</div>';
    header('Location: admin.php?action=PRM');
    exit();
}

// Traitement de l'attribution d'une promotion à un article
if (isset($_POST['assign_promotion'])) {
    $id_promo = $_POST['promotion_id'];
    $id_article = $_POST['article_id'];
    
    // Vérifie si l'association existe déjà
    $stmt = $db->prepare("select COUNT(*) from a_la_promotion where idPromotion = ? and idArticle = ?");
    $stmt->execute([$id_promo, $id_article]);
    
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['message'] = '<div class="message-container error">Cet article a déjà cette promotion.</div>';
    } else {
        // Ajoute l'association
        $stmt = $db->prepare("insert into a_la_promotion (idPromotion, idArticle) VALUES (?, ?)");
        $stmt->execute([$id_promo, $id_article]);
        $_SESSION['message'] = '<div class="success">La promotion a été attribuée à l\'article avec succès.</div>';
    }
    
    header('Location: admin.php?action=PRM');
    exit();
}

// Traitement de la suppression d'une association promotion-article
if (isset($_GET['remove_assign']) && isset($_GET['promo_id']) && isset($_GET['article_id'])) {
    $id_promo = $_GET['promo_id'];
    $id_article = $_GET['article_id'];
    
    $stmt = $db->prepare("delete from a_la_promotion where idPromotion = ? and idArticle = ?");
    $stmt->execute([$id_promo, $id_article]);
    
    $_SESSION['message'] = '<div class="success">L\'association a été supprimée avec succès.</div>';
    header('Location: admin.php?action=PRM');
    exit();
}

// Récupére toutes les promotions
$stmt = $db->query("select * from Promotion order by debut_promotion DESC");
$promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupére tous les articles
$stmt = $db->query("select idArticle, nom from Article order by nom");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupére toutes les associations promotion-article
$stmt = $db->query("
    select a.idArticle, a.nom AS article_nom, p.idPromotion, p.nom_promotion, p.proportion_promotion
    from a_la_promotion ap
    join Article a ON ap.idArticle = a.idArticle
    join Promotion p ON ap.idPromotion = p.idPromotion
    order by p.nom_promotion, a.nom
");
$associations = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Gestion des Promotions</h2>

<!-- Affichage du message s'il existe -->
<?php
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!-- Formulaire d'ajout de promotion -->
<div class="commande-card">
    <div class="commande-header">
        <span class="commande-date">Ajouter une nouvelle promotion</span>
    </div>
    <div class="commande-items">
        <form method="post" action="">
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Nom de la promotion</div>
                    <input type="text" name="nom_promotion" required class="form-control">
                </div>
            </div>
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Proportion (0-1)</div>
                    <input type="number" name="proportion_promotion" step="0.01" min="0.01" max="1" required class="form-control" placeholder="Ex: 0.20 pour 20%">
                </div>
            </div>
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Date de début</div>
                    <input type="datetime-local" name="debut_promotion" required class="form-control">
                </div>
            </div>
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Date de fin</div>
                    <input type="datetime-local" name="fin_promotion" required class="form-control">
                </div>
            </div>
            <div class="commande-footer">
                <button type="submit" name="add_promotion" class="color-button">Ajouter la promotion</button>
            </div>
        </form>
    </div>
</div>

<!-- Tableau des promotions existantes -->
<h3>Liste des promotions</h3>
<table class="list-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Réduction</th>
            <th>Date de début</th>
            <th>Date de fin</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($promotions as $promotion): ?>
        <tr>
            <td><?= $promotion['idPromotion'] ?></td>
            <td><?= htmlspecialchars($promotion['nom_promotion']) ?></td>
            <td><?= ($promotion['proportion_promotion'] * 100) . '%' ?></td>
            <td><?= date('d/m/Y H:i', strtotime($promotion['debut_promotion'])) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($promotion['fin_promotion'])) ?></td>
            <td>
                <a href="admin.php?action=UPP&id=<?= $promotion['idPromotion'] ?>" class="color-button">Modifier</a>
                <a href="admin.php?action=PRM&delete_promo=<?= $promotion['idPromotion'] ?>" class="color-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($promotions)): ?>
        <tr>
            <td colspan="6" class="text-center">Aucune promotion disponible</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Formulaire d'attribution de promotion à un article -->
<h3>Attribuer une promotion à un article</h3>
<div class="commande-card">
    <div class="commande-header">
        <span class="commande-date">Nouvelle association</span>
    </div>
    <div class="commande-items">
        <form method="post" action="">
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Promotion</div>
                    <select name="promotion_id" required class="form-control">
                        <option value="">Sélectionner une promotion</option>
                        <?php foreach ($promotions as $promotion): ?>
                        <option value="<?= $promotion['idPromotion'] ?>"><?= htmlspecialchars($promotion['nom_promotion']) ?> (<?= ($promotion['proportion_promotion'] * 100) ?>%)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="commande-item">
                <div class="produit-details">
                    <div class="produit-nom">Article</div>
                    <select name="article_id" required class="form-control">
                        <option value="">Sélectionner un article</option>
                        <?php foreach ($articles as $article): ?>
                        <option value="<?= $article['idArticle'] ?>"><?= htmlspecialchars($article['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="commande-footer">
                <button type="submit" name="assign_promotion" class="color-button">Attribuer la promotion</button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des associations promotion-article -->
<h3>Articles en promotion</h3>
<table class="list-table">
    <thead>
        <tr>
            <th>Promotion</th>
            <th>Réduction</th>
            <th>Article</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($associations as $association): ?>
        <tr>
            <td><?= htmlspecialchars($association['nom_promotion']) ?></td>
            <td><?= ($association['proportion_promotion'] * 100) . '%' ?></td>
            <td><?= htmlspecialchars($association['article_nom']) ?></td>
            <td>
                <a href="admin.php?action=PRM&remove_assign=1&promo_id=<?= $association['idPromotion'] ?>&article_id=<?= $association['idArticle'] ?>" class="color-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette association ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($associations)): ?>
        <tr>
            <td colspan="4" class="text-center">Aucun article en promotion</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>