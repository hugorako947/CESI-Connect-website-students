<?php include 'header.php'; ?>
<section class="mes-candidatures-section">
    <div class="container">
        <h1>Mon dashboard</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['erreur']) ?>
            </div>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <div class="auth-card" style="margin-bottom: 24px;">
            <h2>Mes informations personnelles</h2>
            <form action="index.php?route=profil" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required
                           value="<?= htmlspecialchars($user['nom'] ?? ($_SESSION['user_nom'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required
                           value="<?= htmlspecialchars($user['prenom'] ?? ($_SESSION['user_prenom'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required
                           value="<?= htmlspecialchars($user['email'] ?? ($_SESSION['user_email'] ?? '')) ?>">
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>

        <div class="auth-card" style="margin-bottom: 24px;">
            <h2>Ma wish-list</h2>
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

        <div class="auth-card" style="margin-bottom: 24px;">
            <h2>Entreprises auxquelles j'ai postulé</h2>
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
                                <p>
                                    <strong>📅 Date :</strong>
                                    <?= date('d/m/Y à H:i', strtotime($candidature['date_candidature'])) ?>
                                </p>
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

        <div class="profil-actions">
            <a href="index.php?route=deconnexion" class="btn-deconnexion">Déconnexion</a>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
