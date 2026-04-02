<?php
// app/controller/AuthController.php

require_once '../app/models/UserManager.php';

/**
 * AuthController - Gestion de l'authentification
 * Version adaptée pour Web4All
 */
class AuthController {
    
    /**
     * Gère l'affichage de la page de connexion ET le traitement du formulaire
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // --- AJOUT : Vérification du domaine CESI ---
            if (!preg_match('/@(viacesi\.fr|cesi\.fr)$/i', $email)) {
                $erreur = "Accès refusé. Seuls les emails @viacesi.fr ou @cesi.fr sont autorisés.";
            } 
            // --- FIN AJOUT ---
            elseif (!empty($email) && !empty($password)) {
                $userManager = new UserManager();
                $user = $userManager->getUserByEmail($email);

                if ($user && password_verify($password, $user['mot_de_passe'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_nom'] = $user['nom'];
                    $_SESSION['user_prenom'] = $user['prenom'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['id_role'];
                    $_SESSION['user_role_nom'] = $user['role_nom'] ?? 'Étudiant';

                    $_SESSION['success'] = "Connexion réussie ! Bienvenue " . $user['prenom'] . ".";
                    header('Location: index.php?route=accueil');
                    exit();
                } else {
                    $erreur = "Email ou mot de passe incorrect.";
                }
            } else {
                $erreur = "Veuillez remplir tous les champs.";
            }
        }
        require_once '../app/views/connexion.php';
    }

    /**
     * Gère l'affichage de la page d'inscription ET la création du compte
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';

            // --- AJOUT : Vérification du domaine CESI ---
            if (!preg_match('/@(viacesi\.fr|cesi\.fr)$/i', $email)) {
                $erreur = "Inscription impossible. Utilisez votre adresse @viacesi.fr ou @cesi.fr.";
            } 
            // --- FIN AJOUT ---
            elseif (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password)) {
                if ($password === $password_confirm) {
                    $userManager = new UserManager();
                    if (!$userManager->getUserByEmail($email)) {
                        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                        if ($userManager->createUser($nom, $prenom, $email, $hashed_password)) {
                            $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
                            header('Location: index.php?route=connexion');
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
        require_once '../app/views/inscription.php';
    }

    /**
     * Gère la déconnexion de l'utilisateur
     */
    public function logout() {
        // Détruire toutes les données de session
        session_unset();
        session_destroy();
        
        // Redirection vers l'accueil
        header('Location: index.php?route=accueil');
        exit();
    }

    // ===== MÉTHODES STATIQUES POUR PROTÉGER LES ROUTES =====

    /**
     * Protéger une route - Vérifier que l'utilisateur est connecté
     * Redirige vers la connexion si non authentifié
     */
    public static function requireAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['erreur'] = "Vous devez être connecté pour accéder à cette page.";
            header('Location: index.php?route=connexion');
            exit();
        }
    }

    /**
     * Protéger une route avec vérification de rôle
     * 
     * @param int $requiredRole ID du rôle requis (1=Admin, 2=Pilote, 3=Étudiant)
     */
    public static function requireRole($requiredRole) {
        self::requireAuth();

        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $requiredRole) {
            $_SESSION['erreur'] = "Accès interdit. Droits insuffisants.";
            header('Location: index.php?route=accueil');
            exit();
        }
    }

    /**
     * Vérifier si l'utilisateur est authentifié (sans redirection)
     * 
     * @return bool True si connecté, false sinon
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique (sans redirection)
     * 
     * @param int $roleId ID du rôle
     * @return bool True si l'utilisateur a ce rôle
     */
    public static function hasRole($roleId) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $roleId;
    }

    // --- Dans app/controller/AuthController.php ---

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $userManager = new UserManager();
            $user = $userManager->getUserByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $userManager->saveResetToken($email, $token);

                // 1. On prépare le lien
                $resetLink = "https://daoud.alwaysdata.net/index.php?route=reinitialiser-mot-de-passe&token=" . $token;

                // mail($email, $subject, $message); 

                // 3. On passe le lien à la vue pour qu'il s'affiche dans le rectangle vert
                $success = "Lien envoyé par mail : <a href='$resetLink'>$resetLink</a>";
            } else {
                $error = "Email non reconnu.";
            }
        }
        require_once '../app/views/password-forgotten.php';
    }
    
    public function resetPassword() {
        $userManager = new UserManager();
        $token = $_GET['token'] ?? $_POST['token'] ?? null;
        $user = null;
    
        if ($token) {
            // Mode Récupération : vérifie le token en BDD
            $user = $userManager->getUserByToken($token);
        } elseif (isset($_SESSION['user_id'])) {
            // Mode Connecté : récupère l'utilisateur par sa session
            $user = $userManager->getUserById($_SESSION['user_id']);
        }
    
        // Si pas de token valide ET pas connecté -> Dehors
        if (!$user) {
            header('Location: index.php?route=connexion');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
    
            if (!empty($password) && $password === $confirm && strlen($password) >= 8) {
                $userManager->updatePassword($user['id'], $password);
                
                if ($token) {
                    header('Location: index.php?route=connexion&success=Mot de passe mis à jour !');
                } else {
                    $_SESSION['success'] = "Votre mot de passe a été modifié avec succès.";
                    header('Location: index.php?route=profil');
                }
                exit();
            } else {
                $error = "Les mots de passe doivent être identiques et faire au moins 8 caractères.";
            }
        }
        require_once '../app/views/password-reset.php';
    }

    public function deleteAccount() {
        // Vérifie que l'utilisateur est bien connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=connexion');
            exit();
        }
    
        $userId = $_SESSION['user_id'];
        $userManager = new UserManager();
    
        // 1. Suppression dans la base de données
        if ($userManager->deleteUser($userId)) {
            // 2. Si succès, on vide la session (déconnexion)
            $_SESSION = array();
            session_destroy();
    
            // 3. Redirection vers l'accueil avec message de succès
            // On ne peut plus utiliser $_SESSION ici car elle est détruite, 
            // on passe donc par une variable GET ou on redémarre une session flash
            header('Location: index.php?route=accueil&success=Compte supprimé avec succès.');
            exit();
        } else {
            $_SESSION['erreur'] = "Erreur lors de la suppression du compte. Contactez un administrateur.";
            header('Location: index.php?route=profil');
            exit();
        }
    }
}
?>
