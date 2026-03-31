document.addEventListener('DOMContentLoaded', function () {
    const burger = document.getElementById('burgerBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const body = document.body;

    if (!sidebar) {
        return;
    }

    const mqDesktop = window.matchMedia('(min-width: 992px)');

    function setOpen(open) {
        sidebar.classList.toggle('sidebar-open', open);
        body.classList.toggle('admin-sidebar-open', open);
        if (overlay) {
            overlay.classList.remove('show');
        }
    }

    function syncSidebarLayout() {
        if (mqDesktop.matches) {
            setOpen(true);
        } else {
            setOpen(false);
        }
    }

    syncSidebarLayout();
    mqDesktop.addEventListener('change', syncSidebarLayout);

    if (burger) {
        burger.addEventListener('click', function (e) {
            e.preventDefault();
            const isOpen = !sidebar.classList.contains('sidebar-open');
            setOpen(isOpen);
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            setOpen(false);
        });
    }
});
