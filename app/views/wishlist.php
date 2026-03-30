<?php include 'header.php'; ?>

<section class="dashboard-container">
    <div class="dashboard-header">
        <h1>Ma Wish-List</h1>
        <p>Retrouvez ici toutes les offres de stage que vous avez mises de côté.</p>
    </div>

    <!-- Plus tard, on mettra une condition PHP ici : -->
    <!-- if (empty($wishlist_offers)) { echo "Votre liste est vide."; } else { ... } -->

    <div class="cards-container vertical">
        <?php if (!empty($wishlist_offers)): ?>
            <?php foreach ($wishlist_offers as $offre): ?>
                <article class="card">
                    <div class="card-header">
                        <h3><?= htmlspecialchars($offre['titre'] ?? '') ?></h3>
                        <span class="tag">Offre</span>
                    </div>
                    <p class="company"><strong><?= htmlspecialchars($offre['entreprise_nom'] ?? '') ?></strong></p>
                    <p class="desc">
                        <?php
                        $desc = $offre['description'] ?? '';
                        $snippet = substr($desc, 0, 120);
                        echo htmlspecialchars($snippet) . (strlen($desc) > 120 ? '...' : '');
                        ?>
                    </p>
                    <div class="card-footer">
                        <a href="index.php?route=offre-details&id=<?= (int) ($offre['id'] ?? 0) ?>" class="btn-details">Voir les détails</a>
                        <!-- Lien pour retirer l'offre de la wishlist (route à implémenter côté contrôleur) -->
                        <a href="index.php?route=wishlist-remove&id=<?= (int) ($offre['id'] ?? 0) ?>" class="btn-remove">Retirer</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Votre wishlist est vide pour le moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>