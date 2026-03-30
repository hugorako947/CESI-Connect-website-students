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
        <?php if (!empty($latestOffers)): ?>
            <?php foreach ($latestOffers as $offer): ?>
                <article class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($offer['titre'] ?? '') ?></h3>
                        <span class="tag">Offre</span>
                    </div>
                    <p class="company"><?= htmlspecialchars($offer['entreprise_nom'] ?? '') ?></p>
                    <p class="desc">
                        <?php
                        $desc = $offer['description'] ?? '';
                        $snippet = substr($desc, 0, 110);
                        echo htmlspecialchars($snippet) . (strlen($desc) > 110 ? '...' : '');
                        ?>
                    </p>
                    <a href="index.php?route=offre-details&id=<?= (int) ($offer['id'] ?? 0) ?>" class="btn-details">
                        Voir l'offre
                    </a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune offre récente pour le moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
