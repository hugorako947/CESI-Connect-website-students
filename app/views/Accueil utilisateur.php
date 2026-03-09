<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Ceci est la page d'accueil personnel d'un utilisateur après s'être connecté sur CESI Connect.">
    <link rel="stylesheet" href="style.css"> 
    <title>CESI Connect</title>
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <a href="Accueil utilisateur.html">CESI Connect</a>
        </div>
        
        <div class="burger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="main-nav">
            <ul>
                <li><a href="Accueil utilisateur.html" class="active">Accueil</a></li>
                <li><a href="Mon suivi.html">Mon suivi</a></li>
                <li><a href="Profil.html">Profil</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Bon retour parmi nous !</h1>
                <p>
                    Tout commence ici.
                    N'hésitez pas à vous connecter aux meilleures entreprises pour lancer votre carrière...
                </p>

                <form action="search.php" method="GET" class="search-bar">
                    <input type="text" name="q" placeholder="Recherche par mot-clé, offre, entreprise...">
                    <a href="Liste offres utilisateur.html" class="btn-login">Rechercher</a>
                </form>

                <div class="hero-buttons">
                    <a href="Liste offres utilisateur.html" class="btn btn-primary">Voir toutes les offres</a>
                </div>
            </div>
        </section>

        <section class="stats">
            <div class="stat-item">
                <span class="number">150+</span>
                <span class="label">Offres actives</span>
            </div>
            <div class="stat-item">
                <span class="number">80</span>
                <span class="label">Entreprises</span>
            </div>
            <div class="stat-item">
                <span class="number">500+</span>
                <span class="label">Étudiants</span>
            </div>
        </section>

        <section class="latest-offers">
            <div class="section-header">
                <h2>À la une</h2>
                <a href="offres.html">Voir tout &rarr;</a>
            </div>
            
            <div class="cards-container">
                <article class="card">
                    <div class="card-header">
                        <h3>Développeur Web</h3>
                        <span class="tag">Stage</span>
                    </div>
                    <p class="company">TechCorp - Paris</p>
                    <p class="desc">Développement d'une application React...</p>
                    <a href="#" class="btn-details">Voir l'offre</a>
                </article>

                <article class="card">
                    <div class="card-header">
                        <h3>Admin Système</h3>
                        <span class="tag">Alternance</span>
                    </div>
                    <p class="company">DataSecure - Lyon</p>
                    <p class="desc">Gestion de parc informatique et sécurité...</p>
                    <a href="#" class="btn-details">Voir l'offre</a>
                </article>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h3>CESI Connect</h3>
            </div>
            <div class="footer-col">
                <h4>Liens utiles</h4>
                <ul>
                    <li><a href="Mentions légales utilisateur.html">Mentions légales</a></li>
                    <li><a href="Contact utilisateur.html">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 CESI Connect - Tous droits réservés</p>
        </div>
    </footer>
</body>
</html>
