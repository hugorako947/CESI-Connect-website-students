<?php include 'header.php'; ?>
        <section class="auth-container">
            <div class="auth-card">
                <h1>Profil</h1>
                <?php if (!empty($_SESSION['user_prenom']) || !empty($_SESSION['user_nom'])): ?>
                    <p class="profil-user">
                        Connecté en tant que
                        <strong><?= htmlspecialchars(trim(($_SESSION['user_prenom'] ?? '') . ' ' . ($_SESSION['user_nom'] ?? ''))) ?></strong>
                    </p>
                <?php endif; ?>

                <div class="profil-actions">
                    <a href="index.php?route=deconnexion" class="btn-deconnexion">Déconnexion</a>
                </div>
            </div>
        </section>
<?php include 'footer.php'; ?>
