<?php include 'header.php'; ?>
        <section class="auth-container">
            <div class="auth-card">
                <h1>Inscription</h1>
                    
                <form action="dashboard.php" method="POST" class="auth-form">
                
                    <div class="form-group">
                        <label for="Nom">NOM</label>
                        <input type="NOM" id="NOM" name="NOM" placeholder="Votre nom" required>
                    </div>

                    <div class="form-group">
                        <label for="Prenom">Prénom</label>
                        <input type="Prenom" id="Prenom" name="Prenom" placeholder="Votre prénom" required>
                    </div>
                    


                    <div class="form-group">
                        <label for="email">Adresse Email</label>
                        <input type="email" id="email" name="email" placeholder="exemple@viacesi.fr" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                                    <div class="form-group">
                        <label for="captcha">Captcha Anti-Bot</label>
                        <input type="captcha" id="captcha" name="captcha" placeholder="1+1=?" required>
                    </div>

                    <a href="index.php?route=suivi-etudiants-pilote" class="btn-submit">Finaliser l'inscription</a>

            </div>
        </section>
<?php include 'footer.php'; ?>
