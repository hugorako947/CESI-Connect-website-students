<?php include 'header.php'; ?>

<!-- Les informations ici (nom, description, etc.) seront bientôt chargées par le Contrôleur -->
<div class="company-profile-layout">

    <!-- COLONNE PRINCIPALE -->
    <section class="company-main-content">
        <header class="company-header">
            <div class="company-logo-placeholder">T</div>
            <div class="company-title">
                <h1>TechCorp</h1>
                <p>Paris (75013) • Leader de la Green Tech</p>
            </div>
        </header>

        <article class="company-description">
            <h2>À propos de nous</h2>
            <p>
                TechCorp est une start-up innovante qui développe des solutions technologiques pour répondre aux défis écologiques. 
                Rejoindre notre équipe, c'est participer à des projets qui ont un impact positif sur la planète.
                Nous valorisons la créativité, l'autonomie et l'esprit d'équipe.
            </p>
        </article>

        <section class="company-offers">
            <h2>Offres de stage chez TechCorp</h2>
            <div class="cards-container vertical">
                <!-- Ces cartes seront générées dynamiquement en PHP -->
                <article class="card">
                    <div class="card-header">
                        <h3>Développeur Web Fullstack</h3>
                        <span class="tag">Stage</span>
                    </div>
                    <p class="desc">Participez au développement de notre plateforme SaaS en React/Node.js...</p>
                    <div class="card-footer">
                        <span class="date">Publié il y a 2 jours</span>
                        <a href="index.php?route=details-offre&id=1" class="btn-details">Voir les détails</a>
                    </div>
                </article>
            </div>
        </section>
    </section>

    <!-- BARRE LATÉRALE -->
    <aside class="company-sidebar">
        <div class="sidebar-card">
            <h3>Contact</h3>
            <ul class="info-list">
                <li><strong>Email :</strong> contact@techcorp.fr</li>
                <li><strong>Téléphone :</strong> 01 23 45 67 89</li>
            </ul>
        </div>
        <div class="sidebar-card">
            <h3>Évaluation</h3>
            <!-- Ce sera la moyenne des évaluations (SFx 5) -->
            <p>Note moyenne : 4.5 / 5 ★</p>
            <p>Basé sur 12 avis de stagiaires.</p>
        </div>
    </aside>
</div>

<?php include 'footer.php'; ?>