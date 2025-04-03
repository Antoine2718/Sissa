<?php
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
        ){
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

function signout($db){
    unset($db);
}
?>