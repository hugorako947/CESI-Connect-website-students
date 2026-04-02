<?php
// app/model/CandidatureManager.php

require_once 'database.php';

/**
 * CandidatureManager - Gestion des candidatures
 * Version adaptée pour Web4All
 */
class CandidatureManager {
    private $conn;

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Créer une nouvelle candidature
     */
    public function create($data) {
        $query = "INSERT INTO candidatures (id_utilisateur, id_offre, cv_path, lettre_motivation, statut, date_candidature)
                  VALUES (:id_utilisateur, :id_offre, :cv_path, :lettre_motivation, :statut, NOW())";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id_utilisateur', $data['id_utilisateur'], PDO::PARAM_INT);
        $stmt->bindParam(':id_offre', $data['id_offre'], PDO::PARAM_INT);
        $stmt->bindParam(':cv_path', $data['cv_path'], PDO::PARAM_STR);
        $stmt->bindParam(':lettre_motivation', $data['lettre_motivation'], PDO::PARAM_STR);
        
        $statut = $data['statut'] ?? 'En attente';
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return (int) $this->conn->lastInsertId();
    }

    /**
     * Vérifier si un utilisateur a déjà candidaté pour une offre
     */
    public function hasApplied($userId, $offerId) {
        $query = "SELECT COUNT(*) FROM candidatures 
                  WHERE id_utilisateur = :user_id AND id_offre = :offer_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Récupérer toutes les candidatures d'un utilisateur
     */
    public function getByUser($userId) {
        $query = "SELECT c.*, 
                         o.titre AS offre_titre, 
                         o.description AS offre_description,
                         e.nom AS entreprise_nom
                  FROM candidatures c
                  INNER JOIN offres o ON c.id_offre = o.id
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE c.id_utilisateur = :user_id
                  ORDER BY c.date_candidature DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer toutes les candidatures pour une offre
     */
    public function getByOffer($offerId) {
        $query = "SELECT c.*, 
                         u.nom AS candidat_nom, 
                         u.prenom AS candidat_prenom,
                         u.email AS candidat_email
                  FROM candidatures c
                  INNER JOIN utilisateurs u ON c.id_utilisateur = u.id
                  WHERE c.id_offre = :offer_id
                  ORDER BY c.date_candidature DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une candidature par son ID
     */
    public function getById($id) {
        $query = "SELECT c.*, 
                         o.titre AS offre_titre, 
                         e.nom AS entreprise_nom,
                         u.nom AS candidat_nom,
                         u.prenom AS candidat_prenom
                  FROM candidatures c
                  INNER JOIN offres o ON c.id_offre = o.id
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  INNER JOIN utilisateurs u ON c.id_utilisateur = u.id
                  WHERE c.id = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mettre à jour le statut d'une candidature
     */
    public function updateStatus($id, $statut) {
        $allowedStatuses = ['En attente', 'Acceptée', 'Refusée'];
        
        if (!in_array($statut, $allowedStatuses)) {
            return false;
        }

        $query = "UPDATE candidatures SET statut = :statut WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Supprimer une candidature
     */
    public function delete($id) {
        $query = "DELETE FROM candidatures WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Compter le nombre de candidatures pour une offre
     */
    public function countByOffer($offerId) {
        $query = "SELECT COUNT(*) FROM candidatures WHERE id_offre = :offer_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        $stmt->execute();
        
        return (int) $stmt->fetchColumn();
    }

    /**
     * Compter le nombre total de candidatures
     */
    public function countAll() {
        $query = "SELECT COUNT(*) FROM candidatures";
        $stmt = $this->conn->query($query);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Compter le nombre de candidatures par statut
     */
    public function countByStatus($statut) {
        $query = "SELECT COUNT(*) FROM candidatures WHERE statut = :statut";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Récupérer les offres les plus populaires (avec le plus de candidatures)
     */
    public function getTopOffers($limit = 10) {
        $query = "SELECT o.id, o.titre, e.nom AS entreprise_nom, COUNT(c.id) AS nb_candidatures
                  FROM candidatures c
                  INNER JOIN offres o ON c.id_offre = o.id
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  GROUP BY o.id, o.titre, e.nom
                  ORDER BY nb_candidatures DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
