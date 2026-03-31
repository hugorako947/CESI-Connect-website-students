<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>CESI Connect</title>
</head>
<body>
    <nav class="nav">
        <div class="logo">
            <a href="index.php?route=accueil">CESI Connect</a>
        </div>
        <div class="nav-links">
            <a href="index.php?route=accueil">Accueil</a>
            <a href="index.php?route=offres">Offres</a>
            <a href="index.php?route=gestion-entreprises">Entreprises</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?route=profil">Mon profil</a>
            <?php endif; ?>
        </div>
        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?route=deconnexion" class="btn-primary">Déconnexion</a>
            <?php else: ?>
                <a href="index.php?route=connexion" class="btn-primary">Connexion</a>
            <?php endif; ?>
        </div>
    </nav>
    <main>
