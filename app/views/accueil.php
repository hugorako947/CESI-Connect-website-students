<?php include 'header.php'; ?>

<section class="hero">
    <h1>Trouvez votre stage, alternance<br>ou premier emploi</h1>
    <p>Votre avenir professionnel commence ici avec les offres de CESI Connect.</p>
    <form action="index.php" method="GET" class="search-bar">
        <div class="search-field">
            <input type="hidden" name="route" value="offres">
            <input type="text" name="q" placeholder="Métier, compétence, entreprise...">
        </div>
        <div class="search-loc">
            <select name="city">
                <option value="">Toutes les villes</option>
                <option value="Île-de-France">Île-de-France</option>
                <option value="lyon">Lyon</option>
                <option value="marseille">Marseille</option>
                <option value="bordeaux">Bordeaux</option>
                <option value="étranger">À l'étranger</option>
            </select>
        </div>
        <button type="submit" class="search-btn">Rechercher</button>
    </form>
    <div class="tags-row">
        <a class="tag" href="index.php?route=offres&q=Marketing">Marketing</a>
        <a class="tag" href="index.php?route=offres&q=Informatique">Informatique</a>
        <a class="tag" href="index.php?route=offres&q=Finance">Finance</a>
        <a class="tag" href="index.php?route=offres&q=Communication">Communication</a>
        <a class="tag" href="index.php?route=offres&q=RH">RH</a>
        <a class="tag" href="index.php?route=offres&q=Commerce">Commerce</a>
        <a class="tag" href="index.php?route=offres&q=Ingenierie">Ingénierie</a>
        <a class="tag" href="index.php?route=offres&q=Design">Design</a>
        <a class="tag" href="index.php?route=offres&q=Juridique">Juridique</a>
    </div>
</section>

<section class="stats-bar">
    <div class="stat-item">
        <strong><?= isset($latestOffers) ? (int) count($latestOffers) : 0 ?></strong>
        <span>Offres récentes affichées</span>
    </div>
    <div class="stat-item">
        <strong>100%</strong>
        <span>Offres vérifiées sur la plateforme</span>
    </div>
    <div class="stat-item">
        <strong>24/7</strong>
        <span>Accès aux offres en ligne</span>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>Offres à la une</h2>
        <a href="index.php?route=offres">Voir toutes les offres →</a>
    </div>
    <div class="offers-grid">
        <?php if (!empty($latestOffers)): ?>
            <?php foreach (array_slice($latestOffers, 0, 6) as $offer): ?>
                <article class="offer-card">
                    <div class="offer-top">
                        <div class="company-logo">ST</div>
                        <div class="offer-meta">
                            <h3><?= htmlspecialchars($offer['titre'] ?? '') ?></h3>
                            <div class="company"><?= htmlspecialchars($offer['entreprise_nom'] ?? '') ?></div>
                        </div>
                    </div>
                    <div class="badges">
                        <span class="badge badge-type">Offre</span>
                        <span class="badge badge-new">Nouveau</span>
                    </div>
                    <div class="offer-footer">
                        <span class="offer-date">Publié récemment</span>
                        <a href="index.php?route=offre-details&id=<?= (int) ($offer['id'] ?? 0) ?>" class="save-btn">Voir l'offre</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune offre récente pour le moment.</p>
        <?php endif; ?>
    </div>
</section>

<section class="section section-muted">
    <div class="section-header">
        <h2>Explorer par domaine</h2>
        <a href="index.php?route=offres">Parcourir les offres →</a>
    </div>
    <div class="cats-grid">
        <div class="cat-card"><div class="cat-icon">💻</div><h4>Informatique</h4><p>Rechercher</p></div>
        <div class="cat-card"><div class="cat-icon">📊</div><h4>Finance</h4><p>Rechercher</p></div>
        <div class="cat-card"><div class="cat-icon">📣</div><h4>Marketing</h4><p>Rechercher</p></div>
        <div class="cat-card"><div class="cat-icon">⚙️</div><h4>Ingénierie</h4><p>Rechercher</p></div>
        <div class="cat-card"><div class="cat-icon">🎨</div><h4>Design</h4><p>Rechercher</p></div>
        <div class="cat-card"><div class="cat-icon">🤝</div><h4>Commercial</h4><p>Rechercher</p></div>
        <div class="cat-card"><div class="cat-icon">👥</div><h4>Ressources humaines</h4><p>Rechercher</p></div>
        <div class="cat-card"><div class="cat-icon">⚖️</div><h4>Juridique</h4><p>Rechercher</p></div>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>Nos entreprises partenaires</h2>
        <a href="index.php?route=gestion-entreprises">Voir toutes les entreprises →</a>
    </div>
    <div class="partners-row">
        <div class="partner">La Poste</div>
        <div class="partner">Orange</div>
        <div class="partner">BNP Paribas</div>
        <div class="partner">Airbus</div>
        <div class="partner">LVMH</div>
        <div class="partner">ADP</div>
        <div class="partner">Renault</div>
        <div class="partner">Société Générale</div>
    </div>
</section>

<section class="section">
    <div class="cta-banner">
        <div>
            <h3>Prêt à candidater ?</h3>
            <p>Consultez les offres disponibles et postulez directement depuis votre espace CESI Connect.</p>
        </div>
        <a href="index.php?route=offres" class="btn-white">Voir les offres</a>
    </div>
</section>

<?php include 'footer.php'; ?>
