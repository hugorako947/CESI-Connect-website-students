<?php include 'header.php'; ?>

<main class="candidature-page">
    <div class="candidature-container">
        
        <div class="page-header" style="text-align: center;">
            <h1>Contactez-nous</h1>
            <p>Une question sur une offre ? Un problème technique ? Notre équipe est là pour vous.</p>
        </div>

        <div class="grid-2">
            <!-- Colonne 1 : Coordonnées -->
            <div class="company-card" style="height: fit-content;">
                <div class="company-card-header">
                    <div class="company-card-logo">📍</div>
                    <div class="company-card-name">Nos coordonnées</div>
                </div>
                <div class="company-contact">
                    <p><strong>Adresse :</strong> Campus CESI Nanterre</p>
                    <p><strong>Email :</strong> <a href="hugo.rakotonanahary@viacesi.fr">support@cesi-connect.fr</a></p>
                    <p><strong>Téléphone :</strong> 06 95 75 61 34</p>
                    <hr style="border: 0; border-top: 1px solid var(--color-border-tertiary); margin: 15px 0;">
                    <p><strong>Horaires du support :</strong><br>Lundi - Vendredi : 9h00 - 17h00</p>
                </div>
            </div>

            <!-- Colonne 2 : Le Formulaire -->
            <div class="candidature-form-card">
                <form action="#" method="POST" class="auth-form">
                    
                    <div class="form-group">
                        <label for="nom">Nom complet</label>
                        <input type="text" id="nom" name="nom" required placeholder="Jean Dupont">
                    </div>

                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" required placeholder="jean.dupont@viacesi.fr">
                    </div>

                    <div class="form-group">
                        <label for="sujet">Sujet de votre message</label>
                        <select id="sujet" name="sujet" class="form-select" required>
                            <option value="">Sélectionnez un sujet...</option>
                            <option value="candidature">Problème avec une candidature</option>
                            <option value="compte">Problème de connexion / compte</option>
                            <option value="entreprise">Je suis une entreprise</option>
                            <option value="autre">Autre demande</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Votre message</label>
                        <textarea id="message" name="message" class="form-textarea" required placeholder="Expliquez-nous votre problème en détail..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit" onclick="alert('Ceci est une page de démonstration. Le message ne sera pas réellement envoyé.'); return false;">Envoyer le message</button>

                </form>
            </div>
        </div>

    </div>
</main>

<?php include 'footer.php'; ?>
