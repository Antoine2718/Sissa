<?php
require_once("utilisateur.php");
function connect(){
    /*
        Lit le fichier de configuration db.json et en extrait les informations de connexion à la base de donnée
    */
    $configPath = '../config/db.json';
    if (!file_exists($configPath)) {
        die("Configuration file not found.");
    }

    $config = json_decode(file_get_contents($configPath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error decoding JSON configuration file.");
    }
    if(!(
        isset($config['host'])&&
        isset($config['port'])&&
        isset($config['username'])&&
        isset($config['password'])&&
        isset($config['database']))
        ){  /*Vérification du formattage du fichier db.json après une lecture réussie*/
        die("Le fichier de configuration est mal formaté");
    }
    $host = $config['host'];
    $port = $config['port'];
    $usr = $config['username'];
    $pwd = $config['password'];
    $dbname = $config['database'];
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    $options = array(PDO::ATTR_PERSISTENT =>
    FALSE);  
    try{
        $connexion = new PDO($dsn,$usr,$pwd,$options);
    }catch(PDOException $e){
        print_r($e);
        die("Erreur dans l'accès à la base de donnée.");
    }
    return $connexion;
}
function connectUser($db,$username){
    $stmt = $db->prepare("SELECT idUtilisateur as id,identifiant as idf, points as pts, idrang as idr, type FROM utilisateur WHERE identifiant = ?");
    $stmt->bindParam(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $result= $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($result)){
        return false;
    }else{
        $user = new Utilisateur($result['id'],$result['idf'],$result['pts'],$result['idr'],$result['type']);
        return $user;
    }
}
function getRang($db,$idUtilisateur){
    try{
        $stmt = $db->prepare("SELECT r.nomRang as name, r.couleur_rang as color FROM utilisateur u inner join rang r on r.idRang = u.idRang where idUtilisateur = ?");
        $stmt->bindParam(1, $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }catch(PDOException $e){
        return "Error";
    }
}
function getUserWithId($db,$id){
    $stmt = $db->prepare("SELECT idUtilisateur as id,identifiant as idf, points as pts, idrang as idr, type FROM utilisateur WHERE idUtilisateur = ?");
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
    $stmt->execute();
    $result= $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($result)){
        header("Location: ../pages/error_page.php");
        exit();
    }else{
        $user = new Utilisateur($result['id'],$result['idf'],$result['pts'],$result['idr'],$result['type']);
        return $user;
    }
}
function isConnected(){
    if(!isset($_SESSION))session_start();
    return isset($_SESSION['user']);
}
function disconnect(){
    if(isConnected()){
        session_unset();
        session_destroy();
    }
}
function signout($db){
    unset($db);
}
function getUser(){
    if(isConnected()){
        return $_SESSION['user'];
    }else{
        return false;
    }
}
function isUsernameUsed($db, $username){
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as cs FROM utilisateur WHERE identifiant = ?");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['cs']>0;
    }catch(PDOException $e){
        return "Error";
    }
}
function isAdmin(){
    return isConnected()&&$_SESSION['user']->getType()=="admin";
}
function updateRank($db,$idUser){
    try{
        //Recupère le nombre de points du joueur
        $stmt = $db->prepare("SELECT points from utilisateur where idUtilisateur=?");
        $stmt->bindParam(1, $idUser, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $points = $result['points'];
        //Requete pour obtenir le bon rang du joueur avec le nombre de points indiqué
        $stmt = $db->prepare("SELECT idRang from rang where points_minimum <=? 
                             ORDER BY points_minimum DESC
                             limit 1");
        $stmt->bindParam(1, $points, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $idRang = $result['idRang'];
        //Fait la mise à jour du rang
        $stmt = $db->prepare("UPDATE utilisateur set idRang=? where idUtilisateur=?");
        $stmt->bindParam(1, $idRang, PDO::PARAM_INT);
        $stmt->bindParam(2, $idUser, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->fetch(PDO::FETCH_ASSOC);
        updateUser($db);
        return true;
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getNumberOfUsers($db){
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as cs FROM utilisateur");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['cs'];
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getNumberOfPurchases($db){
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as cs FROM achete ");
        
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['cs'];
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getNumberOfPurchasesForProduct($db,$idProduit){
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as cs FROM achete where idArticle = ?");
        $stmt->bindParam(1, $idProduit, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['cs'];
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getNumberOfProducts($db){
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as cs FROM article ");
        
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['cs'];
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getPurchaseHistory($pdo, $idUtilisateur) {
    try {
        $stmt = $pdo->prepare("
            SELECT a.idArticle, a.nom, ac.quantité_achat, ac.date_achat, 
                   ac.prix_achat, ac.prix_original, ac.promotion_appliquee, ac.nom_promotion,
                   a.prix, a.lien_image
            FROM achete ac
            JOIN article a ON ac.idArticle = a.idArticle
            WHERE ac.idUtilisateur = ?
            ORDER BY ac.date_achat DESC
        ");
        $stmt->execute([$idUtilisateur]);
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $commandes;
    } catch(PDOException $e) {
        header("Location: ../pages/error_page.php");
        exit();
    }
}
// $win vaut 1 0 -1 en fonction de si c'est une victoire/nulle/défaite
function calculatePoints($win,$difficulty,$move_to_win){
    //on calcule le nombre de points gagné ou perdu
    if($win==1){
        return floor(33 * $difficulty / $move_to_win);
    }else if($win==0){
        return floor(15* ($difficulty-6)/$move_to_win);
    }else{
        return -floor(33 * (11-$difficulty) / $move_to_win);
    }
}
function updateUser($db){
    if(isConnected()){
        $user = $_SESSION['user'];
        $_SESSION['user']=getUserWithId($db,$user->getID());
    }
}
function updatePoints($db,$idUtilisateur,$delta){
    try{
        //on récupère le nombre de points actuel
        $stmt = $db->prepare("SELECT u.points as pts FROM utilisateur u where u.idUtilisateur = ?");
        $stmt->bindParam(1, $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
        $pts = $stmt->fetch(PDO::FETCH_ASSOC);
        $pts = $pts['pts'];
        $new_pts = $pts+$delta;
        if($new_pts<0){
            $new_pts =0;
        }
        //on met à jour le nombre de points
        $stmt = $db->prepare("UPDATE utilisateur u set u.points = ? where u.idUtilisateur = ?");
        $stmt->bindParam(1, $new_pts, PDO::PARAM_INT);
        $stmt->bindParam(2, $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
        //on met à jour le rang
        updateRank($db,$idUtilisateur);
    }catch(PDOException $e){
        echo $e;
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getPurchases($pdo,$page,$page_size){
    try{
        $stmt = $pdo->prepare("
    select u.idUtilisateur as id,u.identifiant as identifiant, a.nom as produit, a.idArticle as idP, ac.quantité_achat as qte, ac.date_achat as date
    from achete ac
    inner join article a on ac.idArticle = a.idArticle
    inner join utilisateur u on ac.idUtilisateur = u.idUtilisateur 
    order by ac.date_achat desc limit ?,?
        ");
        $debut =($page-1) * $page_size;
        $stmt->bindParam(1,$debut, PDO::PARAM_INT);
        $stmt->bindParam(2,$page_size, PDO::PARAM_INT);
        $stmt->execute();
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $commandes;
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getPurchasesForProduct($pdo,$page,$page_size,$idProduit){
    try{
        $stmt = $pdo->prepare("
    select u.idUtilisateur as id,u.identifiant as identifiant, a.nom as produit, a.idArticle as idP, ac.quantité_achat as qte, ac.date_achat as date
    from achete ac
    inner join article a on ac.idArticle = a.idArticle
    inner join utilisateur u on ac.idUtilisateur = u.idUtilisateur
    where a.idArticle = ?
    order by ac.date_achat desc limit ?,?
        ");
        $debut =($page-1) * $page_size;
        $stmt->bindParam(1,$idProduit, PDO::PARAM_INT);
        $stmt->bindParam(2,$debut, PDO::PARAM_INT);
        $stmt->bindParam(3,$page_size, PDO::PARAM_INT);
        $stmt->execute();
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $commandes;
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function groupPurchasesPerDay($commandes){
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
    return $commandesParDate;
}
function getProducts($pdo,$page,$page_size){
    try{
        $stmt = $pdo->prepare('
        SELECT a.idArticle as id,a.nom as name, a.prix as prix, a.stock as stk, a.categorie as ctg from article a
        limit ?,?
        ');
        $debut =($page-1) * $page_size;
        $stmt->bindParam(1,$debut, PDO::PARAM_INT);
        $stmt->bindParam(2,$page_size, PDO::PARAM_INT);
        $stmt->execute();
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $commandes;
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getNumberOfGames($db){
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as cs FROM partie ");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['cs'];
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getPartie($pdo,$page,$page_size){
    try{
        $stmt = $pdo->prepare('
        SELECT p.idPartie as id,p.date_premier_coup as first_coup,p.premier_joueur as first_player, r.niveauRobot as lvl, r.nomRobot robot_name, u.identifiant as player,count(j.idCoup) as nb_coup from partie p
        inner join robot r on p.idRobot = r.idRobot
        inner join utilisateur u on u.idUtilisateur = p.idUtilisateur
        inner join joue_coup j on j.idPartie = p.idPartie
        group by 1
        order by p.idPartie
        limit ?,?
        ');
        $debut =($page-1) * $page_size;
        $stmt->bindParam(1,$debut, PDO::PARAM_INT);
        $stmt->bindParam(2,$page_size, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getPartieforUser($db,$page,$page_size,$idUtilisateur){
    try{
        $stmt = $db->prepare('
        SELECT p.idPartie as id,p.date_premier_coup as first_coup,p.premier_joueur as first_player, r.niveauRobot as lvl, r.nomRobot robot_name, count(j.idCoup) as nb_coup from partie p
        inner join robot r on p.idRobot = r.idRobot
        inner join utilisateur u on u.idUtilisateur = p.idUtilisateur
        inner join joue_coup j on j.idPartie = p.idPartie
        where u.idUtilisateur = ?
        group by 1
        order by p.date_premier_coup DESC
        limit ?,?
        ');
        $debut =($page-1) * $page_size;
        $stmt->bindParam(1,$idUtilisateur, PDO::PARAM_INT);
        $stmt->bindParam(2,$debut, PDO::PARAM_INT);
        $stmt->bindParam(3,$page_size, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getNumberOfGamesOfUser($db,$idUtilisateur){
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as cs FROM partie where idUtilisateur =?");
        $stmt->bindParam(1,$idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['cs'];
    }catch(PDOException $e){
        header("Location: ../pages/error_page.php");
        exit();
    }
}
function getRemboursementOf($db,$idUtilisateur,$page,$page_size){
    try{
        $stmt = $db->prepare(
            "SELECT a.nom as name, r.date_remboursement as date, r.prix_remboursé as prix from remboursement r inner join article a on a.idArticle = r.idArticle where idUtilisateur= ? limit ?,?"
        );
        $debut =($page-1) * $page_size;
        $stmt->bindParam(1,$idUtilisateur, PDO::PARAM_INT);
        $stmt->bindParam(2,$debut, PDO::PARAM_INT);
        $stmt->bindParam(3,$page_size, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }catch(PDOException $e){
        echo $e;
        //header("Location: ../pages/error_page.php");
        exit();
    }
}
?>