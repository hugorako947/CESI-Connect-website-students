<?php

session_start();

// === CHARGEMENT DES CONTROLLERS ===
require_once '../app/controller/HomeController.php';
require_once '../app/controller/OfferController.php';
require_once '../app/controller/EnterpriseController.php';
require_once '../app/controller/AuthController.php';
require_once '../app/controller/StudentController.php';
require_once '../app/controller/ManagementController.php';
require_once '../app/controller/CandidatureController.php';

// === RÉCUPÉRATION DE LA ROUTE ===
$route = $_GET['route'] ?? 'accueil';

// === ROUTAGE ===
switch ($route) {

    // ========== PAGES GÉNÉRALES (HomeController) ==========
    
    case 'accueil':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'mentions-legales':
        $controller = new HomeController();
        $controller->legalMentions();
        break;

    case 'contact':
        $controller = new HomeController();
        $controller->contact();
        break;

    // ========== AUTHENTIFICATION (AuthController) ==========
    
    case 'connexion':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'inscription':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'deconnexion':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'mot-de-passe-oublie':
        $controller = new AuthController();
        $controller->forgotPassword();
        break;
    
    case 'reinitialiser-mot-de-passe':
        $controller = new AuthController();
        $controller->resetPassword();
        break;

    // ========== OFFRES (OfferController) ==========
    
    case 'offres':
        $controller = new OfferController();
        $controller->list();
        break;

    case 'offre-details':
        $controller = new OfferController();
        $controller->details();
        break;

    case 'offres-recherche':
        $controller = new OfferController();
        $controller->search();
        break;

    // ========== CANDIDATURES (CandidatureController) ==========
    
    case 'candidater':
        $controller = new CandidatureController();
        $controller->create();
        break;

    case 'candidater-process':
        $controller = new CandidatureController();
        $controller->store();
        break;

    case 'mes-candidatures':
        $controller = new CandidatureController();
        $controller->index();
        break;

    // ========== ENTREPRISES (EnterpriseController) ==========
    
    case 'details-entreprise':
        $controller = new EnterpriseController();
        $controller->details();
        break;

    // ========== ESPACE ÉTUDIANT (StudentController) ==========
    
    case 'profil':
        $controller = new StudentController();
        $controller->profile();
        break;

    case 'wishlist':
        $controller = new StudentController();
        $controller->wishlist();
        break;

    case 'wishlist-add':
        $controller = new StudentController();
        $controller->addToWishlist();
        break;

    case 'wishlist-remove':
        $controller = new StudentController();
        $controller->removeFromWishlist();
        break;

    // ========== GESTION (ManagementController) ==========
    
    case 'gestion-entreprises':
        $controller = new ManagementController();
        $controller->listEntreprises();
        break;
    
    case 'form-entreprise':
        AuthController::requireRole(1);
        $controller = new ManagementController();
        $controller->entrepriseForm();
        break;
    
    case 'form-offre':
        AuthController::requireRole(1);
        $controller = new ManagementController();
        $controller->offreForm();
        break;
    
    case 'suivi-etudiants-pilote':
        $controller = new ManagementController();
        $controller->suiviPilote();
        break;

    // ========== PAGE 404 ==========
    
    default:
        http_response_code(404);
        require_once '../app/views/404.php';
        break;
}
?>
