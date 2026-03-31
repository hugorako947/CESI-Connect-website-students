<?php include 'header.php'; ?>
        <section class="auth-container">
            <div class="auth-card">
                <h1>Connexion</h1>
                <p>Accédez à votre espace étudiant, administrateur ou pilote.</p>

                <?php if (isset($erreur) && !empty($erreur)): ?>
                    <div class="alert alert-error" role="alert">
                        <?= htmlspecialchars($erreur) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?route=connexion" method="POST" class="auth-form">
                    
                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <input type="email" id="email" name="email" placeholder="exemple@viacesi.fr" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="form-footer">
                        <a href="index.php?route=mot-de-passe-oublie">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn-submit">Se connecter</button>

                    <div class="form-footer">
                        <p>Pas encore de compte ? <a href="index.php?route=inscription">S'inscrire</a></p>
                    </div>

                </form>
            </div>
        </section>
<?php include 'footer.php'; ?>
