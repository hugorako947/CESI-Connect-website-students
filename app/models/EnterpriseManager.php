<?php
// app/models/EnterpriseManager.php

require_once 'Database.php';

class EnterpriseManager {
    private $db;

    public function __construct() {
        // On récupère la connexion via ton Singleton
        $this->db = Database::getInstance()->getConnection();
    }

    // Récupérer la liste de toutes les entreprises
    public function getAllEnterprises() {
        $query = "SELECT * FROM entreprises ORDER BY nom ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une entreprise par son identifiant
    public function getById($id)
    {
        $query = "SELECT * FROM entreprises WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
