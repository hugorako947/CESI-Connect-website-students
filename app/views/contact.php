<?php include 'header.php'; ?>

<main class="container">
    <div class="page-header text-center page-header-spaced" style="display: flex; flex-direction: column; align-items: center; width: 100%; margin-bottom: 3rem;">
        <h1 style="font-weight: 950; font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--text);">Contactez-nous</h1>
        <p class="page-subtitle" style="font-size: 1.1rem; color: var(--muted); margin: 0;">Une question ? Notre équipe vous répond sous 24h.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; align-items: start; margin-bottom: 3rem;">
        
        <div class="section-card">
            <h2 class="section-card-title">📍 Nos coordonnées</h2>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <p><strong>Adresse :</strong><br><span class="text-muted-inline">Campus CESI Nanterre, France</span></p>
                <p><strong>Support technique :</strong><br><a href="mailto:support@cesi-connect.fr" style="color:var(--primary); font-weight:700;">support@cesi-connect.fr</a></p>
                <p><strong>Téléphone :</strong><br><span class="text-muted-inline">06 95 75 61 34</span></p>
                
                <div class="badge badge-blue" style="padding: 1rem; border-radius: var(--r); width: 100%; justify-content: flex-start;">
                    <span>🕒 <strong>Horaires :</strong><br>Lundi - Vendredi : 9h00 - 17h00</span>
                </div>
            </div>
        </div>

        <div class="section-card">
            <h2 class="section-card-title">✍️ Envoyer un message</h2>
            <form action="#" method="POST">
                <div class="form-group">
                    <label>Nom complet</label>
                    <input type="text" placeholder="Ex: Jean Dupont" required>
                </div>

                <div class="form-group">
                    <label>Adresse email</label>
                    <input type="email" placeholder="jean.dupont@viacesi.fr" required>
                </div>

                <div class="form-group">
                    <label>Sujet</label>
                    <select required>
                        <option value="">Sélectionnez un sujet...</option>
                        <option value="candidature">Problème avec une candidature</option>
                        <option value="compte">Problème de compte</option>
                        <option value="autre">Autre demande</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Votre message</label>
                    <textarea placeholder="Comment pouvons-nous vous aider ?" style="min-height:150px;"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block" onclick="alert('Démonstration : message non envoyé.'); return false;">
                    Envoyer le message
                </button>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
