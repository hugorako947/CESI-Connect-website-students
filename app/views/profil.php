<?php include 'header.php'; ?>
<?php
    $role      = (int) ($_SESSION['user_role'] ?? 0);
    $isPilote  = $role === 2;
    $isAdmin   = $role === 1;
    $adminSearch = trim($_GET['admin_search'] ?? '');
?>

<section class="mes-candidatures-section">
    <div class="container">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['erreur'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION['erreur']) ?></div>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <!-- ═══ BARRE D'ONGLETS ═══ -->
        <nav class="profil-tabs-bar" role="tablist">
            <?php if ($isPilote): ?>
                <button class="profil-tab-btn active" id="tab-dashboard" role="tab" aria-selected="true" aria-controls="panel-dashboard" onclick="switchTab('dashboard')">Informations</button>
                <button class="profil-tab-btn" id="tab-pilote" role="tab" aria-selected="false" aria-controls="panel-pilote" onclick="switchTab('pilote')">Dashboard Pilote</button>

            <?php elseif ($isAdmin): ?>
                <button class="profil-tab-btn active" id="tab-dashboard" role="tab" aria-selected="true" aria-controls="panel-dashboard" onclick="switchTab('dashboard')">Informations</button>
                <button class="profil-tab-btn" id="tab-wishlist" role="tab" aria-selected="false" aria-controls="panel-wishlist" onclick="switchTab('wishlist')">Wish-list</button>
                <button class="profil-tab-btn" id="tab-alertes" role="tab" aria-selected="false" aria-controls="panel-alertes" onclick="switchTab('alertes')">Mes alertes</button>
                <button class="profil-tab-btn" id="tab-candidatures" role="tab" aria-selected="false" aria-controls="panel-candidatures" onclick="switchTab('candidatures')">Candidatures</button>
                <button class="profil-tab-btn profil-tab-admin" id="tab-admin" role="tab" aria-selected="false" aria-controls="panel-admin" onclick="switchTab('admin')">Dashboard Admin</button>

            <?php else: ?>
                <button class="profil-tab-btn active" id="tab-dashboard" role="tab" aria-selected="true" aria-controls="panel-dashboard" onclick="switchTab('dashboard')">Informations</button>
                <button class="profil-tab-btn" id="tab-wishlist" role="tab" aria-selected="false" aria-controls="panel-wishlist" onclick="switchTab('wishlist')">Wish-list</button>
                <button class="profil-tab-btn" id="tab-alertes" role="tab" aria-selected="false" aria-controls="panel-alertes" onclick="switchTab('alertes')">Mes alertes</button>
                <button class="profil-tab-btn" id="tab-candidatures" role="tab" aria-selected="false" aria-controls="panel-candidatures" onclick="switchTab('candidatures')">Candidatures</button>
            <?php endif; ?>
        </nav>

        <!-- ═══ PANEL INFORMATIONS ═══ -->
        <div id="panel-dashboard" class="profil-panel active" role="tabpanel" aria-labelledby="tab-dashboard" style="display:block;">
            <h1>&#128100; Mes informations personnelles</h1>
            <div class="auth-card" style="margin-bottom:24px; margin-top:24px;">
                <form action="index.php?route=profil" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" required value="<?= htmlspecialchars($user['prenom'] ?? ($_SESSION['user_prenom'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($user['nom'] ?? ($_SESSION['user_nom'] ?? '')) ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? ($_SESSION['user_email'] ?? '')) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
            <div class="form-footer" style="margin-top:30px; text-align:left;">
                <a href="index.php?route=reinitialiser-mot-de-passe" class="link-change-password">Changer de mot de passe</a>
            </div>
            <div class="profil-actions" style="margin-top:30px;">
                <a href="index.php?route=supprimer-compte" class="btn-deconnexion"
                   onclick="return confirm('ATTENTION : Cette action supprimera définitivement votre compte et toutes vos données. Confirmer ?')">
                    Supprimer mon compte
                </a>
            </div>
        </div>

        <!-- ═══ PANEL PILOTE ═══ -->
        <?php if ($isPilote): ?>
        <div id="panel-pilote" class="profil-panel" role="tabpanel" aria-labelledby="tab-pilote">
            <div class="page-header" style="margin-bottom:2rem;">
                <div>
                    <h1>&#128202; Dashboard Pilote</h1>
                    <p style="color:var(--muted); margin-top:0.5rem;">Accédez ici à votre espace de suivi pilote.</p>
                </div>
            </div>
            <div class="stats" style="margin-bottom:2rem; justify-content:flex-start;">
                <div class="stat-item"><span class="number"><?= (int)($pilotStats['nb_etudiants']??0) ?></span><span class="label">Étudiant<?= ((int)($pilotStats['nb_etudiants']??0))>1?'s':'' ?> suivi<?= ((int)($pilotStats['nb_etudiants']??0))>1?'s':'' ?></span></div>
                <div class="stat-item"><span class="number"><?= (int)($pilotStats['nb_candidatures_total']??0) ?></span><span class="label">Candidature<?= ((int)($pilotStats['nb_candidatures_total']??0))>1?'s':'' ?> total<?= ((int)($pilotStats['nb_candidatures_total']??0))>1?'es':'e' ?></span></div>
                <div class="stat-item"><span class="number"><?= (int)($pilotStats['nb_en_attente']??0) ?></span><span class="label">En attente</span></div>
                <div class="stat-item"><span class="number"><?= (int)($pilotStats['nb_acceptees']??0) ?></span><span class="label">Acceptée<?= ((int)($pilotStats['nb_acceptees']??0))>1?'s':'' ?></span></div>
            </div>
            <div class="auth-card" style="text-align:left; margin-bottom:1.5rem;">
                <h2 style="margin-bottom:1rem;">Accès au suivi pilote</h2>
                <p style="color:var(--muted); margin-bottom:1.25rem;">Ouvrez votre espace de pilotage pour consulter la liste des étudiants, leurs candidatures, leurs wish-lists et les statistiques globales.</p>
                <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
                    <a href="index.php?route=pilote-dashboard" class="btn btn-primary">Ouvrir le dashboard pilote</a>
                    <a href="index.php?route=pilote-statistiques" class="btn btn-outline">Voir les statistiques globales</a>
                </div>
            </div>
        </div>

        <?php else: /* etudiant OU admin */ ?>

        <!-- ═══ PANEL WISH-LIST ═══ -->
        <div id="panel-wishlist" class="profil-panel" role="tabpanel" aria-labelledby="tab-wishlist">
            <h1>&#10084;&#65039; Ma wish-list</h1>
            <div class="auth-card">
                <?php if (empty($wishlist_offers)): ?>
                    <p>Vous n'avez aucune offre en favori pour le moment.</p>
                <?php else: ?>
                    <div class="candidatures-list">
                        <?php foreach ($wishlist_offers as $offre): ?>
                            <div class="candidature-item">
                                <div class="candidature-header"><h3><?= htmlspecialchars($offre['titre']) ?></h3></div>
                                <div class="candidature-body"><p><strong>&#127962; Entreprise :</strong> <?= htmlspecialchars($offre['entreprise_nom']) ?></p></div>
                                <div class="candidature-footer"><a href="index.php?route=offre-details&id=<?= (int)$offre['id'] ?>" class="btn btn-outline">Voir l'offre</a></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ═══ PANEL ALERTES ═══ -->
        <div id="panel-alertes" class="profil-panel" role="tabpanel" aria-labelledby="tab-alertes">
            <div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem;">
                <div>
                    <h1>&#128276; Mes alertes</h1>
                    <p style="color:var(--muted); margin-top:0.5rem;">Créez des alertes personnalisées et soyez notifié des nouvelles offres.</p>
                </div>
                <a href="index.php?route=alerte-form" class="btn btn-primary">&#10133; Créer une alerte</a>
            </div>
            <?php if (empty($alerts)): ?>
                <div class="empty-state">
                    <h2>Aucune alerte configurée</h2>
                    <p>Créez votre première alerte pour recevoir des notifications.</p>
                    <a href="index.php?route=alerte-form" class="btn btn-primary" style="margin-top:1rem;">Créer ma première alerte</a>
                </div>
            <?php else: ?>
                <div class="candidatures-list">
                    <?php foreach ($alerts as $alert): ?>
                        <div class="candidature-item alert-item" style="position:relative;">
                            <div style="position:absolute; top:1rem; right:1rem;">
                                <?php if ($alert['actif']): ?>
                                    <span class="badge badge-success">&#10003; Active</span>
                                <?php else: ?>
                                    <span class="badge" style="background:rgba(239,68,68,.10);color:rgba(239,68,68,.95);border-color:rgba(239,68,68,.22);">&#9208; Désactivée</span>
                                <?php endif; ?>
                            </div>
                            <div class="candidature-header">
                                <h3><?= htmlspecialchars($alert['nom_alerte']) ?></h3>
                                <?php if ($alert['nouvelles_offres'] > 0): ?>
                                    <span class="statut" style="background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;padding:.35rem .85rem;border-radius:999px;font-size:.82rem;font-weight:900;">
                                        &#128276; <?= $alert['nouvelles_offres'] ?> nouvelle<?= $alert['nouvelles_offres']>1?'s':'' ?> offre<?= $alert['nouvelles_offres']>1?'s':'' ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="candidature-body" style="margin-top:1rem;">
                                <div style="display:flex; flex-wrap:wrap; gap:.75rem; margin-bottom:.75rem;">
                                    <?php if (!empty($alert['mot_cle'])): ?><span class="filter-tag">&#128269; <?= htmlspecialchars($alert['mot_cle']) ?></span><?php endif; ?>
                                    <?php if (!empty($alert['ville'])): ?><span class="filter-tag">&#128205; <?= htmlspecialchars($alert['ville']) ?></span><?php endif; ?>
                                    <?php if (!empty($alert['domaine'])): ?><span class="filter-tag">&#127991; <?= htmlspecialchars($alert['domaine']) ?></span><?php endif; ?>
                                    <?php if (!empty($alert['type_contrat'])): ?><span class="filter-tag">&#128196; <?= ucfirst($alert['type_contrat']) ?></span><?php endif; ?>
                                    <?php if (!empty($alert['remuneration_min']) && $alert['remuneration_min'] > 0): ?><span class="filter-tag">&#128182; Min. <?= number_format($alert['remuneration_min'],0,',',' ') ?>€</span><?php endif; ?>
                                </div>
                                <p style="font-size:.86rem; color:var(--muted);"><strong>Créée le :</strong> <?= date('d/m/Y', strtotime($alert['date_creation'])) ?></p>
                            </div>
                            <div class="candidature-footer" style="display:flex; gap:.5rem; flex-wrap:wrap; margin-top:1rem;">
                                <?php if ($alert['nouvelles_offres'] > 0): ?>
                                    <a href="index.php?route=alerte-offres&id=<?= (int)$alert['id'] ?>" class="btn btn-primary">Voir les <?= $alert['nouvelles_offres'] ?> offre<?= $alert['nouvelles_offres']>1?'s':'' ?></a>
                                <?php endif; ?>
                                <a href="index.php?route=alerte-form&id=<?= (int)$alert['id'] ?>" class="btn btn-outline">&#9999;&#65039; Modifier</a>
                                <a href="index.php?route=alerte-toggle&id=<?= (int)$alert['id'] ?>" class="btn btn-outline" onclick="return confirm('<?= $alert['actif']?'Désactiver':'Activer' ?> cette alerte ?')"><?= $alert['actif']?'&#9208; Désactiver':'&#9654; Activer' ?></a>
                                <a href="index.php?route=alerte-delete&id=<?= (int)$alert['id'] ?>" class="btn btn-outline" style="border-color:rgba(239,68,68,.30);color:rgba(239,68,68,.95);" onclick="return confirm('Supprimer définitivement cette alerte ?')">&#128465; Supprimer</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="stats-card" style="margin-top:2rem;">
                    <h3>&#128202; Statistiques</h3>
                    <div class="stats-grid">
                        <div class="stat-item"><div class="stat-number"><?= count($alerts) ?></div><div class="stat-label">Alerte<?= count($alerts)>1?'s':'' ?> créée<?= count($alerts)>1?'s':'' ?></div></div>
                        <div class="stat-item"><div class="stat-number"><?= count(array_filter($alerts,fn($a)=>$a['actif']==1)) ?></div><div class="stat-label">Active<?= count(array_filter($alerts,fn($a)=>$a['actif']==1))>1?'s':'' ?></div></div>
                        <div class="stat-item"><div class="stat-number"><?= array_sum(array_column($alerts,'nouvelles_offres')) ?></div><div class="stat-label">Nouvelle<?= array_sum(array_column($alerts,'nouvelles_offres'))>1?'s':'' ?> offre<?= array_sum(array_column($alerts,'nouvelles_offres'))>1?'s':'' ?></div></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- ═══ PANEL CANDIDATURES ═══ -->
        <div id="panel-candidatures" class="profil-panel" role="tabpanel" aria-labelledby="tab-candidatures">
            <h1>&#128640; Candidatures envoyées</h1>
            <?php
                $nbTotal     = count($candidatures);
                $nbAttente   = count(array_filter($candidatures, fn($c) => $c['statut'] === 'En attente'));
                $nbAcceptees = count(array_filter($candidatures, fn($c) => $c['statut'] === 'Acceptée'));
            ?>
            <?php if ($nbTotal > 0): ?>
                <div class="candidatures-stats-bar">
                    <span class="cstat-pill cstat-total"><?= $nbTotal ?> Candidature<?= $nbTotal>1?'s':'' ?></span>
                    <span class="cstat-pill cstat-attente"><?= $nbAttente ?> En attente<?= $nbAttente>1?'s':'' ?></span>
                    <span class="cstat-pill cstat-acceptee"><?= $nbAcceptees ?> Acceptée<?= $nbAcceptees>1?'s':'' ?></span>
                </div>
            <?php endif; ?>
            <div class="auth-card">
                <?php if (empty($candidatures)): ?>
                    <p>Vous n'avez pas encore postulé.</p>
                <?php else: ?>
                    <div class="candidatures-list">
                        <?php foreach ($candidatures as $candidature): ?>
                            <div class="candidature-item">
                                <div class="candidature-header">
                                    <h3><?= htmlspecialchars($candidature['offre_titre']) ?></h3>
                                    <span class="statut statut-<?= strtolower(str_replace(' ','-',$candidature['statut'])) ?>"><?= htmlspecialchars($candidature['statut']) ?></span>
                                </div>
                                <div class="candidature-body">
                                    <p><strong>&#127962; Entreprise :</strong> <?= htmlspecialchars($candidature['entreprise_nom']) ?></p>
                                    <p><strong>&#128197; Date :</strong> <?= date('d/m/Y à H:i', strtotime($candidature['date_candidature'])) ?></p>
                                    <p><strong>&#128206; Documents :<br>&#10003; CV<br>&#10003; Lettre de motivation</strong></p>
                                </div>
                                <div class="candidature-footer">
                                    <a href="index.php?route=offre-details&id=<?= (int)$candidature['id_offre'] ?>" class="btn btn-outline">Voir l'offre</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             PANEL — DASHBOARD ADMINISTRATEUR (isAdmin seulement)
        ═══════════════════════════════════════════════════════ -->
        <?php if ($isAdmin): ?>
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
                    <div class="admin-stat-icon">&#127891;</div>
                    <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_etudiants']??0) ?></div>
                    <div class="admin-stat-label">Étudiant<?= ((int)($adminData['stats']['nb_etudiants']??0))>1?'s':'' ?></div>
                </div>
                <div class="admin-stat-card admin-stat-purple">
                    <div class="admin-stat-icon">&#129517;</div>
                    <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_pilotes']??0) ?></div>
                    <div class="admin-stat-label">Pilote<?= ((int)($adminData['stats']['nb_pilotes']??0))>1?'s':'' ?></div>
                </div>
                <div class="admin-stat-card admin-stat-red">
                    <div class="admin-stat-icon">&#9881;&#65039;</div>
                    <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_admins']??0) ?></div>
                    <div class="admin-stat-label">Administrateur<?= ((int)($adminData['stats']['nb_admins']??0))>1?'s':'' ?></div>
                </div>
                <div class="admin-stat-card admin-stat-green">
                    <div class="admin-stat-icon">&#128203;</div>
                    <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_offres_stage']??0) ?></div>
                    <div class="admin-stat-label">Offre<?= ((int)($adminData['stats']['nb_offres_stage']??0))>1?'s':'' ?> de stage</div>
                </div>
                <div class="admin-stat-card admin-stat-orange">
                    <div class="admin-stat-icon">&#128260;</div>
                    <div class="admin-stat-value"><?= (int)($adminData['stats']['nb_offres_alt']??0) ?></div>
                    <div class="admin-stat-label">Alternance<?= ((int)($adminData['stats']['nb_offres_alt']??0))>1?'s':'' ?></div>
                </div>
                <div class="admin-stat-card admin-stat-teal">
                    <div class="admin-stat-icon">&#127962;</div>
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
        <?php endif; /* isAdmin */ ?>

        <?php endif; /* not isPilote */ ?>

    </div>
</section>

<!-- ═══════════════ STYLES ADMIN ═══════════════ -->
<style>
.profil-tab-admin { font-weight: 900 !important; color: #7c3aed !important; }
.profil-tab-admin.active { border-bottom-color: #7c3aed !important; color: #7c3aed !important; }

.admin-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:2rem; padding-top:1.5rem; }
.admin-header h1 { font-weight:950; letter-spacing:-.01em; }

.admin-stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(155px,1fr)); gap:1rem; margin-bottom:2rem; }
.admin-stat-card { border-radius:var(--r-lg); padding:1.4rem 1rem; text-align:center; border:1px solid transparent; transition:transform .2s var(--ease),box-shadow .2s var(--ease); }
.admin-stat-card:hover { transform:translateY(-4px); box-shadow:var(--shadow); }
.admin-stat-icon  { font-size:2rem; margin-bottom:.5rem; }
.admin-stat-value { font-size:2.2rem; font-weight:950; margin-bottom:.3rem; }
.admin-stat-label { font-size:.85rem; font-weight:700; }
.admin-stat-blue   { background:rgba(37,99,235,.08);   border-color:rgba(37,99,235,.18);   color:rgba(37,99,235,.95); }
.admin-stat-purple { background:rgba(124,58,237,.08);  border-color:rgba(124,58,237,.18);  color:rgba(124,58,237,.95); }
.admin-stat-red    { background:rgba(239,68,68,.08);   border-color:rgba(239,68,68,.18);   color:rgba(220,38,38,.95); }
.admin-stat-green  { background:rgba(22,163,74,.08);   border-color:rgba(22,163,74,.18);   color:rgba(22,163,74,.95); }
.admin-stat-orange { background:rgba(234,88,12,.08);   border-color:rgba(234,88,12,.18);   color:rgba(194,65,12,.95); }
.admin-stat-teal   { background:rgba(13,148,136,.08);  border-color:rgba(13,148,136,.18);  color:rgba(13,148,136,.95); }

.admin-search-bar { margin-bottom:2rem; background:rgba(255,255,255,.74); border:1px solid rgba(99,102,241,.16); border-radius:var(--r-lg); padding:1.25rem 1.5rem; box-shadow:var(--shadow-sm); }
.admin-search-form { display:flex; gap:.75rem; align-items:center; flex-wrap:wrap; }
.admin-search-input-wrap { position:relative; flex:1; min-width:240px; }
.admin-search-icon { position:absolute; left:.9rem; top:50%; transform:translateY(-50%); font-size:1rem; pointer-events:none; }
.admin-search-input { width:100%; padding:.85rem .95rem .85rem 2.6rem; border-radius:14px; border:1px solid rgba(99,102,241,.16); background:rgba(255,255,255,.80); font-size:.97rem; outline:none; transition:border-color .2s,box-shadow .2s; }
.admin-search-input:focus { border-color:rgba(124,58,237,.35); box-shadow:0 0 0 4px rgba(124,58,237,.12); }
.admin-search-info { margin-top:.75rem; font-size:.88rem; color:var(--muted); }

.admin-table-section { margin-bottom:2.5rem; background:rgba(255,255,255,.74); border:1px solid rgba(99,102,241,.16); border-radius:var(--r-lg); padding:1.5rem; box-shadow:var(--shadow-sm); }
.admin-table-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; flex-wrap:wrap; gap:.5rem; }
.admin-table-header h2 { font-weight:900; font-size:1.1rem; display:flex; align-items:center; gap:.5rem; }
.admin-count-badge { display:inline-flex; align-items:center; justify-content:center; min-width:28px; padding:.15rem .5rem; border-radius:999px; font-size:.78rem; font-weight:900; background:rgba(124,58,237,.12); color:rgba(124,58,237,.95); border:1px solid rgba(124,58,237,.22); }
.admin-empty { color:var(--muted); font-style:italic; padding:.5rem 0; }

