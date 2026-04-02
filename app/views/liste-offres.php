<?php include 'header.php'; ?>
<?php
$inferDomain = function(array $offre): string {
    $text = mb_strtolower(
        trim(
            (string) ($offre['titre'] ?? '') . ' ' .
            (string) ($offre['description'] ?? '') . ' ' .
            (string) ($offre['competences'] ?? '')
        )
    );
    $map = [
        'Informatique' => ['dev', 'informatique', 'web', 'software', 'data', 'php', 'java', 'python', 'cloud', 'ia'],
        'Finance' => ['finance', 'audit', 'compta', 'contrôle de gestion', 'banque'],
        'Marketing' => ['marketing', 'seo', 'communication', 'brand', 'social media'],
        'Ingénierie' => ['ingénieur', 'industrie', 'mécanique', 'électronique', 'qualité'],
        'Design' => ['design', 'ux', 'ui', 'graphique', 'maquette'],
        'Commercial' => ['commercial', 'vente', 'business developer', 'prospection'],
        'Ressources humaines' => ['rh', 'ressources humaines', 'recrutement', 'paie'],
        'Juridique' => ['juridique', 'droit', 'compliance', 'rgpd'],
    ];
    foreach ($map as $domain => $keywords) {
        foreach ($keywords as $keyword) {
            if (mb_strpos($text, $keyword) !== false) {
                return $domain;
            }
        }
    }
    return 'Général';
};
?>

<section class="section">
    <div class="section-header">
        <h2>Offres disponibles</h2>
        <a href="index.php?route=offres">Réinitialiser les filtres</a>
    </div>

    <button type="button" class="btn-toggle-filters" id="toggleFilters">Afficher les filtres</button>

    <aside class="filters-sidebar <?= !empty($_GET['q']) || !empty($_GET['skill']) || !empty($_GET['city']) || !empty($_GET['type']) || isset($_GET['min_money']) ? 'active' : '' ?>" id="filterSidebar">
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
                <input type="text" id="f-domain" name="domain" placeholder="Ex: Informatique, Finance..." value="<?= htmlspecialchars($_GET['domain'] ?? '') ?>">
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
        <?php if (!empty($offres)): ?>
            <?php
                $redirectBase = 'index.php?route=offres';
                if (!empty($queryParams)) {
                    $redirectBase .= '&' . http_build_query($queryParams);
                }
            ?>
            <?php foreach ($offres as $offre): ?>
                <?php $domainLabel = $inferDomain($offre); ?>
                <article class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                        <span class="tag"><?= htmlspecialchars($offre['type_contrat'] ?? 'Offre') ?></span>
                    </div>
                    <p class="company"><?= htmlspecialchars($offre['entreprise_nom'] ?? '') ?></p>
                    <p class="desc"><?= htmlspecialchars($offre['description']) ?></p>
                    <p class="offer-domain">
                        <span class="domain-badge">Domaine: <?= htmlspecialchars($domainLabel) ?></span>
                    </p>
                    <div class="card-footer">
                        <span class="date"><?= date('d/m/Y', strtotime($offre['date_publication'])) ?></span>
                        <a href="index.php?route=offre-details&id=<?= (int) $offre['id'] ?>" class="btn-primary">Détails</a>
                        <?php if (isset($_SESSION['user_id']) && (int) ($_SESSION['user_role'] ?? 0) !== 2): ?>
                            <?php $isFavorite = in_array((int) $offre['id'], $wishlistOfferIds ?? [], true); ?>
                            <?php if ($isFavorite): ?>
                                <a href="index.php?route=wishlist-remove&id=<?= (int) $offre['id'] ?>&redirect=<?= urlencode($redirectBase) ?>" class="btn-details" title="Retirer de ma wish-list" aria-label="Retirer de ma wish-list">❤</a>
                            <?php else: ?>
                                <a href="index.php?route=wishlist-add&id=<?= (int) $offre['id'] ?>&redirect=<?= urlencode($redirectBase) ?>" class="btn-details" title="Ajouter à ma wish-list" aria-label="Ajouter à ma wish-list">♡</a>
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
<?php include 'footer.php'; ?>
