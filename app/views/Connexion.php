<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Connexion à son espace personnel CESI Connect.">
    <link rel="stylesheet" href="style.css">
    <title>CESI Connect</title>
</head>
<body>
    <header class="main-header">
        <div class="logo">
            <a href="Accueil.html">CESI Connect</a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="Accueil.html" class="active">Accueil</a></li>
                <li><a href="Inscription.html">Inscription</a></li>
                <li><a href="Connexion.html" class="btn-login">Connexion</a></li>
            </ul>
        </nav>
    </header>

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

    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h3>CESI Connect</h3>
            </div>
            <div class="footer-col">
                <h4>Liens utiles</h4>
                <ul>
                    <li><a href="Mentions légales.html">Mentions légales</a></li>
                    <li><a href="Contact.html">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 CESI Connect - Tous droits réservés</p>
        </div>
    </footer>
</body>
</html>
