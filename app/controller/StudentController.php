<?php
// app/controller/StudentController.php

require_once '../app/controller/AuthController.php';
require_once '../app/models/OfferManager.php';
require_once '../app/models/UserManager.php';
require_once '../app/models/CandidatureManager.php';
require_once '../app/models/AlertManager.php';

class StudentController {

// Affiche la page de profil
public function profile() {
    AuthController::requireAuth();

    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $userManager = new UserManager();
    $offerManager = new OfferManager();
    $candidatureManager = new CandidatureManager();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nom === '' || $prenom === '' || $email === '') {
            $_SESSION['erreur'] = "Tous les champs sont obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erreur'] = "Adresse email invalide.";
        } elseif ($userManager->emailExistsForOtherUser($email, $userId)) {
            $_SESSION['erreur'] = "Cette adresse email est déjà utilisée.";
        } else {
            if ($userManager->updateProfile($userId, $nom, $prenom, $email)) {
                $_SESSION['user_nom'] = $nom;
                $_SESSION['user_prenom'] = $prenom;
                $_SESSION['user_email'] = $email;
                $_SESSION['success'] = "Informations personnelles mises à jour.";
            } else {
                $_SESSION['erreur'] = "Impossible de mettre à jour le profil.";
            }
        }

        header('Location: index.php?route=profil');
        exit;
    }

    $user = $userManager->getUserById($userId);
    $role = (int) ($user['id_role'] ?? 0);
    $isPilote = $role === 2;
    $isAdmin  = $role === 1;

    $wishlist_offers = [];
    $candidatures    = [];
    $alerts          = [];
    $pilotStats      = [];
    $adminData       = [];

    if ($isPilote) {
        $pilotStats = [
            'nb_etudiants'         => $userManager->countStudents(),
            'nb_candidatures_total' => $candidatureManager->countAll(),
            'nb_en_attente'        => $candidatureManager->countByStatus('En attente'),
            'nb_acceptees'         => $candidatureManager->countByStatus('Acceptée'),
        ];
    } else {
        // Étudiant ET Admin ont wishlist, alertes, candidatures
        $wishlist_offers = $offerManager->getWishlistOffers($userId);
        $candidatures    = $candidatureManager->getByUser($userId);

        $alertManager = new AlertManager();
        $alerts = $alertManager->getAlertsWithCounts($userId);

        if ($isAdmin) {
            // Charger les données du dashboard admin
            require_once '../app/controller/AdminController.php';
            $adminController = new AdminController();
            $search = trim($_GET['admin_search'] ?? '');
            $adminData = $adminController->getDashboardData($search);
        }
    }

    require_once '../app/views/profil.php';
}

// Affiche la wishlist de l'étudiant
public function wishlist() {
    AuthController::requireAuth();
    $offerManager = new OfferManager();
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $wishlist_offers = $userId ? $offerManager->getWishlistOffers($userId) : [];
require_once '../app/views/wishlist.php';
}

public function addToWishlist() {
    AuthController::requireAuth();

    $offerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $redirect = $_GET['redirect'] ?? 'index.php?route=offres';

    if (strpos($redirect, 'index.php') !== 0) {
        $redirect = 'index.php?route=offres';
    }

    if (!$offerId) {
        $_SESSION['erreur'] = "Offre invalide.";
        header('Location: ' . $redirect);
        exit;
    }

    $offerManager = new OfferManager();

    if (!$offerManager->isInWishlist($userId, $offerId)) {
        $offerManager->addToWishlist($userId, $offerId);
        $_SESSION['success'] = "Offre ajoutée à votre wish-list.";
    }

    header('Location: ' . $redirect);
    exit;
}

public function removeFromWishlist() {
    AuthController::requireAuth();

    $offerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $redirect = $_GET['redirect'] ?? 'index.php?route=offres';

    if (strpos($redirect, 'index.php') !== 0) {
        $redirect = 'index.php?route=offres';
    }

    if (!$offerId) {
        $_SESSION['erreur'] = "Offre invalide.";
        header('Location: ' . $redirect);
        exit;
    }

    $offerManager = new OfferManager();
    $offerManager->removeFromWishlist($userId, $offerId);
    $_SESSION['success'] = "Offre retirée de votre wish-list.";

    header('Location: ' . $redirect);
    exit;
}

// Affiche les candidatures envoyées
public function applications() {
// TODO: Vérifier si l'utilisateur est bien connecté
// TODO: Récupérer les candidatures de l'étudiant depuis la BDD
require_once '../app/views/candidatures-envoyees.php';
}

// ========== GESTION DES ALERTES ==========

/**
 * Afficher la page de gestion des alertes
 */
public function alerts() {
    AuthController::requireAuth();
    
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $alertManager = new AlertManager();
    
    // Récupérer les alertes avec le nombre de nouvelles offres
    $alerts = $alertManager->getAlertsWithCounts($userId);
    
    require_once '../app/views/mes-alertes.php';
}

/**
 * Afficher le formulaire de création/modification d'alerte
 */
