<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<body>

    <main>
        <section class="auth-container">
            <div class="auth-card">
                <h1>Connexion</h1>
                <p>Accédez à votre espace étudiant ou pilote.</p>

                <form action="dashboard.php" method="POST" class="auth-form">
                    
                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <input type="email" id="email" name="email" placeholder="exemple@viacesi.fr" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="form-footer">
                        <a href="#">Mot de passe oublié ?</a>
                    </div>

                    <div class="form-group">
                        <label for="captcha">Captcha Anti-Bot</label>
                        <input type="captcha" id="captcha" name="captcha" placeholder="Réponse" required>
                    </div>

                    <a href="Accueil utilisateur.html" class="btn-submit">Se connecter</a>

                </form>
            </div>
        </section>
    </main>

</body>
</html>
<?php include 'footer.php'; ?>
