<?php include 'header.php'; ?>
<main>
    <section class="auth-card">
        <h1>Gérer une offre</h1>
        <form action="index.php?route=save-offre" method="POST" class="auth-form">
            <input type="text" name="titre" placeholder="Titre de l'offre" required>
            <textarea name="description" placeholder="Description du stage"></textarea>
            <input type="number" name="remuneration" placeholder="Rémunération (€)">
            <button type="submit" class="btn-submit">Enregistrer</button>
        </form>
    </section>
</main>
<?php include 'footer.php'; ?>