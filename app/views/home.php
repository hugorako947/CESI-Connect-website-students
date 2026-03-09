<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Il est temps de césir votre chance !</h1>
                <p>
                    Votre avenir professionnel commence ici.
                    Cette plateforme connecte les étudiants aux meilleures entreprises. 
                    Accédez à des centaines d'offres de stage et d'alternance et lancez votre carrière.
                </p>
                
                <form action="search.php" method="GET" class="search-bar">
                    <input type="text" name="q" placeholder="Recherche par mot-clé, offre, entreprise...">
                    <a href="Liste offres.html" class="btn-login">Rechercher</a>
                </form>

                <div class="hero-buttons">
                    <a href="Liste offres.html" class="btn btn-primary">Voir toutes les offres</a>
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
    
</body>
</html>


<?php include 'footer.php'; ?>
