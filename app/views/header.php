<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>CESI Connect</title>
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <a href="index.php?route=accueil">CESI Connect</a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php?route=accueil">Accueil</a></li>
                <li><a href="index.php?route=offres">Offres</a></li>
                <li><a href="index.php?route=gestion-entreprises">Entreprises</a></li>
                <li><a href="index.php?route=inscription">Inscription</a></li>
                
                <!-- GESTION DYNAMIQUE DU MENU -->
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="index.php?route=profil">Mon Profil</a></li>
                    <li><a href="index.php?route=deconnexion" class="btn-login">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="index.php?route=connexion" class="btn-login">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <!-- On ouvre la balise main ici, elle englobera toutes les vues -->
    <main>
