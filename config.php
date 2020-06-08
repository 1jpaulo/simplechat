<?php
    define('DB_SERVER',     '127.0.0.1');
    define('DB_USERNAME',   'root');
    define('DB_PASSWORD',   'joao');
    define('DB_NAME',       'webchat');
    
    try{
        $db = new PDO(
            "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, 
            DB_USERNAME, 
            DB_PASSWORD
        );
        // set the PDO error mode to exception
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    }catch(PDOException $ex){
        // error message
        echo "Connection failed: " . $ex->getMessage();
    }
?>