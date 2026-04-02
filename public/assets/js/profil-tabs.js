/**
 * Gestion des onglets de la page Profil (Étudiant, Pilote, Admin)
 */

function switchTab(tab) {
    // 1. Masquer tous les panneaux et retirer l'état actif des boutons
    document.querySelectorAll('.profil-tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
    });
    
    document.querySelectorAll('.profil-panel').forEach(panel => {
        panel.classList.remove('active');
        panel.style.display = 'none';
    });

    // 2. Activer l'onglet et le panneau demandés
    const activeTab = document.getElementById('tab-' + tab);
    const activePanel = document.getElementById('panel-' + tab);
    
    if (!activeTab || !activePanel) return;

    activeTab.classList.add('active');
    activeTab.setAttribute('aria-selected', 'true');
    activePanel.classList.add('active');
    activePanel.style.display = 'block';

    // 3. Mettre à jour l'URL sans recharger la page (pour le rafraîchissement)
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    history.replaceState(null, '', url);
}

// Initialisation au chargement de la page
document.addEventListener("DOMContentLoaded", function() {
    // On récupère le rôle via les IDs des boutons présents dans le DOM
    const hasPiloteTab = document.getElementById('tab-pilote') !== null;
    const hasAdminTab = document.getElementById('tab-admin') !== null;

    let allowedTabs = ['dashboard', 'wishlist', 'alertes', 'candidatures'];
    if (hasPiloteTab) allowedTabs.push('pilote');
    if (hasAdminTab) allowedTabs.push('admin');

    // Vérifier si un onglet est spécifié dans l'URL (ex: ?tab=admin)
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');

    if (tab && allowedTabs.includes(tab)) {
        switchTab(tab);
    } else {
        // Par défaut, on affiche le premier onglet (Informations)
        switchTab('dashboard');
    }
});
