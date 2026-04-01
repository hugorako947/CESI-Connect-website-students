<?php include 'header.php'; ?>

<section class="section">
    <div class="section-header" style="margin-bottom:1.5rem;">
        <div>
            <h2>🎯 Offres pour : <?= htmlspecialchars($alert['nom_alerte']) ?></h2>
            <p style="color:var(--muted); margin-top:.35rem; font-size:.92rem;">
                <?= count($offres) ?> offre<?= count($offres) > 1 ? 's' : '' ?> 
                correspondant à vos critères depuis la création de l'alerte
            </p>
        </div>
        <a href="index.php?route=mes-alertes" class="btn btn-outline">
            ← Retour aux alertes
        </a>
    </div>

    <!-- Récapitulatif des critères -->
    <div class="offre-card" style="margin-bottom:1.5rem;">
        <h3 style="font-weight:900; margin-bottom:.75rem;">📋 Critères de recherche</h3>
        <div style="display:flex; flex-wrap:wrap; gap:.75rem;">
            <?php if (!empty($alert['mot_cle'])): ?>
                <span class="filter-tag">🔍 <?= htmlspecialchars($alert['mot_cle']) ?></span>
            <?php endif; ?>
            
            <?php if (!empty($alert['ville'])): ?>
                <span class="filter-tag">📍 <?= htmlspecialchars($alert['ville']) ?></span>
            <?php endif; ?>
            
            <?php if (!empty($alert['domaine'])): ?>
                <span class="filter-tag">💼 <?= htmlspecialchars($alert['domaine']) ?></span>
            <?php endif; ?>
            
            <?php if (!empty($alert['type_contrat'])): ?>
                <span class="filter-tag">
                    <?= $alert['type_contrat'] === 'stage' ? '📋' : '🎓' ?> 
                    <?= ucfirst($alert['type_contrat']) ?>
                </span>
            <?php endif; ?>
            
            <?php if (!empty($alert['remuneration_min']) && $alert['remuneration_min'] > 0): ?>
                <span class="filter-tag">💰 Min. <?= number_format($alert['remuneration_min'], 0, ',', ' ') ?>€</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Liste des offres -->
    <?php if (empty($offres)): ?>
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <h2>Aucune nouvelle offre pour le moment</h2>
            <p>Nous vous notifierons dès qu'une nouvelle offre correspondant à vos critères sera publiée.</p>
            <a href="index.php?route=mes-alertes" class="btn btn-primary" style="margin-top:1rem;">
                Retour aux alertes
            </a>
        </div>
    <?php else: ?>
        <div class="cards-container">
            <?php
                $inferDomain = function(array $offre): string {
                    $text = mb_strtolower(
                        trim(
                            (string) ($offre['titre'] ?? '') . ' ' .
                            (string) ($offre['description'] ?? '') . ' ' .
                            (string) ($offre['competences'] ?? '')
                        )
                    );
                    $map = [
                        'Informatique' => ['dev', 'informatique', 'web', 'software', 'data', 'php', 'java', 'python', 'cloud', 'ia'],
                        'Finance' => ['finance', 'audit', 'compta', 'contrôle de gestion', 'banque'],
                        'Marketing' => ['marketing', 'seo', 'communication', 'brand', 'social media'],
                        'Ingénierie' => ['ingénieur', 'industrie', 'mécanique', 'électronique', 'qualité'],
                        'Design' => ['design', 'ux', 'ui', 'graphique', 'maquette'],
                        'Commercial' => ['commercial', 'vente', 'business developer', 'prospection'],
                        'Ressources humaines' => ['rh', 'ressources humaines', 'recrutement', 'paie'],
                        'Juridique' => ['juridique', 'droit', 'compliance', 'rgpd'],
                    ];
                    foreach ($map as $domain => $keywords) {
                        foreach ($keywords as $keyword) {
                            if (mb_strpos($text, $keyword) !== false) {
                                return $domain;
                            }
                        }
                    }
                    return 'Général';
                };
            ?>
            
            <?php foreach ($offres as $offre): ?>
                <?php $domainLabel = $inferDomain($offre); ?>
                <article class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                        <span class="tag">
                            <?= htmlspecialchars($offre['type_contrat'] ?? 'Offre') ?>
                        </span>
                    </div>
                    <p class="company"><?= htmlspecialchars($offre['entreprise_nom'] ?? '') ?></p>
                    <p class="desc"><?= htmlspecialchars(substr($offre['description'], 0, 150)) ?>...</p>
                    <p class="offer-domain">
                        <span class="domain-badge">Domaine: <?= htmlspecialchars($domainLabel) ?></span>
                    </p>
                    <div class="card-footer">
                        <span class="date">
                            Publié le <?= date('d/m/Y', strtotime($offre['date_publication'])) ?>
                        </span>
                        <a href="index.php?route=offre-details&id=<?= (int) $offre['id'] ?>" class="btn-primary">
                            Détails
                        </a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php $isFavorite = in_array((int) $offre['id'], $wishlistOfferIds ?? [], true); ?>
                            <?php if ($isFavorite): ?>
                                <a href="index.php?route=wishlist-remove&id=<?= (int) $offre['id'] ?>&redirect=<?= urlencode('index.php?route=alerte-offres&id=' . (int) $alert['id']) ?>" 
                                   class="btn-details" 
                                   title="Retirer de ma wish-list">❤</a>
                            <?php else: ?>
                                <a href="index.php?route=wishlist-add&id=<?= (int) $offre['id'] ?>&redirect=<?= urlencode('index.php?route=alerte-offres&id=' . (int) $alert['id']) ?>" 
                                   class="btn-details" 
                                   title="Ajouter à ma wish-list">♡</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<style>
.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .35rem .75rem;
    border-radius: 999px;
    font-size: .82rem;
    font-weight: 800;
    background: rgba(37,99,235,.10);
    color: rgba(37,99,235,.95);
    border: 1px solid rgba(37,99,235,.22);
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: rgba(255,255,255,.74);
    border: 1px solid rgba(99,102,241,.16);
    border-radius: var(--r-lg);
    box-shadow: var(--shadow-sm);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: .5;
}

.empty-state h2 {
    font-weight: 900;
    margin-bottom: .5rem;
}

.empty-state p {
    color: var(--muted);
    max-width: 500px;
    margin: 0 auto;
}
</style>

<?php include 'footer.php'; ?>
