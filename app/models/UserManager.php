<?php
// app/model/UserManager.php

require_once 'database.php';

class UserManager {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /**
     * Cherche un utilisateur dans la base de données via son adresse email
     * Utilisé pour la connexion et pour vérifier si un email existe déjà.
     */
    public function getUserByEmail($email) {
        $query = "SELECT u.*, r.nom AS role_nom 
                  FROM utilisateurs u
                  INNER JOIN roles r ON u.id_role = r.id
                  WHERE u.email = :email 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un utilisateur par son ID
     */
    public function getUserById($id) {
        $query = "SELECT u.*, r.nom AS role_nom 
                  FROM utilisateurs u
                  INNER JOIN roles r ON u.id_role = r.id
                  WHERE u.id = :id 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insère un nouvel utilisateur dans la base de données
     * Par défaut, l'id_role est 3 (Étudiant).
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
            return false;
        }
    }

    /**
     * Authentifier un utilisateur (utilisé par AuthController)
     */
    public function authenticate($email, $password) {
        $user = $this->getUserByEmail($email);
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Ne pas renvoyer le mot de passe
            unset($user['mot_de_passe']);
            return $user;
        }
        
        return false;
    }

    /**
     * Vérifier si un email existe déjà
     */
    public function emailExists($email) {
        $query = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchColumn() > 0;
    }

    public function saveResetToken($email, $token) {
        $query = "UPDATE utilisateurs SET reset_token = :token, reset_expires = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
    
    public function getUserByToken($token) {
        $query = "SELECT * FROM utilisateurs WHERE reset_token = :token AND reset_expires > NOW() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $query = "UPDATE utilisateurs SET mot_de_passe = :pass, reset_token = NULL, reset_expires = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pass', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    /**
     * Mettre à jour le profil d'un utilisateur
     */
    public function updateProfile($userId, $nom, $prenom, $email) {
        $query = "UPDATE utilisateurs SET nom = :nom, prenom = :prenom, email = :email WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Vérifier si un email existe pour un autre utilisateur
     */
    public function emailExistsForOtherUser($email, $userId) {
        $query = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email AND id != :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function deleteUser($userId) {
        try {
            $this->conn->beginTransaction();
    
            // 1. Nettoyage des tables avec les bons noms
            $this->conn->prepare("DELETE FROM wishlist WHERE id_utilisateur = :id")->execute(['id' => $userId]);
            $this->conn->prepare("DELETE FROM candidatures WHERE id_utilisateur = :id")->execute(['id' => $userId]);
            
            // On utilise ici le vrai nom de ta table : alertes_offres
            $this->conn->prepare("DELETE FROM alertes_offres WHERE id_utilisateur = :id")->execute(['id' => $userId]);
    
            // 2. Suppression de l'utilisateur
            $query = "DELETE FROM utilisateurs WHERE id = :id";
            $stmtUser = $this->conn->prepare($query);
            $stmtUser->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmtUser->execute();
    
            return $this->conn->commit();
    
        } catch(PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollback();
            }
            // Une fois testé, tu peux supprimer le die() ci-dessous
            // die("Erreur SQL : " . $e->getMessage()); 
            return false;
        }
    }
}
?>
