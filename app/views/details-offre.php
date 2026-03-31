<?php include 'header.php'; ?>
<!-- Vue de détails d'une offre de stage/alternance -->
<section class="details-offre-section">
    <div class="container">
        
        <!-- Messages d'erreur ou de succès -->
        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- En-tête de l'offre -->
        <div class="offre-header">
            <div class="offre-header-content">
                <h1><?= htmlspecialchars($offer['titre']) ?></h1>
                <p class="entreprise-nom">
                    <strong>🏢 <?= htmlspecialchars($offer['entreprise_nom']) ?></strong>
                </p>
                <div class="offre-meta">
                    <span class="meta-item">
                        📅 Publié le <?= date('d/m/Y', strtotime($offer['date_publication'])) ?>
                    </span>
                    <?php if ($candidaturesCount > 0): ?>
                        <span class="meta-item">
                            👥 <?= $candidaturesCount ?> candidature(s)
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="offre-actions">
                    <?php if (!empty($isInWishlist)): ?>
                        <a href="index.php?route=wishlist-remove&id=<?= (int) $offer['id'] ?>&redirect=<?= urlencode('index.php?route=offre-details&id=' . (int) $offer['id']) ?>"
                           class="btn btn-outline"
                           title="Retirer de ma wish-list"
                           aria-label="Retirer de ma wish-list">
                            ❤ Retirer de la wish-list
                        </a>
                    <?php else: ?>
                        <a href="index.php?route=wishlist-add&id=<?= (int) $offer['id'] ?>&redirect=<?= urlencode('index.php?route=offre-details&id=' . (int) $offer['id']) ?>"
                           class="btn btn-outline"
                           title="Ajouter à ma wish-list"
                           aria-label="Ajouter à ma wish-list">
                            ♡ Ajouter à la wish-list
                        </a>
                    <?php endif; ?>

                    <?php if ($hasApplied): ?>
                        <span class="badge badge-success">
                            ✓ Vous avez déjà candidaté
                        </span>
                    <?php else: ?>
                        <a href="index.php?route=candidater&offre=<?= htmlspecialchars($offer['id']) ?>" 
                           class="btn btn-primary btn-large">
                            📄 Candidater maintenant
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="offre-actions">
                    <a href="index.php?route=connexion" class="btn btn-outline btn-large">
                        🔒 Connectez-vous pour candidater
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Contenu principal -->
        <div class="offre-body">
            
            <!-- Description de l'offre -->
            <div class="offre-card">
                <h2>📋 Description de l'offre</h2>
                <div class="offre-description">
                    <?= nl2br(htmlspecialchars($offer['description'])) ?>
                </div>
            </div>

            <!-- Rémunération -->
            <?php if (!empty($offer['remuneration'])): ?>
                <div class="offre-card">
                    <h2>💰 Rémunération</h2>
                    <p class="remuneration-value">
                        <?= htmlspecialchars($offer['remuneration']) ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Compétences -->
            <?php if (!empty($offer['competences'])): ?>
                <div class="offre-card">
                    <h2>🛠️ Compétences requises</h2>
                    <?php
                        $competences = array_filter(array_map('trim', explode(',', $offer['competences'])));
                    ?>
                    <?php if (!empty($competences)): ?>
                        <ul class="competences-list">
                            <?php foreach ($competences as $competence): ?>
                                <li><?= htmlspecialchars($competence) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p><?= nl2br(htmlspecialchars($offer['competences'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Informations sur l'entreprise -->
            <div class="entreprise-card">
                <h2>🏢 À propos de l'entreprise</h2>
                <h3><?= htmlspecialchars($offer['entreprise_nom']) ?></h3>
                
                <?php if (!empty($offer['entreprise_description'])): ?>
                    <p class="entreprise-description">
                        <?= nl2br(htmlspecialchars($offer['entreprise_description'])) ?>
                    </p>
                <?php endif; ?>

                <div class="entreprise-contact">
                    <?php if (!empty($offer['entreprise_email'])): ?>
                        <p>
                            <strong>📧 Email :</strong> 
                            <a href="mailto:<?= htmlspecialchars($offer['entreprise_email']) ?>">
                                <?= htmlspecialchars($offer['entreprise_email']) ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($offer['entreprise_telephone'])): ?>
                        <p>
                            <strong>📞 Téléphone :</strong> 
                            <?= htmlspecialchars($offer['entreprise_telephone']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bouton de candidature en bas (mobile) -->
            <?php if (isset($_SESSION['user_id']) && !$hasApplied): ?>
                <div class="candidature-footer">
                    <a href="index.php?route=candidater&offre=<?= htmlspecialchars($offer['id']) ?>" 
                       class="btn btn-primary btn-block btn-large">
                        📄 Postuler à cette offre
                    </a>
                </div>
            <?php endif; ?>

            <!-- Bouton retour -->
            <div class="navigation-footer">
                <a href="index.php?route=offres" class="btn btn-secondary">
                    ← Retour aux offres
                </a>
            </div>

        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
