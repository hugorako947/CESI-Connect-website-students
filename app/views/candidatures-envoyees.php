<?php include 'header.php'; ?>

<section class="dashboard-container">
    <div class="dashboard-header">
        <h1>Mes candidatures envoyées</h1>
        <p>Retrouvez ici l'historique de vos postulations.</p>
    </div>

    <div class="table-responsive">
        <table class="table-candidatures">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Entreprise</th>
                    <th>Offre</th>
                    <th>Statut</th>
                    <th>Documents envoyés</th>
                </tr>
            </thead>
            <tbody>
                <!-- Ceci sera généré en PHP plus tard avec la Base de Données -->
                <tr>
                    <td>10/03/2026</td>
                    <td>TechCorp</td>
                    <td>Développeur Web Fullstack</td>
                    <td><span class="badge badge-en-cours">En cours d'analyse</span></td>
                    <td><a href="#">Voir mon CV/LM</a></td>
                </tr>
                <tr>
                    <td>05/03/2026</td>
                    <td>DataSecure</td>
                    <td>Assistant Admin Système</td>
                    <td><span class="badge badge-refuse">Refusé</span></td>
                    <td><a href="#">Voir mon CV/LM</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php include 'footer.php'; ?>
