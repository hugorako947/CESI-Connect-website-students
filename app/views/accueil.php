<?php include 'header.php'; ?>

<!-- On attaque directement avec les sections de la page -->
<section class="hero">
    <div class="hero-content">
        <h1>Il est temps de césir votre chance !</h1>
        <p>
            Votre avenir professionnel commence ici.
            Cette plateforme connecte les étudiants aux meilleures entreprises. 
            Accédez à des centaines d'offres de stage et d'alternance et lancez votre carrière.
        </p>
        
        <!-- CORRECTION : Le formulaire pointe vers l'index, en demandant la route "offres" -->
        <form action="index.php" method="GET" class="search-bar">
            <input type="hidden" name="route" value="offres">
            <input type="text" name="q" placeholder="Recherche par mot-clé, offre, entreprise...">
            <button type="submit" class="btn-login">Rechercher</button>
        </form>

        <div class="hero-buttons">
            <!-- CORRECTION : Remplacement de "Liste offres.html" -->
            <a href="index.php?route=offres" class="btn btn-primary">Voir toutes les offres</a>
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
        <!-- CORRECTION : Remplacement de "offres.html" -->
        <a href="index.php?route=offres">Voir tout &rarr;</a>
    </div>
    
    <div class="cards-container">
        <article class="card">
            <div class="card-header">
                <h3>Développeur Web</h3>
                <span class="tag">Stage</span>
            </div>
            <p class="company">TechCorp - Paris</p>
            <p class="desc">Développement d'une application React...</p>
            <!-- CORRECTION : Remplacement du "#" pour pointer vers le détail de l'offre (id=1 simulé) -->
            <a href="index.php?route=details-offre&id=1" class="btn-details">Voir l'offre</a>
        </article>

        <article class="card">
            <div class="card-header">
                <h3>Admin Système</h3>
                <span class="tag">Alternance</span>
            </div>
            <p class="company">DataSecure - Lyon</p>
            <p class="desc">Gestion de parc informatique et sécurité...</p>
            <!-- CORRECTION : Simulation de l'offre id=2 -->
            <a href="index.php?route=details-offre&id=2" class="btn-details">Voir l'offre</a>
        </article>
    </div>
</section>

<?php include 'footer.php'; ?>
