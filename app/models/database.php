<?php
class Database {
    // Tes identifiants Alwaysdata
    private $host = "mysql-daoud.alwaysdata.net";
    private $db_name = "daoud_web4all_bdd";
    private $username = "daoud";
    private $password = "Lezard83655";
    
    public $conn;
    
    // Il manquait cette ligne pour que le Singleton (getInstance) fonctionne !
    private static $instance = null;

     public function __construct() {
        $this->conn = null;
        try {
            // CORRECTION ICI : On utilise bien $this->host et on enlève le port 3307 local !
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            die("Erreur de connexion : " . $exception->getMessage());
        }
    }

    /**
     * Récupérer l'instance unique de Database (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Récupérer la connexion PDO
     */
    public function getConnection() {
        return $this->conn;
    }
}
?>
