document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('toggleFilters');
    const sidebar = document.getElementById('filterSidebar');

    if (filterBtn && sidebar) { // Sécurité supplémentaire
        filterBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            console.log("Filtres basculés !"); // Pour vérifier dans la console
        });
    }
});