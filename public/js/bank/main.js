/**
 * Bank Sampah layout — sidebar + overlay
 */
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const wrapper = document.getElementById('appWrapper');
    const overlay = document.getElementById('bankSidebarOverlay');
    const body = document.body;

    if (!toggleBtn || !sidebar || !wrapper) {
        console.warn('[Bank Sampah] Sidebar elements not found');
        return;
    }

    const mqDesktop = window.matchMedia('(min-width: 992px)');

    function setOpen(open) {
        sidebar.classList.toggle('show', open);
        wrapper.classList.toggle('sidebar-open', open);
        body.classList.toggle('bank-sidebar-open', open);
        if (overlay) {
            overlay.classList.remove('is-visible');
        }
    }

    function syncLayout() {
        if (mqDesktop.matches) {
            setOpen(true);
        } else {
            setOpen(false);
        }
    }

    syncLayout();
    mqDesktop.addEventListener('change', syncLayout);

    toggleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const open = !sidebar.classList.contains('show');
        setOpen(open);
    });

    if (overlay) {
        overlay.addEventListener('click', function () {
            setOpen(false);
        });
    }

    const activeMenu = document.querySelector('.bank-body .nav-item.active');
    if (activeMenu) {
        activeMenu.scrollIntoView({ block: 'nearest' });
    }
});
