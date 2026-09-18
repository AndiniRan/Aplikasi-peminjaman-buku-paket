document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Dropdown Sidebar
    |--------------------------------------------------------------------------
    */
    const dropdownButtons = document.querySelectorAll(
        '.sidebar-dropdown-toggle'
    );

    dropdownButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const dropdown = button.closest('.sidebar-dropdown');

            if (!dropdown) {
                return;
            }

            dropdown.classList.toggle('open');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const dashboardMain = document.querySelector('.dashboard-main');

    if (sidebar && sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {

            if (window.innerWidth <= 900) {
                sidebar.classList.toggle('mobile-open');
                return;
            }

            sidebar.classList.toggle('desktop-hidden');

            if (dashboardMain) {
                dashboardMain.classList.toggle('sidebar-collapsed');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Tutup Sidebar Mobile
    |--------------------------------------------------------------------------
    */
    document.addEventListener('click', function (event) {

        if (
            window.innerWidth <= 900 &&
            sidebar &&
            sidebarToggle &&
            sidebar.classList.contains('mobile-open') &&
            !sidebar.contains(event.target) &&
            !sidebarToggle.contains(event.target)
        ) {
            sidebar.classList.remove('mobile-open');
        }
    });

    /*
    |--------------------------------------------------------------------------
    | Resize
    |--------------------------------------------------------------------------
    */
    window.addEventListener('resize', function () {

        if (!sidebar) {
            return;
        }

        if (window.innerWidth > 900) {
            sidebar.classList.remove('mobile-open');
        }

        if (window.innerWidth <= 900) {
            sidebar.classList.remove('desktop-hidden');

            if (dashboardMain) {
                dashboardMain.classList.remove('sidebar-collapsed');
            }
        }
    });

});