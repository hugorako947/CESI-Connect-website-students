<?php

namespace App\Model;

use PDO;

/**
 * CandidatureManager - Gestion des candidatures
 * Responsable des opérations CRUD sur les candidatures
 */
class CandidatureManager
{
    private PDO $db;

    /**
     * Constructeur - Injection de la connexion PDO
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Créer une nouvelle candidature
     * 
     * @param array $data Données de la candidature
     * @return int ID de la candidature créée
     */
    public function create(array $data): int
    {
        $query = "INSERT INTO candidatures (id_utilisateur, id_offre, cv_path, lettre_motivation, statut, date_candidature)
                  VALUES (:id_utilisateur, :id_offre, :cv_path, :lettre_motivation, :statut, NOW())";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':id_utilisateur', $data['id_utilisateur'], PDO::PARAM_INT);
        $stmt->bindParam(':id_offre', $data['id_offre'], PDO::PARAM_INT);
        $stmt->bindParam(':cv_path', $data['cv_path'], PDO::PARAM_STR);
        $stmt->bindParam(':lettre_motivation', $data['lettre_motivation'], PDO::PARAM_STR);
        
        // Statut par défaut : "En attente"
        $statut = $data['statut'] ?? 'En attente';
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        
        $stmt->execute();
        
        return (int) $this->db->lastInsertId();
    }

    /**
     * Vérifier si un utilisateur a déjà candidaté pour une offre
     * 
     * @param int $userId ID de l'utilisateur
     * @param int $offerId ID de l'offre
     * @return bool True si déjà candidaté, false sinon
     */
    public function hasApplied(int $userId, int $offerId): bool
    {
        $query = "SELECT COUNT(*) FROM candidatures 
                  WHERE id_utilisateur = :user_id AND id_offre = :offer_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Récupérer toutes les candidatures d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return array Liste des candidatures avec les détails des offres
     */
    public function getByUser(int $userId): array
    {
        $query = "SELECT c.*, 
                         o.titre AS offre_titre, 
                         o.description AS offre_description,
                         e.nom AS entreprise_nom
                  FROM candidatures c
                  INNER JOIN offres o ON c.id_offre = o.id
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE c.id_utilisateur = :user_id
                  ORDER BY c.date_candidature DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer toutes les candidatures pour une offre
     * 
     * @param int $offerId ID de l'offre
     * @return array Liste des candidatures avec les détails des candidats
     */
    public function getByOffer(int $offerId): array
    {
        $query = "SELECT c.*, 
                         u.nom AS candidat_nom, 
                         u.prenom AS candidat_prenom,
                         u.email AS candidat_email
                  FROM candidatures c
                  INNER JOIN utilisateurs u ON c.id_utilisateur = u.id
                  WHERE c.id_offre = :offer_id
                  ORDER BY c.date_candidature DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une candidature par son ID
     * 
     * @param int $id ID de la candidature
     * @return array|false Données de la candidature si trouvée, false sinon
     */
    public function getById(int $id): array|false
    {
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
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mettre à jour le statut d'une candidature
     * 
     * @param int $id ID de la candidature
     * @param string $statut Nouveau statut (En attente, Acceptée, Refusée)
     * @return bool True si mise à jour réussie
     */
    public function updateStatus(int $id, string $statut): bool
    {
        // Statuts autorisés
        $allowedStatuses = ['En attente', 'Acceptée', 'Refusée'];
        
        if (!in_array($statut, $allowedStatuses)) {
            return false;
        }

        $query = "UPDATE candidatures SET statut = :statut WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Supprimer une candidature
     * 
     * @param int $id ID de la candidature
     * @return bool True si suppression réussie
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM candidatures WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Compter le nombre de candidatures pour une offre
     * 
     * @param int $offerId ID de l'offre
     * @return int Nombre de candidatures
     */
    public function countByOffer(int $offerId): int
    {
        $query = "SELECT COUNT(*) FROM candidatures WHERE id_offre = :offer_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        $stmt->execute();
        
        return (int) $stmt->fetchColumn();
    }
}
