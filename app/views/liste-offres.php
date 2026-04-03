<?php include 'header.php'; ?>

<section class="section">
    <div class="section-header">
        <h2>Offres disponibles</h2>
        <a href="index.php?route=offres">Réinitialiser les filtres</a>
    </div>

    <button type="button" class="btn-toggle-filters" id="toggleFilters">Afficher les filtres</button>

    <aside class="filters-sidebar <?= !empty($_GET['q']) || !empty($_GET['skill']) || !empty($_GET['city']) || !empty($_GET['domain']) || !empty($_GET['type']) || isset($_GET['min_money']) || !empty($_GET['duree']) || !empty($_GET['niveau']) || isset($_GET['teletravail']) ? 'active' : '' ?>" id="filterSidebar">
        <h2>Filtres</h2>
        <form action="index.php" method="GET" class="filters-form">
            <input type="hidden" name="route" value="offres" />
            <div class="filter-group">
                <label for="f-keyword">Recherche</label>
                <input type="text" id="f-keyword" name="q" placeholder="Mot-clé" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            </div>

            <div class="filter-group">
                <label for="f-skill">Compétence</label>
                <input type="text" id="f-skill" name="skill" placeholder="Ex: React, PHP..." value="<?= htmlspecialchars($_GET['skill'] ?? '') ?>">
            </div>

            <div class="filter-group">
                <label for="f-domain">Domaine</label>
                <select id="f-domain" name="domain" style="width:100%;padding:.75rem .85rem;border-radius:var(--border-radius-md);border:1px solid var(--color-border-secondary);background:#fff;font:inherit;color:var(--color-text-primary);">
                    <option value="">Tous les domaines</option>
                    <?php
                    $domaines = ['Assurance','Automobile','Banque','Commerce','Conseil','Education','Energie','Environnement','Finance','Industrie','Informatique','Media','Santé','Securite','Services','Telecom'];
                    foreach ($domaines as $d):
                        $sel = (isset($_GET['domain']) && $_GET['domain'] === $d) ? 'selected' : '';
                    ?>
                        <option value="<?= htmlspecialchars($d) ?>" <?= $sel ?>><?= htmlspecialchars($d) ?></option>
                    <?php endforeach; ?>
                </select>
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
                <label for="f-duree">Durée du contrat</label>
                <select id="f-duree" name="duree" style="width:100%;padding:.75rem .85rem;border-radius:var(--border-radius-md);border:1px solid var(--color-border-secondary);background:#fff;font:inherit;color:var(--color-text-primary);">
                    <option value="">Toutes les durées</option>
                    <?php
                    $durees = ['1 mois','2 mois','3 mois','4 mois','5 mois','6 mois','1 an','2 ans','3 ans'];
                    foreach ($durees as $dur):
                        $sel = (isset($_GET['duree']) && $_GET['duree'] === $dur) ? 'selected' : '';
                    ?>
                        <option value="<?= htmlspecialchars($dur) ?>" <?= $sel ?>><?= htmlspecialchars($dur) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="f-niveau">Niveau d'étude</label>
                <select id="f-niveau" name="niveau" style="width:100%;padding:.75rem .85rem;border-radius:var(--border-radius-md);border:1px solid var(--color-border-secondary);background:#fff;font:inherit;color:var(--color-text-primary);">
                    <option value="">Tous les niveaux</option>
                    <?php
                    $niveaux = ['Bac','Bac+2','Bac+3','Bac+4','Bac+5','Bac+8'];
                    foreach ($niveaux as $niv):
                        $sel = (isset($_GET['niveau']) && $_GET['niveau'] === $niv) ? 'selected' : '';
                    ?>
                        <option value="<?= htmlspecialchars($niv) ?>" <?= $sel ?>><?= htmlspecialchars($niv) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="f-teletravail">Télétravail</label>
                <select id="f-teletravail" name="teletravail" style="width:100%;padding:.75rem .85rem;border-radius:var(--border-radius-md);border:1px solid var(--color-border-secondary);background:#fff;font:inherit;color:var(--color-text-primary);">
                    <option value="">Indifférent</option>
                    <option value="1" <?= (isset($_GET['teletravail']) && $_GET['teletravail'] === '1') ? 'selected' : '' ?>>Oui</option>
                    <option value="0" <?= (isset($_GET['teletravail']) && $_GET['teletravail'] === '0') ? 'selected' : '' ?>>Non</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="f-remun">Rémunération min. (€)</label>
                <input type="number" id="f-remun" name="min_money" placeholder="500" value="<?= htmlspecialchars($_GET['min_money'] ?? '') ?>">
            </div>

            <button type="submit" class="btn-primary">Appliquer les filtres</button>
        </form>
    </aside>

    <div class="cards-container">
        <?php if (!empty($offres)): ?>
            <?php
                $redirectBase = 'index.php?route=offres';
                if (!empty($queryParams)) {
                    $redirectBase .= '&' . http_build_query($queryParams);
                }
            ?>
            <?php foreach ($offres as $offre): ?>
                <article class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                        <?php if (!empty($offre['Type_contrat'])): ?>
                            <span class="tag"><?= htmlspecialchars(ucfirst($offre['Type_contrat'])) ?></span>
                        <?php endif; ?>
                    </div>
                    <p class="company"><?= htmlspecialchars($offre['entreprise_nom'] ?? '') ?></p>

                    <!-- Méta-infos clés -->
                    <div class="offer-meta-row">
                        <?php if (!empty($offre['Ville'])): ?>
                            <span class="offer-meta-tag">📍 <?= htmlspecialchars($offre['Ville']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($offre['domaine'])): ?>
                            <span class="offer-meta-tag offer-meta-domain">🏷 <?= htmlspecialchars($offre['domaine']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($offre['Durée_contrat'])): ?>
                            <span class="offer-meta-tag">⏱ <?= htmlspecialchars($offre['Durée_contrat']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($offre['Niveau_etude'])): ?>
                            <span class="offer-meta-tag">🎓 <?= htmlspecialchars($offre['Niveau_etude']) ?></span>
                        <?php endif; ?>
                        <?php if (isset($offre['Teletravail'])): ?>
                            <span class="offer-meta-tag <?= $offre['Teletravail'] ? 'offer-meta-tele' : '' ?>">
                                <?= $offre['Teletravail'] ? '💻 Télétravail' : '🏢 Présentiel' ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($offre['remuneration'])): ?>
                            <span class="offer-meta-tag offer-meta-money">💶 <?= htmlspecialchars($offre['remuneration']) ?>€</span>
                        <?php endif; ?>
                    </div>

                    <p class="desc"><?= htmlspecialchars(substr($offre['description'], 0, 130)) ?><?= strlen($offre['description']) > 130 ? '…' : '' ?></p>

                    <div class="card-footer">
                        <span class="date"><?= date('d/m/Y', strtotime($offre['date_publication'])) ?></span>
                        <a href="index.php?route=offre-details&id=<?= (int) $offre['id'] ?>" class="btn-primary">Détails</a>
                        <?php if (isset($_SESSION['user_id']) && (int) ($_SESSION['user_role'] ?? 0) !== 2): ?>
                            <?php $isFavorite = in_array((int) $offre['id'], $wishlistOfferIds ?? [], true); ?>
                            <?php if ($isFavorite): ?>
                                <a href="index.php?route=wishlist-remove&id=<?= (int) $offre['id'] ?>&redirect=<?= urlencode($redirectBase) ?>" class="btn-details" title="Retirer de ma wish-list">❤</a>
                            <?php else: ?>
                                <a href="index.php?route=wishlist-add&id=<?= (int) $offre['id'] ?>&redirect=<?= urlencode($redirectBase) ?>" class="btn-details" title="Ajouter à ma wish-list">♡</a>
                            <?php endif; ?>
                        <?php elseif (isset($_SESSION['user_id']) && (int) ($_SESSION['user_role'] ?? 0) === 2): ?>
                            <span class="btn-details btn-locked" title="Réservé aux étudiants" style="cursor:default; opacity:.6;">🔒</span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune offre disponible pour ces critères.</p>
        <?php endif; ?>
    </div>

    <?php if (($totalPages ?? 1) > 1): ?>
        <nav class="pagination">
            <?php
                $prevPage = max(1, (int) $page - 1);
                $nextPage = min((int) $totalPages, (int) $page + 1);
                $baseParams = ['route' => 'offres'] + ($queryParams ?? []);
            ?>
            <a href="index.php?<?= http_build_query($baseParams + ['page' => $prevPage]) ?>" class="page-btn prev <?= ((int) $page <= 1) ? 'disabled' : '' ?>">&laquo;</a>
            <?php for ($i = 1; $i <= (int) $totalPages; $i++): ?>
                <a href="index.php?<?= http_build_query($baseParams + ['page' => $i]) ?>" class="page-btn <?= ((int) $page === $i) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <a href="index.php?<?= http_build_query($baseParams + ['page' => $nextPage]) ?>" class="page-btn next <?= ((int) $page >= (int) $totalPages) ? 'disabled' : '' ?>">&raquo;</a>
        </nav>
    <?php endif; ?>
</section>
<style>
.offer-meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: .45rem;
    margin: .7rem 0 .6rem;
}
.offer-meta-tag {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    padding: .22rem .6rem;
    border-radius: 999px;
    font-size: .78rem;
    font-weight: 750;
    background: rgba(37,99,235,.07);
    color: rgba(37,99,235,.90);
    border: 1px solid rgba(37,99,235,.16);
    white-space: nowrap;
}
.offer-meta-domain {
    background: rgba(124,58,237,.07);
    color: rgba(124,58,237,.90);
    border-color: rgba(124,58,237,.16);
}
.offer-meta-tele {
    background: rgba(16,185,129,.08);
    color: rgba(5,150,105,.95);
    border-color: rgba(16,185,129,.18);
}
.offer-meta-money {
    background: rgba(234,88,12,.07);
    color: rgba(194,65,12,.95);
    border-color: rgba(234,88,12,.16);
}
</style>

<?php include 'footer.php'; ?>

