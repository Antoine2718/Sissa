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
    if(!
    (
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
    true);  
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
function getPurchaseHistory($pdo,$idUtilisateur){
    try{
        $stmt = $pdo->prepare("
    select a.idArticle, a.nom, ac.quantité_achat, ac.date_achat, a.prix 
    from achete ac
    join article a on ac.idArticle = a.idArticle
    where ac.idUtilisateur = ?
    order by ac.date_achat desc
        ");
        $stmt->execute([$idUtilisateur]);
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
?>