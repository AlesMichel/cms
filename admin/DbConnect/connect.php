<?php

namespace cms\DbConnect;

use PDO;
use PDOException;


require_once __DIR__ . "/../config.php";
require_once ABS_PATH . "/config.php";

class connect{
    private static $instance = null;
    private PDO $connection;

    private function __construct(){
        $dbHost = DBHOST;
        $dbUser = DBUSER;
        $dbPassword = DBPASS;
        $dbName = DBNAME;

        try {

            $this->connection = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

    }

    // Get the single instance of the connection
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new connect();
        }
        return self::$instance;
    }

    // Get the PDO connection
    public function getConnection() {
        return $this->connection;
    }
}

?>