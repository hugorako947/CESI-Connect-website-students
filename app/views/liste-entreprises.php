<?php include 'header.php'; ?>

<section class="section">
    <!-- Messages de succès / erreur -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['erreur'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_SESSION['erreur']) ?></div>
        <?php unset($_SESSION['erreur']); ?>
    <?php endif; ?>

    <div class="section-header" style="align-items:center; margin-bottom:1.5rem;">
        <h1 style="font-weight:950; font-size:1.6rem;">Entreprises partenaires</h1>
        <?php if (isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1): ?>
            <a href="index.php?route=form-entreprise"
               class="btn btn-primary"
               style="padding:.75rem 1.35rem; font-size:1rem; font-weight:900; border-radius:14px;
                      background:linear-gradient(135deg,#2563eb,#7c3aed); color:#fff;
                      box-shadow:0 12px 28px rgba(37,99,235,.25); white-space:nowrap;">
                ➕ Ajouter une entreprise
            </a>
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
