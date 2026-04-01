<?php include 'header.php'; ?>

<section class="candidature-section">
    <div class="container" style="max-width:780px;">
        
        <div class="page-header" style="text-align:center; margin-bottom:2rem;">
            <h1><?= isset($alert) ? ' Modifier l\'alerte' : '➕ Créer une alerte' ?></h1>
            <p style="color:var(--muted); margin-top:0.5rem;">
                Définissez vos critères de recherche et recevez des notifications pour les nouvelles offres correspondantes.
            </p>
        </div>

        <!-- Messages d'erreur -->
        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['erreur']) ?></div>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <!-- Formulaire -->
        <div class="candidature-card">
            <form action="index.php?route=alerte-save" method="POST" class="candidature-form">
                
                <?php if (isset($alert)): ?>
                    <input type="hidden" name="alert_id" value="<?= (int) $alert['id'] ?>">
                <?php endif; ?>

                <!-- Nom de l'alerte -->
                <div class="form-group">
                    <label for="nom_alerte">
                        <span class="label-icon"></span>
                        Nom de l'alerte *
                    </label>
                    <input 
                        type="text" 
                        id="nom_alerte" 
                        name="nom_alerte" 
                        placeholder="Ex: Développeur Web à Lyon"
                        value="<?= htmlspecialchars($alert['nom_alerte'] ?? '') ?>"
                        required
                    >
                    <small class="help-text">Donnez un nom explicite à votre alerte pour la retrouver facilement</small>
                </div>

                <hr style="border:0; border-top:1px solid rgba(99,102,241,.14); margin:1.5rem 0;">

                <h3 style="font-weight:900; margin-bottom:1rem;"> Critères de recherche</h3>
                <p style="color:var(--muted); font-size:.92rem; margin-bottom:1.25rem;">
                    Remplissez au moins un critère. Laissez vide pour ne pas filtrer sur ce critère.
                </p>

                <!-- Mot-clé -->
                <div class="form-group">
                    <label for="mot_cle">
                        <span class="label-icon">🔍</span>
                        Mot-clé
                    </label>
                    <input 
                        type="text" 
                        id="mot_cle" 
                        name="mot_cle" 
                        placeholder="Ex: React, Marketing, Data..."
                        value="<?= htmlspecialchars($alert['mot_cle'] ?? '') ?>"
                    >
                    <small class="help-text">Recherché dans le titre, la description et le nom de l'entreprise</small>
                </div>

                <!-- Ville -->
                <div class="form-group">
                    <label for="ville">
                        <span class="label-icon"></span>
                        Ville
                    </label>
                    <input 
                        type="text" 
                        id="ville" 
                        name="ville" 
                        placeholder="Ex: Lyon, Paris, Marseille..."
                        value="<?= htmlspecialchars($alert['ville'] ?? '') ?>"
                    >
                </div>

                <!-- Domaine -->
                <div class="form-group">
                    <label for="domaine">
                        <span class="label-icon"></span>
                        Domaine
                    </label>
                    <input 
                        type="text" 
                        id="domaine" 
                        name="domaine" 
                        placeholder="Ex: Informatique, Finance, Marketing..."
                        value="<?= htmlspecialchars($alert['domaine'] ?? '') ?>"
                    >
                </div>

                <!-- Type de contrat -->
                <div class="form-group">
                    <label for="type_contrat">
                        <span class="label-icon"></span>
                        Type de contrat
                    </label>
                    <select id="type_contrat" name="type_contrat" style="width:100%; padding:.85rem .95rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.75);">
                        <option value="">Tous les types</option>
                        <option value="stage" <?= isset($alert) && $alert['type_contrat'] === 'stage' ? 'selected' : '' ?>>Stage</option>
                        <option value="alternance" <?= isset($alert) && $alert['type_contrat'] === 'alternance' ? 'selected' : '' ?>>Alternance</option>
                    </select>
                </div>

                <!-- Rémunération minimale -->
                <div class="form-group">
                    <label for="remuneration_min">
                        <span class="label-icon"></span>
                        Rémunération minimale (€)
                    </label>
                    <input 
                        type="number" 
                        id="remuneration_min" 
                        name="remuneration_min" 
                        placeholder="500"
                        min="0"
                        step="50"
                        value="<?= isset($alert) ? (int) $alert['remuneration_min'] : '' ?>"
                    >
                    <small class="help-text">Laissez vide ou à 0 pour ne pas filtrer par rémunération</small>
                </div>

                <!-- Boutons -->
                <div class="form-actions" style="display:flex; justify-content:flex-end; gap:.65rem; margin-top:1.75rem;">
                    <a href="index.php?route=mes-alertes" class="btn btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <?= isset($alert) ? ' Enregistrer les modifications' : ' Créer l\'alerte' ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- Conseils -->
        <div class="conseils-card" style="margin-top:1.5rem;">
            <h3> Conseils pour vos alertes</h3>
            <ul>
                <li> Créez plusieurs alertes avec des critères différents pour ne rater aucune opportunité</li>
                <li> Utilisez des mots-clés précis pour des résultats plus pertinents</li>
                <li> Vous pouvez activer/désactiver vos alertes temporairement sans les supprimer</li>
                <li> Vérifiez régulièrement vos alertes pour voir les nouvelles offres</li>
            </ul>
        </div>

    </div>
</section>

<style>
.label-icon {
    display: inline-block;
    margin-right: .35rem;
}

.conseils-card {
    background: rgba(255,255,255,.74);
    border: 1px solid rgba(99,102,241,.16);
    border-radius: var(--r-lg);
    padding: 1.25rem;
    box-shadow: var(--shadow-sm);
}

.conseils-card h3 {
    font-weight: 900;
    margin-bottom: .85rem;
}

.conseils-card ul {
    list-style: none;
    padding: 0;
}

.conseils-card li {
    margin-bottom: .5rem;
    color: rgba(2,6,23,.72);
    font-size: .94rem;
}
</style>

<?php include 'footer.php'; ?>
