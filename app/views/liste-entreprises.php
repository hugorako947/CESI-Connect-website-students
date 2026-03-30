<?php include 'header.php'; ?>

<section class="dashboard-container">
    <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Gestion des Entreprises</h1>
        <a href="index.php?route=form-entreprise" class="btn-primary">Ajouter une entreprise</a>
    </div>

    <div class="table-responsive">
        <table class="table-candidatures" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--border);">
                    <th style="padding: 12px;">Nom</th>
                    <th style="padding: 12px;">Email</th>
                    <th style="padding: 12px;">Téléphone</th>
                    <th style="padding: 12px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($entreprises)): ?>
                    <?php foreach($entreprises as $ent): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;"><?= htmlspecialchars($ent['nom']) ?></td>
                            <td style="padding: 12px;"><?= htmlspecialchars($ent['email_contact']) ?></td>
                            <td style="padding: 12px;"><?= htmlspecialchars($ent['telephone'] ?? 'N/A') ?></td>
                            <td style="padding: 12px;">
                                <a href="index.php?route=form-entreprise&id=<?= $ent['id'] ?>" style="color: var(--primary);">Modifier</a> 
                                <span style="color: var(--border);">|</span>
                                <a href="index.php?route=delete-entreprise&id=<?= $ent['id'] ?>" style="color: #dc2626;" onclick="return confirm('Supprimer cette entreprise ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center;">Aucune entreprise trouvée dans la base de données.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include 'footer.php'; ?>
