<?php
class Database {
    private $host = "mysql-daoud.alwaysdata.net";
    private $db_name = "daoud_web4all_bdd";
    private $username = "daoud";
    private $password = "Lezard83655";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=127.0.0.1;port=3307;dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            die("Erreur de connexion : " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>
