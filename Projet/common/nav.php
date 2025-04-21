<?php
$nb_articles = 0;
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $item) {
        if (isset($item['quantite'])) {
            $nb_articles += $item['quantite'];
        }
    }
}//On compte le nombre d'articles dans le panier
$nav_pages = array("index.php"=>"Accueil","jeu.php"=>"Jeu","contact.php"=>"Nous Contacter","a_propos.php"=>"À Propos","shop.php"=>"Boutique");
$current_page = $_SERVER['REQUEST_URI'];
//Trouve le fichier de la page courrante en manipulant le path vers le fichier actuel
$page = explode("/",$current_page);
$current_page = end($page);
//Fix pour les pages qui contiennent des requetes GET
$current_page = explode("?",$current_page);
$current_page = reset($current_page);
//current_page vaut le nom du fichier actuel
session_start();
?>
<div class="navbar">
    <div class = "connect-navbar">
        <?php
            if(isConnected()){
                echo "<a class =\"color-button disconnect-button\" href = \"deconnexion.php\">Deconnexion</a>";
            }else echo "<a class =\"color-button\" href = \"login.php\">Connexion</a>";
        ?>
    </div>
    <div class ="title-navbar">
        <h1 id="titre"> Sissa </h1>
    </div>
    <div class= "inner-navbar">
        <?php
            //Affiche les éléments de la navbar
            foreach ($nav_pages as $key => $value) {
                //si le fichier du href match le fichier courant on ajoute la class page_selected
                if($key == $current_page){
                    echo "<a class= \"page_selected\"href = \"$key\">$value</a>";
                }else{
                    echo "<a href = \"$key\">$value</a>";
                }
                if($key == "shop.php"){
                    //On affiche le nombre d'articles dans le panier
                    echo "<div class=\"panier\">";
                    echo "<a href=\"panier.php\">Panier</a>";
                    echo "<span class=\"badge\">$nb_articles</span>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</div>