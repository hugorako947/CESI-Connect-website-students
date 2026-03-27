<?php
// app/model/UserManager.php

require_once 'Database.php';

class UserManager {
    private $conn;

    // Le constructeur initialise la connexion à la base de données
    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Cherche un utilisateur dans la base de données via son adresse email
     * Utilisé pour la connexion et pour vérifier si un email existe déjà.
     */
    public function getUserByEmail($email) {
        // Requête préparée pour contrer les injections SQL
        $query = "SELECT * FROM utilisateurs WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        // Retourne les données de l'utilisateur ou 'false' s'il n'existe pas
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insère un nouvel utilisateur dans la base de données
     * Utilisé pour l'inscription. Par défaut, l'id_role est 3 (Étudiant).
     */
    public function createUser($nom, $prenom, $email, $mot_de_passe, $id_role = 3) {
        try {
            $query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role) 
                      VALUES (:nom, :prenom, :email, :mot_de_passe, :id_role)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);
            $stmt->bindParam(':id_role', $id_role, PDO::PARAM_INT);
            
            return $stmt->execute();

        } catch(PDOException $e) {
            // Si l'email existe déjà (contrainte UNIQUE dans la BDD), on capture l'erreur
            return false;
        }
    }
}
