<?php
// app/model/Database.php

class Database {
    private $host = "localhost";
    private $db_name = "web4all";
    private $username = "root"; // Par défaut sous XAMPP
    private $password = "";     // Par défaut sous XAMPP (vide)
    public $conn;

    // Fonction pour obtenir la connexion
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            // On force PDO à afficher les erreurs pour nous aider à débugger
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Erreur de connexion à la base de données : " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>