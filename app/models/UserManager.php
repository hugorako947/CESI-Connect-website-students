// Ajoute cette méthode dans la classe UserManager

    // Créer un nouvel utilisateur (Inscription)
    public function createUser($nom, $prenom, $email, $mot_de_passe, $id_role = 3) {
        try {
            // Requête préparée pour éviter les injections SQL
            $query = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role) 
                      VALUES (:nom, :prenom, :email, :mot_de_passe, :id_role)";
            
            $stmt = $this->conn->prepare($query);

            // On lie les paramètres
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);
            $stmt->bindParam(':id_role', $id_role, PDO::PARAM_INT);
            
            // Exécute la requête et retourne true si succès, false sinon
            return $stmt->execute();

        } catch(PDOException $e) {
            // Si l'email existe déjà (car on a mis UNIQUE dans la BDD), PDO va lever une erreur
            return false;
        }
    }
