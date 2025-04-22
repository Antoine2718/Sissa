<?php
require_once("../common/utilisateur.php");
require_once("../common/db.php");
function generatePagination($phppage,$page_courante,$nombre_pages,$action,$total_elements,$name){
    if ($nombre_pages > 1){
        echo "<div class=\"pagination\">";
        echo "<div class=\"controles-pagination\">";
        if ($page_courante > 1){
            echo "<a href=\"$phppage?page=1&action=$action\" class=\"bouton-pagination\">&laquo; Première</a>";
            $previous = $page_courante-1;
            echo "<a href=\"$phppage?page=$previous&action=$action\" class=\"bouton-pagination\">&lsaquo; Précédente</a>";
        }
        echo "<div class=\"pages-numeros\">";
        // Affichage des numéros de page avec un intervalle autour de la page courante
        $intervalle = 2; // Nombre de pages à afficher avant et après la page courante
        for ($i = max(1, $page_courante - $intervalle); $i <= min($nombre_pages, $page_courante + $intervalle); $i++) {
            $classe_page = ($i == $page_courante) ? 'page-active' : '';
            echo "<a href=\"$phppage?page=$i&action=$action\" class=\"numero-page\" $classe_page\">$i</a>";
        }
        echo "</div>";
        if ($page_courante < $nombre_pages){
            $next = $page_courante +1;
            echo "<a href=\"$phppage?page=$next&action=$action\" class=\"bouton-pagination\">Suivante &rsaquo;</a>";
            echo "<a href=\"$phppage?page=$nombre_pages&action=$action\" class=\"bouton-pagination\">Dernière &raquo;</a>";
        }
        echo "<div class=\"info-pagination\">";
        echo "Page $page_courante sur $nombre_pages ( $total_elements $name)";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
}
function getUpdateForm(){
    if(empty($_GET) || !isAdmin() || !isset($_GET['id'])){
        header("Location: ../pages/error_pages.php");
        exit();
    }
    $db = connect();
    $id = $_GET['id'];
    $user = getUserWithId($db,$id);
    $username = $user->getIdentifiant();
    $points = $user->getPoints();
?>
<div class="login-container">
    <h1>Modification</h1>
    <form action="../admin/updateUser.php" method="POST">
    <div class="form-group">
        <label for="username">Nom d'Utilisateur:</label>
        <input type="text" id="username" name="username" value= <?php echo"$username" ?> required>
    </div>
    <div class="form-group">
        <label for="points">Points:</label>
        <input type="text" id="points" name="points" pattern ="[0-9]+" value = <?php echo"$points" ?> required>
    </div>
    <div class="form-group">
        <label for="password">Nouveau Mot de passe:</label>
        <input type="password" id="password" name="password">
        <p class ="info">Laisser vide pour ne pas changer le mot de passe</p>
    </div>
    <label for="type">Role:</label>
        <select name="type" id="type">
            <option value="user" <?php if($user->getType()=="user")echo "selected"?>>Utilisateur</option>
            <option value="admin" <?php if($user->getType()=="admin")echo "selected"?>>Administrateur</option>
        </select>
    
    <div class ="form-group">
        <button class ="color-button" type="submit">Modifier</button>
    </div>
    <input hidden type="text" id="id" name ="id" value= <?php echo $id; ?>>
    </form>
</div>
<?php 
signout($db);
}
?>