public function alertForm() {
    AuthController::requireAuth();
    
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $alertManager = new AlertManager();
    $alert = null;
    
    // Mode édition
    if (isset($_GET['id'])) {
        $alertId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($alertId) {
            $alert = $alertManager->getAlertById($alertId, $userId);
            
            if (!$alert) {
                $_SESSION['erreur'] = "Alerte introuvable.";
                header('Location: index.php?route=mes-alertes');
                exit;
            }
        }
    }
    
    require_once '../app/views/alerte-form.php';
}

/**
 * Enregistrer une nouvelle alerte ou modifier une existante
 */
public function saveAlert() {
    AuthController::requireAuth();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?route=mes-alertes');
        exit;
    }
    
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    $alertManager = new AlertManager();
    
    // Récupération des données du formulaire
    $data = [
        'nom_alerte' => trim($_POST['nom_alerte'] ?? ''),
        'mot_cle' => trim($_POST['mot_cle'] ?? ''),
        'ville' => trim($_POST['ville'] ?? ''),
        'domaine' => trim($_POST['domaine'] ?? ''),
        'type_contrat' => trim($_POST['type_contrat'] ?? ''),
        'remuneration_min' => filter_input(INPUT_POST, 'remuneration_min', FILTER_VALIDATE_INT) ?: 0,
        'duree_contrat' => trim($_POST['duree_contrat'] ?? ''),
        'niveau_etude' => trim($_POST['niveau_etude'] ?? ''),
        'teletravail' => isset($_POST['teletravail']) ? trim($_POST['teletravail']) : '',
    ];
    
    // Validation
    if (empty($data['nom_alerte'])) {
        $_SESSION['erreur'] = "Le nom de l'alerte est obligatoire.";
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?route=alerte-form'));
        exit;
    }
    
    // Vérifier qu'au moins un critère est rempli
    if (empty($data['mot_cle']) && empty($data['ville']) && empty($data['domaine']) && 
        empty($data['type_contrat']) && $data['remuneration_min'] <= 0 &&
        empty($data['duree_contrat']) && empty($data['niveau_etude']) && $data['teletravail'] === '') {
        $_SESSION['erreur'] = "Veuillez définir au moins un critère de recherche.";
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?route=alerte-form'));
        exit;
    }
    
    try {
        $alertId = filter_input(INPUT_POST, 'alert_id', FILTER_VALIDATE_INT);
        
        if ($alertId) {
            // Modification
            $alertManager->updateAlert($alertId, $userId, $data);
            $_SESSION['success'] = "Alerte modifiée avec succès !";
        } else {
            // Création
            $alertManager->createAlert($userId, $data);
            $_SESSION['success'] = "Alerte créée avec succès ! Vous serez notifié des nouvelles offres correspondantes.";
        }
        
        header('Location: index.php?route=mes-alertes');
        exit;
        
    } catch (Exception $e) {
        $_SESSION['erreur'] = "Une erreur est survenue lors de l'enregistrement.";
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?route=alerte-form'));
        exit;
    }
}

/**
 * Activer/Désactiver une alerte
 */
public function toggleAlert() {
    AuthController::requireAuth();
    
    $alertId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    
    if (!$alertId) {
        $_SESSION['erreur'] = "Alerte invalide.";
        header('Location: index.php?route=mes-alertes');
        exit;
    }
    
    $alertManager = new AlertManager();
    
    if ($alertManager->toggleAlert($alertId, $userId)) {
        $_SESSION['success'] = "Alerte mise à jour.";
    } else {
        $_SESSION['erreur'] = "Impossible de modifier l'alerte.";
    }
    
    header('Location: index.php?route=mes-alertes');
    exit;
}

/**
 * Supprimer une alerte
 */
public function deleteAlert() {
    AuthController::requireAuth();
    
    $alertId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    
    if (!$alertId) {
        $_SESSION['erreur'] = "Alerte invalide.";
        header('Location: index.php?route=mes-alertes');
        exit;
    }
    
    $alertManager = new AlertManager();
    
    if ($alertManager->deleteAlert($alertId, $userId)) {
        $_SESSION['success'] = "Alerte supprimée avec succès.";
    } else {
        $_SESSION['erreur'] = "Impossible de supprimer l'alerte.";
    }
    
    header('Location: index.php?route=mes-alertes');
    exit;
}

/**
 * Afficher les offres correspondant à une alerte
 */
public function alertOffers() {
    AuthController::requireAuth();
    
    $alertId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userId = (int) ($_SESSION['user_id'] ?? 0);
    
    if (!$alertId) {
        $_SESSION['erreur'] = "Alerte invalide.";
        header('Location: index.php?route=mes-alertes');
        exit;
    }
    
    $alertManager = new AlertManager();
    $offerManager = new OfferManager();
    
    $alert = $alertManager->getAlertById($alertId, $userId);
    
    if (!$alert) {
        $_SESSION['erreur'] = "Alerte introuvable.";
        header('Location: index.php?route=mes-alertes');
        exit;
    }
    
    // Récupérer les offres correspondantes
    $offres = $alertManager->getMatchingOffers($alertId, $userId, 50);
    
    // Récupérer les IDs des offres en wishlist
    $wishlistOfferIds = $offerManager->getWishlistOfferIds($userId);
    
    require_once '../app/views/alerte-offres.php';
}
}
