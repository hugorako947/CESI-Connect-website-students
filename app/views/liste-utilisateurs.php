<?php include 'header.php'; ?>
<section class="dashboard-container">
    <div class="dashboard-header">
        <h1>Gestion des Utilisateurs</h1>
        <a href="index.php?route=form-utilisateur" class="btn btn-primary">Créer un utilisateur</a>
    </div>
    <div class="table-responsive">
        <table class="table-candidatures">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Les lignes de ce tableau seront générées par une boucle PHP -->
                <tr>
                    <td>Martin</td>
                    <td>Paul</td>
                    <td>paul.martin@viacesi.fr</td>
                    <td><span class="badge">Étudiant</span></td>
                    <td><a href="#">Modifier</a> | <a href="#">Supprimer</a></td>
                </tr>
                <tr>
                    <td>Dupont</td>
                    <td>Jean</td>
                    <td>jean.dupont@cesi.fr</td>
                    <td><span class="badge">Pilote</span></td>
                    <td><a href="#">Modifier</a> | <a href="#">Supprimer</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<?php include 'footer.php'; ?>