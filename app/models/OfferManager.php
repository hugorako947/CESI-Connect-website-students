<?php
// app/model/OfferManager.php

// On inclut notre fichier de base de données
require_once 'Database.php';

class OfferManager
{
    private $db;

    // On harmonise le constructeur avec le reste de ton projet
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllOffers($limit = null, $offset = 0)
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

    public function getById($id)
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

    public function getByEntreprise($entrepriseId)
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

    public function create($data)
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

    public function update($id, $data)
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

    public function delete($id)
    {
        $query = "DELETE FROM offres WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function countAll()
    {
        $query = "SELECT COUNT(*) FROM offres";
        $stmt = $this->db->query($query);
        
        return (int) $stmt->fetchColumn();
    }

    public function search($keyword)
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

    /**
     * Récupérer les offres présentes dans la wishlist d'un utilisateur
     */
    public function getWishlistOffers($userId)
    {
        $query = "SELECT o.*, 
                         e.nom AS entreprise_nom
                  FROM wishlist w
                  INNER JOIN offres o ON w.id_offre = o.id
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE w.id_utilisateur = :user_id
                  ORDER BY o.date_publication DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
