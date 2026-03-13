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

        <section class="offers-results">
            <div class="results-header">
                <h1>Offres disponibles</h1>
                <p>152 offres trouvées</p>
            </div>

            <div class="cards-container vertical">
                <article class="card">
                    <div class="card-header">
                        <h3>Développeur Web Fullstack</h3>
                        <span class="tag">Stage</span>
                    </div>
                    <p class="company"><strong>TechCorp</strong> - Paris (75)</p>
                    <p class="desc">Nous recherchons un stagiaire pour travailler sur une application React/Node.js...</p>
                    <div class="card-footer">
                        <span class="date">Publié le 01/03/2026</span>
                        <a href="details-offre.html" class="btn-details">Voir les détails</a>
                    </div>
                </article>

                <article class="card">
                    <div class="card-header">
                        <h3>Assistant Admin Système</h3>
                        <span class="tag">Alternance</span>
                    </div>
                    <p class="company"><strong>DataSecure</strong> - Lyon (69)</p>
                    <p class="desc">Aide à la gestion des serveurs et à la mise en place de protocoles de sécurité...</p>
                    <div class="card-footer">
                        <span class="date">Publié le 28/02/2026</span>
                        <a href="details-offre.html" class="btn-details">Voir les détails</a>
                    </div>
                </article>

                <article class="card">
                    <div class="card-header">
                        <h3>Développeur Mobile iOS</h3>
                        <span class="tag">Stage</span>
                    </div>
                    <p class="company"><strong>Apply</strong> - Bordeaux (33)</p>
                    <p class="desc">Création de nouvelles fonctionnalités sur notre application mobile native Swift...</p>
                    <div class="card-footer">
                        <span class="date">Publié le 25/02/2026</span>
                        <a href="details-offre.html" class="btn-details">Voir les détails</a>
                    </div>
                </article>
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
