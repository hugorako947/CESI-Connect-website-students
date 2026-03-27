<?php

namespace App\Model;

use PDO;

/**
 * OfferManager - Gestion des offres de stage et d'alternance
 * Responsable des opérations CRUD sur les offres
 */
class OfferManager
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
     * Récupérer toutes les offres avec les informations de l'entreprise
     * 
     * @param int|null $limit Nombre maximum d'offres à retourner (null = toutes)
     * @param int $offset Décalage pour la pagination (par défaut 0)
     * @return array Liste des offres
     */
    public function getAllOffers(?int $limit = null, int $offset = 0): array
    {
        $query = "SELECT o.*, e.nom AS entreprise_nom, e.email_contact AS entreprise_email
                  FROM offres o
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  ORDER BY o.date_publication DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($query);
        
        if ($limit !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une offre par son ID avec les détails de l'entreprise
     * 
     * @param int $id ID de l'offre
     * @return array|false Données de l'offre si trouvée, false sinon
     */
    public function getById(int $id): array|false
    {
        $query = "SELECT o.*, 
                         e.nom AS entreprise_nom, 
                         e.description AS entreprise_description,
                         e.email_contact AS entreprise_email,
                         e.telephone AS entreprise_telephone
                  FROM offres o
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE o.id = :id
                  LIMIT 1";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les offres d'une entreprise spécifique
     * 
     * @param int $entrepriseId ID de l'entreprise
     * @return array Liste des offres de cette entreprise
     */
    public function getByEntreprise(int $entrepriseId): array
    {
        $query = "SELECT o.*, e.nom AS entreprise_nom
                  FROM offres o
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE o.id_entreprise = :entreprise_id
                  ORDER BY o.date_publication DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':entreprise_id', $entrepriseId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Créer une nouvelle offre
     * 
     * @param array $data Données de l'offre
     * @return int ID de l'offre créée
     */
    public function create(array $data): int
    {
        $query = "INSERT INTO offres (titre, description, remuneration, id_entreprise, date_publication)
                  VALUES (:titre, :description, :remuneration, :id_entreprise, NOW())";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':titre', $data['titre'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':remuneration', $data['remuneration'], PDO::PARAM_STR);
        $stmt->bindParam(':id_entreprise', $data['id_entreprise'], PDO::PARAM_INT);
        
        $stmt->execute();
        
        return (int) $this->db->lastInsertId();
    }

    /**
     * Mettre à jour une offre existante
     * 
     * @param int $id ID de l'offre à modifier
     * @param array $data Nouvelles données
     * @return bool True si mise à jour réussie
     */
    public function update(int $id, array $data): bool
    {
        $query = "UPDATE offres 
                  SET titre = :titre, 
                      description = :description, 
                      remuneration = :remuneration
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':titre', $data['titre'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':remuneration', $data['remuneration'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Supprimer une offre
     * 
     * @param int $id ID de l'offre à supprimer
     * @return bool True si suppression réussie
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM offres WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Compter le nombre total d'offres (pour pagination)
     * 
     * @return int Nombre total d'offres
     */
    public function countAll(): int
    {
        $query = "SELECT COUNT(*) FROM offres";
        $stmt = $this->db->query($query);
        
        return (int) $stmt->fetchColumn();
    }

    /**
     * Rechercher des offres par mot-clé
     * 
     * @param string $keyword Mot-clé de recherche
     * @return array Liste des offres correspondantes
     */
    public function search(string $keyword): array
    {
        $query = "SELECT o.*, e.nom AS entreprise_nom
                  FROM offres o
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE o.titre LIKE :keyword 
                     OR o.description LIKE :keyword
                     OR e.nom LIKE :keyword
                  ORDER BY o.date_publication DESC";
        
        $stmt = $this->db->prepare($query);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
