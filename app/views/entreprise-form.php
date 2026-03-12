<?php include 'header.php'; ?>
<main>
    <section class="auth-card">
        <h1>Ajouter/Modifier une Entreprise</h1>
        <form action="index.php?route=save-entreprise" method="POST" class="auth-form">
            <input type="text" name="nom" placeholder="Nom de l'entreprise" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="email" name="email" placeholder="Email contact">
            <input type="tel" name="tel" placeholder="Téléphone">
            <button type="submit" class="btn-submit">Enregistrer</button>
        </form>
    </section>
</main>
<?php include 'footer.php'; ?>