.btn-danger { background:linear-gradient(135deg,rgba(239,68,68,.95),rgba(185,28,28,.95)) !important; color:#fff !important; border-color:transparent !important; box-shadow:0 8px 20px rgba(239,68,68,.20) !important; }
.btn-danger:hover { transform:translateY(-2px) !important; box-shadow:0 14px 35px rgba(239,68,68,.28) !important; filter:brightness(1.04) !important; color:#fff !important; }

.admin-role-badge { display:inline-flex; align-items:center; padding:.25rem .65rem; border-radius:999px; font-size:.8rem; font-weight:900; border:1px solid transparent; }
.admin-role-1 { background:rgba(239,68,68,.10);  color:rgba(220,38,38,.95);  border-color:rgba(239,68,68,.22); }
.admin-role-2 { background:rgba(124,58,237,.10); color:rgba(109,40,217,.95); border-color:rgba(124,58,237,.22); }
.admin-role-3 { background:rgba(37,99,235,.10);  color:rgba(29,78,216,.95);  border-color:rgba(37,99,235,.22); }

.admin-type-badge { display:inline-flex; align-items:center; padding:.22rem .6rem; border-radius:999px; font-size:.78rem; font-weight:800; border:1px solid transparent; }
.admin-type-stage      { background:rgba(22,163,74,.10); color:rgba(22,163,74,.95); border-color:rgba(22,163,74,.22); }
.admin-type-alternance { background:rgba(234,88,12,.10); color:rgba(194,65,12,.95); border-color:rgba(234,88,12,.22); }

.admin-row-self { background:rgba(37,99,235,.04); }
.admin-self-tag { display:inline-block; margin-left:.4rem; font-size:.72rem; font-weight:900; padding:.1rem .45rem; border-radius:999px; background:rgba(37,99,235,.12); color:rgba(37,99,235,.95); border:1px solid rgba(37,99,235,.22); vertical-align:middle; }

.filter-tag { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem .75rem; border-radius:999px; font-size:.82rem; font-weight:800; background:rgba(37,99,235,.10); color:rgba(37,99,235,.95); border:1px solid rgba(37,99,235,.22); }
.stats-card { background:rgba(255,255,255,.74); border:1px solid rgba(99,102,241,.16); border-radius:var(--r-lg); padding:1.5rem; box-shadow:var(--shadow-sm); }
.stats-card h3 { font-weight:900; margin-bottom:1.25rem; }
.stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(150px,1fr)); gap:1.5rem; }
.stats-grid .stat-item { text-align:center; padding:1rem; background:rgba(37,99,235,.05); border:1px solid rgba(37,99,235,.12); border-radius:var(--r); }
.stats-grid .stat-number { font-size:2rem; font-weight:950; background:linear-gradient(135deg,var(--primary),var(--primary2)); -webkit-background-clip:text; background-clip:text; color:transparent; }
.stats-grid .stat-label  { color:var(--muted); font-weight:700; font-size:.9rem; margin-top:.25rem; }
</style>

