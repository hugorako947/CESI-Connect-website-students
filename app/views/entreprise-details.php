<?php include 'header.php'; ?>

<main class="container">
    <div class="page-header page-header-spaced" style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 4rem; padding-bottom: 2rem; border-bottom: 1px solid var(--stroke-2);">
        
        <div style="display: flex; align-items: center; justify-content: center; gap: 1.5rem; margin-bottom: 0.5rem;">
            
            <div class="enterprise-avatar" style="width: 85px; height: 85px; font-size: 2.2rem; font-weight: 950; border-radius: var(--r-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--stroke); display: flex; align-items: center; justify-content: center; text-transform: uppercase; flex-shrink: 0;">
                <?= strtoupper(substr((string) ($enterprise['nom'] ?? 'E'), 0, 2)) ?>
            </div>

            <h1 style="font-weight: 950; font-size: clamp(2.2rem, 5vw, 3.5rem); margin: 0; color: var(--text); line-height: 1; letter-spacing: -0.03em;">
                <?= htmlspecialchars($enterprise['nom'] ?? 'Entreprise') ?>
            </h1>
        </div>

        <p class="page-subtitle" style="font-size: 1.2rem; color: var(--muted); margin: 0; font-weight: 600;">Entreprise partenaire de CESI Connect</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 2.5rem; align-items: start; margin-bottom: 3rem;">
        
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            
            <section class="section-card">
                <h2 class="section-card-title">📖 À propos de nous</h2>
                <div style="line-height: 1.8; color: var(--text); font-size: 1.05rem;">
                    <?= nl2br(htmlspecialchars($enterprise['description'] ?? 'Aucune description disponible pour le moment.')) ?>
                </div>
            </section>

            <section class="section-card">
                <h2 class="section-card-title">💼 Offres disponibles chez <?= htmlspecialchars($enterprise['nom'] ?? '') ?></h2>
                
                <div class="cards-container vertical" style="margin-top: 1.5rem;">
                    <?php if (!empty($offers)): ?>
                        <?php foreach ($offers as $offer): ?>
                            <article class="card" style="margin-bottom: 0.5rem; border: 1px solid var(--stroke-2);">
                                <div class="card-header">
                                    <h3><?= htmlspecialchars($offer['titre'] ?? '') ?></h3>
                                    <span class="admin-type-badge <?= (strtolower($offer['type_contrat'] ?? '') === 'stage') ? 'admin-type-stage' : 'admin-type-alternance' ?>">
                                        <?= htmlspecialchars(ucfirst($offer['type_contrat'] ?? 'Offre')) ?>
                                    </span>
                                </div>
                                <p class="desc" style="margin-bottom: 1.2rem;">
                                    <?php
                                    $desc = $offer['description'] ?? '';
                                    echo htmlspecialchars(substr($desc, 0, 160)) . (strlen($desc) > 160 ? '...' : '');
                                    ?>
                                </p>
                                <div class="card-footer">
                                    <span class="date">
                                        📅 Publiée le <?= !empty($offer['date_publication']) ? date('d/m/Y', strtotime($offer['date_publication'])) : '—' ?>
                                    </span>
                                    <a href="index.php?route=offre-details&id=<?= (int) ($offer['id'] ?? 0) ?>" class="btn-details">
                                        Voir les détails
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state" style="background: transparent; border: 1px dashed var(--stroke-2); box-shadow: none;">
                            <div class="empty-icon">📂</div>
                            <p>Aucune offre active pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <aside style="display: flex; flex-direction: column; gap: 2rem;">
            
            <div class="section-card">
                <h3 class="section-card-title">📍 Contact</h3>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <?php if (!empty($enterprise['email_contact'])): ?>
                        <div>
                            <small style="color: var(--muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 800; display: block; margin-bottom: 0.3rem;">Email de contact</small>
                            <a href="mailto:<?= htmlspecialchars($enterprise['email_contact']) ?>" style="color: var(--primary); font-weight: 700; font-size: 1.05rem;">
                                <?= htmlspecialchars($enterprise['email_contact']) ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($enterprise['telephone'])): ?>
                        <div>
                            <small style="color: var(--muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 800; display: block; margin-bottom: 0.3rem;">Téléphone</small>
                            <span style="font-weight: 700; color: var(--text); font-size: 1.05rem;"><?= htmlspecialchars($enterprise['telephone']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="section-card" style="background: linear-gradient(135deg, rgba(37,99,235,0.03), rgba(124,58,237,0.03)); border-color: var(--primary2);">
                <h3 class="section-card-title">⭐ Évaluation</h3>
                <div style="text-align: center; padding: 0.5rem 0;">
                    <div style="font-size: 2.8rem; font-weight: 950; color: var(--primary2); line-height: 1;">4.5 <span style="font-size: 1.2rem; font-weight: 600;">/ 5</span></div>
                    <div style="color: var(--primary2); margin-top: 0.5rem; letter-spacing: 2px;">★★★★★</div>
                    <p style="font-size: 0.85rem; color: var(--muted); margin-top: 1rem;">Basé sur les avis des anciens stagiaires</p>
                </div>
            </div>
        </aside>
    </div>
</main>

<?php include 'footer.php'; ?>
