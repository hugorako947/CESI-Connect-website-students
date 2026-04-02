<?php include 'header.php'; ?>
<?php $isPilote = isset($_SESSION['user_role']) && (int) $_SESSION['user_role'] === 2; ?>

<section class="mes-candidatures-section">
    <div class="container">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['erreur']) ?></div>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <nav class="profil-tabs-bar" role="tablist">
            <?php if ($isPilote): ?>
                <button class="profil-tab-btn active" id="tab-dashboard"
                        role="tab" aria-selected="true" aria-controls="panel-dashboard"
                        onclick="switchTab('dashboard')">Mon dashboard</button>

                <button class="profil-tab-btn" id="tab-pilote"
                        role="tab" aria-selected="false" aria-controls="panel-pilote"
                        onclick="switchTab('pilote')">Dashboard Pilote</button>
            <?php else: ?>
                <button class="profil-tab-btn active" id="tab-dashboard"
                        role="tab" aria-selected="true" aria-controls="panel-dashboard"
                        onclick="switchTab('dashboard')">Dashboard</button>

                <button class="profil-tab-btn" id="tab-wishlist"
                        role="tab" aria-selected="false" aria-controls="panel-wishlist"
                        onclick="switchTab('wishlist')">Wish-list</button>

                <button class="profil-tab-btn" id="tab-alertes"
                        role="tab" aria-selected="false" aria-controls="panel-alertes"
                        onclick="switchTab('alertes')">Mes alertes</button>

                <button class="profil-tab-btn" id="tab-candidatures"
                        role="tab" aria-selected="false" aria-controls="panel-candidatures"
                        onclick="switchTab('candidatures')">Candidatures</button>
            <?php endif; ?>
        </nav>

        <div id="panel-dashboard" class="profil-panel active" role="tabpanel" aria-labelledby="tab-dashboard" style="display:block;">
            <h1><?= $isPilote ? '👤 Mon dashboard' : '📒 Mon dashboard' ?></h1>

            <div class="auth-card" style="margin-bottom: 24px; margin-top: 24px;">
                <h2>Mes informations personnelles</h2>
                <form action="index.php?route=profil" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" required
                               value="<?= htmlspecialchars($user['prenom'] ?? ($_SESSION['user_prenom'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required
                               value="<?= htmlspecialchars($user['nom'] ?? ($_SESSION['user_nom'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required
                               value="<?= htmlspecialchars($user['email'] ?? ($_SESSION['user_email'] ?? '')) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
            <div class="form-footer" style="margin-top: 30px; text-align: left;">
                <a href="index.php?route=reinitialiser-mot-de-passe" class="link-change-password">Changer de mot de passe</a>
            </div>
            <div class="profil-actions" style="margin-top: 30px;">
                <a href="index.php?route=supprimer-compte"
                class="btn-deconnexion"
                onclick="return confirm('⚠️ ATTENTION : Cette action supprimera définitivement votre compte et toutes vos données. Confirmer ?')">
                Supprimer mon compte
                </a>
            </div>
        </div>

        <?php if ($isPilote): ?>
        <div id="panel-pilote" class="profil-panel" role="tabpanel" aria-labelledby="tab-pilote">
            <div class="page-header" style="margin-bottom:2rem;">
                <div>
                    <h1>📊 Dashboard Pilote</h1>
                    <p style="color:var(--muted); margin-top:0.5rem;">
                        Accédez ici à votre espace de suivi pilote. Ce tableau de bord n'est plus affiché dans la navigation principale.
                    </p>
                </div>
            </div>

            <div class="stats" style="margin-bottom:2rem; justify-content:flex-start;">
                <div class="stat-item">
                    <span class="number"><?= (int) ($pilotStats['nb_etudiants'] ?? 0) ?></span>
                    <span class="label">Étudiant<?= ((int) ($pilotStats['nb_etudiants'] ?? 0)) > 1 ? 's' : '' ?> suivi<?= ((int) ($pilotStats['nb_etudiants'] ?? 0)) > 1 ? 's' : '' ?></span>
                </div>
                <div class="stat-item">
                    <span class="number"><?= (int) ($pilotStats['nb_candidatures_total'] ?? 0) ?></span>
                    <span class="label">Candidature<?= ((int) ($pilotStats['nb_candidatures_total'] ?? 0)) > 1 ? 's' : '' ?> total<?= ((int) ($pilotStats['nb_candidatures_total'] ?? 0)) > 1 ? 'es' : 'e' ?></span>
                </div>
                <div class="stat-item">
                    <span class="number"><?= (int) ($pilotStats['nb_en_attente'] ?? 0) ?></span>
                    <span class="label">En attente</span>
                </div>
                <div class="stat-item">
                    <span class="number"><?= (int) ($pilotStats['nb_acceptees'] ?? 0) ?></span>
                    <span class="label">Acceptée<?= ((int) ($pilotStats['nb_acceptees'] ?? 0)) > 1 ? 's' : '' ?></span>
                </div>
            </div>

            <div class="auth-card" style="text-align:left; margin-bottom:1.5rem;">
                <h2 style="margin-bottom:1rem;">Accès au suivi pilote</h2>
                <p style="color:var(--muted); margin-bottom:1.25rem;">
                    Ouvrez votre espace de pilotage pour consulter la liste des étudiants, leurs candidatures, leurs wish-lists et les statistiques globales.
                </p>
                <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
                    <a href="index.php?route=pilote-dashboard" class="btn btn-primary">Ouvrir le dashboard pilote</a>
                    <a href="index.php?route=pilote-statistiques" class="btn btn-outline">Voir les statistiques globales</a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div id="panel-wishlist" class="profil-panel" role="tabpanel" aria-labelledby="tab-wishlist">
            <h1>❤️ Ma wish-list</h1>
            <div class="auth-card">
                <?php if (empty($wishlist_offers)): ?>
                    <p>Vous n'avez aucune offre en favori pour le moment.</p>
                <?php else: ?>
                    <div class="candidatures-list">
                        <?php foreach ($wishlist_offers as $offre): ?>
                            <div class="candidature-item">
                                <div class="candidature-header">
                                    <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                                </div>
                                <div class="candidature-body">
                                    <p><strong>🏢 Entreprise :</strong> <?= htmlspecialchars($offre['entreprise_nom']) ?></p>
                                </div>
                                <div class="candidature-footer">
                                    <a href="index.php?route=offre-details&id=<?= (int) $offre['id'] ?>" class="btn btn-outline">
                                        Voir l'offre
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="panel-alertes" class="profil-panel" role="tabpanel" aria-labelledby="tab-alertes">
            <div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h1>🔔 Mes alertes</h1>
                    <p style="color:var(--muted); margin-top:0.5rem;">
                        Créez des alertes personnalisées et soyez notifié des nouvelles offres correspondant à vos critères.
                    </p>
                </div>
                <a href="index.php?route=alerte-form" class="btn btn-primary">
                    ➕ Créer une alerte
                </a>
            </div>

            <?php if (empty($alerts)): ?>
                <div class="empty-state">
                    <h2>Aucune alerte configurée</h2>
                    <p>Créez votre première alerte pour recevoir des notifications sur les nouvelles offres qui correspondent à vos critères.</p>
                    <a href="index.php?route=alerte-form" class="btn btn-primary" style="margin-top:1rem;">
                        Créer ma première alerte
                    </a>
                </div>
            <?php else: ?>
                <div class="candidatures-list">
                    <?php foreach ($alerts as $alert): ?>
                        <div class="candidature-item alert-item" style="position:relative;">

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
                                        🔔 <?= $alert['nouvelles_offres'] ?> nouvelle<?= $alert['nouvelles_offres'] > 1 ? 's' : '' ?> offre<?= $alert['nouvelles_offres'] > 1 ? 's' : '' ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="candidature-body" style="margin-top:1rem;">
                                <div style="display:flex; flex-wrap:wrap; gap:.75rem; margin-bottom:.75rem;">
                                    <?php if (!empty($alert['mot_cle'])): ?>
                                        <span class="filter-tag">🔍 <?= htmlspecialchars($alert['mot_cle']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($alert['ville'])): ?>
                                        <span class="filter-tag">📍 <?= htmlspecialchars($alert['ville']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($alert['domaine'])): ?>
                                        <span class="filter-tag">🏷 <?= htmlspecialchars($alert['domaine']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($alert['type_contrat'])): ?>
                                        <span class="filter-tag">📄 <?= ucfirst($alert['type_contrat']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($alert['remuneration_min']) && $alert['remuneration_min'] > 0): ?>
                                        <span class="filter-tag">💶 Min. <?= number_format($alert['remuneration_min'], 0, ',', ' ') ?>€</span>
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
                                    ✏️ Modifier
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
                                    🗑 Supprimer
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="stats-card" style="margin-top:2rem;">
                    <h3>📊 Statistiques</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number"><?= count($alerts) ?></div>
                            <div class="stat-label">Alerte<?= count($alerts) > 1 ? 's' : '' ?> créée<?= count($alerts) > 1 ? 's' : '' ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= count(array_filter($alerts, fn($a) => $a['actif'] == 1)) ?></div>
                            <div class="stat-label">Active<?= count(array_filter($alerts, fn($a) => $a['actif'] == 1)) > 1 ? 's' : '' ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= array_sum(array_column($alerts, 'nouvelles_offres')) ?></div>
                            <div class="stat-label">Nouvelle<?= array_sum(array_column($alerts, 'nouvelles_offres')) > 1 ? 's' : '' ?> offre<?= array_sum(array_column($alerts, 'nouvelles_offres')) > 1 ? 's' : '' ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

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
            .stats-card {
                background: rgba(255,255,255,.74);
                border: 1px solid rgba(99,102,241,.16);
                border-radius: var(--r-lg);
                padding: 1.5rem;
                box-shadow: var(--shadow-sm);
            }
            .stats-card h3 { font-weight: 900; margin-bottom: 1.25rem; }
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
        </div>

        <div id="panel-candidatures" class="profil-panel" role="tabpanel" aria-labelledby="tab-candidatures">
            <h1>🚀 Candidatures envoyées</h1>

            <?php
                $nbTotal     = count($candidatures);
                $nbAttente   = count(array_filter($candidatures, fn($c) => $c['statut'] === 'En attente'));
                $nbAcceptees = count(array_filter($candidatures, fn($c) => $c['statut'] === 'Acceptée'));
            ?>

            <?php if ($nbTotal > 0): ?>
                <div class="candidatures-stats-bar">
                    <span class="cstat-pill cstat-total">
                        <?= $nbTotal ?> Candidature<?= $nbTotal > 1 ? 's' : '' ?>
                    </span>
                    <span class="cstat-pill cstat-attente">
                        <?= $nbAttente ?> En attente<?= $nbAttente > 1 ? 's' : '' ?>
                    </span>
                    <span class="cstat-pill cstat-acceptee">
                        <?= $nbAcceptees ?> Acceptée<?= $nbAcceptees > 1 ? 's' : '' ?>
                    </span>
                </div>
            <?php endif; ?>

            <div class="auth-card">
                <?php if (empty($candidatures)): ?>
                    <p>Vous n'avez pas encore postulé.</p>
                <?php else: ?>
                    <div class="candidatures-list">
                        <?php foreach ($candidatures as $candidature): ?>
                            <div class="candidature-item">
                                <div class="candidature-header">
                                    <h3><?= htmlspecialchars($candidature['offre_titre']) ?></h3>
                                    <span class="statut statut-<?= strtolower(str_replace(' ', '-', $candidature['statut'])) ?>">
                                        <?= htmlspecialchars($candidature['statut']) ?>
                                    </span>
                                </div>
                                <div class="candidature-body">
                                    <p><strong>🏢 Entreprise :</strong> <?= htmlspecialchars($candidature['entreprise_nom']) ?></p>
                                    <p><strong>📅 Date de candidature :</strong> <?= date('d/m/Y à H:i', strtotime($candidature['date_candidature'])) ?></p>
                                    <p><strong>📎 Documents envoyés :<br>✓ CV<br>✓ Lettre de motivation</strong></p>
                                </div>
                                <div class="candidature-footer">
                                    <a href="index.php?route=offre-details&id=<?= (int) $candidature['id_offre'] ?>" class="btn btn-outline">
                                        Voir l'offre
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<script>
function switchTab(tab) {
    document.querySelectorAll('.profil-tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
    });
    document.querySelectorAll('.profil-panel').forEach(panel => {
        panel.classList.remove('active');
        panel.style.display = 'none';
    });

    const activeTab = document.getElementById('tab-' + tab);
    const activePanel = document.getElementById('panel-' + tab);

    if (!activeTab || !activePanel) {
        return;
    }

    activeTab.classList.add('active');
    activeTab.setAttribute('aria-selected', 'true');
    activePanel.classList.add('active');
    activePanel.style.display = 'block';

    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    history.replaceState(null, '', url);
}

(function () {
    document.querySelectorAll('.profil-panel').forEach((panel, index) => {
        panel.style.display = index === 0 ? 'block' : 'none';
    });

    const allowedTabs = <?= json_encode($isPilote ? ['dashboard', 'pilote'] : ['dashboard', 'wishlist', 'alertes', 'candidatures']) ?>;
    const tab = new URLSearchParams(window.location.search).get('tab');

    if (tab && allowedTabs.includes(tab)) {
        switchTab(tab);
    }
})();
</script>

<?php include 'footer.php'; ?>
