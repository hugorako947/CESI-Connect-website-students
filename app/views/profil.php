<?php include 'header.php'; ?>

<style>
.profil-tabs-bar {
    display: flex;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 32px;
    background: #fff;
    position: sticky;
    top: 0;
    z-index: 10;
}
.profil-tab-btn {
    flex: 1;
    padding: 16px 24px;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    font-size: 1rem;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    transition: color 0.2s, border-color 0.2s;
}
.profil-tab-btn:first-child  { text-align: left;   padding-left: 0; }
.profil-tab-btn:nth-child(2) { text-align: center; }
.profil-tab-btn:last-child   { text-align: right;  padding-right: 0; }
.profil-tab-btn:hover { color: #111827; }
.profil-tab-btn.active { color: #4f46e5; border-bottom-color: #4f46e5; }
.profil-panel        { display: none; }
.profil-panel.active { display: block; }
</style>

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

        <!-- ── Barre d'onglets ── -->
        <nav class="profil-tabs-bar" role="tablist">
            <button class="profil-tab-btn active" id="tab-dashboard"
                    role="tab" aria-selected="true" aria-controls="panel-dashboard"
                    onclick="switchTab('dashboard')">Dashboard</button>

            <button class="profil-tab-btn" id="tab-wishlist"
                    role="tab" aria-selected="false" aria-controls="panel-wishlist"
                    onclick="switchTab('wishlist')">Wish-list</button>

            <button class="profil-tab-btn" id="tab-candidatures"
                    role="tab" aria-selected="false" aria-controls="panel-candidatures"
                    onclick="switchTab('candidatures')">Candidatures envoyées</button>
        </nav>

        <!-- ══ PANNEAU 1 — DASHBOARD ══ -->
        <div id="panel-dashboard" class="profil-panel active" role="tabpanel" aria-labelledby="tab-dashboard">
            <h1>Mon dashboard</h1>
            <div class="auth-card" style="margin-bottom: 24px;">
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
            <div class="profil-actions">
                <a href="index.php?route=deconnexion" class="btn-deconnexion">Déconnexion</a>
            </div>
        </div>

        <!-- ══ PANNEAU 2 — WISH-LIST ══ -->
        <div id="panel-wishlist" class="profil-panel" role="tabpanel" aria-labelledby="tab-wishlist">
            <h1>Ma wish-list</h1>
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

        <!-- ══ PANNEAU 3 — CANDIDATURES ENVOYÉES ══ -->
        <div id="panel-candidatures" class="profil-panel" role="tabpanel" aria-labelledby="tab-candidatures">
            <h1>Candidatures envoyées</h1>
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
                                    <p><strong>📅 Date :</strong> <?= date('d/m/Y à H:i', strtotime($candidature['date_candidature'])) ?></p>
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
    });
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('tab-' + tab).setAttribute('aria-selected', 'true');
    document.getElementById('panel-' + tab).classList.add('active');

    // Mémorise l'onglet dans l'URL sans rechargement
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    history.replaceState(null, '', url);
}

// Restaure l'onglet actif depuis l'URL après une redirection PHP
(function () {
    const tab = new URLSearchParams(window.location.search).get('tab');
    if (tab && ['dashboard', 'wishlist', 'candidatures'].includes(tab)) {
        switchTab(tab);
    }
})();
</script>

<?php include 'footer.php'; ?>
