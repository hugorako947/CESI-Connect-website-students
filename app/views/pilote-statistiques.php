<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        
        <!-- En-tête -->
        <div class="page-header page-header-spaced">
            <div>
                <h1>Statistiques globales</h1>
                <p class="page-subtitle">
                    Vue d'ensemble de l'activité des étudiants sur la plateforme.
                </p>
            </div>
            <div class="page-header-actions">
                <a href="index.php?route=profil&tab=pilote" class="btn btn-outline btn-page-action">← Quitter</a>
                <a href="index.php?route=pilote-dashboard" class="btn btn-outline btn-page-action">Statistiques détaillées</a>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="stats-grid-large stats-grid-large-spacing">
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-value"><?= $stats['nb_etudiants'] ?></div>
                <div class="stat-label">Étudiant<?= $stats['nb_etudiants'] > 1 ? 's' : '' ?> inscrit<?= $stats['nb_etudiants'] > 1 ? 's' : '' ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-value"><?= $stats['nb_candidatures_total'] ?></div>
                <div class="stat-label">Candidature<?= $stats['nb_candidatures_total'] > 1 ? 's' : '' ?> total<?= $stats['nb_candidatures_total'] > 1 ? 'es' : 'e' ?></div>
            </div>

            <div class="stat-card stat-pending">
                <div class="stat-icon"></div>
                <div class="stat-value"><?= $stats['nb_en_attente'] ?></div>
                <div class="stat-label">En attente</div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-icon"></div>
                <div class="stat-value"><?= $stats['nb_acceptees'] ?></div>
                <div class="stat-label">Acceptée<?= $stats['nb_acceptees'] > 1 ? 's' : '' ?></div>
            </div>

            <div class="stat-card stat-danger">
                <div class="stat-icon"></div>
                <div class="stat-value"><?= $stats['nb_refusees'] ?></div>
                <div class="stat-label">Refusée<?= $stats['nb_refusees'] > 1 ? 's' : '' ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-value">
                    <?php 
                    $tauxAcceptation = $stats['nb_candidatures_total'] > 0 
                        ? round(($stats['nb_acceptees'] / $stats['nb_candidatures_total']) * 100) 
                        : 0;
                    echo $tauxAcceptation;
                    ?>%
                </div>
                <div class="stat-label">Taux d'acceptation</div>
            </div>
        </div>

        <!-- Top des offres les plus populaires -->
        <div class="section-card">
            <h2 class="section-card-title section-card-title-lg"> Top 10 des offres les plus populaires</h2>
            
            <?php if (empty($topOffres)): ?>
                <p class="text-muted-block">Aucune candidature pour le moment.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table-top-offres">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Titre de l'offre</th>
                                <th>Entreprise</th>
                                <th class="text-center">Nombre de candidatures</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topOffres as $index => $offre): ?>
                                <tr>
                                    <td>
                                        <div class="position-badge position-<?= $index + 1 ?>">
                                            #<?= $index + 1 ?>
                                        </div>
                                    </td>
                                    <td><strong><?= htmlspecialchars($offre['titre']) ?></strong></td>
                                    <td><?= htmlspecialchars($offre['entreprise_nom']) ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-blue">
                                             <?= $offre['nb_candidatures'] ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="index.php?route=offre-details&id=<?= (int) $offre['id'] ?>" 
                                           class="btn btn-outline btn-sm">
                                            Voir l'offre
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php include 'footer.php'; ?>
