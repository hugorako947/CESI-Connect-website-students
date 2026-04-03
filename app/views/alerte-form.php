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
                        <span class="label-icon">📍</span>
                        Ville
                    </label>
                    <select id="ville" name="ville" style="width:100%; padding:.85rem .95rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.75); font:inherit;">
                        <option value="">Toutes les villes</option>
                        <?php
                        $villes = ['Bordeaux','Caen','Chartres','Courbevoie','Défense','Grenoble','Herblay','La Defense','Lille','Lyon','Marseille','Metz','Montpellier','Mulhouse','Nantes','Niort','Orleans','Paris','Poissy','Pontoise','Rennes','Rouen','Saint Etienne','Toulouse'];
                        foreach ($villes as $v):
                            $sel = (isset($alert) && ($alert['ville'] ?? '') === $v) ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($v) ?>" <?= $sel ?>><?= htmlspecialchars($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Domaine -->
                <div class="form-group">
                    <label for="domaine">
                        <span class="label-icon">🏷</span>
                        Domaine
                    </label>
                    <select id="domaine" name="domaine" style="width:100%; padding:.85rem .95rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.75); font:inherit;">
                        <option value="">Tous les domaines</option>
                        <?php
                        $domaines = ['Assurance','Automobile','Banque','Commerce','Conseil','Education','Energie','Environnement','Finance','Industrie','Informatique','Media','Santé','Securite','Services','Telecom'];
                        foreach ($domaines as $d):
                            $sel = (isset($alert) && $alert['domaine'] === $d) ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($d) ?>" <?= $sel ?>><?= htmlspecialchars($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Type de contrat -->
                <div class="form-group">
                    <label for="type_contrat">
                        <span class="label-icon">📄</span>
                        Type de contrat
                    </label>
                    <select id="type_contrat" name="type_contrat" style="width:100%; padding:.85rem .95rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.75); font:inherit;">
                        <option value="">Tous les types</option>
                        <option value="stage" <?= isset($alert) && $alert['type_contrat'] === 'stage' ? 'selected' : '' ?>>Stage</option>
                        <option value="alternance" <?= isset($alert) && $alert['type_contrat'] === 'alternance' ? 'selected' : '' ?>>Alternance</option>
                    </select>
                </div>

                <!-- Durée du contrat -->
                <div class="form-group">
                    <label for="duree_contrat">
                        <span class="label-icon">⏱</span>
                        Durée du contrat
                    </label>
                    <select id="duree_contrat" name="duree_contrat" style="width:100%; padding:.85rem .95rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.75); font:inherit;">
                        <option value="">Toutes les durées</option>
                        <?php
                        $durees = ['1 mois','2 mois','3 mois','4 mois','5 mois','6 mois','1 an','2 ans','3 ans'];
                        foreach ($durees as $dur):
                            $sel = (isset($alert) && ($alert['duree_contrat'] ?? '') === $dur) ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($dur) ?>" <?= $sel ?>><?= htmlspecialchars($dur) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Niveau d'étude -->
                <div class="form-group">
                    <label for="niveau_etude">
                        <span class="label-icon">🎓</span>
                        Niveau d'étude
                    </label>
                    <select id="niveau_etude" name="niveau_etude" style="width:100%; padding:.85rem .95rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.75); font:inherit;">
                        <option value="">Tous les niveaux</option>
                        <?php
                        $niveaux = ['Bac','Bac+2','Bac+3','Bac+4','Bac+5','Bac+8'];
                        foreach ($niveaux as $niv):
                            $sel = (isset($alert) && ($alert['niveau_etude'] ?? '') === $niv) ? 'selected' : '';
                        ?>
                            <option value="<?= htmlspecialchars($niv) ?>" <?= $sel ?>><?= htmlspecialchars($niv) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Télétravail -->
                <div class="form-group">
                    <label for="teletravail">
                        <span class="label-icon">💻</span>
                        Télétravail
                    </label>
                    <select id="teletravail" name="teletravail" style="width:100%; padding:.85rem .95rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.75); font:inherit;">
                        <option value="">Indifférent</option>
                        <option value="1" <?= (isset($alert) && isset($alert['teletravail']) && $alert['teletravail'] == 1) ? 'selected' : '' ?>>Oui</option>
                        <option value="0" <?= (isset($alert) && isset($alert['teletravail']) && $alert['teletravail'] == 0 && $alert['teletravail'] !== '') ? 'selected' : '' ?>>Non</option>
                    </select>
                </div>

                <!-- Rémunération minimale -->
                <div class="form-group">
                    <label for="remuneration_min">
                        <span class="label-icon">💶</span>
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
