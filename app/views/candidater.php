<?php include 'header.php'; ?>
<!-- Vue du formulaire de candidature -->
<section class="candidature-section">
    <div class="container">
        <!-- Informations sur l'offre -->
        <div class="offre-recap">
            <h1>Candidater pour : <?= htmlspecialchars($offer['titre']) ?></h1>
            <p class="entreprise-nom">
                <strong>Entreprise :</strong> <?= htmlspecialchars($offer['entreprise_nom']) ?>
            </p>
            <p class="offre-description">
                <?= htmlspecialchars(substr($offer['description'], 0, 150)) ?>...
            </p>
        </div>

        <!-- Messages d'erreur -->
        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="alert alert-error">
                <h3>⚠️ Erreurs détectées</h3>
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Formulaire de candidature -->
        <div class="candidature-card">
            <h2>📄 Déposez votre candidature</h2>
            <p class="info-text">
                Veuillez télécharger votre CV et votre lettre de motivation au format PDF, DOC ou DOCX (max 5 Mo chacun).
            </p>

            <form action="index.php?route=candidater-process" method="POST" enctype="multipart/form-data" class="candidature-form">
                <!-- ID de l'offre (caché) -->
                <input type="hidden" name="offre_id" value="<?= htmlspecialchars($offer['id']) ?>">

                <!-- Upload CV -->
                <div class="form-group">
                    <label for="cv">
                        <span class="label-icon">📎</span>
                        Curriculum Vitae (CV) *
                    </label>
                    <input 
                        type="file" 
                        id="cv" 
                        name="cv" 
                        accept=".pdf,.doc,.docx"
                        required
                    >
                    <small class="help-text">Formats acceptés : PDF, DOC, DOCX | Taille max : 5 Mo</small>
                </div>

                <!-- Upload Lettre de motivation -->
                <div class="form-group">
                    <label for="lettre_motivation">
                        <span class="label-icon">✉️</span>
                        Lettre de motivation *
                    </label>
                    <input 
                        type="file" 
                        id="lettre_motivation" 
                        name="lettre_motivation" 
                        accept=".pdf,.doc,.docx"
                        required
                    >
                    <small class="help-text">Formats acceptés : PDF, DOC, DOCX | Taille max : 5 Mo</small>
                </div>

                <!-- Boutons -->
                <div class="form-actions">
                    <a href="index.php?route=offre-details&id=<?= htmlspecialchars($offer['id']) ?>" class="btn btn-secondary">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        🚀 Envoyer ma candidature
                    </button>
                </div>
            </form>
        </div>

        <!-- Conseils -->
        <div class="conseils-card">
            <h3>💡 Conseils pour votre candidature</h3>
            <ul>
                <li>✅ Assurez-vous que votre CV est à jour et adapté au poste</li>
                <li>✅ Personnalisez votre lettre de motivation pour cette offre</li>
                <li>✅ Vérifiez l'orthographe et la mise en forme de vos documents</li>
                <li>✅ Utilisez des fichiers au format PDF pour garantir la compatibilité</li>
            </ul>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
