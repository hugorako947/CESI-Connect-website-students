<?php include 'header.php'; ?>

<section class="candidature-section">
    <div class="container" style="max-width:780px;">

        <div class="page-header" style="text-align:center; margin-bottom:2rem;">
            <h1><?= isset($enterprise) ? 'Modifier l\'entreprise' : 'Ajouter une entreprise' ?></h1>
            <p style="color:var(--muted); margin-top:0.5rem;">
                <?= isset($enterprise)
                    ? 'Modifiez les informations de l\'entreprise ci-dessous.'
                    : 'Remplissez le formulaire pour ajouter une nouvelle entreprise partenaire.' ?>
            </p>
        </div>

        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['erreur']) ?></div>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <div class="candidature-card" style="background:rgba(255,255,255,.74); border:1px solid rgba(99,102,241,.16); border-radius:var(--r-lg); padding:2rem; box-shadow:var(--shadow-sm);">

            <form action="index.php?route=save-entreprise" method="POST" class="candidature-form">

                <?php if (isset($enterprise)): ?>
                    <input type="hidden" name="id" value="<?= (int) $enterprise['id'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="nom">Nom de l'entreprise *</label>
                    <input type="text" id="nom" name="nom"
                        placeholder="Ex : TechCorp France"
                        value="<?= htmlspecialchars($enterprise['nom'] ?? '') ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"
                        placeholder="Decrivez l'activite de l'entreprise..."
                        style="width:100%; padding:.85rem .95rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.75); min-height:120px; font:inherit; resize:vertical;"
                    ><?= htmlspecialchars($enterprise['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="email">Email de contact</label>
                    <input type="email" id="email" name="email"
                        placeholder="contact@entreprise.fr"
                        value="<?= htmlspecialchars($enterprise['email_contact'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="tel">Telephone</label>
                    <input type="tel" id="tel" name="tel"
                        placeholder="Ex : 06 12 34 56 78"
                        value="<?= htmlspecialchars($enterprise['telephone'] ?? '') ?>">
                </div>

                <div class="form-actions" style="display:flex; justify-content:flex-end; gap:.65rem; margin-top:1.75rem;">
                    <a href="index.php?route=gestion-entreprises" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <?= isset($enterprise) ? 'Enregistrer les modifications' : 'Ajouter l\'entreprise' ?>
                    </button>
                </div>

            </form>
        </div>

    </div>
</section>

<?php include 'footer.php'; ?>
