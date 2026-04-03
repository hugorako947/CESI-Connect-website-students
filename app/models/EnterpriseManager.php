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

    // Créer une nouvelle entreprise
    public function create($data)
    {
        $query = "INSERT INTO entreprises (nom, description, email_contact, telephone)
                  VALUES (:nom, :description, :email_contact, :telephone)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom',           $data['nom'],           PDO::PARAM_STR);
        $stmt->bindParam(':description',   $data['description'],   PDO::PARAM_STR);
        $stmt->bindParam(':email_contact', $data['email_contact'], PDO::PARAM_STR);
        $stmt->bindParam(':telephone',     $data['telephone'],     PDO::PARAM_STR);
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    // Modifier une entreprise existante
    public function update($id, $data)
    {
        $query = "UPDATE entreprises
                  SET nom = :nom, description = :description,
                      email_contact = :email_contact, telephone = :telephone
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom',           $data['nom'],           PDO::PARAM_STR);
        $stmt->bindParam(':description',   $data['description'],   PDO::PARAM_STR);
        $stmt->bindParam(':email_contact', $data['email_contact'], PDO::PARAM_STR);
        $stmt->bindParam(':telephone',     $data['telephone'],     PDO::PARAM_STR);
        $stmt->bindParam(':id',            $id,                    PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Supprimer une entreprise et ses offres associées
    public function delete($id)
    {
        try {
            $db = $this->db;
            $db->beginTransaction();

            // Supprimer les candidatures liées aux offres de l'entreprise
            $db->prepare("DELETE c FROM candidatures c 
                          INNER JOIN offres o ON c.id_offre = o.id 
                          WHERE o.id_entreprise = :id")->execute(['id' => $id]);

            // Supprimer les wishlists liées aux offres
            $db->prepare("DELETE w FROM wishlist w 
                          INNER JOIN offres o ON w.id_offre = o.id 
                          WHERE o.id_entreprise = :id")->execute(['id' => $id]);

            // Supprimer les offres de l'entreprise
            $db->prepare("DELETE FROM offres WHERE id_entreprise = :id")->execute(['id' => $id]);

            // Supprimer l'entreprise
            $stmt = $db->prepare("DELETE FROM entreprises WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Compter toutes les entreprises
    public function countAll()
    {
        $query = "SELECT COUNT(*) FROM entreprises";
        $stmt = $this->db->query($query);
        return (int) $stmt->fetchColumn();
    }

    // Recherche admin avec filtre optionnel
    public function searchAll($search = '')
    {
        $query = "SELECT * FROM entreprises";

        if (!empty($search)) {
            $query .= " WHERE nom LIKE :s OR email_contact LIKE :s";
        }

        $query .= " ORDER BY nom ASC";

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $term = '%' . $search . '%';
            $stmt->bindValue(':s', $term, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