<script>
function switchTab(tab) {
    document.querySelectorAll('.profil-tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
    });
    document.querySelectorAll('.profil-panel').forEach(panel => {
        panel.classList.remove('active');
        panel.style.display = 'none';
    });

    const activeTab   = document.getElementById('tab-' + tab);
    const activePanel = document.getElementById('panel-' + tab);
    if (!activeTab || !activePanel) return;

    activeTab.classList.add('active');
    activeTab.setAttribute('aria-selected', 'true');
    activePanel.classList.add('active');
    activePanel.style.display = 'block';

    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    history.replaceState(null, '', url);
}

(function () {
    document.querySelectorAll('.profil-panel').forEach((panel, index) => {
        panel.style.display = index === 0 ? 'block' : 'none';
    });

    <?php if ($isPilote): ?>
        const allowedTabs = ['dashboard', 'pilote'];
    <?php elseif ($isAdmin): ?>
        const allowedTabs = ['dashboard', 'wishlist', 'alertes', 'candidatures', 'admin'];
    <?php else: ?>
        const allowedTabs = ['dashboard', 'wishlist', 'alertes', 'candidatures'];
    <?php endif; ?>

    const tab = new URLSearchParams(window.location.search).get('tab');
    if (tab && allowedTabs.includes(tab)) {
        switchTab(tab);
    }
})();
</script>

<?php include 'footer.php'; ?>
