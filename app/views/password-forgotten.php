<?php include 'header.php'; ?>

<section class="auth-container">
    <div class="auth-card">
        <h1>Mot de passe oublié</h1>
        <p class="auth-subtitle">Entrez votre adresse email, nous vous enverrons un nouveau mot de passe temporaire.</p>

        <?php if(isset($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="index.php?route=mot-de-passe-oublie" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" id="email" name="email" placeholder="exemple@viacesi.fr" required>
            </div>

            <button type="submit" class="btn-submit">Envoyer</button>

            <div class="form-footer">
                <p><a href="index.php?route=connexion">← Retour</a></p>
            </div>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
