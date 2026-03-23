<?php
// app/model/OfferManager.php

require_once 'Database.php';

class OfferManager {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Récupérer toutes les offres AVEC le nom de l'entreprise associée
    public function getAllOffers() {
        // La jointure (JOIN) permet d'avoir le nom de l'entreprise au lieu juste de son ID
        $query = "SELECT offres.*, entreprises.nom as nom_entreprise 
                  FROM offres 
                  JOIN entreprises ON offres.id_entreprise = entreprises.id
                  ORDER BY offres.date_publication DESC";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        // On retourne toutes les lignes sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
