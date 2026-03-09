<?php
// public/index.php : LE ROUTEUR PRINCIPAL

// 1. On récupère la route demandée dans l'URL (ex: site.com/index.php?route=offres)
// Si rien n'est demandé, on affiche l'accueil ('home') par défaut.
$route = isset($_GET['route']) ? $_GET['route'] : 'home';

// 2. On définit le chemin vers nos Vues (pour ne pas se tromper de dossier)
$viewsPath = '../app/Views/';

// 3. Le système de routage (Switch)
switch ($route) {
    case 'home':
        // Si l'URL demande 'home', on inclut la vue home.php
        require $viewsPath . 'home.php';
        break;

    case 'offres':
        // On inclura le contrôleur des offres plus tard, pour l'instant on charge juste la vue
        require $viewsPath . 'offres.php';
        break;

    case 'login':
        require $viewsPath . 'login.php';
        break;

    case 'mentions':
        require $viewsPath . 'mentions.php';
        break;

    default:
        // Si l'utilisateur tape une URL qui n'existe pas -> Erreur 404
        echo "<h1>Erreur 404 : Page introuvable</h1>";
        break;
}