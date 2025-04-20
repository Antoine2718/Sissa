<?php
$nav_pages = array("index.php"=>"Accueil","jeu.php"=>"Jeu","contact.php"=>"Nous Contacter","a_propos.php"=>"À Propos","shop.php"=>"Boutique");
$current_page = $_SERVER['REQUEST_URI'];
//Trouve le fichier de la page courrante en manipulant le path vers le fichier actuel
$page = explode("/",$current_page);
$current_page = end($page);
//Fix pour les pages qui contiennent des requetes GET
$current_page = explode("?",$current_page);
$current_page = reset($current_page);
//current_page vaut le nom du fichier actuel
?>
<div class="navbar">
    <div class = "connect-navbar">
        <?php
            echo "<a class =\"color-button\" href = \"login.php\">Connexion</a>";
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
            }
        ?>
    </div>
</div>