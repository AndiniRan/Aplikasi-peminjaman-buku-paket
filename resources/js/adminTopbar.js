document.addEventListener('DOMContentLoaded', function () {

    const profileButton = document.getElementById('topbarProfileButton');
    const profileDropdown = document.getElementById('profileDropdown');

    if (!profileButton || !profileDropdown) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE DROPDOWN
    |--------------------------------------------------------------------------
    */
    profileButton.addEventListener(
        'click',
        function (event) {
            event.stopPropagation();
            profileDropdown.classList.toggle(
                'show'
            );
            profileButton.classList.toggle(
                'open'
            );
        }
    );

    /*
    |--------------------------------------------------------------------------
    | KLIK DI LUAR
    |--------------------------------------------------------------------------
    */
    document.addEventListener(
        'click',
        function (event) {
            if (
                !profileDropdown.contains(event.target) &&
                !profileButton.contains(event.target)
            ) {
                profileDropdown.classList.remove(
                    'show'
                );
                profileButton.classList.remove(
                    'open'
                );
            }
        }
    );
});