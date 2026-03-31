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

    public function searchWithFilters($params, $limit = null, $offset = 0)
    {
        $query = "SELECT o.*, e.nom AS entreprise_nom
                  FROM offres o
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE 1=1";

        $bindings = [];

        if (!empty($params['q'])) {
            $query .= " AND (o.titre LIKE :keyword OR o.description LIKE :keyword OR e.nom LIKE :keyword)";
            $bindings[':keyword'] = '%' . $params['q'] . '%';
        }

        if (!empty($params['skill'])) {
            $query .= " AND (o.titre LIKE :skill OR o.description LIKE :skill)";
            $bindings[':skill'] = '%' . $params['skill'] . '%';
        }

        if (!empty($params['domain'])) {
            $query .= " AND (o.titre LIKE :domain OR o.description LIKE :domain OR o.competences LIKE :domain)";
            $bindings[':domain'] = '%' . $params['domain'] . '%';
        }

        if (!empty($params['city'])) {
            // Ne dépend que du champ ville dans offres (pas sûr de l'existence de e.ville)
            $query .= " AND o.ville LIKE :city";
            $bindings[':city'] = '%' . $params['city'] . '%';
        }

        if (!empty($params['type'])) {
            $types = array_filter((array) $params['type'], function($type) {
                return trim($type) !== '';
            });
            $placeholders = [];
            foreach (array_values($types) as $i => $type) {
                $key = ':type' . $i;
                $placeholders[] = $key;
                $bindings[$key] = $type;
            }
            if (!empty($placeholders)) {
                $query .= " AND o.type_contrat IN (" . implode(',', $placeholders) . ")";
            }
        }

        if (!empty($params['min_money']) && is_numeric($params['min_money'])) {
            $query .= " AND o.remuneration >= :min_money";
            $bindings[':min_money'] = (int) $params['min_money'];
        }

        $query .= " ORDER BY o.date_publication DESC";
        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($query);
        foreach ($bindings as $key => $value) {
            $paramType = PDO::PARAM_STR;
            if ($key === ':min_money') {
                $paramType = PDO::PARAM_INT;
            }
            $stmt->bindValue($key, $value, $paramType);
        }
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countWithFilters($params)
    {
        $query = "SELECT COUNT(*)
                  FROM offres o
                  INNER JOIN entreprises e ON o.id_entreprise = e.id
                  WHERE 1=1";

        $bindings = [];

        if (!empty($params['q'])) {
            $query .= " AND (o.titre LIKE :keyword OR o.description LIKE :keyword OR e.nom LIKE :keyword)";
            $bindings[':keyword'] = '%' . $params['q'] . '%';
        }

        if (!empty($params['skill'])) {
            $query .= " AND (o.titre LIKE :skill OR o.description LIKE :skill)";
            $bindings[':skill'] = '%' . $params['skill'] . '%';
        }

        if (!empty($params['domain'])) {
            $query .= " AND (o.titre LIKE :domain OR o.description LIKE :domain OR o.competences LIKE :domain)";
            $bindings[':domain'] = '%' . $params['domain'] . '%';
        }

        if (!empty($params['city'])) {
            $query .= " AND o.ville LIKE :city";
            $bindings[':city'] = '%' . $params['city'] . '%';
        }

        if (!empty($params['type'])) {
            $types = array_filter((array) $params['type'], function($type) {
                return trim($type) !== '';
            });
            $placeholders = [];
            foreach (array_values($types) as $i => $type) {
                $key = ':type' . $i;
                $placeholders[] = $key;
                $bindings[$key] = $type;
            }
            if (!empty($placeholders)) {
                $query .= " AND o.type_contrat IN (" . implode(',', $placeholders) . ")";
            }
        }

        if (!empty($params['min_money']) && is_numeric($params['min_money'])) {
            $query .= " AND o.remuneration >= :min_money";
            $bindings[':min_money'] = (int) $params['min_money'];
        }

        $stmt = $this->db->prepare($query);
        foreach ($bindings as $key => $value) {
            $paramType = ($key === ':min_money') ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn();
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

    /**
     * Récupérer les identifiants d'offres présentes dans la wishlist d'un utilisateur
     */
    public function getWishlistOfferIds($userId)
    {
        $query = "SELECT id_offre FROM wishlist WHERE id_utilisateur = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Vérifier si une offre est dans la wishlist de l'utilisateur
     */
    public function isInWishlist($userId, $offerId)
    {
        $query = "SELECT COUNT(*) FROM wishlist WHERE id_utilisateur = :user_id AND id_offre = :offer_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    /**
     * Ajouter une offre à la wishlist
     */
    public function addToWishlist($userId, $offerId)
    {
        $query = "INSERT INTO wishlist (id_utilisateur, id_offre) VALUES (:user_id, :offer_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retirer une offre de la wishlist
     */
    public function removeFromWishlist($userId, $offerId)
    {
        $query = "DELETE FROM wishlist WHERE id_utilisateur = :user_id AND id_offre = :offer_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':offer_id', $offerId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
