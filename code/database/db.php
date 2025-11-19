<?php
require_once 'code/config/config.php';

class Database
{
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            error_log("DB Connection error: " . $exception->getMessage());
            echo "Une erreur interne est survenue. Veuillez réessayer plus tard.";
        }
        return $this->conn;
    }

    public function getXML($_xmlFILES)
    {

        $xml = simplexml_load_file($_xmlFILES) or die("Erreur : impossible de charger le fichier XML");
        return $xml;
    }
}
