<?php include 'header.php'; ?>

<section class="dashboard-container">
    <div class="dashboard-header">
        <h1>Ma Wish-List</h1>
        <p>Retrouvez ici toutes les offres de stage que vous avez mises de côté.</p>
    </div>

    <!-- Plus tard, on mettra une condition PHP ici : -->
    <!-- if (empty($wishlist_offers)) { echo "Votre liste est vide."; } else { ... } -->

    <div class="cards-container vertical">
        <!-- Chaque carte est une offre sauvegardée -->
        <article class="card">
            <div class="card-header">
                <h3>Développeur Web Fullstack</h3>
                <span class="tag">Stage</span>
            </div>
            <p class="company"><strong>TechCorp</strong> - Paris (75)</p>
            <p class="desc">Nous recherchons un stagiaire pour travailler sur une application React/Node.js...</p>
            <div class="card-footer">
                <a href="index.php?route=details-offre&id=1" class="btn-details">Voir les détails</a>
                <!-- Lien pour retirer l'offre de la wishlist (SFx 25) -->
                <a href="index.php?route=wishlist-remove&id=1" class="btn-remove">Retirer</a>
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
                <a href="index.php?route=details-offre&id=3" class="btn-details">Voir les détails</a>
                <a href="index.php?route=wishlist-remove&id=3" class="btn-remove">Retirer</a>
            </div>
        </article>
    </div>
</section>

<?php include 'footer.php'; ?>