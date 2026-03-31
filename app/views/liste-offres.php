<?php include 'header.php'; ?>

        <button type="button" class="btn-toggle-filters" id="toggleFilters">Afficher les filtres</button>

        <aside class="filters-sidebar" id="filterSidebar">
            <h2>Filtres</h2>
            <form action="index.php?route=offres-recherche" method="GET" class="filters-form">
                <input type="hidden" name="route" value="offres-recherche" />
                <div class="filter-group">
                    <label for="f-keyword">Recherche</label>
                    <input type="text" id="f-keyword" name="q" placeholder="Mot-clé" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </div>

                <div class="filter-group">
                    <label for="f-skill">Compétence</label>
                    <input type="text" id="f-skill" name="skill" placeholder="Ex: React, PHP..." value="<?= htmlspecialchars($_GET['skill'] ?? '') ?>">
                </div>

                <div class="filter-group">
                    <label for="f-city">Ville</label>
                    <input type="text" id="f-city" name="city" placeholder="Ex: Lyon..." value="<?= htmlspecialchars($_GET['city'] ?? '') ?>">
                </div>

                <div class="filter-group">
                    <label>Type de contrat</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="type[]" value="stage" <?= in_array('stage', (array) ($_GET['type'] ?? []), true) ? 'checked' : '' ?>> Stage</label>
                        <label><input type="checkbox" name="type[]" value="alternance" <?= in_array('alternance', (array) ($_GET['type'] ?? []), true) ? 'checked' : '' ?>> Alternance</label>
                    </div>
                </div>

                <div class="filter-group">
                    <label for="f-remun">Rémunération min. (€)</label>
                    <input type="number" id="f-remun" name="min_money" placeholder="500" value="<?= htmlspecialchars($_GET['min_money'] ?? '') ?>">
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
                        <p class="company"><?= htmlspecialchars($offre['entreprise_nom'] ?? '') ?></p>
                
                        <p class="desc"><?= htmlspecialchars($offre['description']) ?></p>
                
                        <div class="card-footer">
                            <span class="date"><?= date('d/m/Y', strtotime($offre['date_publication'])) ?></span>
                            <a href="index.php?route=offre-details&id=<?= $offre['id'] ?>" class="btn-primary">Détails</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php $isFavorite = in_array((int) $offre['id'], $wishlistOfferIds ?? [], true); ?>
                                <?php if ($isFavorite): ?>
                                    <a href="index.php?route=wishlist-remove&id=<?= (int) $offre['id'] ?>&redirect=<?= urlencode('index.php?route=offres') ?>"
                                       class="btn-details"
                                       title="Retirer de ma wish-list"
                                       aria-label="Retirer de ma wish-list">
                                        ❤
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?route=wishlist-add&id=<?= (int) $offre['id'] ?>&redirect=<?= urlencode('index.php?route=offres') ?>"
                                       class="btn-details"
                                       title="Ajouter à ma wish-list"
                                       aria-label="Ajouter à ma wish-list">
                                        ♡
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
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
