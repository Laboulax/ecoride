<?php
require_once 'code/config/config.php';

class Database
{
    private static $pdo = null;

    public function getConnection()
    {
        // 🔥 Réutilisation si déjà connecté
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        try {
            // 🔥 Heroku JawsDB : on utilise l’URL ENV
            $jawsdb_url = getenv("JAWSDB_URL");

            if ($jawsdb_url) {
                $url = parse_url($jawsdb_url);
                $host = $url["host"];
                $dbname = ltrim($url["path"], "/");
                $user = $url["user"];
                $pass = $url["pass"];

            } else {
                // 🔥 Mode local (WAMP / XAMPP)
                $host = DB_HOST;
                $dbname = DB_NAME;
                $user = DB_USER;
                $pass = DB_PASS;
            }

            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

            // 🔥 Connexion unique PDO
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false // IMPORTANT : pas de connexions persistantes !
            ]);

        } catch (PDOException $exception) {
            error_log("DB Connection error: " . $exception->getMessage());
            self::$pdo = null;
        }

        return self::$pdo;
    }

    public function getXML($_xmlFILES)
    {
        $xml = simplexml_load_file($_xmlFILES) 
            or die("Erreur : impossible de charger le fichier XML");
        return $xml;
    }
}
