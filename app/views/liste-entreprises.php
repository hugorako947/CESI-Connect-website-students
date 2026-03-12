<?php include 'header.php'; ?>
<section class="dashboard-container">
    <h1>Gestion des Entreprises</h1>
    <div class="table-responsive">
        <table class="table-candidatures">
            <thead>
                <tr><th>Nom</th><th>Email</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>TechCorp</td>
                    <td>contact@techcorp.fr</td>
                    <td><a href="index.php?route=form-entreprise&id=1">Modifier</a> | <a href="#">Supprimer</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<?php include 'footer.php'; ?>