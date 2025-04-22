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
    $stmt = $db->prepare("SELECT idUtilisateur as id,identifiant as idf, points as pts, idrang as id, type FROM utilisateur WHERE identifiant = ?");
    $stmt->bindParam(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $result= $stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($result)){
        return false;
    }else{
        $user = new Utilisateur($result['id'],$result['idf'],$result['pts'],$result['id'],$result['type']);
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
function getNumberOfUsers($db){
    try{
        $stmt = $db->prepare("SELECT COUNT(*) as cs FROM utilisateur");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        return $count['cs'];
    }catch(PDOException $e){
        return "Error";
    }
}
?>