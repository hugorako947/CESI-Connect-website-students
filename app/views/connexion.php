<?php include 'header.php'; ?>
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
                        <input type="captcha" id="captcha" name="captcha" placeholder="1+1=?" required>
                    </div>

                    <a href="index.php?route=suivi-etudiants-pilote" class="btn-submit">Se connecter</a>

                    <div class="form-footer">
                        <p>Pas encore de compte ? <a href="index.php?route=inscription">S'inscrire</a></p>
                    </div>

                </form>
            </div>
        </section>
<?php include 'footer.php'; ?>
