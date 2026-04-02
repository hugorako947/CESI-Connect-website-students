<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        
        <!-- En-tête du dashboard -->
        <div class="page-header page-header-spaced">
            <div>
                <h1> Dashboard Pilote</h1>
                <p class="page-subtitle">
                    Suivez l'activité de vos étudiants : candidatures, wishlists et statistiques.
                </p>
            </div>
            <div class="page-header-actions">
                <a href="index.php?route=profil&tab=pilot" class="btn btn-outline btn-page-action">← Quitter</a>
                <a href="index.php?route=pilote-statistiques" class="btn btn-outline btn-page-action">Statistiques globales</a>
            </div>
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

        <!-- Statistiques rapides -->
        <div class="stats page-section-spacing">
            <div class="stat-item">
                <span class="number"><?= count($etudiants) ?></span>
                <span class="label">Étudiant<?= count($etudiants) > 1 ? 's' : '' ?> suivi<?= count($etudiants) > 1 ? 's' : '' ?></span>
            </div>
            <div class="stat-item">
                <span class="number"><?= array_sum(array_column($etudiants, 'nb_candidatures')) ?></span>
                <span class="label">Candidature<?= array_sum(array_column($etudiants, 'nb_candidatures')) > 1 ? 's' : '' ?> total<?= array_sum(array_column($etudiants, 'nb_candidatures')) > 1 ? 'es' : 'e' ?></span>
            </div>
            <div class="stat-item">
                <span class="number"><?= array_sum(array_column($etudiants, 'nb_wishlist')) ?></span>
                <span class="label">Offre<?= array_sum(array_column($etudiants, 'nb_wishlist')) > 1 ? 's' : '' ?> en wishlist</span>
            </div>
        </div>

        <!-- Liste des étudiants -->
        <?php if (empty($etudiants)): ?>
            <div class="empty-state">
                <div class="empty-icon"></div>
                <h2>Aucun étudiant à suivre</h2>
                <p>Il n'y a actuellement aucun étudiant inscrit sur la plateforme.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-etudiants">
                    <thead>
                        <tr>
                            <th>Nom & Prénom</th>
                            <th>Email</th>
                            <th class="text-center">Wishlist</th>
                            <th class="text-center">Candidatures</th>
                            <th class="text-center">En attente</th>
                            <th class="text-center">Acceptées</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($etudiants as $etudiant): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($etudiant['email']) ?></td>
                                <td class="text-center">
                                    <?php if ($etudiant['nb_wishlist'] > 0): ?>
                                        <span class="badge badge-purple">
                                             <?= $etudiant['nb_wishlist'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted-inline">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($etudiant['nb_candidatures'] > 0): ?>
                                        <span class="badge badge-blue">
                                             <?= $etudiant['nb_candidatures'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted-inline">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($etudiant['nb_en_attente'] > 0): ?>
                                        <span class="stat-number-pending"><?= $etudiant['nb_en_attente'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted-inline">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($etudiant['nb_acceptees'] > 0): ?>
                                        <span class="stat-number-success">✓ <?= $etudiant['nb_acceptees'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted-inline">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="index.php?route=pilote-etudiant&id=<?= (int) $etudiant['id'] ?>" 
                                       class="btn btn-outline btn-sm">
                                         Voir le détail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include 'footer.php'; ?>
