<?php
// public/index.php : LE ROUTEUR PRINCIPAL (Front Controller)

// 1. Démarrage de la session (Indispensable pour la future connexion utilisateur)
session_start();

// 2. Récupération de la route depuis l'URL (par défaut : 'home')
// Exemple d'URL : http://localhost/D-veloppement-web/public/index.php?route=offres
$route = isset($_GET['route']) ? $_GET['route'] : 'home';

// 3. Système de Routage
switch ($route) {
    
    // ==========================================
    // ROUTES AVEC CONTRÔLEUR (Le vrai MVC en POO)
    // ==========================================
    
    case 'home':
        // 1. On inclut le fichier de la classe
        require_once '../app/Controllers/HomeController.php';
        // 2. On instancie le contrôleur (Création de l'objet)
        $controller = new HomeController();
        // 3. On appelle la méthode qui gère l'affichage
        $controller->index();
        break;


    // ==========================================
    // ROUTES DIRECTES VERS LES VUES (Temporaire)
    // ==========================================
    // Note : Tu devras créer des contrôleurs pour ces pages plus tard, 
    // comme on l'a fait pour 'home'. En attendant, on charge juste la vue.

    case 'offres':
        require_once '../app/Views/liste-offres.php'; 
        break;

    case 'details-offre':
        // Plus tard, l'URL sera index.php?route=details-offre&id=3
        require_once '../app/Views/details-offre.php';
        break;

    case 'connexion':
        require_once '../app/Views/connexion.php';
        break;

    case 'inscription':
        require_once '../app/Views/inscription.php';
        break;

    case 'mentions':
        require_once '../app/Views/mentions-legales.php';
        break;

    case 'dashboard':
        require_once '../app/Views/accueil-utilisateur.php';
        break;


    // ==========================================
    // GESTION DES ERREURS (Route inconnue)
    // ==========================================
    default:
        // Si l'utilisateur tape n'importe quoi dans l'URL (ex: ?route=nimportequoi)
        http_response_code(404);
        
        // On pourrait charger une vue "404.php" ici, mais pour l'instant on fait simple :
        echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
        echo "<h1>Erreur 404</h1>";
        echo "<p>La page que vous cherchez n'existe pas ou a été déplacée.</p>";
        echo "<a href='index.php?route=home' style='color:blue; text-decoration:underline;'>Retour à l'accueil</a>";
        echo "</div>";
        break;
}
