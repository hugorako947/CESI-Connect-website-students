<?php include 'header.php'; ?>

<main class="auth-container">
    <div class="auth-card">
        <h1>Créer un compte</h1>
        <p>Rejoignez CESI Connect pour trouver votre prochain stage.</p>

        <!-- Affichage des messages d'erreur -->
        <?php if(isset($erreur)): ?>
            <div style="background-color: #fee2e2; color: #dc2626; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?= htmlspecialchars($erreur) ?>
            </div>
        <?php endif; ?>

        <!-- Le formulaire envoie les données en POST vers la route inscription -->
        <form action="index.php?route=inscription" method="POST" class="auth-form">
            
            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe *</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px;">S'inscrire</button>

            <p style="margin-top: 15px; font-size: 0.9em; text-align: center;">
                Déjà un compte ? <a class="inscription-login-link" href="index.php?route=connexion" style="color: var(--primary);">Se connecter</a>
            </p>
        </form>
    </div>
</main>

<style>
  .inscription-login-link { font-weight: 800; }
  .inscription-login-link:hover { text-decoration: underline; }
</style>

<?php include 'footer.php'; ?>
