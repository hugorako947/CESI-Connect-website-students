<div id="panel-admin" class="profil-panel" role="tabpanel" aria-labelledby="tab-admin">
    <div class="admin-header">
        <div>
            <h1>&#9881;&#65039; Dashboard Administrateur</h1>
            <p class="page-subtitle">Gérez les utilisateurs, les offres et les entreprises de la plateforme CESI Connect.</p>
        </div>
    </div>

    <!-- STATS GLOBALES -->
    <div class="admin-stats-grid">
        <div class="admin-stat-card admin-stat-blue">
            <!--<div class="admin-stat-icon">&#127891;</div>-->
            <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_etudiants']??0) ?></div>
            <div class="admin-stat-label">Étudiant<?= ((int)($adminData['stats']['nb_etudiants']??0))>1?'s':'' ?></div>
        </div>
        <div class="admin-stat-card admin-stat-purple">
            <!--<div class="admin-stat-icon">&#129517;</div>-->
            <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_pilotes']??0) ?></div>
            <div class="admin-stat-label">Pilote<?= ((int)($adminData['stats']['nb_pilotes']??0))>1?'s':'' ?></div>
        </div>
        <div class="admin-stat-card admin-stat-red">
            <!--<div class="admin-stat-icon">&#9881;&#65039;</div>-->
            <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_admins']??0) ?></div>
            <div class="admin-stat-label">Administrateur<?= ((int)($adminData['stats']['nb_admins']??0))>1?'s':'' ?></div>
        </div>
        <div class="admin-stat-card admin-stat-green">
            <!--<div class="admin-stat-icon">&#128203;</div>-->
            <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_offres_stage']??0) ?></div>
            <div class="admin-stat-label">Offre<?= ((int)($adminData['stats']['nb_offres_stage']??0))>1?'s':'' ?> de stage</div>
        </div>
        <div class="admin-stat-card admin-stat-orange">
            <!--<div class="admin-stat-icon">&#128260;</div>-->
            <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_offres_alt']??0) ?></div>
            <div class="admin-stat-label">Alternance<?= ((int)($adminData['stats']['nb_offres_alt']??0))>1?'s':'' ?></div>
        </div>
        <div class="admin-stat-card admin-stat-teal">
            <!--<div class="admin-stat-icon">&#127962;</div>-->
            <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_entreprises']??0) ?></div>
            <div class="admin-stat-label">Entreprise<?= ((int)($adminData['stats']['nb_entreprises']??0))>1?'s':'' ?></div>
        </div>
    </div>

    <!-- RECHERCHE GLOBALE -->
    <div class="admin-search-bar">
        <form action="index.php" method="GET" class="admin-search-form">
            <input type="hidden" name="route" value="profil">
            <input type="hidden" name="tab" value="admin">
            <div class="admin-search-input-wrap">
                <span class="admin-search-icon">&#128269;</span>
                <input type="text" name="admin_search"
                        placeholder="Rechercher un utilisateur, une offre, une entreprise..."
                        value="<?= htmlspecialchars($adminSearch) ?>"
                        class="admin-search-input">
            </div>
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <?php if ($adminSearch !== ''): ?>
                <a href="index.php?route=profil&tab=admin" class="btn btn-outline">&#10005; Effacer</a>
            <?php endif; ?>
        </form>
        <?php if ($adminSearch !== ''): ?>
            <p class="admin-search-info">
                Résultats pour <strong>"<?= htmlspecialchars($adminSearch) ?>"</strong> —
                <?= count($adminData['utilisateurs']) ?> utilisateur<?= count($adminData['utilisateurs'])>1?'s':'' ?>,
                <?= count($adminData['offres']) ?> offre<?= count($adminData['offres'])>1?'s':'' ?>,
                <?= count($adminData['entreprises']) ?> entreprise<?= count($adminData['entreprises'])>1?'s':'' ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- TABLE UTILISATEURS -->
    <div class="admin-table-section">
        <div class="admin-table-header">
            <h2>&#128101; Utilisateurs <span class="admin-count-badge"><?= count($adminData['utilisateurs']) ?></span></h2>
        </div>
        <?php if (empty($adminData['utilisateurs'])): ?>
            <p class="admin-empty">Aucun utilisateur trouvé.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-etudiants admin-table">
                    <thead>
                        <tr>
                            <th>Nom &amp; Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adminData['utilisateurs'] as $u): ?>
                            <?php $isSelf = (int)$u['id'] === (int)($_SESSION['user_id']??0); ?>
                            <tr <?= $isSelf ? 'class="admin-row-self"' : '' ?>>
                                <td>
                                    <strong><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></strong>
                                    <?php if ($isSelf): ?><span class="admin-self-tag">Vous</span><?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td>
                                    <?php
                                        $roleLabels = [1=>'Administrateur', 2=>'Pilote', 3=>'Étudiant'];
                                        $rid = (int)$u['id_role'];
                                    ?>
                                    <span class="admin-role-badge admin-role-<?= $rid ?>">
                                        <?= $roleLabels[$rid] ?? htmlspecialchars($u['role_nom']??'') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($isSelf): ?>
                                        <span class="text-muted-inline" title="Utilisez 'Supprimer mon compte' dans vos informations">—</span>
                                    <?php else: ?>
                                        <a href="index.php?route=admin-delete-user&id=<?= (int)$u['id'] ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Supprimer définitivement le compte de <?= htmlspecialchars(addslashes($u['prenom'].' '.$u['nom'])) ?> et toutes ses données ?')">
                                            &#128465; Supprimer
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- TABLE OFFRES -->
    <div class="admin-table-section">
        <div class="admin-table-header">
            <h2>&#128203; Offres <span class="admin-count-badge"><?= count($adminData['offres']) ?></span></h2>
        </div>
        <?php if (empty($adminData['offres'])): ?>
            <p class="admin-empty">Aucune offre trouvée.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-etudiants admin-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Entreprise</th>
                            <th>Type</th>
                            <th>Publication</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adminData['offres'] as $offre): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($offre['titre']) ?></strong></td>
                                <td><?= htmlspecialchars($offre['entreprise_nom']??'—') ?></td>
                                <td>
                                    <span class="admin-type-badge admin-type-<?= strtolower($offre['type_contrat']??'') ?>">
                                        <?= htmlspecialchars(ucfirst($offre['type_contrat']??'N/A')) ?>
                                    </span>
                                </td>
                                <td><?= !empty($offre['date_publication']) ? date('d/m/Y', strtotime($offre['date_publication'])) : '—' ?></td>
                                <td class="text-center">
                                    <a href="index.php?route=admin-delete-offer&id=<?= (int)$offre['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Supprimer définitivement l\'offre &quot;<?= htmlspecialchars(addslashes($offre['titre'])) ?>&quot; ?')">
                                        &#128465; Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- TABLE ENTREPRISES -->
    <div class="admin-table-section">
        <div class="admin-table-header">
            <h2>&#127962; Entreprises <span class="admin-count-badge"><?= count($adminData['entreprises']) ?></span></h2>
        </div>
        <?php if (empty($adminData['entreprises'])): ?>
            <p class="admin-empty">Aucune entreprise trouvée.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table-etudiants admin-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email contact</th>
                            <th>Téléphone</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adminData['entreprises'] as $ent): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($ent['nom']) ?></strong></td>
                                <td><?= htmlspecialchars($ent['email_contact']??'—') ?></td>
                                <td><?= htmlspecialchars($ent['telephone']??'—') ?></td>
                                <td class="text-center">
                                    <a href="index.php?route=admin-delete-enterprise&id=<?= (int)$ent['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Supprimer &quot;<?= htmlspecialchars(addslashes($ent['nom'])) ?>&quot; et toutes ses offres associées ?')">
                                        &#128465; Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div><!-- /panel-admin -->
