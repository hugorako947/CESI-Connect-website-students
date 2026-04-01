<?php include 'header.php'; ?>

<!-- Vue de l'historique des candidatures de l'utilisateur -->
<section class="mes-candidatures-section">
    <div class="container">
        <h1>📋 Mes candidatures</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✅ <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (empty($candidatures)): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h2>Aucune candidature pour le moment</h2>
                <p>Vous n'avez pas encore postulé à des offres.</p>
                <a href="index.php?route=offres" class="btn btn-primary">
                    Découvrir les offres
                </a>
            </div>
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
                            <p class="entreprise">
                                <strong>🏢 Entreprise :</strong> <?= htmlspecialchars($candidature['entreprise_nom']) ?>
                            </p>
                            <p class="date">
                                <strong>📅 Date de candidature :</strong> 
                                <?= date('d/m/Y à H:i', strtotime($candidature['date_candidature'])) ?>
                            </p>
                            
                            <div class="documents">
                                <strong>📎 Documents envoyés :</strong>
                                <ul>
                                    <li>✓ CV</li>
                                    <li>✓ Lettre de motivation</li>
                                </ul>
                            </div>
                        </div>

                        <div class="candidature-footer">
                            <a href="index.php?route=offre-details&id=<?= htmlspecialchars($candidature['id_offre']) ?>" 
                               class="btn btn-outline">
                                Voir l'offre
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="stats-card">
                <h3>📊 Statistiques</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?= count($candidatures) ?></div>
                        <div class="stat-label">Candidature(s)</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">
                            <?= count(array_filter($candidatures, fn($c) => $c['statut'] === 'En attente')) ?>
                        </div>
                        <div class="stat-label">En attente</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">
                            <?= count(array_filter($candidatures, fn($c) => $c['statut'] === 'Acceptée')) ?>
                        </div>
                        <div class="stat-label">Acceptée(s)</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
