<?php include 'header.php'; ?>

<section class="section">
    <div class="section-header">
        <h1>Gestion des Entreprises</h1>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 1): ?>
            <a href="index.php?route=form-entreprise" class="btn-primary">Ajouter une entreprise</a>
        <?php endif; ?>
    </div>

    <div class="enterprise-grid">
        <?php if (!empty($entreprises)): ?>
            <?php foreach ($entreprises as $ent): ?>
                <article class="enterprise-card">
                    <div class="enterprise-card-head">
                        <div class="enterprise-avatar"><?= strtoupper(substr((string) ($ent['nom'] ?? 'E'), 0, 2)) ?></div>
                        <div>
                            <h3><?= htmlspecialchars($ent['nom'] ?? '') ?></h3>
                            <p><?= htmlspecialchars($ent['email_contact'] ?? 'Email non renseigné') ?></p>
                        </div>
                    </div>

                    <div class="enterprise-card-meta">
                        <span>☎ <?= htmlspecialchars($ent['telephone'] ?? 'N/A') ?></span>
                    </div>

                    <div class="enterprise-card-actions">
                        <a href="index.php?route=details-entreprise&id=<?= (int) ($ent['id'] ?? 0) ?>" class="btn-outline">Voir la fiche</a>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 1): ?>
                            <a href="index.php?route=form-entreprise&id=<?= (int) ($ent['id'] ?? 0) ?>" class="btn-primary">Modifier</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune entreprise trouvée dans la base de données.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
