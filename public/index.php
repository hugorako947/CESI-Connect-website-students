<?php

session_start();

require_once '../app/controller/HomeController.php';
require_once '../app/controller/OfferController.php';
require_once '../app/controller/EnterpriseController.php';
require_once '../app/controller/AuthController.php';
require_once '../app/controller/StudentController.php';
require_once '../app/controller/ManagementController.php';

$route = $_GET['route'] ?? 'accueil';

switch ($route) {

    // --- Routes des pages générales (HomeController) ---
    case 'accueil':
        $controller = new HomeController(); // Crée un objet "Spécialiste de l'accueil"
        $controller->index();               // Demande à l'objet d'exécuter sa méthode "index"
        break;

    case 'mentions-legales':
        $controller = new HomeController();
        $controller->legalMentions();
        break;

    case 'contact':
        $controller = new HomeController();
        $controller->contact();
        break;

    // --- Routes des offres (OfferController) ---
    case 'offres':
        $controller = new OfferController();
        $controller->list();
        break;

    case 'details-offre':
        $controller = new OfferController();
        $controller->details();
        break;
        
    case 'postuler':
        $controller = new OfferController();
        $controller->showApplyForm();
        break;

    // --- Routes des entreprises (EnterpriseController) ---
    case 'details-entreprise':
        $controller = new EnterpriseController();
        $controller->details();
        break;

    // --- Routes d'authentification (AuthController) ---
    case 'connexion':
        $controller = new AuthController();
        $controller->showLoginForm();
        break;

    case 'inscription':
        $controller = new AuthController();
        $controller->showRegisterForm();
        break;

    case 'deconnexion':
        $controller = new AuthController();
        $controller->logout();
        break;

    // --- Routes de l'espace étudiant (StudentController) ---
    case 'profil':
        $controller = new StudentController();
        $controller->profile();
        break;

    case 'wishlist':
        $controller = new StudentController();
        $controller->wishlist();
        break;

    case 'candidatures':
        $controller = new StudentController();
        $controller->applications();
        break;
    
    // --- Routes de gestion (ManagementController) ---
    case 'gestion-entreprises':
        $controller = new ManagementController();
        $controller->listEntreprises();
        break;
    
    case 'form-entreprise':
        $controller = new ManagementController();
        $controller->entrepriseForm();
        break;
    
    case 'form-offre':
        $controller = new ManagementController();
        $controller->offreForm();
        break;
    
    case 'suivi-pilote':
        $controller = new ManagementController();
        $controller->suiviPilote();
        break;
    

    // --- Cas par défaut : la route n'existe pas ---
    default:
        http_response_code(404); // Informe le navigateur que la page n'existe pas
        require_once '../app/views/404.php'; // Affiche notre page d'erreur
        break;
}