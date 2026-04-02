<?php include 'header.php'; ?>

<section class="section">
    <div class="container">
        
        <!-- En-tête avec retour -->
        <div class="page-header page-header-spaced">
            <div>
                <a href="index.php?route=pilote-dashboard" class="btn btn-outline btn-back">
                    ← Retour au dashboard
                </a>
                <h1>👤 <?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></h1>
                <p class="page-subtitle">
                     <?= htmlspecialchars($etudiant['email']) ?>
                </p>
            </div>
        </div>

        <!-- Statistiques de l'étudiant -->
        <div class="stats page-section-spacing">
            <div class="stat-item">
                <span class="number"><?= count($wishlist) ?></span>
                <span class="label">Offre<?= count($wishlist) > 1 ? 's' : '' ?> en wishlist</span>
            </div>
            <div class="stat-item">
                <span class="number"><?= count($candidatures) ?></span>
                <span class="label">Candidature<?= count($candidatures) > 1 ? 's' : '' ?></span>
            </div>
            <div class="stat-item">
                <span class="number"><?= count(array_filter($candidatures, fn($c) => $c['statut'] === 'En attente')) ?></span>
                <span class="label">En attente</span>
            </div>
            <div class="stat-item">
                <span class="number"><?= count(array_filter($candidatures, fn($c) => $c['statut'] === 'Acceptée')) ?></span>
                <span class="label">Acceptée<?= count(array_filter($candidatures, fn($c) => $c['statut'] === 'Acceptée')) > 1 ? 's' : '' ?></span>
            </div>
        </div>

        <!-- SECTION WISHLIST -->
        <div class="section-card section-card-spacing">
            <h2 class="section-card-title"> Wishlist (<?= count($wishlist) ?>)</h2>
            
            <?php if (empty($wishlist)): ?>
                <p class="text-muted-block">Aucune offre en wishlist pour le moment.</p>
            <?php else: ?>
                <div class="cards-container">
                    <?php foreach ($wishlist as $offre): ?>
                        <article class="card">
                            <div class="card-header">
                                <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                                <span class="tag"><?= htmlspecialchars($offre['type_contrat'] ?? 'Offre') ?></span>
                            </div>
                            <p class="company"><?= htmlspecialchars($offre['entreprise_nom'] ?? '') ?></p>
                            <p class="desc">
                                <?= htmlspecialchars(substr($offre['description'], 0, 120)) ?>...
                            </p>
                            <div class="card-footer">
                                <span class="date">
                                    Publié le <?= date('d/m/Y', strtotime($offre['date_publication'])) ?>
                                </span>
                                <a href="index.php?route=offre-details&id=<?= (int) $offre['id'] ?>" 
                                   class="btn-details">
                                    Voir l'offre
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- SECTION CANDIDATURES -->
        <div class="section-card">
            <h2 class="section-card-title"> Candidatures (<?= count($candidatures) ?>)</h2>
            
            <?php if (empty($candidatures)): ?>
                <p class="text-muted-block">Aucune candidature envoyée pour le moment.</p>
            <?php else: ?>
                <div class="candidatures-list">
                    <?php foreach ($candidatures as $candidature): ?>
                        <div class="candidature-item">
                            <div class="candidature-header">
                                <h3><?= htmlspecialchars($candidature['offre_titre']) ?></h3>
                                <span class="statut statut-<?= strtolower(str_replace(' ', '-', $candidature['statut'])) ?>">
                                    <?php
                                    echo $statutIcon[$candidature['statut']] ?? '';
                                    ?>
                                    <?= htmlspecialchars($candidature['statut']) ?>
                                </span>
                            </div>
                            <div class="candidature-body">
                                <p><strong> Entreprise :</strong> <?= htmlspecialchars($candidature['entreprise_nom']) ?></p>
                                <p><strong> Date de candidature :</strong> <?= date('d/m/Y à H:i', strtotime($candidature['date_candidature'])) ?></p>
                                <p><strong> Documents :</strong> CV + Lettre de motivation</p>
                            </div>
                            <div class="candidature-footer">
                                <a href="index.php?route=offre-details&id=<?= (int) $candidature['id_offre'] ?>" 
                                   class="btn btn-outline">
                                    Voir l'offre
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<?php include 'footer.php'; ?>
