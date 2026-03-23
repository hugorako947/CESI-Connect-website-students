<?php include 'header.php'; ?>

        <button type="button" class="btn-toggle-filters" id="toggleFilters">Afficher les filtres</button>

        <aside class="filters-sidebar" id="filterSidebar">
            <h2>Filtres</h2>
            <form action="#" method="GET" class="filters-form">
                <div class="filter-group">
                    <label for="f-skill">Compétence</label>
                    <input type="text" id="f-skill" name="skill" placeholder="Ex: React, PHP...">
                </div>

                <div class="filter-group">
                    <label for="f-city">Ville</label>
                    <input type="text" id="f-city" name="city" placeholder="Ex: Lyon...">
                </div>

                <div class="filter-group">
                    <label>Type de contrat</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="type" value="stage"> Stage</label>
                        <label><input type="checkbox" name="type" value="alternance"> Alternance</label>
                    </div>
                </div>

                <div class="filter-group">
                    <label for="f-remun">Rémunération min. (€)</label>
                    <input type="number" id="f-remun" name="min_money" placeholder="500">
                </div>

                <button type="submit" class="btn-primary">Appliquer les filtres</button>
            </form>
        </aside>

       <div class="cards-container">
            <?php if(!empty($offres)): ?>
                <?php foreach($offres as $offre): ?>
                    <article class="card">
                        <div class="card-header">
                            <!-- On affiche le vrai titre venant de la BDD -->
                            <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                            <span class="tag">Stage/Alt</span>
                        </div>
                        <!-- On affiche le vrai nom de l'entreprise (grâce à la jointure) -->
                        <p class="company"><?= htmlspecialchars($offre['nom_entreprise']) ?></p>
                
                        <p class="desc"><?= htmlspecialchars($offre['description']) ?></p>
                
                        <div class="card-footer">
                            <span class="date"><?= date('d/m/Y', strtotime($offre['date_publication'])) ?></span>
                            <a href="index.php?route=details-offre&id=<?= $offre['id'] ?>" class="btn-primary">Détails</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune offre disponible pour le moment.</p>
            <?php endif; ?>
        </div>

            <nav class="pagination">
                <a href="#" class="page-btn prev disabled">&laquo;</a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <a href="#" class="page-btn next">&raquo;</a>
            </nav>
        </section>
<?php include 'footer.php'; ?>
