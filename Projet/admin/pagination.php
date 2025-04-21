<?php
function generatePagination($page_courante,$action,$total_elements,$name){
    if ($nombre_pages > 1){
        echo "<div class=\"pagination\">";
        echo "<div class=\"controles-pagination\">";
        if ($page_courante > 1){
            echo "<a href=\"?page=1&action=$action\" class=\"bouton-pagination\">&laquo; Première</a>";
            $previous = $page_courante-1;
            echo "<a href=\"?page=$previous&$action\" class=\"bouton-pagination\">&lsaquo; Précédente</a>";
        }
        echo "<div class=\"pages-numeros\">";
        // Affichage des numéros de page avec un intervalle autour de la page courante
        $intervalle = 2; // Nombre de pages à afficher avant et après la page courante
        for ($i = max(1, $page_courante - $intervalle); $i <= min($nombre_pages, $page_courante + $intervalle); $i++) {
            $classe_page = ($i == $page_courante) ? 'page-active' : '';
            echo "<a href=\"?page=$i\" class=\"numero-page\" $classe_page\">$i</a>";
        }
        echo "</div>";
        if ($page_courante < $nombre_pages){
            $next = $page_courante +1;
            echo "<a href=\"?page=$next&action=$action\" class=\"bouton-pagination\">Suivante &rsaquo;</a>";
            echo "<a href=\"?page=$nombre_pages class=\"bouton-pagination\">Dernière &raquo;</a>";
        }
        echo "<div class=\"info-pagination\">";
        echo "Page $page_courante sur $nombre_pages ( $total_elements $name)";
        echo "</div>";
        echo "</div>";
    }
}
?>