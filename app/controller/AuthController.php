<?php
// app/controller/AuthController.php

require_once '../app/model/UserManager.php';

class AuthController {
    
    /**
     * Gère l'affichage de la page de connexion ET le traitement du formulaire
     */
    public function login() {
        // 1. Si l'utilisateur valide le formulaire (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Nettoyage des champs
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!empty($email) && !empty($password)) {
                
                $userManager = new UserManager();
                $user = $userManager->getUserByEmail($email);

                // Vérification du mot de passe haché (Sécurité STx 11)
                if ($user && password_verify($password, $user['mot_de_passe'])) {
                    
                    // Succès : Création de la session utilisateur
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_nom'] = $user['nom'];
                    $_SESSION['user_prenom'] = $user['prenom'];
                    $_SESSION['user_role'] = $user['id_role']; // 1=Admin, 2=Pilote, 3=Etudiant

                    // Redirection vers l'accueil
                    header('Location: index.php?route=accueil');
                    exit();
                    
                } else {
                    $erreur = "Email ou mot de passe incorrect.";
                }
            } else {
                $erreur = "Veuillez remplir tous les champs.";
            }
        }
        
        // 2. Affichage de la vue de connexion (avec les erreurs s'il y en a)
        require_once '../app/views/connexion.php';
    }

    /**
     * Gère l'affichage de la page d'inscription ET la création du compte
     */
    public function register() {
        // 1. Si l'utilisateur valide le formulaire (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Nettoyage des champs
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            // Vérifier que tout est rempli
            if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password)) {
                
                // Vérifier que les mots de passe correspondent
                if ($password === $password_confirm) {
                    
                    $userManager = new UserManager();

                    // Vérifier si l'email n'existe pas déjà en base
                    if (!$userManager->getUserByEmail($email)) {
                        
                        // Hachage BCRYPT du mot de passe (Sécurité STx 11)
                        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                        // Insertion dans la base de données
                        if ($userManager->createUser($nom, $prenom, $email, $hashed_password)) {
                            // Succès : on redirige vers la connexion avec un message de succès
                            header('Location: index.php?route=connexion&success=inscription');
                            exit();
                        } else {
                            $erreur = "Une erreur est survenue lors de l'inscription.";
                        }
                    } else {
                        $erreur = "Cette adresse email est déjà utilisée.";
                    }
                } else {
                    $erreur = "Les mots de passe ne correspondent pas.";
                }
            } else {
                $erreur = "Veuillez remplir tous les champs obligatoires.";
            }
        }

        // 2. Affichage de la vue d'inscription (avec les erreurs s'il y en a)
        require_once '../app/views/inscription.php';
    }

    /**
     * Gère la déconnexion de l'utilisateur
     */
    public function logout() {
        // On détruit toutes les données de session
        session_unset();
        session_destroy();
        
        // On redirige vers l'accueil
        header('Location: index.php?route=accueil');
        exit();
    }
}
