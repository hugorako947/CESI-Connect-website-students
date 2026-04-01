<?php include 'header.php'; ?>

<!-- Affiche les informations d'une entreprise et la liste de ses offres -->
<div class="company-profile-layout">

    <!-- COLONNE PRINCIPALE -->
    <section class="company-main-content">
        <header class="company-header">
            <div class="company-logo-placeholder"></div>
            <div class="company-title">
                <h1><?= htmlspecialchars($enterprise['nom'] ?? '') ?></h1>
                <?php if (!empty($enterprise['description'])): ?>
                    <p><?= htmlspecialchars(substr($enterprise['description'], 0, 60)) ?><?= (strlen($enterprise['description']) > 60 ? '...' : '') ?></p>
                <?php endif; ?>
            </div>
        </header>

        <article class="company-description">
            <h2>À propos de nous</h2>
            <p>
                <?= nl2br(htmlspecialchars($enterprise['description'] ?? '')) ?>
            </p>
        </article>

        <section class="company-offers">
            <h2>Offres chez <?= htmlspecialchars($enterprise['nom'] ?? '') ?></h2>
            <div class="cards-container vertical">
                <?php if (!empty($offers)): ?>
                    <?php foreach ($offers as $offer): ?>
                        <article class="card">
                            <div class="card-header">
                                <h3><?= htmlspecialchars($offer['titre'] ?? '') ?></h3>
                                <span class="tag">Offre</span>
                            </div>
                            <p class="desc">
                                <?php
                                $desc = $offer['description'] ?? '';
                                $snippet = substr($desc, 0, 120);
                                echo htmlspecialchars($snippet) . (strlen($desc) > 120 ? '...' : '');
                                ?>
                            </p>
                            <div class="card-footer">
                                <span class="date">
                                    <?php if (!empty($offer['date_publication'])): ?>
                                        <?= date('d/m/Y', strtotime($offer['date_publication'])) ?>
                                    <?php endif; ?>
                                </span>
                                <a href="index.php?route=offre-details&id=<?= (int) ($offer['id'] ?? 0) ?>" class="btn-details">
                                    Voir les détails
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune offre pour cette entreprise pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </section>

    <!-- BARRE LATÉRALE -->
    <aside class="company-sidebar">
        <div class="sidebar-card">
            <h3>Contact</h3>
            <ul class="info-list">
                <?php if (!empty($enterprise['email_contact'])): ?>
                    <li><strong>Email :</strong> <?= htmlspecialchars($enterprise['email_contact']) ?></li>
                <?php endif; ?>
                <?php if (!empty($enterprise['telephone'])): ?>
                    <li><strong>Téléphone :</strong> <?= htmlspecialchars($enterprise['telephone']) ?></li>
                <?php endif; ?>
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
