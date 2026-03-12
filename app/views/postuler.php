<?php include 'header.php'; ?>

<main>
    <section class="auth-container">
        <div class="auth-card">
            <h1>Postuler à l'offre</h1>
            <!-- Le titre de l'offre sera chargé dynamiquement par le contrôleur -->
            <h2 class="offer-title-apply">Développeur Fullstack React / Node.js</h2>

            <!-- L'attribut 'enctype' est OBLIGATOIRE pour pouvoir envoyer des fichiers (CV, LM) -->
            <form action="index.php?route=submit-application" method="POST" enctype="multipart/form-data" class="auth-form">

                <!-- Champ pour le CV (obligatoire) -->
                <div class="form-group">
                    <label for="cv">Votre CV (format PDF)</label>
                    <input type="file" id="cv" name="cv" accept=".pdf" required>
                </div>

                <!-- Champ pour la Lettre de Motivation -->
                <div class="form-group">
                    <label for="motivation_text">Votre lettre de motivation</label>
                    <textarea id="motivation_text" name="motivation_text" rows="8" placeholder="Présentez-vous et expliquez pourquoi cette offre vous intéresse..."></textarea>
                </div>

                <!-- Ce champ caché est crucial : il envoie l'ID de l'offre avec le formulaire -->
                <!-- pour qu'on sache à quelle offre l'étudiant postule. -->
                <input type="hidden" name="offer_id" value="1"> <!-- La value sera dynamique -->

                <button type="submit" class="btn-submit">Envoyer ma candidature</button>

            </form>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>