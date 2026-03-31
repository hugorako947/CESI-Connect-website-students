document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('toggleFilters');
    const sidebar = document.getElementById('filterSidebar');

    if (filterBtn && sidebar) {
        filterBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
});