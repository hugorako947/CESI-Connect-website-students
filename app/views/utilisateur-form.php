<?php include 'header.php'; ?>
<main>
    <section class="auth-container">
        <div class="auth-card">
            <!-- Le titre changera si on est en mode création ou modification -->
            <h1>Créer un nouvel utilisateur</h1>
            <form action="index.php?route=save-user" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select id="role" name="role" required>
                        <option value="etudiant">Étudiant</option>
                        <option value="pilote">Pilote</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Enregistrer</button>
            </form>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>