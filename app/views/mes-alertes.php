<?php include 'header.php'; ?>

<section class="mes-candidatures-section">
    <div class="container">
        <div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
            <div>
                <h1> Mes alertes</h1>
                <p style="color:var(--muted); margin-top:0.5rem;">
                    Créez des alertes personnalisées et soyez notifié des nouvelles offres correspondant à vos critères.
                </p>
            </div>
            <a href="index.php?route=alerte-form" class="btn btn-primary">
                ➕ Créer une alerte
            </a>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['erreur']) ?></div>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <?php if (empty($alerts)): ?>
            <!-- État vide -->
            <div class="empty-state">
                <div class="empty-icon"> </div>
                <h2>Aucune alerte configurée</h2>
                <p>Créez votre première alerte pour recevoir des notifications sur les nouvelles offres qui correspondent à vos critères.</p>
                <a href="index.php?route=alerte-form" class="btn btn-primary" style="margin-top:1rem;">
                    Créer ma première alerte
                </a>
            </div>
        <?php else: ?>
            <!-- Liste des alertes -->
            <div class="candidatures-list">
                <?php foreach ($alerts as $alert): ?>
                    <div class="candidature-item alert-item" style="position:relative;">
                        
                        <!-- Indicateur actif/inactif -->
                        <div style="position:absolute; top:1rem; right:1rem;">
                            <?php if ($alert['actif']): ?>
                                <span class="badge badge-success">✓ Active</span>
                            <?php else: ?>
                                <span class="badge" style="background:rgba(239,68,68,.10); color:rgba(239,68,68,.95); border-color:rgba(239,68,68,.22);">
                                    ⏸ Désactivée
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="candidature-header">
                            <h3><?= htmlspecialchars($alert['nom_alerte']) ?></h3>
                            
                            <?php if ($alert['nouvelles_offres'] > 0): ?>
                                <span class="statut" style="background: linear-gradient(135deg, #2563eb, #7c3aed); color:#fff; padding:.35rem .85rem; border-radius:999px; font-size:.82rem; font-weight:900;">
                                     <?= $alert['nouvelles_offres'] ?> nouvelle<?= $alert['nouvelles_offres'] > 1 ? 's' : '' ?> offre<?= $alert['nouvelles_offres'] > 1 ? 's' : '' ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="candidature-body" style="margin-top:1rem;">
                            <div style="display:flex; flex-wrap:wrap; gap:.75rem; margin-bottom:.75rem;">
                                <?php if (!empty($alert['mot_cle'])): ?>
                                    <span class="filter-tag">🔍 <?= htmlspecialchars($alert['mot_cle']) ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($alert['ville'])): ?>
                                    <span class="filter-tag"> <?= htmlspecialchars($alert['ville']) ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($alert['domaine'])): ?>
                                    <span class="filter-tag"> <?= htmlspecialchars($alert['domaine']) ?></span>
                                <?php endif; ?>
                                
                                <?php if (!empty($alert['type_contrat'])): ?>
                                    <span class="filter-tag">
                                        <?= $alert['type_contrat'] === 'stage'  ?> 
                                        <?= ucfirst($alert['type_contrat']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (!empty($alert['remuneration_min']) && $alert['remuneration_min'] > 0): ?>
                                    <span class="filter-tag"> Min. <?= number_format($alert['remuneration_min'], 0, ',', ' ') ?>€</span>
                                <?php endif; ?>
                            </div>
                            
                            <p style="font-size:.86rem; color:var(--muted);">
                                <strong>Créée le :</strong> <?= date('d/m/Y', strtotime($alert['date_creation'])) ?>
                            </p>
                        </div>

                        <div class="candidature-footer" style="display:flex; gap:.5rem; flex-wrap:wrap; margin-top:1rem;">
                            <?php if ($alert['nouvelles_offres'] > 0): ?>
                                <a href="index.php?route=alerte-offres&id=<?= (int) $alert['id'] ?>" class="btn btn-primary">
                                    Voir les <?= $alert['nouvelles_offres'] ?> offre<?= $alert['nouvelles_offres'] > 1 ? 's' : '' ?>
                                </a>
                            <?php endif; ?>
                            
                            <a href="index.php?route=alerte-form&id=<?= (int) $alert['id'] ?>" class="btn btn-outline">
                                 Modifier
                            </a>
                            
                            <a href="index.php?route=alerte-toggle&id=<?= (int) $alert['id'] ?>" 
                               class="btn btn-outline"
                               onclick="return confirm('<?= $alert['actif'] ? 'Désactiver' : 'Activer' ?> cette alerte ?')">
                                <?= $alert['actif'] ? '⏸ Désactiver' : '▶ Activer' ?>
                            </a>
                            
                            <a href="index.php?route=alerte-delete&id=<?= (int) $alert['id'] ?>" 
                               class="btn btn-outline"
                               style="border-color:rgba(239,68,68,.30); color:rgba(239,68,68,.95);"
                               onclick="return confirm('Supprimer définitivement cette alerte ?')">
                                 Supprimer
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Statistiques -->
            <div class="stats-card" style="margin-top:2rem;">
                <h3> Statistiques</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?= count($alerts) ?></div>
                        <div class="stat-label">Alerte<?= count($alerts) > 1 ? 's' : '' ?> créée<?= count($alerts) > 1 ? 's' : '' ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">
                            <?= count(array_filter($alerts, fn($a) => $a['actif'] == 1)) ?>
                        </div>
                        <div class="stat-label">Active<?= count(array_filter($alerts, fn($a) => $a['actif'] == 1)) > 1 ? 's' : '' ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">
                            <?= array_sum(array_column($alerts, 'nouvelles_offres')) ?>
                        </div>
                        <div class="stat-label">Nouvelle<?= array_sum(array_column($alerts, 'nouvelles_offres')) > 1 ? 's' : '' ?> offre<?= array_sum(array_column($alerts, 'nouvelles_offres')) > 1 ? 's' : '' ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
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

.stats-card {
    background: rgba(255,255,255,.74);
    border: 1px solid rgba(99,102,241,.16);
    border-radius: var(--r-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.stats-card h3 {
    font-weight: 900;
    margin-bottom: 1.25rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1.5rem;
}

.stats-grid .stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(37,99,235,.05);
    border: 1px solid rgba(37,99,235,.12);
    border-radius: var(--r);
}

.stats-grid .stat-number {
    font-size: 2rem;
    font-weight: 950;
    background: linear-gradient(135deg, var(--primary), var(--primary2));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.stats-grid .stat-label {
    color: var(--muted);
    font-weight: 700;
    font-size: .9rem;
    margin-top: .25rem;
}
</style>

<?php include 'footer.php'; ?>
